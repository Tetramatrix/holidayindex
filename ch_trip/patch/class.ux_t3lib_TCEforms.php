<?php 

class ux_t3lib_TCEforms extends t3lib_TCEforms {

	var $inline_tree;
	
	/**
	 * Add TBE_EDITOR_submitForm() to the add-wizard
	 *
	 * @return	string	html
	 */	

	function renderWizards($itemKinds,$wizConf,$table,$row,$field,&$PA,$itemName,$specConf,$RTE=0)  {

					 // Init:
			 $fieldChangeFunc = $PA['fieldChangeFunc'];
			 $item = $itemKinds[0];
			 $outArr = array();
			 $colorBoxLinks = array();
			 $fName = '['.$table.']['.$row['uid'].']['.$field.']';
			 $md5ID = 'ID'.t3lib_div::shortmd5($itemName);
			 $listFlag = '_list';

					 // Manipulate the field name (to be the true form field name) and remove a suffix-value if the item is a selector box with renderMode "singlebox":
			 if ($PA['fieldConf']['config']['form_type']=='select')  {
					 if ($PA['fieldConf']['config']['maxitems']<=1)  {       // Single select situation:
							 $listFlag = '';
					 } elseif ($PA['fieldConf']['config']['renderMode']=='singlebox')        {
							 $itemName.='[]';
							 $listFlag = '';
					 }
			 }

					 // traverse wizards:
			 if (is_array($wizConf) && !$this->disableWizards)       {
					 foreach($wizConf as $wid => $wConf)     {
							 if (substr($wid,0,1)!='_'
											 && (!$wConf['enableByTypeConfig'] || @in_array($wid,$specConf['wizards']['parameters']))
											 && ($RTE || !$wConf['RTEonly'])
									 )       {

											 // Title / icon:
									 $iTitle = htmlspecialchars($this->sL($wConf['title']));
									 if ($wConf['icon'])     {
											 $iDat = $this->getIcon($wConf['icon']);
											 $icon = '<img src="'.$iDat[0].'" '.$iDat[1][3].' border="0"'.t3lib_BEfunc::titleAltAttrib($iTitle).' />';
									 } else {
											 $icon = $iTitle;
									 }

											 //
									 switch((string)$wConf['type'])  {
											 case 'userFunc':
											 case 'script':
											 case 'popup':
											 case 'colorbox':
													 if (!$wConf['notNewRecords'] || t3lib_div::testInt($row['uid']))        {

																	 // Setting &P array contents:
															 $params = array();
															 $params['params'] = $wConf['params'];
															 $params['exampleImg'] = $wConf['exampleImg'];
															 $params['table'] = $table;
															 $params['uid'] = $row['uid'];
															 $params['pid'] = $row['pid'];
															 $params['field'] = $field;
															 $params['md5ID'] = $md5ID;
															 $params['returnUrl'] = $this->thisReturnUrl();

																	 // Resolving script filename and setting URL.
															 if (!strcmp(substr($wConf['script'],0,4), 'EXT:')) {
																	 $wScript = t3lib_div::getFileAbsFileName($wConf['script']);
																	 if ($wScript)   {
																			 $wScript = '../'.substr($wScript,strlen(PATH_site));
																	 } else break;
															 } else {
																	 $wScript = $wConf['script'];
															 }
															 $url = $this->backPath.$wScript.(strstr($wScript,'?') ? '' : '?');

																	 // If there is no script and the type is "colorbox", break right away:
															 if ((string)$wConf['type']=='colorbox' && !$wConf['script'])    { break; }

																	 // If "script" type, create the links around the icon:
															 if ((string)$wConf['type']=='script')   {
																	 $aUrl = $url.t3lib_div::implodeArrayForUrl('',array('P'=>$params));
																	 $outArr[]='<a href="'.htmlspecialchars($aUrl).'" onClick="'.$this->blur().'TBE_EDITOR_submitForm();alert(\'�nderungen werden jetzt gesichert!\');">'.
																			 $icon.
																			 '</a>';
															 } else {

																			 // ... else types "popup", "colorbox" and "userFunc" will need additional parameters:
																	 $params['formName'] = $this->formName;
																	 $params['itemName'] = $itemName;
																	 $params['fieldChangeFunc'] = $fieldChangeFunc;

																	 switch((string)$wConf['type'])  {
																			 case 'popup':
																			 case 'colorbox':
																							 // Current form value is passed as P[currentValue]!
																					 $addJS = $wConf['popup_onlyOpenIfSelected']?'if (!TBE_EDITOR_curSelected(\''.$itemName.$listFlag.'\')){alert('.$GLOBALS['LANG']->JScharCode($this->getLL('m_noSelItemForEdit')).'); return false;}':'';
																					 $curSelectedValues='+\'&P[currentSelectedValues]=\'+TBE_EDITOR_curSelected(\''.$itemName.$listFlag.'\')';
																					 $aOnClick=      $this->blur().
																											 $addJS.
																											 'vHWin=window.open(\''.$url.t3lib_div::implodeArrayForUrl('',array('P'=>$params)).'\'+\'&P[currentValue]=\'+TBE_EDITOR_rawurlencode('.$this->elName($itemName).'.value,200)'.$curSelectedValues.',\'popUp'.$md5ID.'\',\''.$wConf['JSopenParams'].'\');'.
																											 'vHWin.focus();return false;';
																							 // Setting "colorBoxLinks" - user LATER to wrap around the color box as well:
																					 $colorBoxLinks = Array('<a href="#" onclick="'.htmlspecialchars($aOnClick).'">','</a>');
																					 if ((string)$wConf['type']=='popup')    {
																							 $outArr[] = $colorBoxLinks[0].$icon.$colorBoxLinks[1];
																					 }
																			 break;
																			 case 'userFunc':
																					 $params['item'] = &$item;       // Reference set!
																					 $params['icon'] = $icon;
																					 $params['iTitle'] = $iTitle;
																					 $params['wConf'] = $wConf;
																					 $params['row'] = $row;
																					 $outArr[] = t3lib_div::callUserFunction($wConf['userFunc'],$params,$this);
																			 break;
																	 }
															 }

																	 // Hide the real form element?
															 if (is_array($wConf['hideParent']) || $wConf['hideParent'])     {
																	 $item = $itemKinds[1];  // Setting the item to a hidden-field.
																	 if (is_array($wConf['hideParent']))     {
																			 $item.= $this->getSingleField_typeNone_render($wConf['hideParent'], $PA['itemFormElValue']);
																	 }
															 }
													 }
											 break;
											 case 'select':
													 $fieldValue = array('config' => $wConf);
													 $TSconfig = $this->setTSconfig($table, $row);
													 $TSconfig[$field] = $TSconfig[$field]['wizards.'][$wid.'.'];
													 $selItems = $this->addSelectOptionsToItemArray($this->initItemArray($fieldValue), $fieldValue, $TSconfig, $field);

													 $opt = array();
													 $opt[] = '<option>'.$iTitle.'</option>';
													 foreach($selItems as $p)        {
															 $opt[] = '<option value="'.htmlspecialchars($p[1]).'">'.htmlspecialchars($p[0]).'</option>';
													 }
													 if ($wConf['mode']=='append')   {
															 $assignValue = $this->elName($itemName).'.value=\'\'+this.options[this.selectedIndex].value+'.$this->elName($itemName).'.value';
													 } elseif ($wConf['mode']=='prepend')    {
															 $assignValue = $this->elName($itemName).'.value+=\'\'+this.options[this.selectedIndex].value';
													 } else {
															 $assignValue = $this->elName($itemName).'.value=this.options[this.selectedIndex].value';
													 }
													 $sOnChange = $assignValue.';this.selectedIndex=0;'.implode('',$fieldChangeFunc);
													 $outArr[] = '<select name="_WIZARD'.$fName.'" onchange="'.htmlspecialchars($sOnChange).'">'.implode('',$opt).'</select>';
											 break;
									 }

											 // Color wizard colorbox:
									 if ((string)$wConf['type']=='colorbox') {
											 $dim = t3lib_div::intExplode('x',$wConf['dim']);
											 $dX = t3lib_div::intInRange($dim[0],1,200,20);
											 $dY = t3lib_div::intInRange($dim[1],1,200,20);
											 $color = $row[$field] ? ' bgcolor="'.htmlspecialchars($row[$field]).'"' : '';
											 $outArr[] = '<table border="0" cellpadding="0" cellspacing="0" id="'.$md5ID.'"'.$color.' style="'.htmlspecialchars($wConf['tableStyle']).'">
																	 <tr>
																			 <td>'.
																					 $colorBoxLinks[0].
																					 '<img src="clear.gif" width="'.$dX.'" height="'.$dY.'"'.t3lib_BEfunc::titleAltAttrib(trim($iTitle.' '.$row[$field])).' border="0" />'.
																					 $colorBoxLinks[1].
																					 '</td>
																	 </tr>
															 </table>';
									 }
							 }
					 }

							 // For each rendered wizard, put them together around the item.
					 if (count($outArr))     {
							 if ($wizConf['_HIDDENFIELD'])   $item = $itemKinds[1];

							 $outStr = '';
							 $vAlign = $wizConf['_VALIGN'] ? ' valign="'.$wizConf['_VALIGN'].'"' : '';
							 if (count($outArr)>1 || $wizConf['_PADDING'])   {
									 $dist = intval($wizConf['_DISTANCE']);
									 if ($wizConf['_VERTICAL'])      {
											 $dist = $dist ? '<tr><td><img src="clear.gif" width="1" height="'.$dist.'" alt="" /></td></tr>' : '';
											 $outStr = '<tr><td>'.implode('</td></tr>'.$dist.'<tr><td>',$outArr).'</td></tr>';
									 } else {
											 $dist = $dist ? '<td><img src="clear.gif" height="1" width="'.$dist.'" alt="" /></td>' : '';
											 $outStr = '<tr><td'.$vAlign.'>'.implode('</td>'.$dist.'<td'.$vAlign.'>',$outArr).'</td></tr>';
									 }
									 $outStr = '<table border="0" cellpadding="'.intval($wizConf['_PADDING']).'" cellspacing="0">'.$outStr.'</table>';
							 } else {
									 $outStr = implode('',$outArr);
							 }

							 if (!strcmp($wizConf['_POSITION'],'left'))      {
									 $outStr = '<tr><td'.$vAlign.'>'.$outStr.'</td><td'.$vAlign.'>'.$item.'</td></tr>';
							 } elseif (!strcmp($wizConf['_POSITION'],'top')) {
									 $outStr = '<tr><td>'.$outStr.'</td></tr><tr><td>'.$item.'</td></tr>';
							 } elseif (!strcmp($wizConf['_POSITION'],'bottom'))      {
									 $outStr = '<tr><td>'.$item.'</td></tr><tr><td>'.$outStr.'</td></tr>';
							 } else {
									 $outStr = '<tr><td'.$vAlign.'>'.$item.'</td><td'.$vAlign.'>'.$outStr.'</td></tr>';
							 }

							 $item = '<table border="0" cellpadding="0" cellspacing="0">'.$outStr.'</table>';
					 }
			 }
			 return $item;
	 }
	 
}