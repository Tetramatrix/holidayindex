<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Chi Hoang ()
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
$LANG->includeLLFile("EXT:ch_trip/mod_setup/locallang.php");

require_once ('../../../../tslib/class.tslib_pibase.php');
require_once (PATH_t3lib.'class.t3lib_browsetree.php');
require_once (PATH_t3lib.'class.t3lib_basicfilefunc.php');
require_once (PATH_t3lib.'class.t3lib_extfilefunc.php');

require_once (t3lib_extMgm::extPath('ch_trip').'lib/class.tx_chtrip_scriptclasses.php');
require_once (t3lib_extMgm::extPath('ch_trip').'lib/class.tx_chtrip_base.php');
require_once (t3lib_extMgm::extPath('ch_trip').'lib/class.tx_chtrip_t3lib_xml.php');

$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]


class tx_chtrip_setup extends tx_chtrip_scriptclasses {
	var $pageinfo;
	var $name = array( '92' => 'Accommodation', '136' => 'Activity');

	function doStartRegionXML() {

		$piBase = t3lib_div::makeInstance('tslib_pibase');
		
		$obj = t3lib_div::makeInstance('tx_chtrip_base');
		$obj->init($piBase,$piBase->cObj);
		$obj->lConf = $obj->getExtConf($this->targetPage);
				
		# Do query array
		$obj->doObjArray();
        
		# Make pulldownmenu 
		$obj->makePullDownMenu($obj->parent_uids); 
    
        	$content = '<table><tr class="tableheader"><td>Region</td><td>Treffer</td></tr>';
        	foreach($obj->pullDownMenu as $k => $v) {
            		$content.='<tr><td>'.$k.'</td><td>'.(sizeOf($v)-1).'</td></tr>';
        	}
		$k = array_keys($obj->parent_uids);
        	$content.= '<tr><td colspan="2">Datei: '.$this->path.'getRegions'.$this->name[$k[0]].'.xml'.'</td></tr></table>';
        
		# Do xml output of pulldownmenu
		$className=t3lib_div::makeInstanceClassName("tx_chtrip_t3lib_xml");		
		$xmlObj = new $className('amiciditalia_finds');
		$xmlObj->setRecFields('tx_chtrip_region','title,finds');	// More fields here...
		$xmlObj->renderHeader();
		$xmlObj->indent(1);
		$xmlObj->newLevel('parameters',1);
		$xmlObj->indent(1);		
		$xmlObj->lines[] = $xmlObj->Icode.$xmlObj->fieldWrapPageId(intval($this->targetPage),'true');
		$xmlObj->indent(0);
		$xmlObj->newLevel('parameters',0);	
		$xmlObj->renderRecords('tx_chtrip_region',$obj->pullDownMenu);
        	$xmlObj->renderFooter();
        
        	$typo3root = str_replace(t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST'),'',t3lib_div::getIndpEnv('TYPO3_SITE_URL'));
          
		# Write xml file
		$cmds = array(	'data' => 'getRegions'.$this->name[$k[0]].'.xml',
						'target' => t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT').$typo3root.$this->path
					);
		$fileObj=t3lib_div::makeInstance('t3lib_extFileFunctions');
		$fileObj->func_newfile($cmds);
		$result = t3lib_div::writeFile(implode('',array_reverse($cmds)),$xmlObj->getResult());	     
        
        	return $content;
	}
    
	function directAccess() {
	
		$piBase = t3lib_div::makeInstance('tslib_pibase');
		
		$obj = t3lib_div::makeInstance('tx_chtrip_base');
		$obj->init($piBase,$piBase->cObj);        
		$obj->lConf = $obj->getExtConf($this->targetPage);     
		
		# Do query array
		$obj->doObjArray();
		
		# Do search
		$params = array( 'region' => '9999',
				'allTypes' => 'true',
				);
	
		$finds = $obj->find($params);
		$finds = $obj->sortFinds($params,$finds);
		
		$c=0;
		foreach ($finds as $k => $v) {
		$c++;
		$content.='<tr><td>'.$c.'.) <a href="#" onClick="'.$this->DB_edit('tx_chtrip_location',$v['uid']).'">'.$v['title'].'</a></td></tr>';       
		}        
		return $content;    
	}

    function realurlLocation() {
    
        $piBase = t3lib_div::makeInstance('tslib_pibase');
        
        $obj = t3lib_div::makeInstance('tx_chtrip_base');
        $obj->init($piBase,$piBase->cObj);        
        $obj->lConf = $obj->getExtConf($this->targetPage);     
        
        # Do query array
        $obj->doObjArray();

		# Do search
        $params = array( 'region' => '9999',
                         'allTypes' => 'true',
                        );

        $finds = $obj->find($params);
        $finds = $obj->sortFinds($params,$finds);
        
        $realurl = array ('valueMap' => array ());        
        foreach ($finds as $k => $v) {
            $realurl['valueMap']['\''.$v['title'].'\''] = '\''.$v['uid'].'\'';          
        }        
        
        print_r($realurl);
        
        #return $content;    
    }
    
     function realurlAccommodation() {     
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(	'tx_chtrip_accommodation.*',
														'tx_chtrip_accommodation',
														'pid='.$this->lConf['sysfolder'].
														' AND tx_chtrip_accommodation.deleted=0 AND tx_chtrip_accommodation.hidden=0'
														);
        $c=0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            if ($realurl['valueMap']['\''.$row['title'].'\'']) {
                $c++;
                $realurl['valueMap']['\''.$row['title'].$c.'\''] = '\''.$row['uid'].'\'';
            } else {
                $realurl['valueMap']['\''.$row['title'].'\''] = '\''.$row['uid'].'\'';
            }
        }        
        print_r($realurl);
     }

	/**
	 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
	 */
	function menuConfig()	{
		global $LANG;
		$this->MOD_MENU = Array (
			"function" => Array (
				"1" => $LANG->getLL("function1"),
				"2" => $LANG->getLL("function2"),
				"3" => $LANG->getLL("function3"),
				"4" => $LANG->getLL("function4"),
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

				// Draw the header.
			$this->doc = t3lib_div::makeInstance("mediumDoc");
			$this->doc->backPath = $BACK_PATH;
			$this->doc->form = '<form name="ch_trip" action="index.php" method="post" enctype="'.$GLOBALS['TYPO3_CONF_VARS']['SYS']['form_enctype'].'">
							<input type="hidden" name="id" value="'.$this->id.'" />';
							
				// JavaScript
			$this->doc->JScode = '
				<script language="javascript" type="text/javascript">
					function jumpToUrl(URL)	{
						document.location = URL;
					}
				</script>
			';
			
			$this->doc->JScode .= $this->doc->getDynTabMenuJScode();
			
			$this->doc->postCode='
				<script language="javascript" type="text/javascript">
					script_ended = 1;
					if (top.fsMod) top.fsMod.recentIds["web"] = '.intval($this->id).';
				</script>
			';

			$this->doc->inDocStyles='			
				.rolloverMain	{background-color:#e3dfdb;}
				.rolloutMain	{}
			';

			$this->doc->bodyTagAdditions='onLoad="refresh()"';		
			
			//$headerSection = $this->doc->getHeader("pages",$this->pageinfo,$this->pageinfo["_thePath"])."<br>".$LANG->sL("LLL:EXT:lang/locallang_core.php:labels.path").": ".t3lib_div::fixed_lgd_pre($this->pageinfo["_thePath"],50);

			$this->content.=$this->doc->startPage("Trip: Setup");
			$this->content.=$this->doc->header($LANG->getLL("title"));
			$this->content.=$this->doc->spacer(5);
			$this->content.=$this->doc->section("",$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,"SET[function]",$this->MOD_SETTINGS["function"],$this->MOD_MENU["function"])));
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

			$this->content.=$this->doc->startPage($LANG->getLL("title"));
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
	
			// get all GP data for extension
		$inData = t3lib_div::_GP('tx_chtripM1');

		$CMD = t3lib_div::_GET('SLCMD'); 
		if (is_array($CMD)) {
			$targetPageID[0]= array_keys($CMD['SELECT']['txchvaSetup']);
			$targetPageID=$targetPageID[0][0];
			$res = mysql_query('select * from pages where uid='.$targetPageID.' and deleted=0 and hidden=0');
				// fetch records
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$title = $row['title'];
		}
  
		switch((string)$this->MOD_SETTINGS["function"])	{
			
           	case 1:

            	/***** start ********/
				/***** target page ********/
				/**** TAB 1 data *****/				
               			$row = array();
				
				if($targetPageID) {
					$msg = '<strong>'.$title.'</strong>';
				} else {
					$msg = '<strong>Please select a page! Thank you!</strong>';					
				}		
 
				$output = '<div margin-right:14px">'.$msg.'</div>';
				
                $row[] = '<input type="hidden" name="tx_chtripM1[targetPageID]" value="'.$targetPageID.'" />';		
				$row[] = '<input type="hidden" name="tx_chtripM1[targetPageSel]" value="'.$targetPageSel.'" />';                

				$row[] = '<tr class="tableheader bgColor5"><td colspan="2">'.$LANG->getLL('f1.tab2.section.targetPage').'</td></tr>';		
				$row[] = '
					<tr class="bgColor4">
					<td valign="top"><strong>'.$LANG->getLL('f1.tab2.section.targetPage.label').'</strong></td>
					<td valign="top">'.$output.'<div align="right" style="margin-right:14px;margin-top:10px;margin-bottom:10px"></div>
					</td>
					</tr>';
            
					//now, compose TAB menu array for TAB2
				$menuItems[] = array(
					'label' => $LANG->getLL('f1.tab2'),
					'content' => '<table border="0" cellpadding="1" cellspacing="1" width="100%">'.implode('',$row).'</table>',
					'description' => $LANG->getLL('f1.tab2.description'),
					'linkTitle' => '',
					'stateIcon' => $targetPageID != '' ? -1 : 0
				);
				/**** TAB 1 data *****/

				/***** start ********/
				/***** target folder ********/
				/**** TAB 2 data *****/
				$row = array();
		
                		if ($targetPageID != '') {
                    			$row[] = '<tr class="tableheader bgColor5"><td colspan="2">'.$LANG->getLL('f1.tab4.section.targetFolder').'</td></tr>';
                   			$row[] = '<tr class="bgColor4"><td><div>'.$LANG->getLL('f1.tab4.section.targetFolder.label').
                    
					'</div><div style="margin-top:10px;" align="left"><input style="width:440px;" type="text" name="tx_chtripM1[targetFolder]" value="'.$this->MCONF['uploadPath'].'" '.($_POST['submitTargetFolder'] ? 'disabled' : '').'>'.
					'</div><div style="margin-top:10px; align="right"><input type="submit" name="submitTargetFolder" value="'.$LANG->getLL('f1.tab4.section.targetFolder.submit',1).'" '.($_POST['submitTargetFolder'] ? 'disabled' : '').'></div></td></tr>';		
                		}
                
				$menuItems[] = array(
					'label' => $LANG->getLL('f1.tab4'),
					'content' => $targetPageID != '' ? '<table border="0" cellpadding="1" cellspacing="1" width="100%">'.implode('',$row).'</table>' : '',
					'description' => $LANG->getLL('f1.tab4.description'),
					'linkTitle' => '',
					'stateIcon' => $_POST['submitTargetFolder'] != '' ? -1 : 0
				);
		
				/***** TAB 2 data ********/	
                
				/***** start ********/
				/***** updating xml ********/
				/**** TAB 3 data *****/
				$row = array();
				
				if (!$_POST['submitRefresh']) {
                        
					$row[] = '<input type="hidden" name="tx_chtripM1[targetFolder]" value="'.$_POST['tx_chtripM1']['targetFolder'].'">';				
					$row[] = '<input type="hidden" name="submitTargetFolder" value="true">';				
					$row[] = '<tr class="tableheader bgColor5"><td colspan="2">'.$LANG->getLL('f1.tab5.section.refresh').'</td></tr>';
					$row[] = '<tr class="bgColor4"><td>'.$LANG->getLL('f1.tab5.section.refresh.label').'<div align="right"><input type="submit" name="submitRefresh" value="'.$LANG->getLL('f1.tab5.section.refresh.submit',1).'" '.($_POST['importNow'] ? 'disabled' : '').' onclick="return confirm(\''.$LANG->getLL('f1.tab5.section.refresh.sure',1).'\');"></div></td></tr>';
				
                } else {             

                    $this->targetPage = $_POST['tx_chtripM1']['targetPageID'];
                    $this->path = $_POST['tx_chtripM1']['targetFolder'];
          
                    $content = $this->doStartRegionXML();
					
					$row[] = '<tr class="tableheader bgColor5"><td colspan="2">'.$LANG->getLL('f1.tab5.section.refresh').'</td></tr>';
					$row[] = '<tr class="bgColor4"><td colspan="2">'.$content.'</td></tr>';
					$row[] = '<tr class="bgColor4"><td colspan="2">'.$LANG->getLL('f1.tab5.section.refresh.succesfully').'</td></tr>';
				}
		
				$menuItems[] = array(
					'label' => $LANG->getLL('f1.tab5'),
					'content' => $_POST['submitTargetFolder'] ? '<table border="0" cellpadding="1" cellspacing="1" width="100%">'.implode('',$row).'</table>' : '',
					'description' => $LANG->getLL('f1.tab5.description'),
					'linkTitle' => '',
					'stateIcon' => $_POST['submitRefresh']  ? -1 : 0
				);
		
				/***** TAB 3 data ********/	
			break;
            
            case 2:

            	/***** start ********/
				/***** target page ********/
				/**** TAB 1 data *****/				
                $row = array();
                
                		$treeView = t3lib_div::makeInstance('tx_chtrip_localPageTree');
				$treeView->ext_IconMode = true;
				$treeView->thisScript = 'index.php';
                		$treeView->domIdPrefix = 'targetPage';                
				$output = $treeView->getBrowsableTree();
				$output = '<div style="width:250px; height:350px;overflow:auto;background-color:#f7f3ef;margin-right:14px">'.$output.'</div>';
                
                		$row[] = '<input type="hidden" name="tx_chtripM1[targetPageID]" value="'.$targetPageID.'" />';		
				$row[] = '<input type="hidden" name="tx_chtripM1[targetPageSel]" value="'.$targetPageSel.'" />';                

				$row[] = '<tr class="tableheader bgColor5"><td colspan="2">'.$LANG->getLL('f1.tab2.section.targetPage').'</td></tr>';		
				$row[] = '
                        <tr class="bgColor4">
                            <td valign="top"><strong>'.$LANG->getLL('f1.tab2.section.targetPage.label').'</strong></td>
                            <td valign="top">'.$LANG->getLL('f1.tab2.section.targetPage.tree').$output.'<div align="right" style="margin-right:14px;margin-top:10px;margin-bottom:10px"><input type="submit" value="Submit" '.($targetPageID != '' ? 'disabled' : '').'/></div>
                            </td>
                        </tr>';           
            
				//now, compose TAB menu array for TAB2
				$menuItems[] = array(
					'label' => $LANG->getLL('f1.tab2'),
					'content' => '<table border="0" cellpadding="1" cellspacing="1" width="100%">'.implode('',$row).'</table>',
					'description' => $LANG->getLL('f1.tab2.description'),
					'linkTitle' => '',
					'stateIcon' => $targetPageID != '' ? -1 : 0
				);
				/**** TAB 1 data *****/	

                		/***** start ********/
				/***** list ********/
				/**** TAB 2 data *****/				
                $row = array();
                
                if ($targetPageID != '') {

                    $this->targetPage = $_POST['tx_chtripM1']['targetPageID'];
                    $content = '<table>'.$this->directAccess().'</table>';
                    
                    $row[] = '<tr class="tableheader bgColor5"><td colspan="2">'.$LANG->getLL('f2.tab3.section.refresh').'</td></tr>';
                    $row[] = '<tr class="bgColor4"><td colspan="2">'.$content.'</td></tr>';
                    $row[] = '<tr class="bgColor4"><td colspan="2">'.$LANG->getLL('f2.tab3.section.refresh.succesfully').'</td></tr>';
                }
            
				$menuItems[] = array(
					'label' => $LANG->getLL('f2.tab3'),
					'content' => $targetPageID != '' ? '<table border="0" cellpadding="1" cellspacing="1" width="100%">'.implode('',$row).'</table>' : '',
					'description' => $LANG->getLL('f2.tab3.description'),
					'linkTitle' => '',
					'stateIcon' => $targetPageID != '' ? -1 : 0
				);
            
            break;           

           case 3:

            	/***** start ********/
				/***** target page ********/
				/**** TAB 1 data *****/				
                $row = array();
                
                $treeView = t3lib_div::makeInstance('tx_chtrip_localPageTree');
				$treeView->ext_IconMode = true;
				$treeView->thisScript = 'index.php';
                $treeView->domIdPrefix = 'targetPage';                
				$output = $treeView->getBrowsableTree();
				$output = '<div style="width:250px; height:350px;overflow:auto;background-color:#f7f3ef;margin-right:14px">'.$output.'</div>';
                
                $row[] = '<input type="hidden" name="tx_chtripM1[targetPageID]" value="'.$targetPageID.'" />';		
				$row[] = '<input type="hidden" name="tx_chtripM1[targetPageSel]" value="'.$targetPageSel.'" />';                

				$row[] = '<tr class="tableheader bgColor5"><td colspan="2">'.$LANG->getLL('f1.tab2.section.targetPage').'</td></tr>';		
				$row[] = '
                        <tr class="bgColor4">
                            <td valign="top"><strong>'.$LANG->getLL('f1.tab2.section.targetPage.label').'</strong></td>
                            <td valign="top">'.$LANG->getLL('f1.tab2.section.targetPage.tree').$output.'<div align="right" style="margin-right:14px;margin-top:10px;margin-bottom:10px"><input type="submit" value="Submit" '.($targetPageID != '' ? 'disabled' : '').'/></div>
                            </td>
                        </tr>';           
            
				//now, compose TAB menu array for TAB2
				$menuItems[] = array(
					'label' => $LANG->getLL('f1.tab2'),
					'content' => '<table border="0" cellpadding="1" cellspacing="1" width="100%">'.implode('',$row).'</table>',
					'description' => $LANG->getLL('f1.tab2.description'),
					'linkTitle' => '',
					'stateIcon' => $targetPageID != '' ? -1 : 0
				);
				/**** TAB 1 data *****/	

                /***** start ********/
				/***** list ********/
				/**** TAB 2 data *****/				
                $row = array();
                
                if ($targetPageID != '') {

                    $this->targetPage = $_POST['tx_chtripM1']['targetPageID'];                    
                    $content = '<table>'.$this->realurlLocation().'</table>';
                    
                    $row[] = '<tr class="tableheader bgColor5"><td colspan="2">'.$LANG->getLL('f2.tab3.section.refresh').'</td></tr>';
                    $row[] = '<tr class="bgColor4"><td colspan="2">'.$content.'</td></tr>';
                    $row[] = '<tr class="bgColor4"><td colspan="2">'.$LANG->getLL('f2.tab3.section.refresh.succesfully').'</td></tr>';
                }
            
				$menuItems[] = array(
					'label' => $LANG->getLL('f2.tab3'),
					'content' => $targetPageID != '' ? '<table border="0" cellpadding="1" cellspacing="1" width="100%">'.implode('',$row).'</table>' : '',
					'description' => $LANG->getLL('f2.tab3.description'),
					'linkTitle' => '',
					'stateIcon' => $targetPageID != '' ? -1 : 0
				);
            
            break;
        
            case 4:

            	/***** start ********/
				/***** target page ********/
				/**** TAB 1 data *****/				
                $row = array();
                
                $treeView = t3lib_div::makeInstance('tx_chtrip_localPageTree');
				$treeView->ext_IconMode = true;
				$treeView->thisScript = 'index.php';
                $treeView->domIdPrefix = 'targetPage';                
				$output = $treeView->getBrowsableTree();
				$output = '<div style="width:250px; height:350px;overflow:auto;background-color:#f7f3ef;margin-right:14px">'.$output.'</div>';
                
                $row[] = '<input type="hidden" name="tx_chtripM1[targetPageID]" value="'.$targetPageID.'" />';		
				$row[] = '<input type="hidden" name="tx_chtripM1[targetPageSel]" value="'.$targetPageSel.'" />';                

				$row[] = '<tr class="tableheader bgColor5"><td colspan="2">'.$LANG->getLL('f1.tab2.section.targetPage').'</td></tr>';		
				$row[] = '
                        <tr class="bgColor4">
                            <td valign="top"><strong>'.$LANG->getLL('f1.tab2.section.targetPage.label').'</strong></td>
                            <td valign="top">'.$LANG->getLL('f1.tab2.section.targetPage.tree').$output.'<div align="right" style="margin-right:14px;margin-top:10px;margin-bottom:10px"><input type="submit" value="Submit" '.($targetPageID != '' ? 'disabled' : '').'/></div>
                            </td>
                        </tr>';           
            
				//now, compose TAB menu array for TAB2
				$menuItems[] = array(
					'label' => $LANG->getLL('f1.tab2'),
					'content' => '<table border="0" cellpadding="1" cellspacing="1" width="100%">'.implode('',$row).'</table>',
					'description' => $LANG->getLL('f1.tab2.description'),
					'linkTitle' => '',
					'stateIcon' => $targetPageID != '' ? -1 : 0
				);
				/**** TAB 1 data *****/	

                /***** start ********/
				/***** list ********/
				/**** TAB 2 data *****/				
                $row = array();
                
                if ($targetPageID != '') {

                    $this->targetPage = $_POST['tx_chtripM1']['targetPageID'];                   
                    $content = '<table>'.$this->realurlAccommodation().'</table>';
                    
                    $row[] = '<tr class="tableheader bgColor5"><td colspan="2">'.$LANG->getLL('f2.tab3.section.refresh').'</td></tr>';
                    $row[] = '<tr class="bgColor4"><td colspan="2">'.$content.'</td></tr>';
                    $row[] = '<tr class="bgColor4"><td colspan="2">'.$LANG->getLL('f2.tab3.section.refresh.succesfully').'</td></tr>';
                }
            
				$menuItems[] = array(
					'label' => $LANG->getLL('f2.tab3'),
					'content' => $targetPageID != '' ? '<table border="0" cellpadding="1" cellspacing="1" width="100%">'.implode('',$row).'</table>' : '',
					'description' => $LANG->getLL('f2.tab3.description'),
					'linkTitle' => '',
					'stateIcon' => $targetPageID != '' ? -1 : 0
				);
            
            break;
            
            
		}        
        
        // finally, print out the whole tabmenu
        //getDynTabMenu($menuItems,$identString,$toggle=0,$foldout=FALSE,$newRowCharLimit=50,$noWrap=1,$fullWidth=FALSE,$defaultTabIndex=1)
        $content = $this->doc->getDynTabMenu($menuItems,'tx_chtripM1',0,'',40);
        $this->content .= $content;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/mod_setup/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/mod_setup/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_chtrip_setup');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>