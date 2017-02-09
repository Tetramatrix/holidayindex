<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Chi Hoang (info@chihoang.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

	// DEFAULT initialization of a module [BEGIN]
unset($MCONF);
require ("conf.php");
require ($BACK_PATH."init.php");
require ($BACK_PATH."template.php");
$LANG->includeLLFile("EXT:ch_trip/mod_region/locallang.php");
require_once (t3lib_extMgm::extPath('ch_trip').'lib/class.tx_chtrip_scriptclasses.php');


$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]

class tx_chtrip_region extends tx_chtrip_scriptclasses {
	
	/**
	 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
	 */
	function menuConfig()	{
		global $LANG;
        
		$this->MOD_MENU = Array (
			"function" => Array (
				"1" => $LANG->getLL("function1"),
				"2" => $LANG->getLL("function2"),
			)
		);
		parent::menuConfig();
	}

		// If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
	/**
	 * Main function of the module. Write the content to $this->content
	 */
	function main()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

			// Access check!
			// The page will show only if there is a valid page and if this page may be viewed by the user
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;

		if (($this->id && $access) || ($BE_USER->user["admin"] && !$this->id))	{

			$CMD = t3lib_div::_GET('SLCMD');
			if (is_array($CMD)) {			
				$uid = array_keys($CMD['SELECT']['txchvaRegion']);
				$addParams = '?SLCMD[SELECT][txchvaRegion]['.$uid[0].']=1';
				
				$res = mysql_query('select * from tx_chtrip_region where uid='.$uid[0]);
					// fetch records
				$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
				$title = $row['title'];
			}

				// Draw the header.
			$this->doc = t3lib_div::makeInstance("mediumDoc");
			$this->doc->backPath = $BACK_PATH;
			$this->doc->form = '<form name="ch_trip" action="index.php" method="post" enctype="'.$GLOBALS['TYPO3_CONF_VARS']['SYS']['form_enctype'].'">
						<input type="hidden" name="id" value="'.$this->id.'" />';
            
			//$this->doc->JScode .= $this->doc->getDynTabMenuJScode();

			$CMD = t3lib_div::_GET('SLCMD');
    		if (is_array($CMD)) { 
        		$uid = array_keys($CMD['SELECT']['txchvaRegion']);
        		$addParams = '?SLCMD[SELECT][txchvaRegion]['.$uid[0].']=1';
    		}

    				// JavaScript
			$this->doc->JScodeArray['redirectUrls'] = $this->doc->redirectUrls(t3lib_extMgm::extRelPath('ch_trip').'mod_region/index.php'.$addParams);
            
				/*
			$this->doc->JScodeArray['jumpExt'] = '
				function jumpExt(URL,anchor)	{
					var anc = anchor?anchor:"";
					document.location = URL+(T3_THIS_LOCATION?"&returnUrl="+T3_THIS_LOCATION:"")+anc;
				}
			';
			*/

			$this->doc->JScodeArray['jumpExt'] = '
				function jumpToUrl(URL)	{
						document.location = URL;
					}';

			$this->doc->JScodeArray['highlight'] = '
					/*
					Highlight Link script
					By JavaScript Kit (http://javascriptkit.com)
					Over 400+ free scripts here!
					Above notice MUST stay entact for use
					*/

					function highlight(which,color){
						if (document.all||document.getElementById)
							which.style.backgroundColor=color
						}
				 	';
							
			$this->doc->postCode='
				<script language="javascript" type="text/javascript">
					script_ended = 1;
					if (top.fsMod) top.fsMod.recentIds["web"] = '.intval($this->id).';
				</script>
			';
			
			$this->content.=$this->doc->startPage("Trip: Regionen");
			$this->content.=$this->doc->header($LANG->getLL("title"));
			$this->content.=$this->doc->spacer(5);
			$this->content.=$this->doc->header($LANG->getLL("region").$title);
			$this->content.=$this->doc->section("",$this->doc->funcMenu($headerSection,
										t3lib_BEfunc::getFuncMenu(
												$this->id,
												"SET[function]",
												$this->MOD_SETTINGS["function"],
												$this->MOD_MENU["function"],
												'',
												t3lib_div::implodeArrayForUrl('SLCMD',is_array(t3lib_div::_GP('SLCMD')) ? t3lib_div::_GP('SLCMD') : array() )
										)));
			$this->content.=$this->doc->divider(5);
            
				// Render content:
			$this->moduleContent();

				// ShortCut
			if ($BE_USER->mayMakeShortcut())	{
				$this->content.=$this->doc->spacer(20).$this->doc->section("",$this->doc->makeShortcutIcon("id",implode(",",array_keys($this->MOD_MENU)),$this->MCONF["name"]));
			}

			$this->content.=$this->doc->spacer(10);
		
        } else {
				
                // If no access or if ID == zero
			$this->doc = t3lib_div::makeInstance("mediumDoc");
			$this->doc->backPath = $BACK_PATH;

			$this->content.=$this->doc->startPage("Trip: Regionen");
			$this->content.=$this->doc->header($LANG->getLL("title"));
			$this->content.=$this->doc->spacer(5);
			$this->content.=$this->doc->spacer(10);
		}
	}

	/**
	 * Prints out the module HTML
	 */
	function printContent()	{
		$this->content.=$this->doc->endPage();
		echo $this->content;
	}

	/**
	 * Generates the module content
	 */
	function moduleContent()	{
		global $LANG;
	
		switch((string)$this->MOD_SETTINGS["function"])	{
			
            		case 2:
				$this->sort = 'DESC';
				break;

			default:
				$this->sort = 'ASC';
				break;
		}

		$CMD = t3lib_div::_GET('SLCMD'); 
		if (is_array($CMD)) {
	
			$uid = array_keys($CMD['SELECT']['txchvaRegion']);
		
				// get rootline
			$this->region = t3lib_div::makeInstance("t3lib_treeView");
			$this->region->init();
			$this->region->table = 'tx_chtrip_region';
			$this->region->parentField = 'parent_uid';
			$this->region->treeName = 'tx_chtrip_region';
			$this->region->expandAll = 1;
			$tree = $this->region->getTree(intval($uid[0]));
			
			if($tree>0) {
					foreach ($this->region->tree as $key => $value) {
						$region[] = $value['row']['uid'];
					}
			} else {
				$region[] = intval($uid[0]);
			}

				// pidlist
			$this->conf['pidList'] = 2;  //$this->top->tree[0]['row']['pid'];
			
			$this->getType();
			
							
				//t3lib_div::loadTCA("tx_chtrip_location");
			
			$table = 'tx_chtrip_hotel';
			$results_at_a_time = 20;
			
			if (!$region) {
				$addWhere = 'AND location_f04338f846 IN (9999)';
			} else {
				$addWhere = 'AND location_f04338f846 IN ('.implode(',',$region).')';
			}

			$this->pb = t3lib_div::makeInstance("tx_ch_pagebrowser");

			if (t3lib_div::_GET('ter_pointer')) {
				$this->pb->ter_pointer = t3lib_div::_GET('ter_pointer');
			}	
				// Initializing the query parameters:
			$this->pb->internal["results_at_a_time"]=t3lib_div::intInRange($results_at_a_time,0,1000,9);		
				// Number of results to show in a listing.
			$this->pb->internal['results_at_a_time'] = $results_at_a_time;
		
				// The maximum number of "pages" in the browse-box: "Page 1", "Page 2", etc.
			$this->pb->internal["maxPages"]=t3lib_div::intInRange(5,0,1000,2); 
			$this->pb->internal["searchFieldList"]='';
			$this->pb->internal["orderByList"]= 'title';
			$this->pb->internal["orderBy"] = 'title';
			$this->pb->internal["descFlag"] =  $this->sort;
			$this->pb->internal["currentTable"] = $table;
			$this->pb->internal['pagefloat'] = 'center';
	
				// Get number of records:
			$res = $this->pb->pi_exec_query($table,1,$addWhere,'','','','',$this->conf['pidList']);
			list($this->pb->internal['res_count']) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
	
				// Make listing query, pass query to SQL database:
			$res = $this->pb->pi_exec_query($table,0,$addWhere,'','','','',$this->conf['pidList']);
		
				// fetch records
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$lines[]=$row; 
			}
			
				// add records to the output
			if (sizeOf($lines)) {
				$c=0;
				foreach ($lines as $k => $v) {
					$c++;
					$kill = $c % 2;
					
					foreach ($this->mm[$v['uid']] as $a => $b) {
						if ($this->type->hash[$b]) {
							$icon = $this->type->hash[$b][1];
							$title = $this->type->hash[$b][2];	
						}												
					}
					
					$cmd = 'onMouseover=highlight(this,\'yellow\') onMouseout="highlight(this,document.bgColor)" onClick="'.$this->DB_edit($table,$v['uid']).'"';
					switch ($kill) {					
						case 1:
							$tmp = preg_replace('/document\.bgColor/','\'#e7dba8\'',$cmd);
							$content.='<tr><td width="500px" class="bgcolor6"><table><tr><td><img title="'.$title.'" src="../../../../uploads/tx_chtrip/'.$icon.'"></td><td><a href="#" '.$tmp.'">'.$v['title'].'</a></td></tr></table></td></tr>';
							break;
						default:
							$content.='<tr><td width="500px"><table><tr><td><img title="'.$title.'" src="../../../../uploads/tx_chtrip/'.$icon.'"></td><td><a href="#" '.$cmd.'">'.$v['title'].'</a></td></tr></table></td></tr>';
							break;	
					}					
				}
				
			} else {
				$content = '<tr><td>Keine Treffer!</td></tr>'; 
			}
	
				// add pagebrowser
			$pagebrowser = $this->pb->pi_list_browseresults(1,'width="1%"',
																		array ( 'browseBoxWrap' => '',
																				'inactiveLinkWrap' => '<tr><td nowrap="nowrap"><p>|</p></td></tr>',
																				'activeLinkWrap' => '<tr><td style="background-color:#aaff00" nowrap="nowrap"><p>|</p></td></tr>',
																				'disabledLinkWrap' => '<tr><td nowrap="nowrap"><p>|</p></td></tr>',
																				'showResultsWrap' => '<table><tr><td align="left" nowrap="nowrap">|</td></tr></table>',
																				'browseLinksWrap' => '<table height="1%" style="background-color:#d0e4c9">|</table>',
																				)
																		);
		
			$content .= '<tr><td>'.$this->pb->resultCountMsg.'</td></tr>';
			
			$this->content .= '<table border="0" width="500px" cellspacing="0" cellpadding="0"><tr><td><table>'.$content.'</table></td><td valign="top">'.$pagebrowser.'</td></tr></table>';
			
		}		
	}


	function getType() {

			// get top elements
		$this->top = t3lib_div::makeInstance("t3lib_treeView");
		$this->top->init();
		$this->top->table = 'tx_chtrip_properties';
		$this->top->parentField = 'parent_uid';
		$this->top->treeName = 'tx_chtrip_properties';
		$this->top->expandAll = 0;
		$tree = $this->top->getTree('0');
		
		/*
			1. Typ
			2. Eigenschaften Suchrelevant
			3. Eigenschaften Allgemein
			4. Belegung
			5. Küche
			6. Ausstattung			
		*/
		
		if($tree>0) {
				foreach ($this->top->tree as $key => $value) {
					$this->top->hash[] = $value['row']['uid'];
				}
		}	

			// get type elements
		$this->type = t3lib_div::makeInstance("t3lib_treeView");
		$this->type->init();
		$this->type->table = 'tx_chtrip_properties';
		$this->type->parentField = 'parent_uid';
		$this->type->treeName = 'tx_chtrip_properties';
		$this->type->expandAll = 1;
		$this->type->makeHTML = 0;
		$this->type->fieldArray = Array('uid','title','icon');
		$tree = $this->type->getTree($this->top->tree[0]['row']['uid']);

		if($tree>0) {
				foreach ($this->type->tree as $key => $value) {
					$this->type->hash[$value['row']['uid']][] = $value['row']['uid'];
					$this->type->hash[$value['row']['uid']][] = $value['row']['icon'];
					$this->type->hash[$value['row']['uid']][] = $value['row']['title'];
				}
		}	

		$res = mysql_query('Select * from tx_chtrip_properties_mm');
		
			// fetch records
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$this->mm[$row['uid_local']][]=$row['uid_foreign']; 
		}


	}
	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/mod_region/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/mod_region/index.php']);
}

// Make instance:
$SOBE = t3lib_div::makeInstance('tx_chtrip_region');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>