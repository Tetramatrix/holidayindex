<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2005 Chi Hoang
 *  All rights reserved
 *
 ***************************************************************/
/**
 * Plugin 'TRIP-Travel-Information-Presenter' for the 'ch_trip' extension.
 *
 * @author	Chi Hoang
 */

if (! defined ( 'PATH_txchva' )) {
	define ( 'PATH_txchva', t3lib_extMgm::extPath ( 'ch_trip' ) );
}

require_once (PATH_tslib . 'class.tslib_pibase.php');
require_once (PATH_t3lib . 'class.t3lib_treeview.php');
require_once (PATH_t3lib . 'class.t3lib_basicfilefunc.php');
require_once (PATH_t3lib . 'class.t3lib_extfilefunc.php');
require_once (PATH_t3lib . 'class.t3lib_htmlmail.php');
require_once (PATH_t3lib . 'class.t3lib_svbase.php');

require_once (PATH_txchva . 'lib/class.tx_chtrip_list.php');
require_once (PATH_txchva . 'lib/class.tx_chtrip_t3lib_xml.php');
require_once (PATH_txchva . 'lib/class.tx_chtrip_email.php');
//require_once(PATH_txchva.'lib/class.tx_chtrip_sp.php');
require_once (PATH_txchva . 'lib/class.tx_chtrip_metatags.php');
//require_once(PATH_txchva.'lib/class.tx_chtrip_popup.php');


class tx_chtrip_pi1 extends tslib_pibase {
	
	var $prefixId = 'tx_chtrip_pi1'; // Same as class name
	var $scriptRelPath = 'pi1/class.tx_chtrip_pi1.php'; // Path to this script relative to the extension dir.
	var $extKey = 'ch_trip'; // The extension key.
	var $pi_checkCHash = false;
	var $allowCaching = false;
	
	var $findAll = '9999';
	
	// lang
	var $lang = array ('0' => '0', '2' => '1' );
	
	var $pObj;
	var $cObj;
	var $lConf;
	var $confArray;
	var $conf;
	
	// Internal, dynamic:
	var $parent_uids;
	var $objType;
	var $objTypeReverse;
	var $objFinds;
	var $objFindsReverse;
	
	var $flashFile = '';
	var $pullDownMenu = array ();
	
	var $treeC = 0;
	var $treeUid = 0;
	var $treeItem = 0;
	
	# getRegion
	var $region = array ();
	var $regionTitle = array ();
	var $recursionLevel = 999;
	
	var $params = array ();
	
	var $t3DocRoot;
	
	function main($content, $conf) {
		
		$this->conf = $conf;
		$this->pi_setPiVarDefaults ();
		$this->pi_loadLL ();
		
		# Conf
		$this->confArray = unserialize ( $GLOBALS ['TYPO3_CONF_VARS'] ['EXT'] ['extConf'] [$this->extKey] );
		
		$this->pi_initPIflexForm (); // Init and get the flexform data of the plugin		
		$piFlexForm = $this->cObj->data ['pi_flexform']; // Assign the flexform data to a local variable for easier access

		$index = $this->lang [$GLOBALS ['TSFE']->sys_language_uid];
		$sDef = current ( $piFlexForm ['data'] );
		$lDef = array_keys ( $sDef );
		
		foreach ( $piFlexForm ['data'] as $sheet => $data ) {
			foreach ( $data [$lDef [$index]] as $key => $val ) {
				$this->lConf [$key] = $this->pi_getFFvalue ( $piFlexForm, $key, $sheet, $lDef [$index], 'vDEF' );
			}
		}
		
		# Get the template
		$this->templateCode = $this->cObj->fileResource ( $this->uploadPath () . $this->lConf ['template_file'] );
		
		switch ($this->piVars ['mode']) {
			
			case 'request' :
				$content = $this->runFunction ( 'tx_chtrip_email', 'makeForm' );
				break;
			
			case 'send' :
				$content = $this->runFunction ( 'tx_chtrip_email', 'sendForm' );
				break;
			
			case 'dosearch' :
				$content = $this->runFunction ( 'tx_chtrip_list', 'finds' );
				break;
			
			case 'general' :
				$content = $this->runFunction ( 'tx_chtrip_list', 'general' );
				break;
			
			case 'room' :
				if ($this->piVars ['type'] == 'room') {
					$content = $this->runFunction ( 'tx_chtrip_list', 'image' );
				} else {
					$content = $this->runFunction ( 'tx_chtrip_list', 'room' );
				}
				$content = $content;
				break;
			
			case 'image' :
				$content = $this->runFunction ( 'tx_chtrip_list', 'image' );
				break;
			
			case 'map' :
				$content = $this->runFunction ( 'tx_chtrip_list', 'map' );
				break;
			
			case 'video' :
				$content = $this->runFunction ( 'tx_chtrip_list', 'video' );
				break;
			
			default :
				
				switch ($this->lConf ['what_to_display']) {
					case 'popup' :
						$content = $this->runFunction ( 'tx_chtrip_popup', 'popup' );
						break;
					case 'spteaser' :
						$content = $this->runFunction ( 'tx_chtrip_sp', 'teaser' );
						break;
					case 'splist' :
						$content = $this->runFunction ( 'tx_chtrip_sp', 'listNO' );
						break;
					case 'listNo' :
						$content = $this->runFunction ( 'tx_chtrip_list', 'listNO' );
						return $content;
						break;
					case 'listAlt' :
						$content = $this->runFunction ( 'tx_chtrip_list', 'listAlt' );
						break;
				}
				break;
		}
		
		return "test".$content;
	}
	
	function browserTitle($content, $conf) {
		$content = $this->runFunction ( 'tx_chtrip_metatags', 'title' );
		return $content;
	}
	
	function metatags($content, $conf) {
		$content = $this->runFunction ( 'tx_chtrip_metatags', 'description' );
		return $content;
	}
	
	# Get Yahoo Weather code and store it in $GLOBALS['TSFE']->page to make it accessible to Typo3 constants!
	function weathercode() {
		if ($this->piVars ['mode'] == 'general') {
			$location = $this->getWeatherCode ( intval ( $this->piVars ['uid'] ) );
			$GLOBALS ['TSFE']->page [$this->prefixId . '_weathercode'] = $location ['weathercode'];
		}
	}
	
	function runFunction($class, $func) {
		$obj = t3lib_div::makeInstance ( $class );
		return $obj->$func ( $this );
	}
	
	function uploadPath() {
		return $this->confArray ['uploadPath'] . '/';
	}
	
	function vLoader() {
		return $this->confArray ['vLoader'];
	}
	
	function vType() {
		return $this->confArray ['vType'];
	}
	
	function xmlPath() {
		return $this->confArray ['xmlPath'] . '/';
	}
	
	function indexFinds($array) {
		$temp = array ();
		foreach ( $array as $k => $v ) {
			$temp [$v ['uid']] = $array [$k];
		}
		return $temp;
	}
	
	function bubbleSort($sort_array, $reverse) {
		$num = sizeof ( $sort_array );
		for($i = 0; $i < $num; $i ++) {
			for($j = $i + 1; $j < $num; $j ++) {
				if ($reverse) {
					if ($sort_array [$i] ['tstamp'] < $sort_array [$j] ['tstamp']) {
						$tmp = $sort_array [$i];
						$sort_array [$i] = $sort_array [$j];
						$sort_array [$j] = $tmp;
					}
				} else {
					if ($sort_array [$i] ['tstamp'] > $sort_array [$j] ['tstamp']) {
						$tmp = $sort_array [$i];
						$sort_array [$i] = $sort_array [$j];
						$sort_array [$j] = $tmp;
					}
				}
			}
		}
		return $sort_array;
	}
	
	function getTitle($uid) {
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel.title', 'tx_chtrip_hotel', 'uid=' . $uid . ' AND tx_chtrip_hotel.deleted=0 AND tx_chtrip_location.hidden=0' );
		return $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res );
	}
	
	function getTeaser($uid) {
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel.teaser,tx_chtrip_location.metakeywords', 'tx_chtrip_hotel', 'uid=' . $uid . ' AND tx_chtrip_hotel.deleted=0 AND tx_chtrip_location.hidden=0' );
		return $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res );
	}
	
	function getWeatherCode($uid) {
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel.weathercode', 'tx_chtrip_hotel', 'uid=' . $uid . ' AND tx_chtrip_hotel.deleted=0 AND tx_chtrip_hotel.hidden=0' );
		return $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res );
	}
	
	function getRegionTitle($uid) {
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_region.title', 'tx_chtrip_region', 'pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_region.uid=' . $uid );
		return $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res );
	}
	
	/**
	 * Get type and find selection from extension configuration and make 4 arrays with the md5 hash and the uid 
	 *
	 * @params	parent object
	 * @return	void
	 */
	function getExtConfFlexform() {
		$uids = explode ( ',', $this->lConf ['typeSelection'] );
		if ($uids [0] != '') {
			$arr = $this->getPropertiesUid ( $uids );
			foreach ( $arr as $k => $v ) {
				$this->parent_uids [$v ['parent_uid']] = $v ['parent_uid'];
				$this->objType [substr ( md5 ( $v ['title'] ), 1, 7 )] = $v ['uid'];
				$this->objTypeReverse [$v ['uid']] = substr ( md5 ( $v ['title'] ), 1, 7 );
			}
		}
		
		$uids = explode ( ',', $this->lConf ['findSelection'] );
		if ($uids [0] != '') {
			$arr = $this->getPropertiesUid ( $uids );
			foreach ( $arr as $k => $v ) {
				$this->objFinds [substr ( md5 ( $v ['title'] ), 1, 7 )] = $v ['uid'];
				$this->objFindsReverse [$v ['uid']] = substr ( md5 ( $v ['title'] ), 1, 7 );
			}
		}
		print_r($this->parent_uids);

	}
	
	/**
	 * Get extension configuration from content element
	 *
	 * @params 	pointer parent object
	 * @return	configuration array
	 */
	function getExtConfContentElement($uid) {
		
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tt_content.pi_flexform', 'tt_content', 'pid=' . $uid . ' AND tt_content.deleted=0 AND tt_content.hidden=0 AND list_type="ch_trip_pi1" ORDER BY sorting DESC' );
		$piFlexForm = t3lib_div::xml2array ( $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) );
		
		if (is_array ( $piFlexForm )) {
			$this->lConf = $this->getPiFlexform ( $piFlexForm );
		}
		return $this->lConf;
	}
	
	function getPiFlexform($piFlexForm) {
		$index = $GLOBALS ['TSFE']->sys_language_uid;
		$sDef = current ( $piFlexForm ['data'] );
		$lDef = array_keys ( $sDef );
		
		foreach ( $piFlexForm ['data'] as $sheet => $data )
			foreach ( $data [$lDef [$index]] as $key => $val )
				$lConf [$key] = $this->pi_getFFvalue ( $piFlexForm, $key, $sheet, $lDef [$index] );
		
		return $lConf;
	}
	
	/**
	 * Get a special region and return all parent of this region 
	 *
	 * @params 	int		uid of the region
	 * @params 	pointer parent object
	 * @return	void
	 */
	function getRegion($uid) {
		
		$crazyRecursionLimiter = 999;
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_region.*', 'tx_chtrip_region', 'pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_region.deleted=0 AND tx_chtrip_region.hidden=0
														  AND tx_chtrip_region.uid=' . intval ( $uid ) );
		while ( $crazyRecursionLimiter > 0 && $this->region [$this->recursionLevel] = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
			if ($this->region [$this->recursionLevel] ['parent_uid'] != 0) {
				$parent_uid = $this->region [$this->recursionLevel] ['parent_uid'];
				$this->recursionLevel --;
				$nextLevel = $this->getRegion ( $parent_uid );
			} else {
				sort ( $this->region );
				foreach ( $this->region as $k => $v ) {
					$this->regionTitle [] = $v ['title'];
				}
			}
			$crazyRecursionLimiter --;
		}
	}
	
	/**
	 * Get related object
	 *
	 * @params 	pointer parent object
	 * @return	array	related objects database row
	 */
	function getRelation($uid) {
		$result = false;
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECT_mm_query ( 'tx_chtrip_hotel.uid,
															 tx_chtrip_hotel.title', '', 'tx_chtrip_hotel_properties_mm', 'tx_chtrip_hotel', ' AND tx_chtrip_hotel_properties_mm.uid_local=' . intval ( $uid ) . ' ORDER BY tx_chtrip_hotel_properties_mm.sorting ASC' );
		while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
			$result [] = $row;
		}
		return $result;
	}
	
	/**
	 * Get location from post params 
	 *
	 * @params 	pointer parent object
	 * @return	array	hotel database row
	 */
	function getHotel() {
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel.*', 'tx_chtrip_hotel', 'pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_hotel.deleted=0 AND tx_chtrip_hotel.hidden=0
														  AND tx_chtrip_hotel.uid=' . intval ( $this->piVars ['uid'] ) );
		return $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res );
	}
	
	/**
	 * Get room from post params 
	 *
	 * @params 	pointer parent object
	 * @return	array	room database row
	 */
	function getRoom() {
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_room.*', 'tx_chtrip_room', 'pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_room.deleted=0 AND tx_chtrip_room.hidden=0
														  AND tx_chtrip_room.parent_uid=' . intval ( $this->piVars ['id'] ) );
		return $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res );
	}
	
	function getPropertiesUid($uids) {
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_properties.uid,
											 tx_chtrip_properties.title,
											 tx_chtrip_properties.parent_uid', 'tx_chtrip_properties', 'pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_properties.deleted=0 AND tx_chtrip_properties.hidden=0
											  AND tx_chtrip_properties.uid IN (' . implode ( ',', $uids ) . ')' );
		while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
			$mark [] = $row;
		}
		return $mark;
	}
	
	/**
	 * Get all title properties (kitchen, equipment, etc.) of a hotel
	 *
	 * @param	int			uid of the location
	 * @param	int			parent uid of the object
	 * @param	pointer		parent object
	 * @return	array		array with the titles
	 */
	
	function getTitleAllPropertiesHotel($uid, $parent_uid) {
		
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel_properties_mm.uid_foreign,
														 tx_chtrip_properties.title', 'tx_chtrip_hotel_properties_mm,tx_chtrip_properties', 'uid_local=' . $uid . ' AND tx_chtrip_properties.pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_properties.uid=tx_chtrip_hotel_properties_mm.uid_foreign
														  AND tx_chtrip_properties.deleted=0 AND tx_chtrip_properties.hidden=0' );
		
		while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
			$mark [] = $row ['title'];
		}
		if (is_array ( $mark )) {
			$mark = implode ( ', ', $mark );
		}
		return $mark;
	}
	
	/**
	 * Get all properties of a hotel and return the title and icons
	 *
	 * @param	int			uid of the location
	 * @param	pointer		parent object
	 * @return	array		array with titles and icons
	 */
	function getInfoAllPropertiesHotel($uid) {
		
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel_properties_mm.uid_foreign,
														 tx_chtrip_properties.*', 'tx_chtrip_hotel_properties_mm,tx_chtrip_properties', 'uid_local=' . intval ( $uid ) . ' AND tx_chtrip_properties.pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_properties.uid=tx_chtrip_hotel_properties_mm.uid_foreign
														  AND tx_chtrip_properties.deleted=0 AND tx_chtrip_properties.hidden=0' );
		$objIcon = $this->conf ['icon.'];
		
		while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
			
			if ($this->objTypeReverse [$row ['uid_foreign']]) {
				
				$arr ['title'] = $row ['title'];
				$tmp = $objIcon ['file'] = $this->uploadPath () . $row ['icon'];
				$arr ['type_icon'] = $this->cObj->cObjGetSingle ( $this->conf ['icon'], $tmp );
				$arr ['parent_uid'] = $row ['parent_uid'];
			
			} elseif ($this->objFindsReverse [$row ['uid_foreign']]) {
				
				$tmp = $objIcon ["file"] = $this->uploadPath () . $row ['icon'];
				$arr ['mark'] [$row ['title']] ['title'] = $row ['title'];
				$arr ['mark'] [$row ['title']] ['icon'] = $this->cObj->cObjGetSingle ( $this->conf ['icon'], $tmp );
				$arr ['mark'] [$row ['title']] ['parent_uid'] = $row ['parent_uid'];
			
			} else {
				
				$tmp = $objIcon ["file"] = $this->uploadPath () . $row ['icon'];
				$arr ['general'] [$row ['title']] ['title'] = $row ['title'];
				$arr ['general'] [$row ['title']] ['icon'] = $this->cObj->cObjGetSingle ( $this->conf ['icon'], $tmp );
				$arr ['general'] [$row ['title']] ['parent_uid'] = $row ['parent_uid'];
			}
		}
		
		return $arr;
	}
	
	/**
	 * Get all rooms from a hotel
	 *
	 * @param	int			uid of the location
	 * @param	pointer		parent object
	 * @return	void
	 */
	function getAllRooms($uid) {
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel.*', 'tx_chtrip_hotel', 'tx_chtrip_hotel.uid=' . intval ( $uid ) . ' ORDER BY tx_chtrip_hotel.uid ASC' );
		$row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res );
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( '*', 'tx_chtrip_room', 'tx_chtrip_room.uid IN (' . $row ['room'] . ') ORDER BY tx_chtrip_room.uid ASC' );
		while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
			$arr [] = $row;
		}
		return $arr;
	}
	
	/**
	 * Get all categories from an accommodation
	 *
	 * @param	int			uid of the accommodation
	 * @return	void
	 */
	function getCategory($uid) {
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel.*', 'tx_chtrip_hotel', 'tx_chtrip_hotel.uid=' . intval ( $uid ) . ' ORDER BY tx_chtrip_hotel.uid ASC' );
		$row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res );
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_category.*', 'tx_chtrip_category', 'tx_chtrip_category.uid IN (' . $row ['category'] . ') ORDER BY tx_chtrip_category.uid ASC' );
		while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
			$arr [] = $row;
		}
		return $arr;
	}
	
	/**
	 * Get all regions with location finds
	 *
	 * @param	pointer		parent object
	 * @param	int			uid of the parent region
	 * @return	void
	 */
	function getRegionMenu($parent_uids) {
		
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel_properties_mm.uid_local,
		                                                 tx_chtrip_hotel_properties_mm.uid_foreign,
														 tx_chtrip_properties.uid AS objTypeUid,
														 tx_chtrip_properties.title,
														 tx_chtrip_properties.parent_uid,															 
														 tx_chtrip_region.uid AS regionUid,
														 tx_chtrip_region.title AS regionTitle,
														 tx_chtrip_region.parent_uid AS regionParentUid,
														 tx_chtrip_hotel.location_f04338f846', 'tx_chtrip_hotel_properties_mm,tx_chtrip_properties,tx_chtrip_region,tx_chtrip_hotel', ' tx_chtrip_hotel_properties_mm.uid_local=tx_chtrip_hotel.uid
														  AND tx_chtrip_hotel.location_f04338f846=tx_chtrip_region.uid
														  AND tx_chtrip_hotel_properties_mm.uid_foreign=tx_chtrip_properties.uid
														  AND tx_chtrip_region.pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_hotel.pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_region.deleted=0 AND tx_chtrip_region.hidden=0
														  AND tx_chtrip_hotel.deleted=0 AND tx_chtrip_hotel.hidden=0 															  
														  AND tx_chtrip_properties.parent_uid IN (' . implode ( ',', $parent_uids ) . ') ORDER BY tx_chtrip_region.uid ASC' );
		
		while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
			$arr [] = $row;
		}
		
		// Get parent region
		$uid = array ();
		foreach ( $arr as $k => $v ) {
			$uid [] = $v ['regionParentUid'];
		}
		
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_region.uid,
														 tx_chtrip_region.title', 'tx_chtrip_region', 'pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_region.uid IN (' . implode ( ',', $uid ) . ') 
														AND tx_chtrip_region.deleted=0 AND tx_chtrip_region.hidden=0' );
		while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
			$this->pullDownMenu [$row ['title']] [] = $row;
		}
	}
	
	/**
	 * Get sub info from category
	 *
	 * @param	int			uid of the category
	 * @param	pointer		parent object
	 * @return	array		titles of additional infos
	 */
	function getSubInfo($uid) {
		
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECT_mm_query ( 'tx_chtrip_pricecategory.title', 'tx_chtrip_category', 'tx_chtrip_pricecategory_mm', 'tx_chtrip_pricecategory', ' AND tx_chtrip_category.uid=' . intval ( $uid ) . ' ORDER BY tx_chtrip_pricecategory_mm.sorting' );
		
		while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
			$arr [] = $row;
		}
		
		$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_category.requestperunit,
														 tx_chtrip_category.requestperperson,
														 tx_chtrip_category.halfboard', 'tx_chtrip_category', 'pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_category.uid=' . intval ( $uid ) );
		$row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res );
		
		if ($row ['requestperunit'] == 1) {
			$arr [] = array ('title' => $this->pObj->pi_getLL ( 'requestperunit' ) );
		}
		if ($row ['requestperperson'] == 1) {
			$arr [] = array ('title' => $this->pObj->pi_getLL ( 'requestperperson' ) );
		}
		if ($row ['halfboard'] == 1) {
			$arr [] = array ('title' => $this->pObj->pi_getLL ( 'halfboard' ) );
		}
		return $arr;
	}
	
	/**
	 * Find location
	 * 
	 * @param	pointer		parent object
	 * @param	params		(post) params
	 * @return	array		tree with finds
	 */
	function find($piVars, $count = 0) {
		
		$finds = array ();
		$findsParams = array ();
		$addWhere = array ();
		$limit = '';
		$where = '';
		
		$select = 'L.uid,
                    L.title,
                    L.location_f04338f846,
                    L.teaser,
                    L.pictures,
                    L.location,
                    L.tstamp,
                    M.uid_local AS objTypeUidLocal,
                    M.uid_foreign,
                    O.uid AS typeUid,
                    O.parent_uid AS typeParent,
                    R.uid AS regionUid,
                    R.parent_uid AS regionParentUid';
		
		$from = 'tx_chtrip_hotel L,tx_chtrip_hotel_properties_mm M,tx_chtrip_region R,tx_chtrip_properties O';
		
		$where = ' L.pid=' . $this->lConf ['sysfolder'] . ' AND L.uid=M.uid_local
                   AND L.location_f04338f846=R.uid
                   AND O.uid=M.uid_foreign 
                   AND O.parent_uid IN (' . implode ( ',', $this->parent_uids ) . ')' . ' AND L.deleted=0 AND L.hidden=0';
		
		$orderBy = ' ORDER BY L.title ASC';
		
		#if ($piVars['page'] != '') {
		#	$limit .= ' LIMIT '.$piVars['page']*$this->lConf['results_at_a_time'].','.($piVars['page']*$this->lConf['results_at_a_time']+$this->lConf['results_at_a_time']);
		#}        
		

		if ($piVars ['region'] != $this->findAll) {
			$addWhere ['region'] = 'R.parent_uid=' . intval ( $piVars ['region'] );
		}
		
		//if ($piVars ['specialOffer'] == 'on') {
		//	$addWhere ['sp'] = 'L.specialoffer=1';
		//}
		
		if ($count) {
			$select = 'count(*)';
			$limit = '';
		}
		
		if ($piVars ['allTypes'] && $piVars ['region'] == $this->findAll) {
			$m = 1;
		}
		
		if ($piVars ['allTypes'] && $piVars ['region'] != $this->findAll) {
			$m = 1;
		}
		
		if (! $piVars ['allTypes'] && $piVars ['region'] != $this->findAll) {
			$m = 0;
		}
		
		//TODO: security features
		switch ($m) {
			case 1 :
				foreach ( $this->objType as $k => $v ) {
					$this->params [$k] = $v;
				}
				if (sizeOf ( $this->objFinds ) > 0) {
					foreach ( $this->objFinds as $k => $v ) {
						if ($piVars [$k]) {
							$this->params [$k] = $v;
						}
					}
				}
				;
				break;
			case 0 :
				foreach ( $this->objType as $k => $v ) {
					if ($piVars [$k]) {
						$this->params [$k] = $v;
					}
				}
				if (sizeOf ( $this->objFinds ) > 0) {
					foreach ( $this->objFinds as $k => $v ) {
						if ($piVars [$k]) {
							$this->params [$k] = $v;
						}
					}
				}
				break;
		}
		
		# Find params
		if (sizeOf ( $this->params ) > 0) {
			
			$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( $select, $from, $where . ' AND M.uid_foreign IN (' . implode ( ',', $this->params ) . ')' . (count ( $addWhere ) ? ' AND ' . implode ( ' AND ', $addWhere ) : '') . $orderBy . $limit );
			while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
				$arr [] = $row;
			}
		
		} else {
			
			$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( $select, $from, $where . (count ( $addWhere ) ? ' AND ' . implode ( ' AND ', $addWhere ) : '') . $orderBy . $limit );
			while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
				$arr [] = $row;
			}
		}
		
		return $arr;
	}
	
	function rawCountFinds($finds) {
		$c = 0;
		foreach ( $finds as $k => $v ) {
			$c += $v ['count(*)'];
		}
		return $c;
	}
	
	function filterFinds($piVars, $hits) {
		
		foreach ( $piVars as $k => $v ) {
			if ($this->objFinds [$k]) {
				$properties [$this->objFinds [$k]] = $k;
			}
		}
		
		//TODO: use implode
		if (sizeOf ( $properties ) > 0) {
			
			foreach ( $hits as $k => $v ) {
				
				$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel_properties_mm.*', 'tx_chtrip_hotel_properties_mm', 'uid_local=' . $k );
				echo $GLOBALS ['TYPO3_DB']->sql_error ();
				
				$mark = 0;
				while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
					if ($properties [$row ['uid_foreign']]) {
						$mark ++;
					}
				}
				
				// delete marked from hitlist
				if ($mark != sizeOf ( $properties )) {
					unset ( $hits [$k] );
				}
			}
		}
		return $hits;
	}
	
	/**
	 * Merge finds with locations
	 *
	 */
	function mergeFindsRegions($hits) {
		
		$tree = array ();
		$treeView = t3lib_div::makeInstance ( 't3lib_treeView' );
		$treeView->init ();
		$treeView->table = 'tx_chtrip_region';
		$treeView->parentField = 'parent_uid';
		$treeView->ext_IconMode = '1'; // no context menu on icons
		$treeView->expandAll = 1;
		$treeView->expandFirst = 1;
		$treeView->makeHTML = 0;
		$treeView->fieldArray = array ('uid', 'title', 'parent_uid' );
		$treeView->getTree ( 0, 2 );
		
		foreach ( $treeView->tree as $treeKey => $treeValue ) {
			foreach ( $hits as $key => $value ) {
				if ($value ['regionParentUid'] == $treeValue ['row'] ['uid']) {
					$treeView->tree [$treeKey] ['_SUB_LEVEL'] [] = $value;
					unset ( $hits [$key] );
				}
			}
			
			unset ( $treeView->tree [$treeKey] ['HTML'] );
			unset ( $treeView->tree [$treeKey] ['blankLineCode'] );
			unset ( $treeView->tree [$treeKey] ['bank'] );
		}
		
		foreach ( $treeView->tree as $treeKey => $treeValue ) {
			switch ($treeValue ['invertedDepth']) {
				case 2 :
					$parentKey = $treeKey;
					$tree [$parentKey] = $treeValue;
					break;
				case 1 :
					$middleKey = $treeKey;
					$tree [$parentKey] ['_SUB_LEVEL'] [$middleKey] = $treeValue;
					break;
				default :
					$tree [$parentKey] ['_SUB_LEVEL'] [$middleKey] ['_SUB_LEVEL'] [] = $treeValue;
					break;
			}
		}
		
		return $tree;
	}
	
	/**
	 * Merge finds with locations
	 *
	 */
	function mergeFindsLocation($hits) {
		
		$tree = array ();
		$treeView = t3lib_div::makeInstance ( 't3lib_treeView' );
		$treeView->init ();
		$treeView->table = 'tx_chtrip_region';
		$treeView->parentField = 'parent_uid';
		$treeView->ext_IconMode = '1'; // no context menu on icons
		$treeView->expandAll = 1;
		$treeView->expandFirst = 1;
		$treeView->makeHTML = 0;
		$treeView->fieldArray = array ('uid', 'title', 'parent_uid' );
		$treeView->getTree ( 0 );
		
		foreach ( $treeView->tree as $treeKey => $treeValue ) {
			foreach ( $hits as $key => $value ) {
				if ($value ['regionUid'] == $treeValue ['row'] ['uid']) {
					$treeView->tree [$treeKey] ['_SUB_LEVEL'] [] = $value;
					unset ( $hits [$key] );
				}
			}
			
			unset ( $treeView->tree [$treeKey] ['HTML'] );
			unset ( $treeView->tree [$treeKey] ['blankLineCode'] );
			unset ( $treeView->tree [$treeKey] ['bank'] );
		}
		
		foreach ( $treeView->tree as $treeKey => $treeValue ) {
			switch ($treeValue ['invertedDepth']) {
				case 999 :
					$parentKey = $treeKey;
					$tree [$parentKey] = $treeValue;
					break;
				case 998 :
					$middleKey = $treeKey;
					$tree [$parentKey] ['_SUB_LEVEL'] [$middleKey] = $treeValue;
					break;
				default :
					$tree [$parentKey] ['_SUB_LEVEL'] [$middleKey] ['_SUB_LEVEL'] [] = $treeValue;
					break;
			}
		}
		return $tree;
	}
	
	/**
	 * Write searchresult into xml file
	 * 
	 * @param	array		typo3 tree
	 * @param	pointer		parent object
	 * @return	string		filename
	 */
	function xmlFile($tree) {
		
		if (sizeOf ( $tree ) > 0) {
			
			$className = t3lib_div::makeInstanceClassName ( "tx_chtrip_t3lib_xml" );
			$xmlObj = new $className ( 'amiciditalia_finds' );
			
			$xmlObj->tree = $tree;
			$xmlObj->setRecFields ( 'tx_chtrip_region', 'title,finds' ); // More fields here...
			$xmlObj->renderHeader ();
			$xmlObj->indent ( 1 );
			$xmlObj->newLevel ( 'parameters', 1 );
			$xmlObj->indent ( 1 );
			
			$xmlObj->lines [] = $xmlObj->Icode . $xmlObj->fieldWrapPageId ( intval ( $this->cObj->stdWrap ( $this->conf ['pid'], $this->conf ['pid.'] ) ), 'true' );
			
			if (sizeOf ( $this->params ) > 0) {
				foreach ( $this->params as $k => $v ) {
					$xmlObj->lines [] = $xmlObj->Icode . $xmlObj->fieldWrapSearchString ( $v, 'true' );
				}
			}
			$xmlObj->indent ( 0 );
			$xmlObj->newLevel ( 'parameters', 0 );
			$xmlObj->getTree ( $tree );
			
			if (is_array ( $xmlObj->finds )) {
				$xmlObj->renderRecords ( 'tx_chtrip_region', $xmlObj->finds );
				$xmlObj->renderFooter ();
				
				# Write xml file
				$cmds = array ('data' => date ( 'ymd', time () ) . '_finds_' . substr ( md5 ( 'xOn4eq69' . time () ), 0, 5 ) . '.xml', 'target' => $this->xmlPath () );
				$fileObj = t3lib_div::makeInstance ( 't3lib_extFileFunctions' );
				$fileObj->func_newfile ( $cmds );
				$result = t3lib_div::writeFile ( implode ( '', array_reverse ( $cmds ) ), $xmlObj->getResult () );
				return $cmds ['data'];
			}
		}
	}
	
	/**
	 * Walk through a typo3 tree and return the uid of an item number
	 * 
	 * @param	array		typo3 tree
	 * @param	int			item
	 * @return	void
	 */
	function findItem($tree, $item) {
		$crazyRecursionLimiter = 999;
		while ( $crazyRecursionLimiter > 0 && list ( $key, $val ) = each ( $tree ) ) {
			switch ($val ['_SUB_LEVEL']) {
				case true :
					$nextLevel = $this->findItem ( $val ['_SUB_LEVEL'], $item );
					break;
				default :
					if (! $val ['row']) {
						$this->treeC ++;
						if ($this->treeC == $item) {
							$this->treeUid = $val ['uid'];
							break;
						}
					}
					break;
			}
		}
	}
	
	/**
	 * Walk through a typo3 tree and return the item number of an uid
	 * 
	 * @param	array		typo3 tree
	 * @param	int			uid
	 * @return	void
	 */
	function findUid($tree, $uid) {
		$crazyRecursionLimiter = 999;
		while ( $crazyRecursionLimiter > 0 && list ( $key, $val ) = each ( $tree ) ) {
			switch ($val ['_SUB_LEVEL']) {
				case true :
					$nextLevel = $this->findUid ( $val ['_SUB_LEVEL'], $uid );
					break;
				default :
					if (! $val ['row']) {
						$this->treeC ++;
						if ($val ['uid'] == $uid) {
							$this->treeItem = $this->treeC;
							break;
						}
					}
					break;
			}
		}
	}
	
	/**
	 * Get location or accommodation from post parameters
	 * 
	 * @param	string		mode
	 * @param	pointer		parent object
	 * @return	array		array of the retrieved location or accommodation
	 */
	function getRowHotel($mode) {
		switch ($mode) {
			case 'location' :
			case 'hotel' :
				$obj = $this->getHotel ();
				break;
			case 'room' :
				$obj = $this->getRoom ();
				break;
		}
		return $obj;
	}
	
	/**
	 * Get type titles from post parameters  
	 * 
	 * @param	pointer		parent object		
	 * @return	array		array of the type titles
	 */
	function getWhatInfo() {
		foreach ( $this->objType as $key => $value ) {
			$uid = array ();
			foreach ( $this->objFinds as $k => $v ) {
				if ($this->piVars [$k]) {
					$uid [] = $v;
				}
			}
			if (sizeOf ( $uid ) > 1) {
				$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel.title', 'tx_chtrip_hotel', 'pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_hotel.uid IN (' . implode ( ',', $uid ) . ')' );
				while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
					$result [] = $row ['title'];
				}
			}
		}
		return $result;
	}
	
	/**
	 * Get finds titles from post parameters  
	 * 
	 * @param	pointer		parent object		
	 * @return	array		array of the find titles
	 */
	function getMiscellaneous() {
		
		if (sizeOf ( $this->objFinds ) > 0) {
			$uid = array ();
			foreach ( $this->objFinds as $k => $v ) {
				if ($this->piVars [$k]) {
					$uid [] = $v;
				}
			}
			if (sizeOf ( $uid )) {
				$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_hotel.title', 'tx_chtrip_hotel', 'pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_hotel.uid IN (' . implode ( ',', $uid ) . ')' );
				while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
					$result [] = $row ['title'];
				}
			}
		}
		return $result;
	}
	
	/**
	 * Get all type titles
	 * 
	 * @param	pointer		parent object		
	 * @return	array		array of the type titles
	 */
	function getAllWhatInfo() {
		foreach ( $this->objType as $key => $value ) {
			$uid [] = $value;
		}
		if (sizeOf ( $uid ) > 0) {
			$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_properties.title', 'tx_chtrip_properties', 'pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_properties.uid IN (' . implode ( ',', $uid ) . ')' );
			while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
				$result [] = $row ['title'];
			}
		}
		return $result;
	}
	
	/**
	 * Get finds titles from post parameters  
	 * 
	 * @param	pointer		parent object		
	 * @return	array		array of the find titles
	 */
	function getMiscAlt() {
		if (sizeOf ( $this->objType ) > 0) {
			foreach ( $this->objType as $key => $value ) {
				if ($this->piVars [$key]) {
					$uid [] = $value;
				}
			}
			if (sizeOf ( $uid ) > 0) {
				$res = $GLOBALS ['TYPO3_DB']->exec_SELECTquery ( 'tx_chtrip_properties.title', 'tx_chtrip_properties', 'pid=' . $this->lConf ['sysfolder'] . ' AND tx_chtrip_properties.uid IN (' . implode ( ',', $uid ) . ')' );
				while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ( $res ) ) {
					$result [] = $row ['title'];
				}
			}
		}
		return $result;
	}
	
	/**
	 * Calc new uid from pagescroller  
	 *
	 * @params 	pointer parent object
	 * @params 	pointer this object
	 * @return	int		new uid in $this->piVars
	 */
	function pageScroller() {
		
		if ($this->piVars ['scroll']) {
			
			$finds = $this->find ( $this->piVars, false );
			
			if (sizeOf ( $finds ) > 0) {
				$finds = $this->bubbleSort ( $finds, 1 );
				$finds = $this->indexFinds ( $finds );
				$finds = $this->filterFinds ( $this->piVars, $finds );
				
				$this->totalFinds = sizeOf ( $finds );
				
				# Merge finds with regions
				$tree = $this->mergeFindsRegions ( $finds );
			}
			
			unset ( $this->piVars ['uid'] );
			
			switch ($this->piVars ['scroll']) {
				case 'prev' :
					$this->piVars ['item'] -= 1;
					$this->findItem ( $tree, intval ( $this->piVars ['item'] ) );
					$this->piVars ['uid'] = $this->treeUid;
					$this->piVars ['page'] = $this->piVars ['item'] % $this->lConf ['results_at_a_time'] == 0 ? floor ( $this->piVars ['item'] / $this->lConf ['results_at_a_time'] ) - 1 : floor ( $this->piVars ['item'] / $this->lConf ['results_at_a_time'] );
					
					break;
				case 'next' :
					$this->piVars ['item'] += 1;
					$this->findItem ( $tree, intval ( $this->piVars ['item'] ) );
					$this->piVars ['uid'] = $this->treeUid;
					$this->piVars ['page'] = $this->piVars ['item'] % $this->lConf ['results_at_a_time'] == 0 ? floor ( $this->piVars ['item'] / $this->lConf ['results_at_a_time'] ) - 1 : floor ( $this->piVars ['item'] / $this->lConf ['results_at_a_time'] );
					break;
			}
		}
	}
	
	/**
	 * Make menu link from typoscript settings
	 *
	 * @params 	string	current mode
	 * @params 	pointer parent object
	 * @params 	int 	-1=active link, current id=active link, else: normal link 
	 * @return	array	typo3 configuration array for a link
	 */
	function menuLink($mode, $id = -1) {
		switch ($this->piVars ['mode']) {
			case $mode :
				switch ($id) {
					case - 1 :
						$conf = $this->conf ['menuActLink.'];
						break;
					case $this->piVars ['id'] :
						$conf = $this->conf ['menuActLink.'];
						break;
					default :
						$conf = $this->conf ['menuLink.'];
						break;
				}
				break;
			default :
				$conf = $this->conf ['menuLink.'];
				break;
		}
		return $conf;
	}
	
	/**
	 * Make menu and submenu
	 *
	 * @params 	pointer parent object 
	 * @params 	pointer this object
	 * @return	string  html
	 */
	function makeMenu() {
		
		# Get type icon
		$type = $this->getInfoAllPropertiesHotel ( intval ( $this->piVars ['uid'] ) );
		
		# Get location
		$loc = $this->getHotel ();
		
		# Get region		
		$this->getRegion ( $loc ['location_f04338f846'] );
		
		# Icon
		$type ['type_icon'] = preg_replace ( '/alt="" title=""/', 'alt="' . $type ['title'] . ' ' . addslashes ( $loc ['title'] ) . '" title="' . $type ['title'] . ' ' . addslashes ( $loc ['title'] ) . '"', $type ['type_icon'] );
		$markerArray ['###TITLEICON###'] = $type ['type_icon'];
		
		# Title
		$markerArray ['###TITLE###'] = $loc ['title'];
		$markerArray ['###REGION###'] = $this->regionTitle [0] . ', ' . $this->regionTitle [1] . ', ' . $this->pi_getLL ( 'Province' ) . $this->regionTitle [2] . ', ' . $loc ['location'];
		
		# Add menu
		$template ['menu'] = $this->cObj->getSubpart ( $this->templateCode, '###MENU###' );
		$template ['tabmenu'] = $this->cObj->getSubpart ( $template ['menu'], '###TABMENU###' );
		$template ['add_accommodation'] = $this->cObj->getSubpart ( $template ['menu'], '###ADD_ACCOMMODATION###' );
		
		unset ( $subpartArray );
		$subpartArray ['###TABMENU###'] = $this->menu ( 'location' );
		$subpartArray ['###ADD_ACCOMMODATION###'] = $this->submenu ();
		
		# Do singlepagebrowser
		if ($this->piVars ['item']) {
			
			$template ['singlepagebrowser'] = $this->cObj->getSubpart ( $this->templateCode, '###SINGLEPAGEBROWSER###' );
			$template ['singlepage'] = $this->cObj->getSubpart ( $template ['singlepagebrowser'], '###SINGLEPAGE###' );
			$template ['previtem'] = $this->cObj->getSubpart ( $template ['singlepage'], '###PREVITEM###' );
			$template ['nextitem'] = $this->cObj->getSubpart ( $template ['singlepage'], '###NEXTITEM###' );
			
			$temp_piVars = $this->piVars;
			$temp_piVars ['mode'] = 'general';
			
			unset ( $temp_piVars ['type'] );
			unset ( $temp_piVars ['scroll'] );
			unset ( $temp_piVars ['s'] );
			
			$naviArray ['###WHICHFIND###'] = $temp_piVars ['item'];
			$naviArray ['###TOTALFINDS###'] = $temp_piVars ['total'];
			
			$link_conf = $this->conf ['singlePageBrowserLink.'];
			$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			
			switch ($temp_piVars ['item']) {
				case 1 :
					$prevButton = '';
					break;
				default :
					$temp_piVars ['scroll'] = 'prev';
					$link_conf ['additionalParams'] = t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars );
					$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
					$prevButton = $this->cObj->substituteMarkerArrayCached ( $template ['previtem'], array (), array (), $wrappedSubpartContentArray );
					break;
			}
			if ($temp_piVars ['item'] + 1 > $temp_piVars ['total']) {
				$nextButton = '';
			} else {
				$temp_piVars ['scroll'] = 'next';
				$link_conf ['additionalParams'] = t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars );
				$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
				$nextButton = $this->cObj->substituteMarkerArrayCached ( $template ['nextitem'], array (), array (), $wrappedSubpartContentArray );
			}
			
			# Back button
			$temp_piVars = $this->piVars;
			$temp_piVars ['mode'] = 'dosearch';
			unset ( $temp_piVars ['s'] );
			unset ( $temp_piVars ['type'] );
			unset ( $temp_piVars ['scroll'] );
			
			$link_conf ['useCacheHash'] = ! $this->allowCaching;
			$link_conf ['no_cache'] = $this->allowCaching;
			
			$link_conf ['additionalParams'] = t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars );
			$wrappedSubpartContentArray ['###BACK###'] = $this->cObj->typolinkWrap ( $link_conf );
			$naviSubArray ['###SINGLEPAGE###'] = $prevButton . $nextButton;
		}
		# Do Singlepagebrowser
		$markerArray ['###PAGEMENU###'] = $this->cObj->substituteMarkerArrayCached ( $template ['singlepagebrowser'], $naviArray, $naviSubArray, $wrappedSubpartContentArray );
		
		return $this->cObj->substituteMarkerArrayCached ( $template ['menu'], $markerArray, $subpartArray );
	}
	
	/**
	 * Make menu 
	 *
	 * @params 	string	current mode
	 * @params 	pointer parent object
	 * @params 	pointer this object
	 * @return	string	html
	 */
	function menu($mode) {
		
		$template ['menu'] = $this->cObj->getSubpart ( $this->templateCode, '###MENU###' );
		$template ['tabmenu'] = $this->cObj->getSubpart ( $template ['menu'], '###TABMENU###' );
		$template ['generalitem'] = $this->cObj->getSubpart ( $template ['tabmenu'], '###GENERALITEM###' );
		$template ['mapitem'] = $this->cObj->getSubpart ( $template ['tabmenu'], '###MAPITEM###' );
		$template ['videoitem'] = $this->cObj->getSubpart ( $template ['tabmenu'], '###VIDEOITEM###' );
		$template ['imageitem'] = $this->cObj->getSubpart ( $template ['tabmenu'], '###IMAGEITEM###' );
		$template ['accommodationitem'] = $this->cObj->getSubpart ( $template ['tabmenu'], '###ACCOMMODATIONITEM###' );
		
		# General Tab
		$temp_piVars = $this->piVars;
		
		$temp_piVars ['mode'] = 'general';
		unset ( $temp_piVars ['type'] );
		unset ( $temp_piVars ['scroll'] );
		unset ( $temp_piVars ['s'] );
		
		$link_conf = $this->menuLink ( 'general' );
		$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
		$link_conf ['additionalParams'] .= t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars );
		$link_conf ['useCacheHash'] = $this->allowCaching;
		$link_conf ['no_cache'] = ! $this->allowCaching;
		$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
		$menu_item = $this->cObj->substituteMarkerArrayCached ( $template ['generalitem'], array (), array (), $wrappedSubpartContentArray );
		
		# Get object		
		$object = $this->getRowHotel ( $mode );
		
		# Map Tab
		$map = explode ( ',', $object ['wheremap'] );
		if ($map [0] != '') {
			
			$temp_piVars = $this->piVars;
			$temp_piVars ['mode'] = 'map';
			$temp_piVars ['type'] = $mode;
			unset ( $temp_piVars ['scroll'] );
			unset ( $temp_piVars ['s'] );
			
			$link_conf = $this->menuLink ( 'map' );
			$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
			$link_conf ['additionalParams'] .= t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars );
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			
			$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
			$menu_item .= $this->cObj->substituteMarkerArrayCached ( $template ['mapitem'], array (), array (), $wrappedSubpartContentArray );
		}
		
		# Video Tab
		$video = explode ( ',', $object ['video'] );
		if ($video [0] != '') {
			
			$temp_piVars = $this->piVars;
			$temp_piVars ['mode'] = 'video';
			$temp_piVars ['type'] = 'location';
			unset ( $temp_piVars ['scroll'] );
			unset ( $temp_piVars ['s'] );
			
			$link_conf = $this->menuLink ( 'video' );
			$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
			$link_conf ['additionalParams'] .= t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars );
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
			$menu_item .= $this->cObj->substituteMarkerArrayCached ( $template ['videoitem'], array (), array (), $wrappedSubpartContentArray );
		}
		
		# Image Tab
		$image = explode ( ',', $object ['pictures'] );
		if (sizeOf ( $image ) > 2) {
			
			$temp_piVars = $this->piVars;
			$temp_piVars ['mode'] = 'image';
			$temp_piVars ['type'] = $mode;
			unset ( $temp_piVars ['scroll'] );
			unset ( $temp_piVars ['s'] );
			
			$link_conf = $this->menuLink ( 'image' );
			$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
			$link_conf ['additionalParams'] .= t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars );
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
			$menu_item .= $this->cObj->substituteMarkerArrayCached ( $template ['imageitem'], array (), array (), $wrappedSubpartContentArray );
		}
		
		$temp_piVars = $this->piVars;
		
		$temp_piVars ['mode'] = 'room';
		unset ( $temp_piVars ['id'] );
		unset ( $temp_piVars ['type'] );
		unset ( $temp_piVars ['scroll'] );
		unset ( $temp_piVars ['s'] );
		
		$features = explode ( ',', $this->lConf ['featureSelection'] );
		
		for($i = 0; $i < 3; $i ++) {
			
			if ($this->acc [$i] ['title']) {
				
				$markerArray ['###TITLE###'] = $this->cObj->stdWrap ( $this->acc [$i] ['title'], $this->conf ['menu_stdWrap.'] );

/*
				$find = $this->getTitleAllPropertiesHotel ( $this->acc [$i] ['uid'], $features [2] );
				
				if ($find) {
					$markerArray ['###TITLE###'] .= ' &#40;' . trim ( preg_replace ( '/[A-Za-z ]/', '', $find ) ) . '&#41;';
				}
*/				
				$link_conf = $this->menuLink ( 'room', $this->acc [$i] ['uid'] );
				$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
				$link_conf ['additionalParams'] = t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars ) . '&tx_chtrip_pi1[id]=' . $this->acc [$i] ['uid'];
				$link_conf ['useCacheHash'] = $this->allowCaching;
				$link_conf ['no_cache'] = ! $this->allowCaching;
				$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
				$menu_item .= $this->cObj->substituteMarkerArrayCached ( $template ['accommodationitem'], $markerArray, array (), $wrappedSubpartContentArray );
			}
		}
		
		return $menu_item;
	}
	
	/**
	 * Make additional menu  
	 *
	 * @params 	pointer parent object 
	 * @params 	pointer this object
	 * @return	string  html
	 */
	function submenu() {
		
		$template ['menu'] = $this->cObj->getSubpart ( $this->templateCode, '###MENU###' );
		$template ['add_accommodation'] = $this->cObj->getSubpart ( $template ['menu'], '###ADD_ACCOMMODATION###' );
		$template ['add_accommodationitem'] = $this->cObj->getSubpart ( $template ['add_accommodation'], '###ACCOMMODATIONITEM###' );
		
		$temp_piVars = $this->piVars;
		unset ( $temp_piVars ['type'] );
		unset ( $temp_piVars ['scroll'] );
		unset ( $temp_piVars ['id'] );
		unset ( $temp_piVars ['s'] );
		$temp_piVars ['mode'] = 'room';
		
		$features = explode ( ',', $this->lConf ['featureSelection'] );
		
		for($i = 3; $i < sizeOf ( $this->acc ); $i ++) {
			
			unset ( $submenu_item );
			
			for($j = 0; $j < 5 && $j < sizeOf ( $this->acc ); $j ++) {
				
				if ($this->acc [$i + $j] ['title']) {
					
					$markerArray ['###TITLE###'] = $this->cObj->stdWrap ( $this->acc [$i + $j] ['title'], $this->conf ['menu_stdWrap.'] );

/*
					$find = $this->getTitleAllPropertiesHotel ( $this->acc [$i + $j] ['uid'], $features [2] );
					
					if ($find) {
						$markerArray ['###TITLE###'] .= ' &#40;' . trim ( preg_replace ( '/[A-Za-z ]/', '', $find ) ) . '&#41;';
					}
*/					
					$link_conf = $this->menuLink ( 'room', $this->acc [$i + $j] ['uid'] );
					$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
					$link_conf ['additionalParams'] = t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars ) . '&tx_chtrip_pi1[id]=' . $this->acc [$i + $j] ['uid'];
					$link_conf ['useCacheHash'] = $this->allowCaching;
					$link_conf ['no_cache'] = ! $this->allowCaching;
					$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
					$submenu_item .= $this->cObj->substituteMarkerArrayCached ( $template ['add_accommodationitem'], $markerArray, array (), $wrappedSubpartContentArray );
				}
			}
			$i += 4;
			unset ( $subpartArray );
			$subpartArray ['###ACCOMMODATION###'] = $submenu_item;
			$submenu .= $this->cObj->substituteMarkerArrayCached ( $template ['add_accommodation'], array (), $subpartArray );
		}
		
		return $submenu;
	}
	
	/**
	 * Format string with general_stdWrap from configuration
	 * 
	 * @param	string		$string to wrap
	 * @return	string		wrapped string
	 */
	function formatStr($str) {
		if (is_array ( $this->conf ['general_stdWrap.'] )) {
			$str = $this->cObj->stdWrap ( $str, $this->conf ['general_stdWrap.'] );
		}
		return $str;
	}
	
	/**
	 * Format string with rte_stdWrap from configuration
	 * 
	 * @param	string		$string to wrap
	 * @return	string		wrapped string
	 */
	function formatStrRTE($str) {
		if (is_array ( $this->conf ['rte_stdWrap.'] )) {
			$str = $this->cObj->stdWrap ( $str, $this->conf ['rte_stdWrap.'] );
		}
		return $str;
	}

}

if (defined ( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS [TYPO3_MODE] ['XCLASS'] ['ext/ch_trip/pi1/class.tx_chtrip_pi1.php']) {
	include_once ($TYPO3_CONF_VARS [TYPO3_MODE] ['XCLASS'] ['ext/ch_trip/pi1/class.tx_chtrip_pi1.php']);
}
?>
