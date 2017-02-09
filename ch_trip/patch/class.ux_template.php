<?php
/***************************************************************
*  Copyright notice
*
*  (c) 1999-2005 Kasper Skaarhoj (kasperYYYY@typo3.com)
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
***************************************************************/
/**
 * Contains class with layout/output function for TYPO3 Backend Scripts
 *
 * $Id: template.php,v 1.41 2005/04/13 23:12:00 mundaun Exp $
 * Revised for TYPO3 3.6 2/2003 by Kasper Skaarhoj
 * XHTML-trans compliant
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *

 *
 * TOTAL FUNCTIONS: 49
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */



if (!defined('TYPO3_MODE'))	die("Can't include this file directly.");

/**
 * TYPO3 Backend Template Class
 *
 * This class contains functions for starting and ending the HTML of backend modules
 * It also contains methods for outputting sections of content.
 * Further there are functions for making icons, links, setting form-field widths etc.
 * Color scheme and stylesheet definitions are also available here.
 * Finally this file includes the language class for TYPO3's backend.
 *
 * After this file $LANG and $TBE_TEMPLATE are global variables / instances of their respective classes.
 * This file is typically included right after the init.php file,
 * if language and layout is needed.
 *
 * Please refer to Inside TYPO3 for a discussion of how to use this API.
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class ux_template extends template {

	/**
	 * Creates a DYNAMIC tab-menu where the tabs are switched between with DHTML.
	 * Should work in MSIE, Mozilla, Opera and Konqueror. On Konqueror I did find a serious problem: <textarea> fields loose their content when you switch tabs!
	 *
	 * @param	array		Numeric array where each entry is an array in itself with associative keys: "label" contains the label for the TAB, "content" contains the HTML content that goes into the div-layer of the tabs content. "description" contains description text to be shown in the layer. "linkTitle" is short text for the title attribute of the tab-menu link (mouse-over text of tab). "stateIcon" indicates a standard status icon (see ->icon(), values: -1, 1, 2, 3). "icon" is an image tag placed before the text.
	 * @param	string		Identification string. This should be unique for every instance of a dynamic menu!
	 * @param	integer		If "1", then enabling one tab does not hide the others - they simply toggles each sheet on/off. This makes most sense together with the $foldout option. If "-1" then it acts normally where only one tab can be active at a time BUT you can click a tab and it will close so you have no active tabs.
	 * @param	boolean		If set, the tabs are rendered as headers instead over each sheet. Effectively this means there is no tab menu, but rather a foldout/foldin menu. Make sure to set $toggle as well for this option.
	 * @param	integer		Character limit for a new row.
	 * @param	boolean		If set, tab table cells are not allowed to wrap their content
	 * @param	boolean		If set, the tabs will span the full width of their position
	 * @param	integer		Default tab to open (for toggle <=0). Value corresponds to integer-array index + 1 (index zero is "1", index "1" is 2 etc.). A value of zero (or something non-existing) will result in no default tab open.
	 * @return	string		JavaScript section for the HTML header.
	 */
	function getDynTabMenu($menuItems,$identString,$toggle=0,$foldout=FALSE,$newRowCharLimit=50,$noWrap=1,$fullWidth=FALSE,$defaultTabIndex=1)	{
		$content = '';

		if (is_array($menuItems))	{

				// Init:
			$options = array(array());
			$divs = array();
			$JSinit = array();
			$id = 'DTM-'.t3lib_div::shortMD5($identString);
			$noWrap = $noWrap ? ' nowrap="nowrap"' : '';

				// Traverse menu items
			$c=0;
			$tabRows=0;
			$titleLenCount = 0;
			foreach($menuItems as $index => $def) {
				$index+=1;	// Need to add one so checking for first index in JavaScript is different than if it is not set at all.

					// Switch to next tab row if needed
				if (!$foldout && $titleLenCount>$newRowCharLimit)	{	// 50 characters is probably a reasonable count of characters before switching to next row of tabs.
					$titleLenCount=0;
					$tabRows++;
					$options[$tabRows] = array();
				}

				if ($toggle==1)	{
					$onclick = 'this.blur(); DTM_toggle("'.$id.'","'.$index.'"); return false;';
				} else {
					$onclick = 'this.blur(); DTM_activate("'.$id.'","'.$index.'", '.($toggle<0?1:0).'); TBE_EDITOR_submitForm(); return false;';
				}

				$isActive = strcmp($def['content'],'');

				$mouseOverOut = 'onmouseover="DTM_mouseOver(this);" onmouseout="DTM_mouseOut(this);"';

				if (!$foldout)	{
						// Create TAB cell:
					$options[$tabRows][] = '
							<td class="'.($isActive ? 'tab' : 'disabled').'" id="'.$id.'-'.$index.'-MENU"'.$noWrap.$mouseOverOut.'>'.
							($isActive ? '<a href="#" onclick="'.htmlspecialchars($onclick).'"'.($def['linkTitle'] ? ' title="'.htmlspecialchars($def['linkTitle']).'"':'').'>' : '').
							$def['icon'].
							($def['label'] ? htmlspecialchars($def['label']) : '&nbsp;').
							$this->icons($def['stateIcon'],'margin-left: 10px;').
							($isActive ? '</a>' :'').
							'</td>';
					$titleLenCount+= strlen($def['label']);
				} else {
						// Create DIV layer for content:
					$divs[] = '
						<div class="'.($isActive ? 'tab' : 'disabled').'" id="'.$id.'-'.$index.'-MENU"'.$mouseOverOut.'>'.
							($isActive ? '<a href="#" onclick="'.htmlspecialchars($onclick).'"'.($def['linkTitle'] ? ' title="'.htmlspecialchars($def['linkTitle']).'"':'').'>' : '').
							$def['icon'].
							($def['label'] ? htmlspecialchars($def['label']) : '&nbsp;').
							($isActive ? '</a>' : '').
							'</div>';
				}

				if ($isActive)	{
						// Create DIV layer for content:
					$divs[] = '
							<div style="display: none;" id="'.$id.'-'.$index.'-DIV" class="c-tablayer">'.
								($def['description'] ? '<p class="c-descr">'.nl2br(htmlspecialchars($def['description'])).'</p>' : '').
								$def['content'].
								'</div>';
						// Create initialization string:
					$JSinit[] = '
							DTM_array["'.$id.'"]['.$c.'] = "'.$id.'-'.$index.'";
					';
					if ($toggle==1)	{
						$JSinit[] = '
							if (top.DTM_currentTabs["'.$id.'-'.$index.'"]) { DTM_toggle("'.$id.'","'.$index.'",1); }
						';
					}

					$c++;
				}
			}

				// Render menu:
			if (count($options))	{

					// Tab menu is compiled:
				if (!$foldout)	{
					$tabContent = '';
					for($a=0;$a<=$tabRows;$a++)	{
						$tabContent.= '

					<!-- Tab menu -->
					<table cellpadding="0" cellspacing="0" border="0"'.($fullWidth ? ' width="100%"' : '').' class="typo3-dyntabmenu">
						<tr>
								'.implode('',$options[$a]).'
						</tr>
					</table>';
					}
					$content.= '<div class="typo3-dyntabmenu-tabs">'.$tabContent.'</div>';
				}

					// Div layers are added:
				$content.= '
				<!-- Div layers for tab menu: -->
				<div class="typo3-dyntabmenu-divs'.($foldout?'-foldout':'').'">
				'.implode('',$divs).'</div>';

				if (t3lib_div::_POST('txvacactionindextabindex') == '') {
					$currentTabindex = intval($defaultTabIndex);
				} else {
					$currentTabindex = intval(t3lib_div::_POST('txvacactionindextabindex'));
				}

					// Java Script section added:
				$content.= '
				<!-- Initialization JavaScript for the menu -->
				<script type="text/javascript">
					DTM_array["'.$id.'"] = new Array();
					'.implode('',$JSinit).'
					'.($toggle<=0 ? 'DTM_activate("'.$id.'", top.DTM_currentTabs["'.$id.'"]?top.DTM_currentTabs["'.$id.'"]:'.$currentTabindex.', 0);' : '').'
				</script>
				<input type="hidden" id="txvacactionindextabindex" name="txvacactionindextabindex" value="'.$currentTabindex.'">
				';
			}

		}
		return $content;
	}
	

	/**
	 * Returns dynamic tab menu header JS code.
	 *
	 * @return	string		JavaScript section for the HTML header.
	 */
	function getDynTabMenuJScode()	{
		return '
			<script type="text/javascript">
			/*<![CDATA[*/
				var DTM_array = new Array();
				var DTM_origClass = new String();

					// if tabs are used in a popup window the array might not exists
				
				if(!top.DTM_currentTabs) {
					top.DTM_currentTabs = new Array();
				}
				
				function DTM_activate(idBase,index,doToogle)	{	//
						// Hiding all:
					if (DTM_array[idBase])	{
						for(cnt = 0; cnt < DTM_array[idBase].length ; cnt++)	{
							if (DTM_array[idBase][cnt] != idBase+"-"+index)	{
								document.getElementById(DTM_array[idBase][cnt]+"-DIV").style.display = "none";
								document.getElementById(DTM_array[idBase][cnt]+"-MENU").attributes.getNamedItem("class").nodeValue = "tab";
							}
						}
					}

						// Showing one:
					if (document.getElementById(idBase+"-"+index+"-DIV"))	{
						if (doToogle && document.getElementById(idBase+"-"+index+"-DIV").style.display == "block")	{
							document.getElementById(idBase+"-"+index+"-DIV").style.display = "none";
							if(DTM_origClass=="") {
								document.getElementById(idBase+"-"+index+"-MENU").attributes.getNamedItem("class").nodeValue = "tab";
							} else {
								DTM_origClass = "tab";
							}
							top.DTM_currentTabs[idBase] = -1;
						} else {
							document.getElementById(idBase+"-"+index+"-DIV").style.display = "block";
							if(DTM_origClass=="") {
								document.getElementById(idBase+"-"+index+"-MENU").attributes.getNamedItem("class").nodeValue = "tabact";
							} else {
								DTM_origClass = "tabact";
							}
							top.DTM_currentTabs[idBase] = index;
						}
					}
					document.getElementById("txvacactionindextabindex").value = index;
				}
				function DTM_toggle(idBase,index,isInit)	{	//
						// Showing one:
					if (document.getElementById(idBase+"-"+index+"-DIV"))	{
						if (document.getElementById(idBase+"-"+index+"-DIV").style.display == "block")	{
							document.getElementById(idBase+"-"+index+"-DIV").style.display = "none";
							if(isInit) {
								document.getElementById(idBase+"-"+index+"-MENU").attributes.getNamedItem("class").nodeValue = "tab";
							} else {
								DTM_origClass = "tab";
							}
							top.DTM_currentTabs[idBase+"-"+index] = 0;
						} else {
							document.getElementById(idBase+"-"+index+"-DIV").style.display = "block";
							if(isInit) {
								document.getElementById(idBase+"-"+index+"-MENU").attributes.getNamedItem("class").nodeValue = "tabact";
							} else {
								DTM_origClass = "tabact";
							}
							top.DTM_currentTabs[idBase+"-"+index] = 1;
						}
					}
				}

				function DTM_mouseOver(obj) {	//
						DTM_origClass = obj.attributes.getNamedItem(\'class\').nodeValue;
						obj.attributes.getNamedItem(\'class\').nodeValue += "_over";
				}

				function DTM_mouseOut(obj) {	//
						obj.attributes.getNamedItem(\'class\').nodeValue = DTM_origClass;
						DTM_origClass = "";
				}


			/*]]>*/
			</script>
		';
	}

}



// ******************************
// Extension classes of the template class.
// These are meant to provide backend screens with different widths.
// They still do because of the different class-prefixes used for the <div>-sections
// but obviously the final width is determined by the stylesheet used.
// ******************************

/**
 * Extension class for "template" - used for backend pages which are wide. Typically modules taking up all the space in the "content" frame of the backend
 * The class were more significant in the past than today.
 *
 */
class ux_bigDoc extends ux_template {
	var $divClass = 'typo3-bigDoc';
}

/**
 * Extension class for "template" - used for backend pages without the "document" background image
 * The class were more significant in the past than today.
 *
 */
class ux_noDoc extends ux_template {
	var $divClass = 'typo3-noDoc';
}

/**
 * Extension class for "template" - used for backend pages which were narrow (like the Web>List modules list frame. Or the "Show details" pop up box)
 * The class were more significant in the past than today.
 *
 */
class ux_smallDoc extends ux_template {
	var $divClass = 'typo3-smallDoc';
}

/**
 * Extension class for "template" - used for backend pages which were medium wide. Typically submodules to Web or File which were presented in the list-frame when the content frame were divided into a navigation and list frame.
 * The class were more significant in the past than today. But probably you should use this one for most modules you make.
 *
 */
class ux_mediumDoc extends ux_template {
	var $divClass = 'typo3-mediumDoc';
}



// Include extension to the template class?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/compat/class.ux_template.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/compat/class.ux_template.php']);
}

?>