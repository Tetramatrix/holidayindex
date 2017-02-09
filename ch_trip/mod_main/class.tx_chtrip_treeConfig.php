<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006-2008 Chi Hoang (info@chihoang.de)
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

require_once(PATH_t3lib.'class.t3lib_foldertree.php');;
require_once(PATH_txva.'mod_main/class.tx_chtrip_browseTree.php');

class tx_chtrip_treeConfigRegion extends tx_chtrip_browseTree {

	var $isTCEFormsSelectClass = true;
	var $supportMounts = true;

	function tx_chtrip_treeConfigRegion()	{
		global $LANG, $BACK_PATH;

		$this->title = $LANG->sL('LLL:EXT:ch_trip/mod_main/locallang.php:region',1);
		$this->treeName = 'txchvaRegion';
		$this->domIdPrefix = $this->treeName;
		$this->stdselection = 'tree=region';
		$this->mode = 'elbrowser';

		$this->table='tx_chtrip_region';
		$this->parentField = 'parent_uid';
		$this->typeField = $GLOBALS['TCA'][$this->table]['ctrl']['type'];

		$this->iconName = 'cat3.gif';
		$this->iconPath = $BACK_PATH.PATH_txchva_rel.'res/';
		$this->rootIcon = $BACK_PATH.PATH_txchva_rel.'res/cat3folder.gif';

		$this->fieldArray = Array('uid','title');
		if($this->parentField) $this->fieldArray[] = $this->parentField;
		if($this->typeField) $this->fieldArray[] = $this->typeField;
		$this->defaultList = 'uid,pid,tstamp';

		$this->clause = ' AND deleted=0 AND hidden=0';
		$this->orderByFields = 'title';

		$this->ext_IconMode = '0'; // no context menu on icons
	}
}


class tx_chtrip_treeConfigSetup extends tx_chtrip_browseTree {

	var $isTCEFormsSelectClass = true;
	var $supportMounts = true;

	function tx_chtrip_treeConfigSetup()	{
		global $LANG, $BACK_PATH;

		$this->title = $LANG->sL('LLL:EXT:ch_trip/mod_main/locallang.php:setup',1);
		$this->treeName = 'txchvaSetup';
		$this->domIdPrefix = $this->treeName;
		$this->stdselection = 'tree=setup';
		$this->mode = 'elbrowser';

		$this->table='pages';
		$this->parentField = 'pid';
		$this->typeField = $GLOBALS['TCA'][$this->table]['ctrl']['type'];

		$this->iconName = 'cat2.gif';
		$this->iconPath = $BACK_PATH.PATH_txchva_rel.'res/';
		$this->rootIcon = $BACK_PATH.PATH_txchva_rel.'res/cat2folder.gif';

		$this->fieldArray = Array('uid','title');
		if($this->parentField) $this->fieldArray[] = $this->parentField;
		if($this->typeField) $this->fieldArray[] = $this->typeField;
		$this->defaultList = 'uid,pid,tstamp';

		$this->clause = ' AND deleted=0 AND hidden=0';
		$this->orderByFields = 'title';

		$this->ext_IconMode = '0'; // no context menu on icons
	}
}


class tx_chtrip_treeConfigProp extends tx_chtrip_browseTree {

	var $isTCEFormsSelectClass = true;
	var $supportMounts = true;

	function tx_chtrip_treeConfigProp()	{
		global $LANG, $BACK_PATH;

		$this->title = $LANG->sL('LLL:EXT:ch_trip/mod_main/locallang.php:prop',1);
		$this->treeName = 'txchvaProp';
		$this->domIdPrefix = $this->treeName;
        	$this->stdselection = 'tree=property';
		$this->mode = 'elbrowser';

		$this->table='tx_chtrip_properties';
		$this->parentField = 'parent_uid';
		$this->typeField = $GLOBALS['TCA'][$this->table]['ctrl']['type'];

		$this->iconName = 'cat.gif';
		$this->iconPath = $BACK_PATH.PATH_txchva_rel.'res/';
		$this->rootIcon = $BACK_PATH.PATH_txchva_rel.'res/catfolder.gif';

		$this->fieldArray = Array('uid','title');
		if($this->parentField) $this->fieldArray[] = $this->parentField;
		if($this->typeField) $this->fieldArray[] = $this->typeField;
		$this->defaultList = 'uid,pid,tstamp';

		$this->clause = ' AND deleted=0 AND hidden=0';
		$this->orderByFields = 'title';

		$this->ext_IconMode = '0'; // no context menu on icons
	}
}

class tx_chtrip_treeConfigOrder extends tx_chtrip_browseTree {

	var $isTCEFormsSelectClass = true;
	var $supportMounts = true;

	function tx_chtrip_treeConfigOrder()	{
		global $LANG, $BACK_PATH;

		$this->title = $LANG->sL('LLL:EXT:ch_trip/mod_main/locallang.php:order',1);
		$this->treeName = 'txchvaOrder';
		$this->domIdPrefix = $this->treeName;
        $this->stdselection = 'tree=order';
		$this->mode = 'elbrowser';

		$this->table='tx_chtrip_request';
		$this->parentField = 'parent_uid';
		$this->typeField = $GLOBALS['TCA'][$this->table]['ctrl']['type'];

		$this->iconName = 'cat.gif';
		$this->iconPath = $BACK_PATH.PATH_txchva_rel.'res/';
		$this->rootIcon = $BACK_PATH.PATH_txchva_rel.'res/catfolder.gif';

		$this->fieldArray = Array('uid','title');
		if($this->parentField) $this->fieldArray[] = $this->parentField;
		if($this->typeField) $this->fieldArray[] = $this->typeField;
		$this->defaultList = 'uid,pid,tstamp';

		$this->clause = ' AND deleted=0 AND hidden=0';
		$this->orderByFields = 'title';

		$this->ext_IconMode = '0'; // no context menu on icons
	}

	/* rendering the browsable trees
	 * 
	 * @return	string		tree HTML content
	 */
	function getBrowsableTree()	{
		
		$alphabet = array ('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','Y','Z');
		
		$i=10000;
		foreach ($alphabet as $val) {
			$lexi[$i++]=array('title' => $val, 'uid' => $i);
		}
		
		$res = mysql_query('Select uid,pid,name,objname,deleted,hidden from tx_chtrip_request Where deleted=0 And hidden=0 Order By name');
		
			// fetch records & sort by name
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$row['table']='tx_chtrip_request';
			$row['bak_uid']=$row['uid'];
			$row['title']=$row['name'];
			$row['editOnClick']=true;
			$tmp[$row['name']][]=$row;
		}		

		if (is_array($tmp)) {
			$i=20000;
			foreach ($lexi as $key => $value) {
				foreach($tmp as $a => $b) {
					if(preg_match('/^'.$value['title'].'/',$a)) {
						$lexi[$key][$this->subLevelID][$i]=array('title' => $a, 'uid' => $i);
						foreach ($b as $c => $d) {
							$d['title']=$d['objname'];
							$lexi[$key][$this->subLevelID][$i][$this->subLevelID][$d['uid']]=$d;
						}
					}
					$i++;
				}
			}
		}
		
		$this->setDataFromArray($lexi);
		
		$this->table = '';
		$tree = parent::getBrowsableTree();
		return $tree;

	}
}

class tx_chtrip_treeConfigObject extends tx_chtrip_browseTree {

	var $isTCEFormsSelectClass = true;
	var $supportMounts = true;

	function tx_chtrip_treeConfigObject()	{
		global $LANG, $BACK_PATH;

		$this->title = $LANG->sL('LLL:EXT:ch_trip/mod_main/locallang.php:object',1);
		$this->treeName = 'txchvaObject';
		$this->domIdPrefix = $this->treeName;
        $this->stdselection = 'tree=object';
		$this->mode = 'elbrowser';

		$this->table='tx_chtrip_hotel';
		$this->parentField = 'tx_perfectlightbox_slideshow';
		$this->typeField = $GLOBALS['TCA'][$this->table]['ctrl']['type'];

		$this->iconName = 'cat.gif';
		$this->iconPath = $BACK_PATH.PATH_txchva_rel.'res/';
		$this->rootIcon = $BACK_PATH.PATH_txchva_rel.'res/catfolder.gif';

		$this->fieldArray = Array('uid','title');
		if($this->parentField) $this->fieldArray[] = $this->parentField;
		if($this->typeField) $this->fieldArray[] = $this->typeField;
		$this->defaultList = 'uid,pid,tstamp';

		$this->clause = ' AND deleted=0 AND hidden=0';
		$this->orderByFields = 'title';

		$this->ext_IconMode = '0'; // no context menu on icons
	}


	/* rendering the browsable trees
	 * 
	 * @return	string		tree HTML content
	 */
	function getBrowsableTree()	{
		
		$alphabet = array ('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','Y','Z');
		
		$i=10000;
		foreach ($alphabet as $val) {
			$lexi[$i]=array('title' => $val, 'uid' => $i);
			$i++;
		}
				
		$res = mysql_query('Select uid,pid,title,deleted,hidden from tx_chtrip_hotel Where deleted=0 And hidden=0 Order By title');
		
			// fetch records
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$row['table']='tx_chtrip_hotel';
			$row['editOnClick']=true;
			$location[$row['uid']]=$row; 
		}

		$res = mysql_query('Select uid,pid,title,parent_uid,deleted,hidden from tx_chtrip_room Where deleted=0 And hidden=0 Order By title');
		
		
			// fetch records
		if (count($row)>1) {
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$row['table']='tx_chtrip_room';
				$row['editOnClick']=true;
				$row['bak_uid']=$row['uid'];
				//$row['uid']=$row['uid']+1000;
				$accommodation[$row['parent_uid']][$row['uid']+1000]=$row;
			}
			
			$res = mysql_query('Select uid,pid,title,parent_uid,deleted,hidden from tx_chtrip_category Where deleted=0 And hidden=0 Order By title');
			
				// fetch records
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$row['table']='tx_chtrip_category';
				$row['editOnClick']=true;
				$row['bak_uid']=$row['uid'];
				//$row['uid']=$row['uid']+2000;
				$category[$row['parent_uid']][$row['uid']+2000]=$row;
			}
		}
		
		if (count($accommodation)>1) {
			foreach ($accommodation as $k => $v) {
				foreach ($v as $a => $b) {
					if($category[$b['bak_uid']]) {
						$accommodation[$k][$a][$this->subLevelID]=$category[$b['bak_uid']];
					}
				}		
			}
		}
		
		if (count($location)>1) {
			foreach ($location as $key => $value) {
				if (is_array($accommodation[$key])) {
					$location[$key][$this->subLevelID]=$accommodation[$key];
				}
			}		
		}
		
		foreach ($lexi as $key => $value) {
			foreach($location as $a => $b) {
				if(preg_match('/^'.$value['title'].'/',$b['title'])) {
					$lexi[$key][$this->subLevelID][$a]=$b;
				}
			}
		}
				
		$this->setDataFromArray($lexi);
		
		$this->table = '';
		$tree = parent::getBrowsableTree();
		return $tree;

	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/mod_main/class.tx_chtrip_treeConfig.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/mod_main/class.tx_chtrip_treeConfig.php']);
}
?>