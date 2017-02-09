<?php
/**
 * ************************************************************
 *  Copyright notice
 *  
 *  (c) 1999-2003 Kasper Skårhøj (kasper@typo3.com)
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
 *  A copy is found in the textfile GPL.txt and important notices to the license 
 *  from the author is found in LICENSE.txt distributed with these scripts.
 * 
 * 
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  This copyright notice MUST APPEAR in all copies of the script!
 * **************************************************************/


class tx_chtrip_db_list extends localRecordList {

	/**
	 * Traverses the table(s) to be listed and renders the output code for each:
	 * The HTML is accumulated in $this->HTMLcode
	 * Finishes off with a stopper-gif
	 * 
	 * @return	void
	 */
	function generateList()	{
		global $TCA;

		$table = $this->table;
		t3lib_div::loadTCA($table);

		$fields = $this->makeFieldList($table);
        
		if (is_array($this->setFields[$table]))	{
			$fields = array_intersect($fields,$this->setFields[$table]);
		} else {
			$fields = array();
		}
        
        print_r($fields);
        
		$this->HTMLcode.=$this->getTable($table, $this->id,implode(',',$fields));
	}

    

	/**
	 * Creates the listing of records from a single table
	 *
	 * @param	string		Table name
	 * @param	integer		Page id
	 * @param	string		List of fields to show in the listing. Pseudo fields will be added including the record header.
	 * @return	string		HTML table with the listing for the record.
	 */
	function getTable($table,$id,$rowlist)	{
		global $TCA;

			// Loading all TCA details for this table:
		t3lib_div::loadTCA($table);

			// Init
		$addWhere = '';
		$titleCol = $TCA[$table]['ctrl']['label'];
		$thumbsCol = $TCA[$table]['ctrl']['thumbnail'];
		$l10nEnabled = $TCA[$table]['ctrl']['languageField'] && $TCA[$table]['ctrl']['transOrigPointerField'] && !$TCA[$table]['ctrl']['transOrigPointerTable'];

			// Cleaning rowlist for duplicates and place the $titleCol as the first column always!
		$this->fieldArray=array();
		$this->fieldArray[] = $titleCol;	// Add title column
		if ($this->localizationView && $l10nEnabled)	{
			$this->fieldArray[] = '_LOCALIZATION_';
			$this->fieldArray[] = '_LOCALIZATION_b';
			$addWhere.=' AND '.$TCA[$table]['ctrl']['languageField'].'<=0';
		}
		if (!t3lib_div::inList($rowlist,'_CONTROL_'))	{
			$this->fieldArray[] = '_CONTROL_';
		}
		if ($this->showClipboard)	{
			$this->fieldArray[] = '_CLIPBOARD_';
		}
		if ($this->searchLevels)	{
			$this->fieldArray[]='_PATH_';
		}
			// Cleaning up:
		$this->fieldArray=array_unique(array_merge($this->fieldArray,t3lib_div::trimExplode(',',$rowlist,1)));
		if ($this->noControlPanels)	{
			$tempArray = array_flip($this->fieldArray);
			unset($tempArray['_CONTROL_']);
			unset($tempArray['_CLIPBOARD_']);
			$this->fieldArray = array_keys($tempArray);
		}

         print_r($this->fieldArray);
         
			// Creating the list of fields to include in the SQL query:
		$selectFields = $this->fieldArray;
		$selectFields[] = 'uid';
		$selectFields[] = 'pid';
		if ($thumbsCol)	$selectFields[] = $thumbsCol;	// adding column for thumbnails

		if (is_array($TCA[$table]['ctrl']['enablecolumns']))	{
			$selectFields = array_merge($selectFields,$TCA[$table]['ctrl']['enablecolumns']);
		}
		if ($TCA[$table]['ctrl']['type'])	{
			$selectFields[] = $TCA[$table]['ctrl']['type'];
		}
		if ($TCA[$table]['ctrl']['typeicon_column'])	{
			$selectFields[] = $TCA[$table]['ctrl']['typeicon_column'];
		}
		if ($l10nEnabled)	{
			$selectFields[] = $TCA[$table]['ctrl']['languageField'];
			$selectFields[] = $TCA[$table]['ctrl']['transOrigPointerField'];
		}
		if ($TCA[$table]['ctrl']['label_alt'])	{
			$selectFields = array_merge($selectFields,t3lib_div::trimExplode(',',$TCA[$table]['ctrl']['label_alt'],1));
		}
		$selectFields = array_unique($selectFields);		// Unique list!
		$selectFields = array_intersect($selectFields,$this->makeFieldList($table,1));		// Making sure that the fields in the field-list ARE in the field-list from TCA!
		$selFieldList = implode(',',$selectFields);		// implode it into a list of fields for the SQL-statement.

			// Create the SQL query for selecting the elements in the listing:
		$queryParts = $this->makeQueryArray($table,$id,$addWhere,$selFieldList);	// (API function from class.db_list.inc)        
        
        $this->setTotalItems($queryParts);		// Finding the total amount of records on the page (API function from class.db_list.inc)

			// Init:
		$dbCount = 0;
		$out = '';

			// If the count query returned any number of records, we perform the real query, selecting records.
		if ($this->totalItems)	{
			$result = $GLOBALS['TYPO3_DB']->exec_SELECT_queryArray($queryParts);
			$dbCount = $GLOBALS['TYPO3_DB']->sql_num_rows($result);
		}
			// If any records was selected, render the list:
		if ($dbCount)	{

            // Half line is drawn between tables:
            $theData = Array();
            if (!$this->table && !$rowlist)	{
                $theData[$titleCol] = '<img src="clear.gif" width="'.($GLOBALS['SOBE']->MOD_SETTINGS['bigControlPanel']?'230':'350').'" height="1" alt="" />';
                if (in_array('_CONTROL_',$this->fieldArray))	$theData['_CONTROL_']='';
                if (in_array('_CLIPBOARD_',$this->fieldArray))	$theData['_CLIPBOARD_']='';
            }
            $out.=$this->addelement(0,'',$theData,'',$this->leftMargin);

				// Header line is drawn
			$theData = Array();
			if ($this->disableSingleTableView)	{
				$theData[$titleCol] = '<span class="c-table">'.$GLOBALS['LANG']->sL($TCA[$table]['ctrl']['title'],1).'</span> ('.$this->totalItems.')';
			} else {
				$theData[$titleCol] = $this->linkWrapTable($table,'<span class="c-table">'.$GLOBALS['LANG']->sL($TCA[$table]['ctrl']['title'],1).'</span> ('.$this->totalItems.') <img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/'.($this->table?'minus':'plus').'bullet_list.gif','width="18" height="12"').' hspace="10" class="absmiddle" title="'.$GLOBALS['LANG']->getLL(!$this->table?'expandView':'contractView',1).'" alt="" />');
			}

				// CSH:
			$theData[$titleCol].= t3lib_BEfunc::cshItem($table,'',$this->backPath,'',FALSE,'margin-bottom:0px; white-space: normal;');

            $theUpIcon = ($table=='pages'&&$this->id&&isset($this->pageRow['pid'])) ? '<a href="'.htmlspecialchars($this->listURL($this->pageRow['pid'])).'"><img'.t3lib_iconWorks::skinImg('','gfx/i/pages_up.gif','width="18" height="16"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.upOneLevel',1).'" alt="" /></a>':'';
            $out.=$this->addelement(1,$theUpIcon,$theData,' class="c-headLineTable"','');

                // Fixing a order table for sortby tables
            $this->currentTable = array();
            $currentIdList = array();
            $doSort = ($TCA[$table]['ctrl']['sortby'] && !$this->sortField);

            $prevUid = 0;
            $prevPrevUid = 0;
            $accRows = array();	// Accumulate rows here
            while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result))	{
                $accRows[] = $row;
                $currentIdList[] = $row['uid'];
                if ($doSort)	{
                    if ($prevUid)	{
                        $this->currentTable['prev'][$row['uid']] = $prevPrevUid;
                        $this->currentTable['next'][$prevUid] = '-'.$row['uid'];
                        $this->currentTable['prevUid'][$row['uid']] = $prevUid;
                    }
                    $prevPrevUid = isset($this->currentTable['prev'][$row['uid']]) ? -$prevUid : $row['pid'];
                    $prevUid=$row['uid'];
                }
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($result);     
            
                // CSV initiated
            if ($this->csvOutput) $this->initCSV();

                // Render items:
            $this->CBnames=array();
            $this->duplicateStack=array();
            $this->eCounter=$this->firstElementNumber;

            $iOut = '';
            $cc = 0;
            foreach($accRows as $row)	{

                    // Forward/Backwards navigation links:
                list($flag,$code) = $this->fwd_rwd_nav($table);
                $iOut.=$code;

                    // If render item, increment counter and call function
                if ($flag)	{
                    $cc++;
                    
                    $iOut.=$this->renderListRow($table,$row,$cc,$titleCol,$thumbsCol);

                        // If localization view is enabled it means that the selected records are either default or All language and here we will not select translations which point to the main record:
                    if ($this->localizationView && $l10nEnabled)	{

                            // Look for translations of this record:
                        $translations = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
                            $selFieldList,
                            $table,
                            'pid='.$row['pid'].
                                ' AND '.$TCA[$table]['ctrl']['languageField'].'>0'.
                                ' AND '.$TCA[$table]['ctrl']['transOrigPointerField'].'='.intval($row['uid']).
                                t3lib_BEfunc::deleteClause($table)
                        );

                            // For each available translation, render the record:
                        foreach($translations as $lRow)	{
                            $iOut.=$this->renderListRow($table,$lRow,$cc,$titleCol,$thumbsCol,18);
                        }
                    }
                }

                    // Counter of total rows incremented:
                $this->eCounter++;
            }

                // The header row for the table is now created:
            $out.=$this->renderListHeader($table,$currentIdList);
        }

            // The list of records is added after the header:
        $out.=$iOut;

            // ... and it is all wrapped in a table:
        $out='
        <!--
            DB listing of elements:	"'.htmlspecialchars($table).'"
        -->
            <table border="0" cellpadding="0" cellspacing="0" class="typo3-dblist">
                '.$out.'
            </table>';

            // Output csv if...
        if ($this->csvOutput)	$this->outputCSV($table);	// This ends the page with exit.
		

			// Return content:
		return $out;
	}

	/******************************
	 *
	 * Various helper functions
	 *
	 ******************************/


	/**
	 * Setting the field names to display in extended list.
	 * Sets the internal variable $this->setFields
	 * 
	 * @return	void
	 */
	function setDispFields()	{

			// Getting from session:
		$dispFields = $GLOBALS['BE_USER']->getModuleData('tx_chtrip_db_list.php/displayFields');
        
		$dispFields_in = t3lib_div::_GP('displayFields');
		if (is_array($dispFields_in))	{
			reset($dispFields_in);
			$tKey = key($dispFields_in);
			$dispFields[$tKey]=$dispFields_in[$tKey];
			$GLOBALS['BE_USER']->pushModuleData('tx_chtrip_db_list.php/displayFields',$dispFields);
		}

			// Setting result:
		$this->setFields=$dispFields;
    }
    
    
    	/**
	 * Creates the control panel for a single record in the listing.
	 * 
	 * @param	string		The table
	 * @param	array		The record for which to make the control panel.
	 * @return	string		HTML table with the control panel (unless disabled)
	 */
	function makeControl($table,$row)	{
		global $TCA, $LANG, $SOBE;

			// Return blank, if disabled:
#		if ($this->dontShowClipControlPanels)	return '';

			// Initialize:
		t3lib_div::loadTCA($table);
		$cells=array();

			// If the listed table is 'pages' we have to request the permission settings for each page:
		if ($table=='pages')	{
			$localCalcPerms = $GLOBALS['BE_USER']->calcPerms(t3lib_BEfunc::getRecord('pages',$row['uid']));
		}

			// This expresses the edit permissions for this particular element:
		$permsEdit = ($table=='pages' && ($localCalcPerms&2)) || ($table!='pages' && ($this->calcPerms&16));

			// "Edit" link: ( Only if permissions to edit the page-record of the content of the parent page ($this->id)
		if ($permsEdit)	{
			$params='&edit['.$table.']['.$row['uid'].']=edit';
			$icon = '<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/edit2'.(!$TCA[$table]['ctrl']['readOnly']?'':'_d').'.gif','width="11" height="12"').' title="'.$LANG->getLL('edit',1).'" alt="" />';
			$cells[] = $this->wrapEditLink($icon, $params);

		}

			// If the extended control panel is enabled OR if we are seeing a single table:
		if ($SOBE->MOD_SETTINGS['bigControlPanel'] || $this->table)	{

					// "Info": (All records)
			
			$cells[]='<a href="#" onclick="'.htmlspecialchars('top.launchView(\''.$table.'\', \''.$row['uid'].'\'); return false;').'">'.
					'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/zoom2.gif','width="12" height="12"').' title="'.$LANG->getLL('showInfo',1).'" alt="" />'.
					'</a>';
			

				// If the table is NOT a read-only table, then show these links:
			if (!$TCA[$table]['ctrl']['readOnly'])	{

					// "Revert" link (history/undo)
				
				$cells[]='<a href="#" onclick="'.htmlspecialchars('return jumpExt(\''.$this->backPath.'show_rechis.php?element='.rawurlencode($table.':'.$row['uid']).'\',\'#latest\');').'">'.
						'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/history2.gif','width="13" height="12"').' title="'.$LANG->getLL('history',1).'" alt="" />'.
						'</a>';
				

					// Versioning:
				if (t3lib_extMgm::isLoaded('version'))	{
					$vers = t3lib_BEfunc::selectVersionsOfRecord($table, $row['uid'], $fields='uid');
					if (is_array($vers))	{	// If table can be versionized.
						if (count($vers)>1)	{
							$st = 'background-color: #FFFF00; font-weight: bold;';
							$lab = count($vers)-1;
						} else {
							$st = 'background-color: #9999cc; font-weight: bold;';
							$lab = 'V';
						}

						$cells[]='<a href="'.htmlspecialchars(t3lib_extMgm::extRelPath('version')).'cm1/index.php?table='.rawurlencode($table).'&uid='.rawurlencode($row['uid']).'" style="'.htmlspecialchars($st).'">'.
								$lab.
								'</a>';
					}
				}

					// "Edit Perms" link:
				if ($table=='pages' && $GLOBALS['BE_USER']->check('modules','web_perm'))	{
					$cells[]='<a href="'.htmlspecialchars($this->backPath.'mod/web/perm/index.php?id='.$row['uid'].'&return_id='.$row['uid'].'&edit=1').'">'.
							'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/perm.gif','width="7" height="12"').' title="'.$LANG->getLL('permissions',1).'" alt="" />'.
							'</a>';
				}

					// "Up/Down" links
				if ($permsEdit && $TCA[$table]['ctrl']['sortby']  && !$this->sortField)	{	//
					if (isset($this->currentTable['prev'][$row['uid']]))	{	// Up
						$params='&cmd['.$table.']['.$row['uid'].'][move]='.$this->currentTable['prev'][$row['uid']];
						$cells[]='<a href="#" onclick="'.htmlspecialchars('return jumpToUrl(\''.$SOBE->doc->issueCommand($params,-1).'\');').'">'.
								'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/button_up.gif','width="11" height="10"').' title="'.$LANG->getLL('moveUp',1).'" alt="" />'.
								'</a>';
					} else {
						$cells[]='<img src="clear.gif" '.t3lib_iconWorks::skinImg($this->backPath,'gfx/button_up.gif','width="11" height="10"',2).' alt="" />';
					}
					if ($this->currentTable['next'][$row['uid']])	{	// Down
						$params='&cmd['.$table.']['.$row['uid'].'][move]='.$this->currentTable['next'][$row['uid']];
						$cells[]='<a href="#" onclick="'.htmlspecialchars('return jumpToUrl(\''.$SOBE->doc->issueCommand($params,-1).'\');').'">'.
								'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/button_down.gif','width="11" height="10"').' title="'.$LANG->getLL('moveDown',1).'" alt="" />'.
								'</a>';
					} else {
						$cells[]='<img src="clear.gif" '.t3lib_iconWorks::skinImg($this->backPath,'gfx/button_down.gif','width="11" height="10"',2).' alt="" />';
					}
				}

					// "Hide/Unhide" links:
				$hiddenField = $TCA[$table]['ctrl']['enablecolumns']['disabled'];
				if ($permsEdit && $hiddenField && $TCA[$table]['columns'][$hiddenField] && (!$TCA[$table]['columns'][$hiddenField]['exclude'] || $GLOBALS['BE_USER']->check('non_exclude_fields',$table.':'.$hiddenField)))	{
					if ($row[$hiddenField])	{
						$params='&data['.$table.']['.$row['uid'].']['.$hiddenField.']=0';
						$cells[]='<a href="#" onclick="'.htmlspecialchars('return jumpToUrl(\''.$SOBE->doc->issueCommand($params,-1).'\');').'">'.
								'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/button_unhide.gif','width="11" height="10"').' title="'.$LANG->getLL('unHide'.($table=='pages'?'Page':''),1).'" alt="" />'.
								'</a>';
					} else {
						$params='&data['.$table.']['.$row['uid'].']['.$hiddenField.']=1';
						$cells[]='<a href="#" onclick="'.htmlspecialchars('return jumpToUrl(\''.$SOBE->doc->issueCommand($params,-1).'\');').'">'.
								'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/button_hide.gif','width="11" height="10"').' title="'.$LANG->getLL('hide'.($table=='pages'?'Page':''),1).'" alt="" />'.
								'</a>';
					}
				}

					// "Delete" link:
#				if (
#					($table=="pages" && ($localCalcPerms&4)) || ($table!="pages" && ($this->calcPerms&16)) && in_array('delRec',$shEl)
#					)	{
#					$params='&cmd['.$table.']['.$row['uid'].'][delete]=1';
#					$cells[]='<a href="#" onClick="if (confirm(unescape(\''.rawurlencode($LANG->getLL('deleteWarning')).'\'))) {jumpToUrl(\''.$GLOBALS['SOBE']->doc->issueCommand($params,-1).'\');} return false;"><img src="'.$this->backPath.'gfx/garbage.gif" width="11" height="12" border="0" align="top" title="'.$LANG->getLL('delete').'" /></a>';
#				}


			}
		}

			// If the record is edit-locked	by another user, we will show a little warning sign:
		if ($lockInfo=t3lib_BEfunc::isRecordLocked($table,$row['uid']))	{
			$cells[]='<a href="#" onclick="'.htmlspecialchars('alert('.$LANG->JScharCode($lockInfo['msg']).');return false;').'">'.
					'<img'.t3lib_iconWorks::skinImg('','gfx/recordlock_warning3.gif','width="17" height="12"').' title="'.htmlspecialchars($lockInfo['msg']).'" alt="" />'.
					'</a>';
		}


			// Compile items into a DIV-element:
		return '
											<!-- CONTROL PANEL: '.$table.':'.$row['uid'].' -->
											<div class="typo3-DBctrl">'.implode('',$cells).'</div>';
	}
    
    /********************************
	 *
	 * GUI
	 *
	 ********************************/

	/**
	 * Create the selector box for selecting fields to display from a table:
	 * 
	 * @param	string		Table name
	 * @param	boolean		If true, form-fields will be wrapped around the table.
	 * @return	string		HTML table with the selector box (name: displayFields['.$table.'][])
	 */
	function fieldSelectBox($table='',$formFields=1)	{
		global $TCA, $LANG;

			// Init:
		$table = $table ? $table : $this->table;
		t3lib_div::loadTCA($table);
		$formElements=array('','');
		if ($formFields)	{
			$formElements=array('<form action="'.htmlspecialchars($this->listURL()).'" method="post">','</form>');
		}

			// Load already selected fields, if any:
		$setFields=is_array($this->setFields[$table]) ? $this->setFields[$table] : array();

			// Request fields from table:
		$fields = $this->makeFieldList($table);

			// Add pseudo "control" fields
		$fields[]='_PATH_';
		$fields[]='_LOCALIZATION_';
		$fields[]='_CONTROL_';
		$fields[]='_CLIPBOARD_';

			// Create an option for each field:
		$opt=array();
		$opt[] = '<option value=""></option>';
		foreach($fields as $fN)	{
			$fL = is_array($TCA[$table]['columns'][$fN]) ? ereg_replace(':$','',$LANG->sL($TCA[$table]['columns'][$fN]['label'])) : '['.$fN.']';	// Field label
			$opt[] = '
											<option value="'.$fN.'"'.(in_array($fN,$setFields)?' selected="selected"':'').'>'.htmlspecialchars($fL).'</option>';
		}

			// Compile the options into a multiple selector box:
		$lMenu = '
										<select size="'.t3lib_div::intInRange(count($fields)+1,3,8).'" multiple="multiple" name="displayFields['.$table.'][]">'.implode('',$opt).'
										</select>
				';

			// Table with the search box:
		$content.= '<br />
		<table border="0" cellpadding="1" cellspacing="0"">
		'.$formElements[0].'
			<tr>
				<td bgcolor="#9BA1A8">
				<!--
					Field selector for extended table view:
				-->
				<table border="0" cellpadding="0" cellspacing="0" class="bgColor4" id="typo3-dblist-fieldSelect">
					<tr>
						<td>'.$lMenu.'</td>
						<td><input type="Submit" name="search" value="&gt;&gt;"></td>
					</tr>
					</table>
				</td>
			</tr>'.$formElements[1].'
		</table>
		';
		return $content;
	}
    
    /********************************
	 *
	 * tools
	 *
	 ********************************/


	function wrapEditLink($content, $params) {
		$onClick = t3lib_BEfunc::editOnClick($params,$this->backPath,-1);
		return '<a href="#" onclick="'.htmlspecialchars($onClick).'">'.$content.'</a>';
	}
}

?>