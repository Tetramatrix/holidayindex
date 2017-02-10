<?php

/**
 * Plugin 'TRIP-Travel-Information-Presenter' for the 'ch_trip' extension.
 *
 * @author	Chi Hoang
 */

class tx_chtrip_list {
	
	var $pObj;
	var $cObj;
	var $lConf;
	var $confArray;
	var $piVars;
	var $findAll;
	var $allowCaching;
	var $conf;
	
	function listNo($pObj) {
		
		$this->pObj = &$pObj;
		$this->cObj = &$pObj->cObj;
		$this->lConf = &$pObj->lConf;
		$this->confArray = &$pObj->confArray;
		$this->piVars = &$pObj->piVars;
		$this->findAll = &$pObj->findAll;
		$this->allowCaching = &$pObj->allowCaching;
		$this->conf = &$pObj->conf;
		$this->t3DocRoot = &$pObj->t3DocRoot;
		
		$this->pullDownMenu = &$pObj->pullDownMenu;
		
		//$getExtConfFlexform = "\$this->pObj->getExtConfFlexform";
		
		// get pluginConfiguration	
		$this->pObj->getExtConfFlexform ();
		$this->parent_uids = &$pObj->parent_uids;		
		
		$this->t3DocRoot = str_replace ( '/', '', str_replace ( t3lib_div::getIndpEnv ( 'TYPO3_REQUEST_HOST' ), '', t3lib_div::getIndpEnv ( 'TYPO3_SITE_URL' ) ) );
		if ($this->t3DocRoot == '/') {
			$this->t3DocRoot = 'undefined';
		}
				
		$template ['total'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###FINDACCOMMODATION###' );
		$template ['pulldownbox'] = $this->cObj->getSubpart ( $template ['total'], '###PULLDOWNBOX###' );
		$template ['pulldownbox_item'] = $this->cObj->getSubpart ( $template ['pulldownbox'], '###ITEM###' );
		
		$template ['typeSelection'] = $this->cObj->getSubpart ( $template ['total'], '###TYPESELECTION###' );
		$template ['typeSelection_item'] = $this->cObj->getSubpart ( $template ['typeSelection'], '###ITEM###' );
		
		$template ['findSelection'] = $this->cObj->getSubpart ( $template ['total'], '###FINDSELECTION###' );
		$template ['findSelection_item'] = $this->cObj->getSubpart ( $template ['findSelection'], '###ITEM###' );
		
		$markerArray ['###FORM###'] = $this->pObj->pi_linkTP_keepPIvars_url ( array ('mode' => 'dosearch' ), 0 );
		$markerArray ['###OBJSWFFILE###'] = $this->confArray ['swfFile'] . '?showit=search&tdr=' . $this->t3DocRoot . '&path=' . $this->confArray ['xmlPath'] . '&xml=getRegionsAccommodation.xml';
		$markerArray ['###EMBEDSWFFILE###'] = $this->confArray ['swfFile'] . '?showit=search&tdr=' . $this->t3DocRoot . '&path=' . $this->confArray ['xmlPath'] . '&xml=getRegionsAccommodation.xml';
		
		$row = $this->pObj->getPropertiesUid ( explode ( ',', $this->lConf ['typeSelection'] ) );
		
		foreach ( $row as $k => $v ) {
			$markerArray ['###TYPENAME###'] = $v ['title'];
			$markerArray ['###TYPE###'] = substr ( md5 ( $v ['title'] ), 1, 7 );
			$type_item .= $this->cObj->substituteMarkerArrayCached ( $template ['typeSelection_item'], $markerArray );
		}
		
		$row = $this->pObj->getPropertiesUid ( explode ( ',', $this->lConf ['findSelection'] ) );
		
		foreach ( $row as $k => $v ) {
			$markerArray ['###FINDNAME###'] = $v ['title'];
			$markerArray ['###FIND###'] = substr ( md5 ( $v ['title'] ), 1, 7 );
			$find_item .= $this->cObj->substituteMarkerArrayCached ( $template ['findSelection_item'], $markerArray );
		}
		
		# Init pulldownmenu
		$subpartArray ['###TITEL###'] = $this->pObj->pi_getLL ( 'findAll' );
		$subpartArray ['###UID###'] = $this->pObj->findAll;
		$pulldownbox_item = $this->cObj->substituteMarkerArrayCached ( $template ['pulldownbox_item'], $subpartArray );
		
		# Make pulldownmenu "Region" 
		$this->pObj->getRegionMenu ( $this->parent_uids );
		
		# Fill template with pulldownmenu		
		foreach ( $this->pullDownMenu as $k => $v ) {
			$subpartArray ['###TITEL###'] = $k;
			$subpartArray ['###UID###'] = $v ['uid'];
			$pulldownbox_item .= $this->cObj->substituteMarkerArrayCached ( $template ['pulldownbox_item'], $subpartArray );
		}
		
		unset ( $subpartArray );
		$subpartArray ['###TYPESELECTION###'] = $type_item;
		$subpartArray ['###FINDSELECTION###'] = $find_item;
		$subpartArray ['###PULLDOWNBOX###'] = $pulldownbox_item;

		$content = $this->cObj->substituteMarkerArrayCached ( $template ['total'], $markerArray, $subpartArray );
		return $content;
	}
	
	function listAlt($pObj) {
		
		$this->pObj = &$pObj;
		$this->cObj = &$pObj->cObj;
		$this->lConf = &$pObj->lConf;
		$this->confArray = &$pObj->confArray;
		$this->piVars = &$pObj->piVars;
		$this->findAll = &$pObj->findAll;
		$this->allowCaching = &$pObj->allowCaching;
		$this->conf = &$pObj->conf;
		$this->t3DocRoot = &$pObj->t3DocRoot;
		$this->parent_uids = &$pObj->parent_uids;
		$this->pullDownMenu = &$pObj->pullDownMenu;
		
		// get pluginConfiguration	
		$this->pObj->getExtConfFlexform ();
		
		$this->t3DocRoot = str_replace ( '/', '', str_replace ( t3lib_div::getIndpEnv ( 'TYPO3_REQUEST_HOST' ), '', t3lib_div::getIndpEnv ( 'TYPO3_SITE_URL' ) ) );
		if ($this->t3DocRoot == '/') {
			$this->t3DocRoot = 'undefined';
		}
		
		$template ['total'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###FINDACTIVITY###' );
		$template ['pulldownbox'] = $this->cObj->getSubpart ( $template ['total'], '###PULLDOWNBOX###' );
		$template ['pulldownbox_item'] = $this->cObj->getSubpart ( $template ['pulldownbox'], '###ITEM###' );
		
		$template ['findSelection'] = $this->cObj->getSubpart ( $template ['total'], '###FINDSELECTION###' );
		$template ['findSelection_item'] = $this->cObj->getSubpart ( $template ['findSelection'], '###ITEM###' );
		
		$markerArray ['###FORM###'] = $this->pObj->pi_linkTP_keepPIvars_url ( array ('mode' => 'dosearch' ), 0 );
		$markerArray ['###OBJSWFFILE###'] = $this->confArray ['swfFile'] . '?showitsearch&tdr=' . $this->t3DocRoot . '&path=' . $this->confArray ['xmlPath'] . '&xml=getRegionsActivity.xml';
		$markerArray ['###EMBEDSWFFILE###'] = $this->confArray ['swfFile'] . '?showit=search&tdr=' . $this->t3DocRoot . '&path=' . $this->confArray ['xmlPath'] . '&xml=getRegionsActivity.xml';
		
		$finds = $this->pObj->getPropertiesUid ( explode ( ',', $this->lConf ['typeSelection'] ) );
		
		foreach ( $finds as $k => $v ) {
			$markerArray ['###FINDNAME###'] = $v ['title'];
			$markerArray ['###FIND###'] = substr ( md5 ( $v ['title'] ), 1, 7 );
			$find_item .= $this->cObj->substituteMarkerArrayCached ( $template ['findSelection_item'], $markerArray );
		}
		
		# Init pulldownmenu
		$subpartArray ['###TITEL###'] = $this->pObj->pi_getLL ( 'findAll' );
		$subpartArray ['###UID###'] = $this->pObj->findAll;
		$pulldownbox_item = $this->cObj->substituteMarkerArrayCached ( $template ['pulldownbox_item'], $subpartArray );
		
		# Make pulldownmenu "Region" 
		$this->getRegionMenu ( $this->parent_uids );
		
		# Fill template with pulldownmenu		
		foreach ( $this->pullDownMenu as $k => $v ) {
			$subpartArray ['###TITEL###'] = $k;
			$subpartArray ['###UID###'] = $v ['uid'];
			$pulldownbox_item .= $this->cObj->substituteMarkerArrayCached ( $template ['pulldownbox_item'], $subpartArray );
		}
		
		unset ( $subpartArray );
		$subpartArray ['###FINDSELECTION###'] = $find_item;
		$subpartArray ['###PULLDOWNBOX###'] = $pulldownbox_item;
		return $this->cObj->substituteMarkerArrayCached ( $template ['total'], $markerArray, $subpartArray );
	}
	
	function general($pObj) {
		
		$this->pObj = &$pObj;
		$this->cObj = &$pObj->cObj;
		$this->lConf = &$pObj->lConf;
		$this->confArray = &$pObj->confArray;
		$this->piVars = &$pObj->piVars;
		$this->findAll = &$pObj->findAll;
		$this->allowCaching = &$pObj->allowCaching;
		$this->conf = &$pObj->conf;
		$this->t3DocRoot = &$pObj->t3DocRoot;
		$this->parent_uids = &$pObj->parent_uids;
		$this->pullDownMenu = &$pObj->pullDownMenu;
		$this->acc = &$pObj->acc;
		
		// get pluginConfiguration	
		$this->pObj->getExtConfFlexform ();
		
		$this->t3DocRoot = str_replace ( '/', '', str_replace ( t3lib_div::getIndpEnv ( 'TYPO3_REQUEST_HOST' ), '', t3lib_div::getIndpEnv ( 'TYPO3_SITE_URL' ) ) );
		if ($this->t3DocRoot == '/') {
			$this->t3DocRoot = 'undefined';
		}
		
		# Do pagescroller
		$this->pObj->pageScroller ();
		
		# Get all accommodations
		$this->acc = $this->pObj->getAllRooms ( intval ( $this->piVars ['uid'] ) );
		
		# Make menu
		$this->content = $this->pObj->makeMenu ();
		
		$template ['generaltab'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###GENERALTAB###' );
		$template ['listofaccommodationitem'] = $this->cObj->getSubpart ( $template ['generaltab'], '###LISTOFACCOMMODATIONITEM###' );
		$template ['relation'] = $this->cObj->getSubpart ( $template ['generaltab'], '###RELATION###' );
		$template ['relationlist'] = $this->cObj->getSubpart ( $template ['relation'], '###RELATIONLIST###' );
		$template ['relationitem'] = $this->cObj->getSubpart ( $template ['relationlist'], '###RELATIONITEM###' );
		$template ['feature'] = $this->cObj->getSubpart ( $template ['generaltab'], '###FEATURE###' );
		$template ['featureitem'] = $this->cObj->getSubpart ( $template ['feature'], '###FEATUREITEM###' );
		
		$template ['singleimage'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###SINGLEIMAGE###' );
		$template ['imageitem'] = $this->cObj->getSubpart ( $template ['singleimage'], '###IMAGEITEM###' );
		
		$temp_piVars = $this->piVars;
		unset ( $temp_piVars ['id'] );
		unset ( $temp_piVars ['type'] );
		unset ( $temp_piVars ['scroll'] );
		unset ( $temp_piVars ['specialOffer'] );
		$temp_piVars ['mode'] = 'room';
		
		$link_conf = $this->conf ['listLink.'];
		$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
		$link_conf ['useCacheHash'] = $this->allowCaching;
		$link_conf ['no_cache'] = ! $this->allowCaching;
		$linkATagParams = $link_conf ['ATagParams'];
		
		# Get type icon
		$type = $this->pObj->getInfoAllPropertiesHotel ( intval ( $this->piVars ['uid'] ) );
		
		# Get location
		$loc = $this->pObj->getHotel ();
		
		$features = explode ( ',', $this->lConf ['featureSelection'] );
		
		for($i = 0; $i < sizeOf ( $this->acc ); $i ++) {
			
			$markerArray ['###TITLE###'] = $this->acc [$i] ['title'];

/*
			$find = $this->pObj->getTitleAllPropertiesHotel ( $this->acc [$i] ['uid'], $features [2] );
			
			if ($find) {
				$markerArray ['###TITLE###'] .= ' (' . trim ( preg_replace ( '/[A-Za-z ]/', '', $find ) ) . ' ' . trim ( preg_replace ( '/[0-9-\s+]/', '', $find ) ) . ')';
			} 
*/
						
			$link_conf ['additionalParams'] = t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars ) . '&tx_chtrip_pi1[id]=' . $this->acc [$i] ['uid'];
			$link_conf ['ATagParams'] = $linkATagParams . ' title="' . addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ': ' . addslashes ( $this->acc [$i] ['title'] ) . '"';
			
			$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
			$listofaccomodation .= $this->cObj->substituteMarkerArrayCached ( $template ['listofaccommodationitem'], $markerArray, array (), $wrappedSubpartContentArray );
		}
		
		$subpartArray ['###LISTOFACCOMMODATION###'] = $listofaccomodation;
		
		# Get image
		$image = explode ( ',', $loc ['pictures'] );
		$captions = explode ( '|', $loc ['captions'] );
		$markerArray ['###IMAGE_2###'] = $markerArray ['###IMAGE_1###'] = '';
		
		unset ( $galleryWrappedSubpartContentArray );
		
		if ($image [0]) {
			$title = addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ($captions [0] == '' ? '' : ': ' . trim ( addslashes ( $captions [0] ) ));
			
			$previewImg = $this->conf ['popUpImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $image [0];
			$popUpImg = $this->cObj->cObjGetSingle ( $this->conf ['popUpImg'], $previewImg );
			
			$previewImg = $this->conf ['previewImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $image [0];
			$previewImg ['titleText'] = $previewImg ['altText'] = $title;
			
			$galleryArray ['###IMAGE###'] = $this->cObj->cObjGetSingle ( $this->conf ['previewImg'], $previewImg );
			$galleryArray ['###CAPTION###'] = $captions [0];
			
			//perfectlightbox popuup
			$link_conf = $this->conf ['popUpLink.'];
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			$link_conf ['ATagParams'] .= ' title="' . $title . '" rel="lightbox[lb' . intval ( $this->piVars ['uid'] ) . ']"';
			
			$galleryWrappedSubpartContentArray ['###LINK###'] [0] = '<a href="' . $previewImg ['file'] . '" ' . $link_conf ['ATagParams'] . '/>';
			$galleryWrappedSubpartContentArray ['###LINK###'] [1] = '</a>';
			
			$markerArray ['###IMAGE_1###'] = $this->cObj->substituteMarkerArrayCached ( $template ['imageitem'], $galleryArray, array (), $galleryWrappedSubpartContentArray );
		}
		
		if ($image [1]) {
			
			$title = addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ($captions [1] == '' ? '' : ': ' . trim ( addslashes ( $captions [1] ) ));
			
			$previewImg = $this->conf ['popUpImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $image [1];
			$popUpImg = $this->cObj->cObjGetSingle ( $this->conf ['popUpImg'], $previewImg );
			
			$previewImg = $this->conf ['previewImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $image [1];
			$previewImg ['titleText'] = $previewImg ['altText'] = $title;
			
			$galleryArray ['###IMAGE###'] = $this->cObj->cObjGetSingle ( $this->conf ['previewImg'], $previewImg );
			$galleryArray ['###CAPTION###'] = $captions [1];
			
			//perfectlightbox integration
			//$galleryArray['###IMAGE###'] = preg_replace('/\/>/','rel="lightbox[lb'.intval($this->piVars['uid']).']"/>',$galleryArray['###IMAGE###']);
			$link_conf = $this->conf ['popUpLink.'];
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			$link_conf ['ATagParams'] .= ' title="' . $title . '" rel="lightbox[lb' . intval ( $this->piVars ['uid'] ) . ']"';
			
			$galleryWrappedSubpartContentArray ['###LINK###'] [0] = '<a href="' . $previewImg ['file'] . '" ' . $link_conf ['ATagParams'] . '/>';
			$galleryWrappedSubpartContentArray ['###LINK###'] [1] = '</a>';
			$markerArray ['###IMAGE_2###'] = $this->cObj->substituteMarkerArrayCached ( $template ['imageitem'], $galleryArray, array (), $galleryWrappedSubpartContentArray );
		}
		
		# Do object description
		$markerArray ['###DESCRIPTION###'] = $this->pObj->formatStrRTE ( $loc ['description'] );
		
		# Do Goberlinea Weather Information Extension (via Typoscript)
		$markerArray ['###WEATHERINFORMATION###'] = $this->cObj->cObjGet ( $this->conf ['weatherInfo.'], 'weatherInfo.' );
		
		# Do relation
		$finds = $this->pObj->getRelation ( intval ( $this->piVars ['uid'] ) );
		unset ( $relation_item );
		
		if ($finds) {
			
			$temp_piVars ['mode'] = 'general';
			
			$link_conf = $this->conf ['relLink.'];
			$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			
			$related = explode ( ',', $this->lConf ['PIDrelatedObjectDisplay'] );
			
			if (sizeof ( $related ) > 1) {
				
				foreach ( $finds as $k => $v ) {
					foreach ( $related as $r => $s ) {
						
						$mConf = t3lib_div::makeInstance ( 'tx_chtrip_base' );
						$mConf->init ( $this, $this );
						$mConf->lConf = $this->getExtConf ( $s );
						$mConf->getObjArray ();
						
						$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
						
						# Get type
						$result = $this->getObjType ( $v ['uid'] );
						
						$haystack [] = $result ['parent_uid'];
						if (is_array ( $result ['general'] )) {
							foreach ( $result ['general'] as $l => $m ) {
								$haystack [] = $m ['parent_uid'];
							}
						}
						
						$needle = array_keys ( $mConf->parent_uids );
						$result = array_intersect ( $needle, $haystack );
						if (count ( $result )) {
							
							$temp_piVars = $this->piVars;
							unset ( $temp_piVars ['id'] );
							unset ( $temp_piVars ['type'] );
							unset ( $temp_piVars ['scroll'] );
							unset ( $temp_piVars ['uid'] );
							unset ( $temp_piVars ['specialOffer'] );
							$link_conf ['parameter'] = $s;
							
							$params = array ('region' => $this->findAll, 'allTypes' => 'true' );
							
							$m = $mConf->find ( $params, false );
							
							if (sizeOf ( $m ) > 0) {
								
								$m = $mConf->bubbleSort ( $m, 1 );
								$m = $mConf->indexFinds ( $m );
								$m = $mConf->filterFinds ( $params, $m );
								
								$mConf->totalFinds = sizeOf ( $m );
								
								# Merge finds with regions
								$tree = $mConf->mergeFindsRegions ( $m );
								
								# Lookup item position
								unset ( $mConf->treeC );
								$mConf->findUid ( $tree, $v ['uid'] );
								
								$temp_piVars ['total'] = $mConf->totalFinds;
								$temp_piVars ['item'] = $mConf->treeItem;
								$temp_piVars ['page'] = $temp_piVars ['item'] % $mConf->lConf ['results_at_a_time'] == 0 ? floor ( $temp_piVars ['item'] / $mConf->lConf ['results_at_a_time'] ) - 1 : floor ( $temp_piVars ['item'] / $mConf->lConf ['results_at_a_time'] );
							}
						}
					}
					
					$markerArray ['###TITLE###'] = $v ['title'];
					$link_conf ['additionalParams'] = t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars ) . '&tx_chtrip_pi1[uid]=' . $v ['uid'];
					$link_conf ['ATagParams'] .= ' title="' . addslashes ( $type ['title'] ) . ' ' . addslashes ( $v ['title'] ) . '"';
					
					$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
					$relation_item .= $this->cObj->substituteMarkerArrayCached ( $template ['relationitem'], $markerArray, array (), $wrappedSubpartContentArray );
				}
			}
		}
		
		# Get type and feature
		$subpartArray ['###FEATURE###'] = '';
		
		$arr = $this->pObj->getInfoAllPropertiesHotel ( intval ( $this->piVars ['uid'] ) );
		
		if (is_array ( $arr ['mark'] )) {
			foreach ( $arr ['mark'] as $k => $v ) {
				$markerArray ['###TITLE###'] = $v ['title'];
				$markerArray ['###ICON###'] = preg_replace ( '/alt="" title=""/', 'alt="' . addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ': ' . addslashes ( $v ['title'] ) . '" title="' . addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ': ' . addslashes ( $v ['title'] ) . '"', $v ['icon'] );
				$icon_item .= $this->cObj->substituteMarkerArrayCached ( $template ['featureitem'], $markerArray );
			}
		}
		if (is_array ( $arr ['general'] )) {
			foreach ( $arr ['general'] as $k => $v ) {
				$markerArray ['###TITLE###'] = $v ['title'];
				$markerArray ['###ICON###'] = preg_replace ( '/alt="" title=""/', 'alt="' . addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ': ' . addslashes ( $v ['title'] ) . '" title="' . addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ': ' . addslashes ( $v ['title'] ) . '"', $v ['icon'] );
				$icon_item .= $this->cObj->substituteMarkerArrayCached ( $template ['featureitem'], $markerArray );
			}
		}
		
		if ($relation_item) {
			$relationArray ['###RELATIONLIST###'] = $relation_item;
			$subpartArray ['###RELATION###'] = $this->cObj->substituteMarkerArrayCached ( $template ['relation'], array (), $relationArray );
		} else {
			$subpartArray ['###RELATION###'] = '';
		}
		
		if (is_string ( $icon_item )) {
			$iconSubpartArray ['###FEATUREITEM###'] = $icon_item;
			$subpartArray ['###FEATURE###'] = $this->cObj->substituteMarkerArrayCached ( $template ['feature'], array (), $iconSubpartArray );
		}
		
		$this->content .= $this->cObj->substituteMarkerArrayCached ( $template ['generaltab'], $markerArray, $subpartArray, $wrappedSubpartContentArray );
		return $this->content;
	}
	
	function room($pObj) {
		
		$this->pObj = &$pObj;
		$this->cObj = &$pObj->cObj;
		$this->lConf = &$pObj->lConf;
		$this->confArray = &$pObj->confArray;
		$this->piVars = &$pObj->piVars;
		$this->findAll = &$pObj->findAll;
		$this->allowCaching = &$pObj->allowCaching;
		$this->conf = &$pObj->conf;
		$this->t3DocRoot = &$pObj->t3DocRoot;
		$this->parent_uids = &$pObj->parent_uids;
		$this->pullDownMenu = &$pObj->pullDownMenu;
		$this->acc = &$pObj->acc;
		
		// get pluginConfiguration	
		$this->pObj->getExtConfFlexform ();
		
		$this->t3DocRoot = str_replace ( '/', '', str_replace ( t3lib_div::getIndpEnv ( 'TYPO3_REQUEST_HOST' ), '', t3lib_div::getIndpEnv ( 'TYPO3_SITE_URL' ) ) );
		if ($this->t3DocRoot == '/') {
			$this->t3DocRoot = 'undefined';
		}
		
		# Do pagescroller
		$this->pObj->pageScroller ();
		
		# Get all accommodations
		$this->acc = $this->pObj->getAllRooms ( intval ( $this->piVars ['uid'] ) );
		
		# Make menu
		$this->content = $this->pObj->makeMenu ();
		
		$template ['accommodationtab'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###ACCOMMODATIONTAB###' );
		$template ['kitchen'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###KITCHEN###' );
		$template ['kitchenlist'] = $this->cObj->getSubpart ( $template ['kitchen'], '###KITCHENLIST###' );
		
		$template ['equipment'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###EQUIPMENT###' );
		$template ['equipmentlist'] = $this->cObj->getSubpart ( $template ['equipment'], '###EQUIPMENTLIST###' );
		
		$template ['booking'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###BOOKING###' );
		$template ['season'] = $this->cObj->getSubpart ( $template ['booking'], '###SEASON###' );
		$template ['seasonitem'] = $this->cObj->getSubpart ( $template ['season'], '###SEASONITEM###' );
		
		$template ['wheninfo'] = $this->cObj->getSubpart ( $template ['booking'], '###WHENINFO##' );
		$template ['whenrow1item'] = $this->cObj->getSubpart ( $template ['wheninfo'], '###WHENROW1ITEM###' );
		$template ['whenrow2item'] = $this->cObj->getSubpart ( $template ['wheninfo'], '###WHENROW2ITEM###' );
		
		$template ['pricecategory'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###PRICECATEGORY###' );
		$template ['titleitem'] = $this->cObj->getSubpart ( $template ['pricecategory'], '###TITLEITEM###' );
		$template ['priceitem'] = $this->cObj->getSubpart ( $template ['pricecategory'], '###PRICEITEM###' );
		
		$template ['pricefeature'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###PRICEFEATURE###' );
		$template ['featureitem'] = $this->cObj->getSubpart ( $template ['pricefeature'], '###FEATUREITEM###' );
		
		$template ['spacc'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###SPACC###' );
		
		$template ['singleimage'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###SINGLEIMAGE###' );
		$template ['imageitem'] = $this->cObj->getSubpart ( $template ['singleimage'], '###IMAGEITEM###' );
		
		# Get type icon
		$type = $this->pObj->getInfoAllPropertiesHotel ( intval ( $this->piVars ['uid'] ) );
		
		# Get location
		$loc = $this->pObj->getHotel ();
		
		# Get Date & Price
		$cat = $this->pObj->getCategory ( intval ( $this->piVars ['uid'] ) );
		
		//todo subroutine
		# Do Season 1
		$y = date ( "Y", time () );
		
		$z = array_keys ( $this->whenInfo );
		foreach ( $z as $k => $v ) {
			if (preg_match ( '/till/', $v )) {
				unset ( $z [$k] );
			}
		}
		
		// todo bubblesort
		$m = count ( $z ) / 8;
		$n = count ( $cat );
		$val = 0;
		for($i = 0; $i < $n; $i ++) {
			$c = 0;
			for($j = 0; $j < $m; $j ++) {
				$from = date ( "Y", $cat [$i] [$z [$c]] );
				if ($from > $val) {
					$val = $from;
				}
				$c ++;
			}
		}
		$tab ['1'] = $val;
		
		# Do Season 2
		$y = date ( "Y", time () );
		
		$z = array_keys ( $this->altWhenInfo );
		foreach ( $z as $k => $v ) {
			if (preg_match ( '/till/', $v )) {
				unset ( $z [$k] );
			}
		}
		
		// todo bubblesort
		$m = count ( $z ) / 8;
		$n = count ( $cat );
		$val = 0;
		for($i = 0; $i < $n; $i ++) {
			$c = 0;
			for($j = 0; $j < $m; $j ++) {
				$from = date ( "Y", $cat [$i] [$z [$c]] );
				if ($from > $val) {
					$val = $from;
				}
				$c ++;
			}
		}
		$tab ['2'] = $val;
		
		# Do Time & Price Table
		$y = date ( "Y", time () );
		
		/*
		DATUM �NDERN !!!
		ZUGEF�GT: 7.01.2008
		
		PROBLEM: 2008 reiter zeigt feld von 2007 an
		
		else if( $tab['1'] >=  $tab['2'] && $tab['1'] >= $y){
			$w = array (&$this->whenInfo,&$this->altWhenInfo);	
			$p = array (&$this->priceInfo,&$this->altPriceInfo); // $p[0] == priceInfo
			$h = array (&$this->halfboard,&$this->altHalfboard);	
		} 
		
		HIER �NDERN
		
		sebastian@takomat.com
		
		
		*/
		
		if ($tab ['1'] <= $tab ['2'] && $tab ['1'] >= $y) {
			$w = array (&$this->whenInfo, &$this->altWhenInfo );
			$p = array (&$this->priceInfo, &$this->altPriceInfo ); // $p[0] == priceInfo
			$h = array (&$this->halfboard, &$this->altHalfboard );
		} else if ($tab ['1'] >= $tab ['2'] && $tab ['1'] >= $y) {
			$w = array (&$this->whenInfo, &$this->altWhenInfo );
			$p = array (&$this->priceInfo, &$this->altPriceInfo ); // $p[0] == priceInfo
			$h = array (&$this->halfboard, &$this->altHalfboard );
		} else {
			$w = array (&$this->altWhenInfo, &$this->whenInfo );
			$p = array (&$this->altPriceInfo, &$this->priceInfo ); // $p[0] == altPriceInfo
			$h = array (&$this->altHalfboard, &$this->halfboard );
		}
		
		if ($tab ['1'] >= $y) {
			$index [] = 1;
		}
		// hier bitte einzelne Abfragen!!!
		if ($tab ['2'] >= $y && $tab ['1'] != '1970') {
			$index [] = 2;
		}
		
		if (! is_array ( $index )) {
			$index [] = 1;
		}
		
		$s = intval ( $this->piVars ['s'] );
		
		$wP = $w [$s];
		$pP = $p [$s];
		$hP = $h [$s];
		
		if (is_array ( $cat )) {
			
			# Do Wheninfo table						
			$z = array_keys ( $wP );
			
			foreach ( $cat as $key => $value ) {
				
				$d = 0;
				$t = 0;
				
				for($row = 0; $row < 4; $row ++) {
					$d = 0;
					for($item = 0; $item < 8; $item ++) {
						
						if (empty ( $value [$z [$t]] )) {
							$d ++;
							if (! is_scalar ( $when ['r' . $row] ['i' . $item] [0] )) {
								$when ['r' . $row] ['i' . $item] [0] = '';
							} elseif ($when ['r' . $row] ['i' . $item] [0] != '') {
								$d --;
							}
						} else {
							$when ['r' . $row] ['i' . $item] [0] = date ( $this->conf ['dateFormat'], $value [$z [$t]] );
						}
						
						$t ++;
						
						if (empty ( $value [$z [$t]] )) {
							$d ++;
							if (! is_scalar ( $when ['r' . $row] ['i' . $item] [0] )) {
								$when ['r' . $row] ['i' . $item] [0] = '';
							} elseif ($when ['r' . $row] ['i' . $item] [0] != '') {
								$d --;
							}
						} else {
							$when ['r' . $row] ['i' . $item] [1] = date ( $this->conf ['dateFormat'], $value [$z [$t]] );
						}
						$t ++;
					}
					
					if ($d == 16) {
						$when ['r' . $row] ['showRow'] = '0';
					} else {
						$when ['r' . $row] ['showRow'] = '1';
						$when ['r' . $row] ['showColumn'] = 8 - ($d / 2);
					}
				}
			}
			
			$column = 0;
			$cHash = explode ( ',', $this->pObj->pi_getLL ( 'SeasonTitle' ) );
			$row = 'whenrow1item';
			$maxColumn = 0;
			foreach ( $when as $kP => $vP ) {
				if ($vP ['showColumn']) {
					$showColumn = $vP ['showColumn'];
					unset ( $vP ['showColumn'] );
					if ($showColumn > $maxColumn) {
						$thisColumn = $showColumn - $maxColumn;
						$maxColumn = $showColumn;
					}
				}
				if ($vP ['showRow'] == '1') {
					unset ( $vP ['showRow'] );
					foreach ( $vP as $kS => $vS ) {
						if ($thisColumn > 0) {
							$season ['###TITLE###'] = $cHash [$column ++];
							$season_item .= $this->cObj->substituteMarkerArrayCached ( $template ['seasonitem'], $season );
							$thisColumn --;
						}
						$whenArray ['###FROM###'] = empty ( $vS [0] ) ? '' : $vS [0];
						$whenArray ['###TILL###'] = empty ( $vS [1] ) ? '' : $vS [1];
						$when_item .= $this->cObj->substituteMarkerArrayCached ( $template [$row], $whenArray );
					}
				}
				$row = 'whenrow2item';
			}
			
			unset ( $price_category1 );
			
			# Do full price table
			foreach ( $cat as $key => $value ) {
				unset ( $fullPrice );
				unset ( $price_item );
				
				foreach ( $pP as $k => $v ) {
					if (! empty ( $value [$k] )) {
						$priceArray ['###PRICE###'] = $value [$k];
						$price_item .= $this->cObj->substituteMarkerArrayCached ( $template ['priceitem'], $priceArray );
						$fullPrice = true;
						$showFeature = true;
					} else {
						$priceArray ['###PRICE###'] = '';
						$hidden = $this->cObj->substituteMarkerArrayCached ( $template ['priceitem'], $priceArray );
						$hidden = preg_replace ( '/(<div)(.*?)(id=")(.*?)(")(>)/', '$1$2$3preis-saison-hidden$5$6', $hidden );
						$price_item .= $hidden;
					}
				}
				
				if ($fullPrice) {
					
					$link_conf = $this->conf ['catLink.'];
					$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
					$link_conf ['useCacheHash'] = $this->allowCaching;
					$link_conf ['no_cache'] = ! $this->allowCaching;
					
					$needle = array ('/[\-\-]/', '/\(/' );
					$replace = array ('�<span class="moz-break"></span>', '<span class="moz-break"></span>(' );
					$title = preg_replace ( $needle, $replace, $value ['title'] );
					$priceArray ['###TITLE###'] = $title;
					$link_conf ['additionalParams'] .= '&tx_chtrip_pi1[mode]=request&tx_chtrip_pi1[uid]=' . intval ( $this->piVars ['uid'] ) . '&tx_chtrip_pi1[id]=' . intval ( $this->piVars ['id'] ) . '&tx_chtrip_pi1[cat]=' . $value ['uid'];
					$link_conf ['ATagParams'] .= ' title="' . addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ': ' . trim ( addslashes ( $value ['title'] ) ) . '"';
					
					$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
					$price_title = $this->cObj->substituteMarkerArrayCached ( $template ['titleitem'], $priceArray, array (), $wrappedSubpartContentArray );
					
					$subpartArray ['###TITLEINFO###'] = $price_title;
					$subpartArray ['###PRICEINFO###'] = $price_item;
					$price_category1 .= $this->cObj->substituteMarkerArrayCached ( $template ['pricecategory'], array (), $subpartArray );
				}
			}
			
			# Do line
			if ($price_category1) {
				$fullPrice = true;
			} else {
				$fullPrice = false;
			}
			
			# Get price property of 1st category!
			if ($showFeature == true) {
				$priceFeature = $this->getSubInfo ( $cat [0] ['uid'] );
				unset ( $markerArray );
				if (is_array ( $priceFeature )) {
					foreach ( $priceFeature as $k => $v ) {
						$markerArray ['###TITLE###'] = $v ['title'];
						$price_feature .= $this->cObj->substituteMarkerArrayCached ( $template ['featureitem'], $markerArray );
					}
				}
			}
			
			$subpartArray ['###PRICEFEATUREROW###'] = $price_feature;
			$price_category1 .= $this->cObj->substituteMarkerArrayCached ( $template ['pricefeature'], array (), $subpartArray );
			
			unset ( $showFeature );
			unset ( $price_feature );
			unset ( $price_category2 );
			
			# Do halfboard price table			
			foreach ( $cat as $key => $value ) {
				
				unset ( $halfboard );
				unset ( $price_item );
				
				foreach ( $hP as $k => $v ) {
					if (! empty ( $value [$k] )) {
						$priceArray ['###PRICE###'] = $value [$k];
						$price_item .= $this->cObj->substituteMarkerArrayCached ( $template ['priceitem'], $priceArray );
						$halfboard = true;
						$showFeature = true;
					} else {
						$priceArray ['###PRICE###'] = '';
						$hidden = $this->cObj->substituteMarkerArrayCached ( $template ['priceitem'], $priceArray );
						$hidden = preg_replace ( '/(<div)(.*?)(id=")(.*?)(")(>)/', '$1$2$3preis-saison-hidden$5$6', $hidden );
						$price_item .= $hidden;
					}
				}
				
				if ($halfboard) {
					
					$link_conf = $this->conf ['catLink.'];
					$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
					$link_conf ['useCacheHash'] = $this->allowCaching;
					$link_conf ['no_cache'] = ! $this->allowCaching;
					
					$needle = array ('/[\-\-]/', '/\(/' );
					$replace = array ('�<span class="moz-break"></span>', '<span class="moz-break"></span>(' );
					$title = preg_replace ( $needle, $replace, $value ['title'] );
					$priceArray ['###TITLE###'] = $title;
					$link_conf ['additionalParams'] .= '&tx_chtrip_pi1[mode]=request&tx_chtrip_pi1[uid]=' . intval ( $this->piVars ['uid'] ) . '&tx_chtrip_pi1[id]=' . intval ( $this->piVars ['id'] ) . '&tx_chtrip_pi1[cat]=' . $value ['uid'];
					$link_conf ['ATagParams'] .= ' title="' . addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ': ' . trim ( addslashes ( $value ['title'] ) ) . '"';
					
					$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
					$price_title = $this->cObj->substituteMarkerArrayCached ( $template ['titleitem'], $priceArray, array (), $wrappedSubpartContentArray );
					
					$subpartArray ['###TITLEINFO###'] = $price_title;
					$subpartArray ['###PRICEINFO###'] = $price_item;
					$price_category2 .= $this->cObj->substituteMarkerArrayCached ( $template ['pricecategory'], array (), $subpartArray );
				}
			}
			
			# Do line
			if ($price_category2) {
				$halfboard = true;
			} else {
				$halfboard = false;
			}
			
			if ($showFeature) {
				$markerArray ['###TITLE###'] = $this->pObj->pi_getLL ( 'halfboardinfo' );
				$price_feature .= $this->cObj->substituteMarkerArrayCached ( $template ['featureitem'], $markerArray );
			}
			$subpartArray ['###PRICEFEATUREROW###'] = $price_feature;
			$price_category2 .= $this->cObj->substituteMarkerArrayCached ( $template ['pricefeature'], array (), $subpartArray );
		}
		
		# Do Booking
		$altTitle = addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] );
		
		$markerArray ['###SEASON2###'] = $markerArray ['###SEASON1###'] = '';
		$wrappedSubpartContentArray ['###LINKSEASON2###'] = $wrappedSubpartContentArray ['###LINKSEASON1###'] = '';
		
		$temp_piVars = $this->piVars;
		$temp_piVars ['mode'] = 'room';
		unset ( $temp_piVars ['s'] );
		
		$y = date ( "Y", time () );
		
		if (in_array ( '1', $index )) {
			$markerArray ['###SEASON1###'] = $this->pObj->pi_getLL ( 'SeasonTabTitle' ) . ' ' . $y;
			$link_conf = intval ( $this->piVars ['s'] ) === 0 || ! intval ( $this->piVars ['s'] ) ? $this->conf ['menuActLink.'] : $this->conf ['menuLink.'];
			$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
			$link_conf ['additionalParams'] .= t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars ) . '&tx_chtrip_pi1[specialOffer]=0';
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			$link_conf ['ATagParams'] .= ' title="' . $altTitle . ' ' . $this->pObj->pi_getLL ( 'SeasonAltTitle' ) . ' ' . $y . '"';
			
			$wrappedSubpartContentArray ['###LINKSEASON1###'] = $this->cObj->typolinkWrap ( $link_conf );
			
			//increment year
			$y ++;
		}
		
		if (in_array ( '2', $index )) {
			
			$markerArray ['###SEASON2###'] = $this->pObj->pi_getLL ( 'SeasonTabTitle' ) . ' ' . $y;
			$link_conf = intval ( $this->piVars ['s'] ) === 1 ? $this->conf ['menuActLink.'] : $this->conf ['menuLink.'];
			$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
			$link_conf ['additionalParams'] .= t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars ) . '&tx_chtrip_pi1[specialOffer]=1';
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			$link_conf ['ATagParams'] .= ' title="' . $altTitle . ' ' . $this->pObj->pi_getLL ( 'SeasonAltTitle' ) . ' ' . $y . '"';
			
			$wrappedSubpartContentArray ['###LINKSEASON2###'] = $this->cObj->typolinkWrap ( $link_conf );
		}
		$booking = '';
		
		if ($season_item && $when_item) {
			$subpartArray ['###SEASON###'] = $season_item;
			$subpartArray ['###WHENINFO###'] = $when_item;
			$markerArray ['###PRICECATEGORYPART###'] = $price_category1 . $line . $price_category2;
			$booking = $this->cObj->substituteMarkerArrayCached ( $template ['booking'], $markerArray, $subpartArray, $wrappedSubpartContentArray );
		}
		
		# Do special offer 
		unset ( $markerArray );
		
		$acc = $this->pObj->getRoom ();
		
		for($i = 1; $i < 4; $i ++) {
			if (! empty ( $acc ['sp_title_' . $i] )) {
				$markerArray ['###TITLE###'] = $acc ['sp_title_' . $i];
				$markerArray ['###DESCRIPTION###'] = $this->pObj->formatStr ( $acc ['sp_description_' . $i] );
				$spacc .= $this->cObj->substituteMarkerArrayCached ( $template ['spacc'], $markerArray );
			}
		}
		
		unset ( $markerArray );
		
		$markerArray ['###BOOKINGPART###'] = $booking;
		$markerArray ['###SPECIALOFFER###'] = $spacc;
		$markerArray ['###TYPOLOGIE###'] = $this->pObj->formatStr ( $acc ['description'] );
		$markerArray ['###ARRIVALANDDEPARTURE###'] = $this->pObj->formatStr ( $acc ['arrivalanddeparture'] );
		$markerArray ['###MISCELLANEOUS###'] = $this->pObj->formatStrRTE ( $acc ['miscellaneous'] );
		
		$features = explode ( ',', $this->lConf ['featureSelection'] );
		
		# Get kitchenlist
		$finds = $this->pObj->getTitleAllPropertiesHotel ( intval ( $this->piVars ['id'] ), $features [1] );
		
		if ($finds) {
			$markerArray ['###KITCHENLIST###'] = $finds;
			$kitchenList = $this->cObj->substituteMarkerArrayCached ( $template ['kitchen'], $markerArray );
		}
		
		# Get equipmentlist
		$finds = $this->pObj->getTitleAllPropertiesHotel ( intval ( $this->piVars ['id'] ), $features [0] );
		
		if ($finds) {
			$markerArray ['###EQUIPMENTLIST###'] = $finds;
			$equipmentList = $this->cObj->substituteMarkerArrayCached ( $template ['equipment'], $markerArray );
		}
		
		if ($fullPrice && $halfboard) {
			$line = '<hr>';
		} else {
			$line = '';
		}
		
		unset ( $subpartArray );
		$subpartArray ['###KITCHEN###'] = $kitchenList;
		$subpartArray ['###EQUIPMENT###'] = $equipmentList;
		
		# Get image
		$image = explode ( ',', $acc ['pictures'] );
		$captions = explode ( '|', $acc ['captions'] );
		unset ( $galleryWrappedSubpartContentArray );
		$markerArray ['###IMAGE_2###'] = $markerArray ['###IMAGE_1###'] = '';
		
		if ($image [0]) {
			$title = addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ($captions [0] == '' ? '' : ': ' . trim ( addslashes ( $captions [0] ) ));
			
			$previewImg = $this->conf ['popUpImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $image [0];
			$popUpImg = $this->cObj->cObjGetSingle ( $this->conf ['popUpImg'], $previewImg );
			
			$previewImg = $this->conf ['previewImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $image [0];
			$previewImg ['titleText'] = $previewImg ['altText'] = $title;
			
			$galleryArray ['###IMAGE###'] = $this->cObj->cObjGetSingle ( $this->conf ['previewImg'], $previewImg );
			$galleryArray ['###CAPTION###'] = $captions [0];
			
			//perfectlightbox popuup
			$link_conf = $this->conf ['popUpLink.'];
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			$link_conf ['ATagParams'] .= ' title="' . $title . '" rel="lightbox[lb' . intval ( $this->piVars ['uid'] ) . ']"';
			
			$galleryWrappedSubpartContentArray ['###LINK###'] [0] = '<a href="' . $previewImg ['file'] . '" ' . $link_conf ['ATagParams'] . '/>';
			$galleryWrappedSubpartContentArray ['###LINK###'] [1] = '</a>';
			
			$markerArray ['###IMAGE_1###'] = $this->cObj->substituteMarkerArrayCached ( $template ['imageitem'], $galleryArray, array (), $galleryWrappedSubpartContentArray );
		}
		
		if ($image [1]) {
			$title = addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ($captions [1] == '' ? '' : ': ' . trim ( addslashes ( $captions [1] ) ));
			
			$previewImg = $this->conf ['popUpImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $image [1];
			$popUpImg = $this->cObj->cObjGetSingle ( $this->conf ['popUpImg'], $previewImg );
			
			$previewImg = $this->conf ['previewImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $image [1];
			$previewImg ['titleText'] = $previewImg ['altText'] = $title;
			
			$galleryArray ['###IMAGE###'] = $this->cObj->cObjGetSingle ( $this->conf ['previewImg'], $previewImg );
			$galleryArray ['###CAPTION###'] = $captions [1];
			
			//perfectlightbox popuup
			$link_conf = $this->conf ['popUpLink.'];
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			$link_conf ['ATagParams'] .= ' title="' . $title . '" rel="lightbox[lb' . intval ( $this->piVars ['uid'] ) . ']"';
			
			$galleryWrappedSubpartContentArray ['###LINK###'] [0] = '<a href="' . $previewImg ['file'] . '" ' . $link_conf ['ATagParams'] . '/>';
			$galleryWrappedSubpartContentArray ['###LINK###'] [1] = '</a>';
			
			$markerArray ['###IMAGE_2###'] = $this->cObj->substituteMarkerArrayCached ( $template ['imageitem'], $galleryArray, array (), $galleryWrappedSubpartContentArray );
		}
		
		# Do image gallery link
		$wrappedSubpartContentArray ['###LINK###'] = $markerArray ['###IMAGEGALLERYPREFIX###'] = $markerArray ['###IMAGEGALLERY###'] = '';
		
		if (count ( $image ) > 2) {
			$markerArray ['###IMAGEGALLERY###'] = $this->pObj->pi_getLL ( 'ImageGallery' );
			$markerArray ['###IMAGEGALLERYPREFIX###'] = $this->pObj->pi_getLL ( 'ImageGalleryPrefix' );
			$temp_piVars = $this->piVars;
			$temp_piVars ['type'] = $temp_piVars ['mode'] = 'room';
			
			$link_conf = $this->conf ['moreLink.'];
			$link_conf ['parameter'] = $GLOBALS ['TSFE']->id;
			$link_conf ['additionalParams'] .= t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $temp_piVars );
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			$link_conf ['ATagParams'] .= ' title="' . addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . '"';
			
			$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
		}
		
		# Do template
		$this->content .= $this->cObj->substituteMarkerArrayCached ( $template ['accommodationtab'], $markerArray, $subpartArray, $wrappedSubpartContentArray );
		return $this->content;
	
	}
	
	function image($pObj) {
		
		$this->pObj = &$pObj;
		$this->cObj = &$pObj->cObj;
		$this->lConf = &$pObj->lConf;
		$this->confArray = &$pObj->confArray;
		$this->piVars = &$pObj->piVars;
		$this->findAll = &$pObj->findAll;
		$this->allowCaching = &$pObj->allowCaching;
		$this->conf = &$pObj->conf;
		$this->t3DocRoot = &$pObj->t3DocRoot;
		$this->parent_uids = &$pObj->parent_uids;
		$this->pullDownMenu = &$pObj->pullDownMenu;
		$this->acc = &$pObj->acc;
		
		// get pluginConfiguration	
		$this->pObj->getExtConfFlexform ();
		
		$this->t3DocRoot = str_replace ( '/', '', str_replace ( t3lib_div::getIndpEnv ( 'TYPO3_REQUEST_HOST' ), '', t3lib_div::getIndpEnv ( 'TYPO3_SITE_URL' ) ) );
		if ($this->t3DocRoot == '/') {
			$this->t3DocRoot = 'undefined';
		}
		
		# Do pagescroller
		$this->pObj->pageScroller ();
		
		# Get all accommodations
		$this->acc = $this->pObj->getAllRooms ( intval ( $this->piVars ['uid'] ) );
		
		# Make menu
		$this->content = $this->pObj->makeMenu ();
		
		$template ['imagetab'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###IMAGETAB###' );
		$template ['imageitem'] = $this->cObj->getSubpart ( $template ['imagetab'], '###IMAGEITEM###' );
		
		# Get type icon
		$type = $this->pObj->getInfoAllPropertiesHotel ( intval ( $this->piVars ['uid'] ) );
		
		# Get location
		$loc = $this->pObj->getHotel ();
		
		# Get object
		$obj = $this->pObj->getRowHotel ( $this->piVars ['type'] );
		
		# Get image
		$image = explode ( ',', $obj ['pictures'] );
		$captions = explode ( '|', $obj ['captions'] );
		
		$switch = 0;
		
		for($i = 0; $i < sizeOf ( $image ); $i ++) {
			
			$title = addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ($captions [$i] == '' ? '' : ': ' . trim ( addslashes ( $captions [$i] ) ));
			
			$previewImg = $this->conf ['popUpImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $image [$i];
			$popUpImg = $this->cObj->cObjGetSingle ( $this->conf ['popUpImg'], $previewImg );
			
			$previewImg = $this->conf ['previewImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $image [$i];
			$previewImg ['titleText'] = $previewImg ['altText'] = $title;
			
			$markerArray ['###IMAGE###'] = $this->cObj->cObjGetSingle ( $this->conf ['previewImg'], $previewImg );
			$markerArray ['###CAPTION###'] = $captions [$i];
			
			$link_conf = $this->conf ['popUpLink.'];
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			$link_conf ['ATagParams'] .= ' title="' . $title . '" rel="lightbox[lb' . intval ( $this->piVars ['uid'] ) . ']"';
			
			$link_conf ['parameter'] = $this->lConf ['PIDpopUpDisplay'];
			
			$wrappedSubpartContentArray ['###LINK###'] [0] = '<a href="' . $previewImg ['file'] . '" ' . $link_conf ['ATagParams'] . '/>';
			$wrappedSubpartContentArray ['###LINK###'] [1] = '</a>';
			
			$switch = $switch ^ 1;
			if ($switch == 1) {
				$content_item .= $this->cObj->substituteMarkerArrayCached ( $template ['imageitem'], $markerArray, array (), $wrappedSubpartContentArray );
			} else {
				$content_item .= $this->cObj->substituteMarkerArrayCached ( $template ['imageitem'], $markerArray, array (), $wrappedSubpartContentArray );
				$content_item .= '<div style="clear:both"></div>';
			}
		
		}
		
		$subpartArray ['###IMAGELIST###'] = $content_item;
		$this->content .= $this->cObj->substituteMarkerArrayCached ( $template ['imagetab'], array (), $subpartArray );
		
		return $this->content;
	}
	
	function map($pObj) {
		
		$this->pObj = &$pObj;
		$this->cObj = &$pObj->cObj;
		$this->lConf = &$pObj->lConf;
		$this->confArray = &$pObj->confArray;
		$this->piVars = &$pObj->piVars;
		$this->findAll = &$pObj->findAll;
		$this->allowCaching = &$pObj->allowCaching;
		$this->conf = &$pObj->conf;
		$this->t3DocRoot = &$pObj->t3DocRoot;
		$this->parent_uids = &$pObj->parent_uids;
		$this->pullDownMenu = &$pObj->pullDownMenu;
		$this->acc = &$pObj->acc;
		
		// get pluginConfiguration	
		$this->pObj->getExtConfFlexform ();
		
		$this->t3DocRoot = str_replace ( '/', '', str_replace ( t3lib_div::getIndpEnv ( 'TYPO3_REQUEST_HOST' ), '', t3lib_div::getIndpEnv ( 'TYPO3_SITE_URL' ) ) );
		if ($this->t3DocRoot == '/') {
			$this->t3DocRoot = 'undefined';
		}
		
		# Do pagescroller
		$this->pObj->pageScroller ();
		
		# Get all accommodations
		$this->acc = $this->pObj->getAllRooms ( intval ( $this->piVars ['uid'] ) );
		
		# Make menu
		$this->content = $this->pObj->makeMenu ();
		
		$template ['imagetab'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###IMAGETAB###' );
		$template ['imageitem'] = $this->cObj->getSubpart ( $template ['imagetab'], '###IMAGEITEM###' );
		
		// get all properties from this hotel
		$type = $this->pObj->getAllPropertiesHotel ( intval ( $this->piVars ['uid'] ) );
		
		# Get location
		$loc = $this->pObj->getHotel ();
		
		# Get object
		$obj = $this->pObj->getRowHotel ( $this->piVars ['type'] );
		
		# Get map
		$map = explode ( ',', $obj ['wheremap'] );
		
		for($i = 0; $i < sizeOf ( $map ); $i ++) {
			
			$title = addslashes ( $type ['title'] ) . ' ' . addslashes ( $loc ['title'] ) . ($captions [$i] == '' ? '' : ': ' . trim ( addslashes ( $captions [$i] ) ));
			
			$previewImg = $this->conf ['mapImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $map [$i];
			$popUpImg = $this->cObj->cObjGetSingle ( $this->conf ['mapImg'], $previewImg );
			
			$previewImg = $this->conf ['mapImg.'];
			$previewImg ['file'] = $this->pObj->uploadPath () . $map [$i];
			$previewImg ['titleText'] = $previewImg ['altText'] = $title;
			
			$markerArray ['###IMAGE###'] = $this->cObj->cObjGetSingle ( $this->conf ['mapImg'], $previewImg );
			$markerArray ['###CAPTION###'] = '';
			
			$link_conf = $this->conf ['popUpLink.'];
			$link_conf ['useCacheHash'] = $this->allowCaching;
			$link_conf ['no_cache'] = ! $this->allowCaching;
			
			$link_conf ['ATagParams'] .= ' title="' . $title . '"';
			$link_conf ['parameter'] = $this->lConf ['PIDpopUpDisplay'] . $this->resizePopUpParams ( $popUpImg );
			
			if ($piVars ['type'] == 'location') {
				$link_conf ['additionalParams'] .= '&tx_chtrip[mode]=popup&tx_chtrip_pi1[uid]=' . intval ( $this->piVars ['uid'] ) . '&tx_chtrip_pi1[pic]=' . ($i + 1) . '&tx_chtrip_pi1[type]=' . $this->piVars ['type'] . '&tx_chtrip_pi1[special]=map';
			} else {
				$link_conf ['additionalParams'] .= '&tx_chtrip[mode]=popup&tx_chtrip_pi1[uid]=' . intval ( $this->piVars ['uid'] ) . '&tx_chtrip_pi1[id]=' . intval ( $this->piVars ['id'] ) . '&tx_chtrip_pi1[pic]=' . ($i + 1) . '&tx_chtrip_pi1[type]=' . $this->piVars ['type'] . '&tx_chtrip_pi1[special]=map';
			}
			$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $link_conf );
			
			$content_item .= $this->cObj->substituteMarkerArrayCached ( $template ['imageitem'], $markerArray, array (), $wrappedSubpartContentArray );
		}
		
		$subpartArray ['###IMAGELIST###'] = $content_item;
		$this->content .= $this->cObj->substituteMarkerArrayCached ( $template ['imagetab'], array (), $subpartArray );
		
		return $this->content;
	}
	
	function video($pObj) {
		
		$this->pObj = &$pObj;
		$this->cObj = &$pObj->cObj;
		$this->lConf = &$pObj->lConf;
		$this->confArray = &$pObj->confArray;
		$this->piVars = &$pObj->piVars;
		$this->findAll = &$pObj->findAll;
		$this->allowCaching = &$pObj->allowCaching;
		$this->conf = &$pObj->conf;
		$this->t3DocRoot = &$pObj->t3DocRoot;
		$this->parent_uids = &$pObj->parent_uids;
		$this->pullDownMenu = &$pObj->pullDownMenu;
		$this->acc = &$pObj->acc;
		
		// get pluginConfiguration	
		$this->pObj->getExtConfFlexform ();
		
		$this->t3DocRoot = str_replace ( '/', '', str_replace ( t3lib_div::getIndpEnv ( 'TYPO3_REQUEST_HOST' ), '', t3lib_div::getIndpEnv ( 'TYPO3_SITE_URL' ) ) );
		if ($this->t3DocRoot == '/') {
			$this->t3DocRoot = 'undefined';
		}
		
		# Do pagescroller
		$this->pObj->pageScroller ();
		
		# Get all accommodations
		$this->acc = $this->pObj->getAllRooms ( intval ( $this->piVars ['uid'] ) );
		
		# Make menu
		$this->content = $this->pObj->makeMenu ();
		
		$template ['videotab'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###VIDEOTAB###' );
		
		# Get object
		$row = $this->pObj->getRowHotel ( $this->piVars ['type'] );
		
		# Get video
		$video = explode ( ',', $row ['video'] );
		$vFile = str_replace ( '.' . $this->vType (), '', $video [0] );
		
		$markerArray ['###OBJSWFFILE###'] = $this->vLoader () . '?timestamp=' . time () . '&flv=' . $this->pObj->uploadPath () . $vFile . '&type=' . $this->vType ();
		$markerArray ['###EMBEDSWFFILE###'] = $this->vLoader () . '?timestamp=' . time () . '&flv=' . $this->pObj->uploadPath () . $vFile . '&type=' . $this->vType ();
		
		$this->content .= $this->cObj->substituteMarkerArrayCached ( $template ['videotab'], array (), $markerArray );
		return $this->content;
	}
	
	function finds($pObj) {
		
		$this->pObj = &$pObj;
		$this->cObj = &$pObj->cObj;
		$this->lConf = &$pObj->lConf;
		$this->confArray = &$pObj->confArray;
		$this->piVars = &$pObj->piVars;
		$this->findAll = &$pObj->findAll;
		$this->allowCaching = &$pObj->allowCaching;
		$this->conf = &$pObj->conf;
		$this->t3DocRoot = &$pObj->t3DocRoot;
		$this->parent_uids = &$pObj->parent_uids;
		$this->pullDownMenu = &$pObj->pullDownMenu;
		$this->acc = &$pObj->acc;
		
		// get pluginConfiguration	
		$this->pObj->getExtConfFlexform ();
		
		$this->t3DocRoot = str_replace ( '/', '', str_replace ( t3lib_div::getIndpEnv ( 'TYPO3_REQUEST_HOST' ), '', t3lib_div::getIndpEnv ( 'TYPO3_SITE_URL' ) ) );
		if ($this->t3DocRoot == '/') {
			$this->t3DocRoot = 'undefined';
		}
		
		$this->maxPages = $this->lConf ['maxPages'];
		$this->findsAtATime = $this->lConf ['results_at_a_time'];
		$this->tempFindsAtATime = $this->lConf ['results_at_a_time'];
		$this->tempFindsAtATime2 = $this->lConf ['results_at_a_time'];
		$this->page = empty ( $this->piVars ['page'] ) ? 0 : $this->piVars ['page'];
		
		$this->template ['spteaser'] = $this->cObj->getSubpart ( $pObj->templateCode, '###SPLIST###' );
		$this->template ['top'] = $this->cObj->getSubpart ( $pObj->templateCode, '###TOPCATEGORY###' );
		$this->template ['total'] = $this->cObj->getSubpart ( $pObj->templateCode, '###OBJECTLIST###' );
		$this->template ['singlerow'] = $this->cObj->getSubpart ( $this->template ['total'], '###SINGLEROW###' );
		$this->template ['row'] = $this->cObj->getSubpart ( $this->template ['singlerow'], '###ITEM###' );
		
		$this->template ['pagebrowser'] = $this->cObj->getSubpart ( $pObj->templateCode, '###PAGEBROWSER###' );
		$this->template ['singlepage'] = $this->cObj->getSubpart ( $this->template ['pagebrowser'], '###SINGLEPAGE###' );
		$this->template ['page'] = $this->cObj->getSubpart ( $this->template ['singlepage'], '###PAGEITEM###' );
		$this->template ['prev'] = $this->cObj->getSubpart ( $this->template ['singlepage'], '###PREVITEM###' );
		$this->template ['next'] = $this->cObj->getSubpart ( $this->template ['singlepage'], '###NEXTITEM###' );
		
		if (! $this->piVars ['region']) {
			$this->piVars ['region'] = $this->findAll;
		}
		
		# Do search
		$finds = $this->pObj->find ( $this->piVars, false );
		
		if (sizeOf ( $finds ) > 0) {
			
			$finds = $this->pObj->bubbleSort ( $finds, 1 );
			$finds = $this->pObj->indexFinds ( $finds );
			$finds = $this->pObj->filterFinds ( $this->piVars, $finds );
			
			//TODO: insert limit
			$this->totalFinds = sizeOf ( $finds );
			
			# Get all rooms from hotel        
			foreach ( $finds as $k => $v ) {
				$finds [$k] ['accommodations'] = $this->pObj->getAllRooms ( $v ['uid'] );
			}
			
			# Merge finds with regions
			$this->tree = $this->pObj->mergeFindsRegions ( $finds );
			
			# Do XML-File
			$flashFile = $this->pObj->xmlFile ( $this->tree );
		}
		
		# Do flash map        
		$markerArray ['###OBJSWFFILE###'] = $this->confArray ['swfFile'] . '?clearache=' . time () . '&showit=result&tdr=' . $t3DocRoot . '&path=' . $this->confArray ['xmlPath'] . '&xml=' . $flashFile;
		$markerArray ['###EMBEDSWFFILE###'] = $this->confArray ['swfFile'] . '?clearache=' . time () . '&showit=result&tdr=' . $t3DocRoot . '&path=' . $this->confArray ['xmlPath'] . '&xml=' . $flashFile;
		
		# Do finds template		
		if ($this->piVars ['region'] != $this->findAll) {
			$row = $this->pObj->getRegionTitle ( intval ( $this->piVars ['region'] ) );
			$whereinfo = $row ['title'];
		} else {
			$whereinfo = $this->pObj->pi_getLL ( 'findAll' );
		}
		
		$markerArray ['###WHEREINFO###'] = $whereinfo;
		
		if ($this->lConf ['what_to_display'] == 'listNo') {
			
			$miscellaneous = $this->pObj->getMiscellaneous ();
			
			if ($this->piVars ['allTypes'] && $this->piVars ['region'] == $this->findAll) {
				$m = 1;
			}
			
			if ($this->piVars ['allTypes'] && $this->piVars ['region'] != $this->findAll) {
				$m = 1;
			}
			
			if (! $this->piVars ['allTypes'] && $this->piVars ['region'] != $this->findAll) {
				$m = 0;
			}
			
			switch ($m) {
				case 1 :
					$whatinfo = $this->pObj->getAllWhatInfo ();
					break;
				case 0 :
					$whatinfo = $this->pObj->getWhatInfo ();
					break;
			}
			
			$template ['total'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###FINDSACCOMMODATION###' );
			$markerArray ['###WHATINFO###'] = is_array ( $whatinfo ) ? implode ( ', ', $whatinfo ) : '';
			$markerArray ['###MISCELLANEOUS###'] = is_array ( $miscellaneous ) ? implode ( ', ', $miscellaneous ) : '';
		
		} else {
			
			if ($this->piVars ['allTypes'] && $this->piVars ['region'] == $this->findAll) {
				$m = 1;
			}
			
			if ($this->piVars ['allTypes'] && $this->piVars ['region'] != $this->findAll) {
				$m = 1;
			}
			
			if (! $this->piVars ['allTypes'] && $this->piVars ['region'] != $this->findAll) {
				$m = 0;
			}
			
			switch ($m) {
				case 1 :
					$whatinfo = $this->pObj->getAllWhatInfo ();
					break;
				case 0 :
					$whatinfo = $this->pObj->getMiscAlt ();
					break;
			}
			
			$template ['total'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###FINDSACTIVITY###' );
			$markerArray ['###WHATINFO###'] = is_array ( $whatinfo ) ? implode ( ', ', $whatinfo ) : '';
		}
		
		$markerArray ['###COUNT###'] = $this->totalFinds;
		$content = $this->cObj->substituteMarkerArrayCached ( $template ['total'], $markerArray, array (), array () );
		
		if (sizeOf ( $this->tree ) > 0) {
			
			$this->titleLinkpiVars = $this->piVars;
			$this->titleLinkpiVars ['mode'] = 'general';
			unset ( $this->titleLinkpiVars ['uid'] );
			$this->titleLinkpiVars ['total'] = $this->totalFinds;
			
			$this->titleLink = $this->conf ['titleLink.'];
			$this->titleLink ['parameter'] = $GLOBALS ['TSFE']->id;
			$this->titleLink ['useCacheHash'] = $this->allowCaching;
			$this->titleLink ['no_cache'] = ! $this->allowCaching;
			$this->titleLinkATagParams = $this->titleLink ['ATagParams'];
			
			$this->pbLink_conf = $this->conf ['pageBrowserLink.'];
			$this->pbLink_conf ['parameter'] = $GLOBALS ['TSFE']->id;
			$this->pbLink_conf ['useCacheHash'] = ! $this->allowCaching;
			$this->pbLink_conf ['no_cache'] = $this->allowCaching;
			
			$this->pbLink_piVars = $this->piVars;
			$this->pbLink_piVars ['region'] = intval ( $this->piVars ['region'] );
			
			$this->objImg = $this->conf ['findImg.'];
			
			$this->getTree ( $this->tree, '' );
			
			if (sizeof ( $this->pageBrowserArray ) > $this->maxPages && $this->page > 0) {
				$tempArray [] = $this->pageBrowserArray [$this->page - 1] ['prev'];
			}
			
			foreach ( $this->pageBrowserArray as $key => $value ) {
				if ($key > $this->page && $key < $this->page + $this->maxPages || $this->page + $this->maxPages > sizeof ( $this->pageBrowserArray ) && $key >= sizeof ( $this->pageBrowserArray ) - $this->maxPages && $key != $this->page) {
					$tempArray [] = $value ['page'];
				} elseif ($key == $this->page) {
					$tempArray [] = preg_replace ( '/' . $this->conf ['pageBrowserLink.'] ['ATagParams'] . '/', $this->conf ['pageBrowserLinkAct.'] ['ATagParams'], $value ['page'] );
				}
			}
			if (sizeof ( $this->pageBrowserArray ) > $this->page + $this->maxPages) {
				$tempArray [] = $this->pageBrowserArray [$this->page + 1] ['next'];
			}
			$template ['pagebrowser'] = $this->cObj->getSubpart ( $this->pObj->templateCode, '###PAGEBROWSER###' );
			unset ( $markerArray );
			$markerArray ['###PAGECURSOR###'] = $this->page * $this->findsAtATime + 1;
			$markerArray ['###FINDS###'] = $this->totalFinds;
			$markerArray ['###PAGEEND###'] = $this->page * $this->findsAtATime + $this->findsAtATime > $this->totalFinds ? $this->totalFinds : $this->page * $this->findsAtATime + $this->findsAtATime;
			unset ( $subpartArray );
			$subpartArray ['###SINGLEPAGE###'] = implode ( '', $tempArray );
			$this->finds .= $this->cObj->substituteMarkerArrayCached ( $template ['pagebrowser'], $markerArray, $subpartArray, array () );
		}
		return $content . $this->finds;
	}
	
	/**
	 * Make a find list with pagebrowser navigation 
	 *
	 * @params 	array 	typo3 tree
	 * @params 	string 	title of current level
	 * @params 	pointer parent object
	 * @params 	pointer dbLayer object
	 * @return	void
	 */
	
	function getTree($tree, $title = '') {
		
		$crazyRecursionLimiter = 999;
		
		while ( $crazyRecursionLimiter > 0 && list ( $key, $val ) = each ( $tree ) ) {
			
			$crazyRecursionLimiter --;
			
			switch ($val ['_SUB_LEVEL']) {
				case true :
					switch ($val ['invertedDepth']) {
						case 2 :
							$this->topLevelTitle = $val ['row'] ['title'];
							break;
						case 1 :
							$this->midLevelTitle = $val ['row'] ['title'];
							break;
					}
					$nextLevel = $this->getTree ( $val ['_SUB_LEVEL'], $val ['row'] ['title'] );
					break;
				
				default :
					
					if (! $val ['row']) {
						
						$this->tempFindsAtATime2 --;
						
						# Do pagebrowser on every page, i.e in totalfinds
						if ($this->tempFindsAtATime2 === 0 || $this->pageTotalItem >= $this->currentPage * $this->findsAtATime) {
							
							$this->tempFindsAtATime2 = $this->findsAtATime;
							
							$this->pbLink_piVars ['page'] = $this->currentPage;
							$this->pbLink_conf ["additionalParams"] = t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $this->pbLink_piVars );
							$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $this->pbLink_conf );
							
							$markerArray ['###PAGENUMBER###'] = $this->currentPage + 1;
							
							$this->pageBrowserArray [$this->currentPage] ['page'] = $this->cObj->substituteMarkerArrayCached ( $this->template ['page'], $markerArray, array (), $wrappedSubpartContentArray );
							$this->pageBrowserArray [$this->currentPage] ['prev'] = $this->cObj->substituteMarkerArrayCached ( $this->template ['prev'], $markerArray, array (), $wrappedSubpartContentArray );
							$this->pageBrowserArray [$this->currentPage] ['next'] = $this->cObj->substituteMarkerArrayCached ( $this->template ['next'], $markerArray, array (), $wrappedSubpartContentArray );
							$this->currentPage ++;
						}
						
						# Do finds only in current page, i.e in current finds
						if ($this->tempFindsAtATime > 0 && $this->pageTotalItem >= $this->page * $this->findsAtATime) {
							
							$this->tempFindsAtATime --;
							
							# Do region title
							if ($this->prevMidLevelTitle != $this->midLevelTitle) {
								$this->prevMidLevelTitle = $this->midLevelTitle;
								$topArray ['###TITLE###'] = $this->midLevelTitle;
								$this->finds .= $this->cObj->substituteMarkerArrayCached ( $this->template ['top'], $topArray );
							}
							
							unset ( $finds );
							unset ( $result );
							
							# Get type
							$result = $this->pObj->getInfoAllPropertiesHotel ( $val ['objTypeUidLocal'] );
							
							$result ['type_icon'] = preg_replace ( '/alt="" title=""/', 'alt="' . $result ['title'] . ' ' . addslashes ( $val ['title'] ) . '" title="' . $result ['title'] . ' ' . addslashes ( $val ['title'] ) . '"', $result ['type_icon'] );
							$markerArray ['###TITLEICON###'] = $result ['type_icon'];
							
							if (is_array ( $result ['mark'] )) {
								foreach ( $result ['mark'] as $k => $v ) {
									$v ['icon'] = preg_replace ( '/alt="" title=""/', 'alt="' . $result ['title'] . ' ' . addslashes ( $val ['title'] ) . ': ' . $v ['title'] . '" title="' . $result ['title'] . ' ' . addslashes ( $val ['title'] ) . ': ' . $v ['title'] . '"', $v ['icon'] );
									$finds .= $v ['icon'];
								}
							}
							$markerArray ['###OBJECTICON###'] = $finds;
							
							$image = explode ( ',', $val ['pictures'] );
							
							$this->objImg ['file'] = $this->pObj->uploadPath () . $image [0];
							
							$markerArray ['###IMAGE###'] = $this->cObj->cObjGetSingle ( $this->conf ['findImg'], $this->objImg );
							$markerArray ['###TITLE###'] = $val ['title'];
							$markerArray ['###WHEREINFO###'] = $val ['location'];
							$markerArray ['###TEASER###'] = $val ['teaser'];
							
							if ($val ['specialoffer'] == 1) {
								$titleArray ['###TITLE###'] = $val ['accommodations'] [0] ['sp_title_1'];
								$markerArray ['###SPECIALOFFER###'] = $this->cObj->substituteMarkerArrayCached ( $this->template ['spteaser'], $titleArray, $subpartArray );
							} else {
								$markerArray ['###SPECIALOFFER###'] = '';
							}
							
							# Title Link
							$this->titleLinkpiVars ['page'] = $this->currentPage - 1;
							$this->titleLinkpiVars ['item'] = $this->pageTotalItem + 1;
							$this->titleLink ['additionalParams'] = '&tx_chtrip_pi1[uid]=' . $val ['uid'] . t3lib_div::implodeArrayForUrl ( 'tx_chtrip_pi1', $this->titleLinkpiVars );
							$this->titleLink ['ATagParams'] = $this->titleLinkATagParams . ' title="' . $result ['title'] . ' ' . addslashes ( $val ['title'] ) . '"';
							
							$wrappedSubpartContentArray ['###LINK###'] = $this->cObj->typolinkWrap ( $this->titleLink );
							
							$subpartArray ['###SINGLEROW###'] = $this->cObj->substituteMarkerArrayCached ( $this->template ['row'], $markerArray, array (), $wrappedSubpartContentArray );
							$this->finds .= $this->cObj->substituteMarkerArrayCached ( $this->template ['total'], array (), $subpartArray );
						
						}
						
						$this->pageTotalItem ++;
					}
					break;
			}
		}
	}
}

?>