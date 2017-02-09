<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2008 Chi Hoang (info@chihoang.de)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
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


require_once(PATH_t3lib.'class.t3lib_treeview.php');


class tx_chtrip_browseTree extends t3lib_treeView {

		// is able to generate a browasable tree
	var $isTreeViewClass = TRUE;

		// is able to generate a tree for a select field in TCEForms
	var $isTCEFormsSelectClass = false;
	var $tceformsSelect_prefixTreeName = false;

		// is able to handle mount points
	var $supportMounts = false;

	/**
	 * element browser mode
	 */
	var $mode = 'browse';


	/**
	 * enables selection icons: + = -
	 */	
	var $clickMenuScript=true;

	/**
	 * indicates if we need to output a root icon
	 */	
	var $rootIconIsSet = false;

	/**
	 * [Describe function...]
	 * 
	 * @param	[t]		$row: ...
	 * @param	[t]		$command: ...
	 * @return	[t]		...
	 */
	function getJumpToParam($row, $command='SELECT') {
		return '&SLCMD['.$command.']['.$this->treeName.']['.rawurlencode($row['uid']).']=1';
	}

	/**
	 * @param	[t]		$title: ...
	 * @param	[t]		$row: ...
	 * @return	[t]		...
	 */
	function wrapTitle($title,$row)	{
		global $SOBE;
		if ($row['uid'] && !$row['editOnClick']) {
			$aOnClick = 'jumpTo(\''.$this->getJumpToParam($row).'\',this,\''.$this->domIdPrefix.$this->getId($row).'\');hilight_row(\'txchtripM1\',\''.$this->domIdPrefix.$this->getId($row).'_'.$this->bank.'\');';
			return '<a href="#" onclick="'.htmlspecialchars($aOnClick).'">'.$title.'</a>';
		} elseif($row['editOnClick']) {
			$aOnClick = 'top.content.list_frame.location.href=top.TS.PATH_typo3+\'alt_doc.php?returnUrl=\'+top.rawurlencode(top.content.list_frame.document.location)+\'&edit['.$row['table'].']['.$this->getId($row).']=edit\';hilight_row(\'txchtripM1\',\''.$this->domIdPrefix.$this->getId($row).'_'.$this->bank.'\');';
			return '<a href="#" onclick="'.htmlspecialchars($aOnClick).'">'.$title.'</a>';
		} else {
			return $title;
		}
	}

	/**
	 * Wrap the plus/minus icon in a link
	 *
	 * @param	string		HTML string to wrap, probably an image tag.
	 * @param	string		Command for 'PM' get var
	 * @param	boolean		If set, the link will have a anchor point (=$bMark) and a name attribute (=$bMark)
	 * @return	string		Link-wrapped input string
	 * @access private
	 */
	function PM_ATagWrap($icon,$cmd,$bMark='')	{
		if ($this->thisScript) {
			if ($bMark)	{
				$anchor = '#'.$bMark;
				$name=' name="'.$bMark.'"';
			}
			$aUrl = $this->thisScript.'?PM='.$cmd.'&'.$this->stdselection.$anchor;
			return '<a href="'.htmlspecialchars($aUrl).'"'.$name.'>'.$icon.'</a>';
		} else {
			return $icon;
		}
	}
    
	/**
	 * [Describe function...]
	 * 
	 * @param	[t]		$rec: ...
	 * @return	[t]		...
	 */
	function getRootIcon($row) {
		global $BACK_PATH;

		if($this->rootIcon) {
			$icon = $this->wrapIcon('<img src="'.$this->rootIcon.'" width="18" height="16" align="top" alt="" />',$row);
		} else {
			$icon =  parent::getRootIcon($row);
		}
		$this->rootIconIsSet = true;

		return $icon;
	}


	/**
	 * Wrapping the image tag, $icon, for the row, $row (except for mount points)
	 *
	 * @param	string		The image tag for the icon
	 * @param	array		The row for the current element
	 * @return	string		The processed icon input value.
	 * @access private
	 */
	function wrapIcon($icon,$row)	{
		global $SOBE;

			// Add title attribute to input icon tag
		$theIcon = $this->addTagAttributes($icon,($this->titleAttrib ? $this->titleAttrib.'="'.$this->getTitleAttrib($row).'"' : ''));

			// Wrap icon in click-menu link.
		if (!$this->ext_IconMode)	{
			
			$theIcon = $SOBE->doc->wrapClickMenuOnIcon($theIcon,($row['table'] ? $row['table'] : $this->table),$this->getId($row),1,'','+new,edit,delete');

		} elseif (!strcmp($this->ext_IconMode,'titlelink'))	{
			
				// unused for now
			$aOnClick = 'return jumpTo(\''.$this->getJumpToParam($row).'\',this,\''.$this->domIdPrefix.$this->getId($row).'_'.$this->bank.'\');';
			$theIcon='<a href="#" onclick="'.htmlspecialchars($aOnClick).'">'.$theIcon.'</a>';
		}
		return $theIcon;
	}

	/**
	 * Returns the id from the record (typ. uid)
	 *
	 * @param	array		Record array
	 * @return	integer		The "uid" field value.
	 */
	function getId($row) {
		if ($row['uid'] > 2000) {
			return $row['uid']-2000;
		} elseif ($row['uid'] > 1000) {
			return $row['uid']-1000;
		} else {	
			return $row['uid'];
		}
	}

	/**
	 * Create the folder navigation tree in HTML
	 * 
	 * @param	mixed		Input tree array. If not array, then $this->tree is used.
	 * @return	string		HTML output of the tree.
	 */
	function printTree($treeArr='')	{
		global $SOBE, $BE_USER;

		$titleLen=intval($BE_USER->uc['titleLen']);

		if (!is_array($treeArr))	$treeArr=$this->tree;

		$out='';
		$c=0;

			// Preparing the current-path string (if found in the listing we will see a red blinking arrow).
		if (!$SOBE->curUrlInfo['value'])	{
			$cmpPath='';
		} else if (substr(trim($SOBE->curUrlInfo['info']),-1)!='/')	{
			$cmpPath=PATH_site.dirname($SOBE->curUrlInfo['info']).'/';
		} else {
			$cmpPath=PATH_site.$SOBE->curUrlInfo['info'];
		}

			// Traverse rows for the tree and print them into table rows:
		foreach($treeArr as $k => $v)	{
			
			$c++;
			$bgColorClass=($c+1)%2 ? 'bgColor' : 'bgColor-10';

			$idAttr = htmlspecialchars($this->domIdPrefix.$this->getId($v['row']).'_'.$v['bank']);

				// Put table row with folder together:
			$out.='
				<tr class="'.$bgColorClass.'">
					<td id="'.$idAttr.'" nowrap="nowrap">'.$v['HTML'].$this->wrapTitle($this->getTitleStr($v['row'],$titleLen),$v['row']).'</td>
				</tr>';
		}
			$out='
				<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree" style="width:100%">
				'.$out.'
				</table>';
		return $out;
	}


	function printRootOnly() {
			// Artificial record for the tree root, id=0
		$rootRec = $this->getRootRecord(0);
		$firstHtml =$this->getRootIcon($rootRec);
		$treeArr[] = array('HTML'=>$firstHtml,'row'=>$rootRec,'bank'=>0);
		$this->rootIconIsSet = true;

		return $this->printTree($treeArr);
	}


	function setMounts($mountpoints) {

		if (is_array($mountpoints)) {
			$this->MOUNTS = $mountpoints;
		}
	}



	/********************************
	 *
	 * fix for non-trees - mabye not needed in the future
	 *
	 ********************************/


	/**
	 * Getting the tree data: Counting elements in resource
	 *
	 * @param	mixed		data handle
	 * @return	integer		number of items
	 * @access private
	 * @see getDataInit()
	 */
	function getDataCount($res) {
		if ($res) {
			return parent::getDataCount($res);
		}
		return 0;
	}


	/**
	 * Getting the tree data: frees data handle
	 *
	 * @param	mixed		data handle
	 * @return	void
	 * @access private
	 */
	function getDataFree($res){
		if ($res) {
			return parent::getDataFree($res);
		}
	}


	/********************************
	 *
	 * DAM specific functions
	 *
	 ********************************/



	/**
	 * @return	[t]		...
	 */
	function dam_defaultIcon()	{
		return $this->iconPath.$this->iconName;
	}

	/**
	 * Returns the title for the tree
	 * 
	 * @return	string		
	 */
	function dam_treeTitle()	{
		return $this->title;
	}

	/**
	 * Returns the treename (used for storage of expanded levels)
	 * 
	 * @return	string		
	 */
	function dam_treeName()	{
		return $this->treeName;
	}



	/**
	 * Returns the title of an item
	 * 
	 * @param	[t]		$id: ...
	 * @return	string		
	 */
	function dam_itemTitle($id)	{
		$itemTitle=$id;

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(implode(',',$this->fieldArray), $this->table, 'uid='.intval($id));
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$itemTitle = $this->getTitleStr($row);
		}
		return $itemTitle;
	}

	/**
	 * Function, processing the query part for selecting/filtering records in DAM
	 * Called from DAM
	 * 
	 * @param	string		Query type: AND, OR, ...
	 * @param	string		Operator, eg. '!=' - see DAM Documentation
	 * @param	string		Category - corresponds to the "treename" used for the category tree in the nav. frame
	 * @param	string		The select value/id
	 * @param	string		The select value (true/false,...)
	 * @param	object		Reference to the parent DAM object.
	 * @return	string		
	 * @see tx_dam_SCbase::getWhereClausePart()
	 */
	function dam_selectProc($queryType, $operator, $cat, $id, $value, &$damObj)      {
#		return array($queryType,$query);
	}


}





?>