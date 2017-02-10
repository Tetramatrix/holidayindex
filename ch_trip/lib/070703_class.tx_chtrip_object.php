<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2008 Chi Hoang
*  All rights reserved
*
***************************************************************/
/**
 * Plugin 'TRIP-Travel-Information-Presenter' for the 'ch_trip' extension.
 *
 * @author	Chi Hoang
 */
 
require_once(t3lib_extMgm::extPath('ch_trip').'lib/class.tx_chtrip_static.php');

class tx_chtrip_object extends tx_chtrip_static {

    var $pObj;
    var $cObj;
    var $lConf;
    var $confArray;
    var $piVars;
    var $findAll;
    var $allowCaching;
    var $conf;    
    
    var $content = '';
    var $acc = array();    
    
    function init(&$pObj,&$cObj,$confArray,$lConf,$conf,$piVars,$findAll,$allowCaching) {
       
        $this->pObj = $pObj;
        $this->cObj = $cObj;
        $this->lConf = $lConf;
        $this->confArray = $confArray;
        $this->piVars = $piVars;
        $this->findAll = $findAll;
        $this->allowCaching = $allowCaching;
        $this->conf = $conf;          
    
    	# Do query array
		$this->doObjArray();
		
		# Do pagescroller
		$this->pageScroller();
		
		# Get all accommodations
		$this->acc = $this->getAllAccommodation(intval($this->piVars['uid']));

		# Make menu
		$this->content = $this->makeMenu();        
    }

    function general() {    
    
		$template['generaltab'] = $this->cObj->getSubpart($this->pObj->templateCode,'###GENERALTAB###');
		$template['listofaccommodationitem'] = $this->cObj->getSubpart($template['generaltab'],'###LISTOFACCOMMODATIONITEM###');
		$template['relation'] = $this->cObj->getSubpart($template['generaltab'],'###RELATION###');
		$template['relationlist'] = $this->cObj->getSubpart($template['relation'],'###RELATIONLIST###');
		$template['relationitem'] = $this->cObj->getSubpart($template['relationlist'],'###RELATIONITEM###');
		$template['feature'] = $this->cObj->getSubpart($template['generaltab'],'###FEATURE###');
		$template['featureitem'] = $this->cObj->getSubpart($template['feature'],'###FEATUREITEM###');
		
		$template['singleimage'] = $this->cObj->getSubpart($this->pObj->templateCode,'###SINGLEIMAGE###');
		$template['imageitem'] = $this->cObj->getSubpart($template['singleimage'],'###IMAGEITEM###');			
		
		$temp_piVars=$this->piVars;
		unset($temp_piVars['id']);	
		unset($temp_piVars['type']);	
		unset($temp_piVars['scroll']);	
		unset($temp_piVars['s']);
		$temp_piVars['mode']='accommodation';

		$link_conf = $this->conf['listLink.'];
		$link_conf['parameter'] = $GLOBALS['TSFE']->id;	
		$link_conf['useCacheHash'] = $this->allowCaching;                            
		$link_conf['no_cache'] = !$this->allowCaching;
        $linkATagParams = $link_conf['ATagParams'];        

        $features = explode(',',$this->lConf['featureSelection']);

		# Get type icon
		$type = $this->getObjType(intval($this->piVars['uid']));
        
		# Get location
		$loc = $this->getLocation();
        
        for ($i=0;$i<sizeOf($this->acc);$i++) {
			$find = $this->getObjList($this->acc[$i]['uid'],$features[2]);
			if ($find) {
				$markerArray['###TITLE###'] = $this->acc[$i]['title'].' ('.trim(preg_replace('/[A-Za-z ]/','',$find)).' '.trim(preg_replace('/[0-9-\s+]/','',$find)).')';
			} else {
				$markerArray['###TITLE###'] = $this->acc[$i]['title'];
			}			
			$link_conf['additionalParams'] = t3lib_div::implodeArrayForUrl('tx_chtrip_pi1',$temp_piVars).'&tx_chtrip_pi1[id]='.$this->acc[$i]['uid'];
			$link_conf['ATagParams'] = $linkATagParams.' title="'.addslashes($type['title']).' '.addslashes($loc['title']).': '.addslashes($this->acc[$i]['title']).'"';
            
            $wrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($link_conf);
			$listofaccomodation .= $this->cObj->substituteMarkerArrayCached($template['listofaccommodationitem'], $markerArray, array(), $wrappedSubpartContentArray);
		}
		
		$subpartArray['###LISTOFACCOMMODATION###'] = $listofaccomodation;
		
		# Get image
		$image = explode(',',$loc['pictures']);
		$captions = explode('|',$loc['captions']);
		$markerArray['###IMAGE_1###'] = '';
		$markerArray['###IMAGE_2###'] = '';
		
		unset($galleryWrappedSubpartContentArray);
		
		if ($image[0]) {
	        $title = addslashes($type['title']).' '.addslashes($loc['title']).($captions[0]=='' ? '' : ': '.trim(addslashes($captions[0])));
		
			$previewImg = $this->conf['popUpImg.'];
			$previewImg['file'] = $this->uploadPath().$image[0];
			$popUpImg = $this->cObj->cObjGetSingle($this->conf['popUpImg'], $previewImg);
	
			$previewImg = $this->conf['previewImg.'];
			$previewImg['file'] = $this->uploadPath().$image[0];
	        $previewImg['altText'] = $title;
	        $previewImg['titleText'] = $title;
			
			$galleryArray['###IMAGE###'] = $this->cObj->cObjGetSingle($this->conf['previewImg'], $previewImg);
			$galleryArray['###CAPTION###'] = $captions[0];
	        
	        $link_conf = $this->conf['popUpLink.'];
	        $link_conf['useCacheHash'] = $this->allowCaching;
	        $link_conf['no_cache'] = !$this->allowCaching;
			$link_conf['ATagParams'] .= ' title="'.$title.'"';
	        
			$link_conf['parameter'] = $this->lConf['PIDpopUpDisplay'].$this->resizePopUpParams($popUpImg);
			$link_conf['additionalParams'].= '&tx_chtrip[m]=popup&tx_chtrip_pi1[uid]='.intval($this->piVars['uid']).'&tx_chtrip_pi1[id]='.intval($this->piVars['id']).'&tx_chtrip_pi1[pic]=1&tx_chtrip_pi1[t]=location';
			$galleryWrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($link_conf);
			
			$markerArray['###IMAGE_1###'] = $this->cObj->substituteMarkerArrayCached($template['imageitem'],$galleryArray, array(), $galleryWrappedSubpartContentArray);
		}
		
		if ($image[1]) {		
	        $title = addslashes($type['title']).' '.addslashes($loc['title']).($captions[1]=='' ? '' : ': '.trim(addslashes($captions[1])));
		
			$previewImg = $this->conf['popUpImg.'];
			$previewImg['file'] = $this->uploadPath().$image[1];
			$popUpImg = $this->cObj->cObjGetSingle($this->conf['popUpImg'], $previewImg);
	
			$previewImg = $this->conf['previewImg.'];
			$previewImg['file'] = $this->uploadPath().$image[1];
	        $previewImg['altText'] = $title;
	        $previewImg['titleText'] = $title;
			
			$galleryArray['###IMAGE###'] = $this->cObj->cObjGetSingle($this->conf['previewImg'], $previewImg);
			$galleryArray['###CAPTION###'] = $captions[1];
	        
	        $link_conf = $this->conf['popUpLink.'];
	        $link_conf['useCacheHash'] = $this->allowCaching;
	        $link_conf['no_cache'] = !$this->allowCaching;
			$link_conf['ATagParams'] .= ' title="'.$title.'"';
	        
			$link_conf['parameter'] = $this->lConf['PIDpopUpDisplay'].$this->resizePopUpParams($popUpImg);
			$link_conf['additionalParams'].= '&tx_chtrip[m]=popup&tx_chtrip_pi1[uid]='.intval($this->piVars['uid']).'&tx_chtrip_pi1[id]='.intval($this->piVars['id']).'&tx_chtrip_pi1[pic]=2&tx_chtrip_pi1[t]=location';
			$galleryWrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($link_conf);
			
			$markerArray['###IMAGE_2###'] = $this->cObj->substituteMarkerArrayCached($template['imageitem'],$galleryArray, array(), $galleryWrappedSubpartContentArray);	
		}
		
		# Do object description
		$markerArray['###DESCRIPTION###'] = $this->formatStrRTE($loc['description']);

		# Do Goberlinea Weather Information Extension (via Typoscript)
		$markerArray['###WEATHERINFORMATION###'] = $this->cObj->cObjGet($this->conf['weatherInfo.'],'weatherInfo.');
		
		# Do relation
		$finds = $this->getRelation(intval($this->piVars['uid']));
		unset($relation_item);
		
   		if ($finds) {
            
            $temp_piVars['mode']='general';	

			$link_conf = $this->conf['relLink.'];
			$link_conf['parameter'] = $GLOBALS['TSFE']->id;	
			$link_conf['useCacheHash'] = $this->allowCaching;                            
			$link_conf['no_cache'] = !$this->allowCaching;
			
			$related = explode(',',$this->lConf['PIDrelatedObjectDisplay']);
            
			foreach ($finds as $k => $v) {
				
				foreach ($related as $r => $s) {
					
					$mConf = t3lib_div::makeInstance('tx_chtrip_base'); 
	            	$mConf->init($this,$this);            
	            	$mConf->lConf = $this->getExtConf($s);            
	    	    	$mConf->doObjArray(); 
            
	                $link_conf['parameter'] = $GLOBALS['TSFE']->id;	
	                
	                # Get type
	                $result = $this->getObjType($v['uid']);                
	                	                
                	$haystack[]=$result['parent_uid'];
                	if (is_array($result['general'])) {
	                	foreach ($result['general'] as $l => $m) {
	                		$haystack[]=$m['parent_uid'];                	
	                	}                
                	}
                	                	
                	$needle = array_keys($mConf->parent_uids);
                	$result=array_intersect($needle,$haystack);                
	                if (count($result)) {
	                	
	                	$temp_piVars = $this->piVars;
	                	unset($temp_piVars['id']);	
	                	unset($temp_piVars['type']);	
	                	unset($temp_piVars['scroll']);
	                	unset($temp_piVars['uid']);
	                	unset($temp_piVars['specialOffer']);
	                    $link_conf['parameter'] = $s;
	                    
	                    $params = array(    'region' => $this->findAll,
	                                        'allTypes' => 'true',
	                                    );
	        
	                    $m = $mConf->find($params,false);
	  
	                    if (sizeOf($m)>0) {
	                        $m = $mConf->bubbleSort($m,1);
	                        $m = $mConf->indexFinds($m);
	                        $m = $mConf->filterFinds($params,$m);
	                    }
	                    if (sizeOf($m)>0) {  
	                        $mConf->totalFinds = sizeOf($m);
	                        
	                        # Merge finds with regions
	                        $tree = $mConf->mergeFindsRegions($m);
	                        
	                        # Lookup item position
	                        unset($mConf->treeC);
	                        $mConf->findUid($tree,$v['uid']);                  
	           
	                        $temp_piVars['total']=$mConf->totalFinds;
	                        $temp_piVars['item']=$mConf->treeItem;
	                        $temp_piVars['page']=$temp_piVars['item']%$mConf->lConf['results_at_a_time'] == 0 ? floor($temp_piVars['item']/$mConf->lConf['results_at_a_time'])-1 : floor($temp_piVars['item']/$mConf->lConf['results_at_a_time']);
	                    }
	                }
                }                
                
				$markerArray['###TITLE###'] = $v['title'];
				$link_conf['additionalParams'] = t3lib_div::implodeArrayForUrl('tx_chtrip_pi1',$temp_piVars).'&tx_chtrip_pi1[uid]='.$v['uid'];
                $link_conf['ATagParams'] .= ' title="'.addslashes($type['title']).' '.addslashes($v['title']).'"';
                
                $wrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($link_conf);			
				$relation_item .= $this->cObj->substituteMarkerArrayCached($template['relationitem'], $markerArray, array(), $wrappedSubpartContentArray);
			}
		}
		
		# Get type and feature
		$subpartArray['###FEATURE###']='';
		$finds = $this->getObjType(intval($this->piVars['uid']));

		if (is_array($finds['finds'])) {
			foreach ($finds['finds'] as $k => $v) {
				$markerArray['###TITLE###'] = $v['title'];
                $v['icon'] = preg_replace('/alt="" title=""/','alt="'.addslashes($type['title']).' '.addslashes($loc['title']).': '.addslashes($v['title']).'" title="'.addslashes($type['title']).' '.addslashes($loc['title']).': '.addslashes($v['title']).'"',$v['icon']);
				$markerArray['###ICON###'] = $v['icon'];
				$icon_item .= $this->cObj->substituteMarkerArrayCached($template['featureitem'], $markerArray);
			}
		}
		if (is_array($finds['general'])) {
			foreach ($finds['general'] as $k => $v) {
				$markerArray['###TITLE###'] = $v['title'];
                $v['icon'] = preg_replace('/alt="" title=""/','alt="'.addslashes($type['title']).' '.addslashes($loc['title']).': '.addslashes($v['title']).'" title="'.addslashes($type['title']).' '.addslashes($loc['title']).': '.addslashes($v['title']).'"',$v['icon']);
				$markerArray['###ICON###'] = $v['icon'];
				$icon_item .= $this->cObj->substituteMarkerArrayCached($template['featureitem'], $markerArray);
			}
		}

		if ($relation_item) {
			$relationArray['###RELATIONLIST###'] = $relation_item;
			$subpartArray['###RELATION###'] = $this->cObj->substituteMarkerArrayCached($template['relation'], array(), $relationArray);
		} else {
			$subpartArray['###RELATION###'] = '';
		}

		if (is_string($icon_item)) {
			$iconSubpartArray['###FEATUREITEM###'] = $icon_item;
			$subpartArray['###FEATURE###'] = $this->cObj->substituteMarkerArrayCached($template['feature'],array(), $iconSubpartArray);
		}
		
		$this->content .= $this->cObj->substituteMarkerArrayCached($template['generaltab'],$markerArray, $subpartArray, $wrappedSubpartContentArray);		
		return $this->content;        
    }
    
    
    function accommodation()  {
		
		$template['accommodationtab'] = $this->cObj->getSubpart($this->pObj->templateCode,'###ACCOMMODATIONTAB###');
		$template['kitchen'] = $this->cObj->getSubpart($this->pObj->templateCode,'###KITCHEN###');
		$template['kitchenlist'] = $this->cObj->getSubpart($template['kitchen'],'###KITCHENLIST###');
		
        $template['equipment'] = $this->cObj->getSubpart($this->pObj->templateCode,'###EQUIPMENT###');
		$template['equipmentlist'] = $this->cObj->getSubpart($template['equipment'],'###EQUIPMENTLIST###');

		$template['booking'] = $this->cObj->getSubpart($this->pObj->templateCode,'###BOOKING###');				
		$template['season'] = $this->cObj->getSubpart($template['booking'],'###SEASON###');
		$template['seasonitem'] = $this->cObj->getSubpart($template['season'],'###SEASONITEM###');
		
		$template['wheninfo'] = $this->cObj->getSubpart($template['booking'],'###WHENINFO##');
		$template['whenrow1item'] = $this->cObj->getSubpart($template['wheninfo'],'###WHENROW1ITEM###');		
		$template['whenrow2item'] = $this->cObj->getSubpart($template['wheninfo'],'###WHENROW2ITEM###');
		
		$template['pricecategory'] = $this->cObj->getSubpart($this->pObj->templateCode,'###PRICECATEGORY###');
		$template['titleitem'] = $this->cObj->getSubpart($template['pricecategory'],'###TITLEITEM###');
		$template['priceitem'] = $this->cObj->getSubpart($template['pricecategory'],'###PRICEITEM###');
		
		$template['pricefeature'] = $this->cObj->getSubpart($this->pObj->templateCode,'###PRICEFEATURE###');		
		$template['featureitem'] = $this->cObj->getSubpart($template['pricefeature'],'###FEATUREITEM###');
        
		$template['spacc'] =  $this->cObj->getSubpart($this->pObj->templateCode,'###SPACC###');

		$template['singleimage'] = $this->cObj->getSubpart($this->pObj->templateCode,'###SINGLEIMAGE###');
		$template['imageitem'] = $this->cObj->getSubpart($template['singleimage'],'###IMAGEITEM###');	
		
		# Get type icon
		$type = $this->getObjType(intval($this->piVars['uid']));
        
		# Get location
		$loc = $this->getLocation();

		# Get Date & Price
		$cat = $this->getCategory(intval($this->piVars['id']));
		
		# Do Season
		$y = date("Y",time());
		
		$z=array_keys($this->whenInfo);
		foreach ($z as $k => $v) {
			if (preg_match('/till/',$v)) {
				unset($z[$k]);
			}
		}
		$m=count($z)/8;
		$s=$y;
		$max=$s;
		$n=count($cat);
		for ($i=0;$i<$n;$i++) {
			$c=0;
			for ($j=0;$j<$m;$j++) {			
				$val=date("Y",$cat[$i][$z[$c]]);
				if ($val>$max) {
					$max=$val;				
				}
				$c++;			
			}
		}
				
		$showSeason[$max]= $max>=$s ? true : false;
		//$y++;
		
		$z=array_keys($this->altWhenInfo);
		foreach ($z as $k => $v) {
			if (preg_match('/till/',$v)) {
				unset($z[$k]);
			}
		}
		$m=count($z)/8;
		$s=$y;
		$max=$s;
		$n=count($cat);
		for ($i=0;$i<$n;$i++) {	
			$c=0;
			for ($j=0;$j<$m;$j++) {
				// todo bubblesort
				$val=date("Y",$cat[$i][$z[$c]]);
				if ($val>$max) {
					$max=$val;				
				}
				$c++;			
			}
		}
		
		$showSeason[$max]= $max>=$s ? true : false;
		
		# Do Time & Price Table
		$y = date("Y",time());
		
		if ($showSeason[0] == $y) {
			$w = array (&$this->whenInfo,&$this->altWhenInfo);				
		} else {
			$w = array (&$this->altWhenInfo,&$this->whenInfo);
		}

		$p = array (&$this->priceInfo,&$this->altPriceInfo);
		$h = array (&$this->halfboard,&$this->altHalfboard);
		
		$y = date("Y",time());
		
		if ($showSeason[$y]) {
			$s = intval($this->piVars['s']);
		} else {
			$s = 1;
			$showSeason[$y] = true;
		}		
		
		$wP = $w[$s];
		$pP = $p[$s];
		$hP = $h[$s];
		
		if (is_array($cat)) {
			
			# Do Wheninfo table						
			$z = array_keys($wP);
					
			foreach ($cat as $key => $value) {				
	
				$d=0;
				$t=0;
				
				for ($row=0;$row<4;$row++) {					
					$d=0;					
					for ($item=0;$item<8;$item++) {
								
						if (empty($value[$z[$t]])) {
							$d++;
							if (!is_scalar($when['r'.$row]['i'.$item][0])) {								
								$when['r'.$row]['i'.$item][0] = '';
							} elseif ($when['r'.$row]['i'.$item][0] != '') {
								$d--;
							}
						} else {
							$when['r'.$row]['i'.$item][0] = date($this->conf['dateFormat'],$value[$z[$t]]);								
						}					
						
						$t++;
						
						if (empty($value[$z[$t]])) {
							$d++;
							if (!is_scalar($when['r'.$row]['i'.$item][0])) {								
								$when['r'.$row]['i'.$item][0] = '';
							} elseif ($when['r'.$row]['i'.$item][0] != '') {
								$d--;
							}
						} else {
							$when['r'.$row]['i'.$item][1] = date($this->conf['dateFormat'],$value[$z[$t]]);								
						}
						$t++;						
					}	
					
					if($d==16) {
						$when['r'.$row]['showRow']='0';
					} else {
						$when['r'.$row]['showRow']='1';
						$when['r'.$row]['showColumn']=8-($d/2);
					}
				}
			}
			
			
			$column=0;
			$cHash = explode(',',$this->pObj->pi_getLL('SeasonTitle'));			
			$row = 'whenrow1item';
			$maxColumn=0;
			foreach ($when as $kP => $vP) {
				if ($vP['showColumn']) {
					$showColumn=$vP['showColumn'];
					unset($vP['showColumn']);		
					if ($showColumn>$maxColumn) {
						$thisColumn=$showColumn-$maxColumn;
						$maxColumn=$showColumn;					
					}
				}				
				if ($vP['showRow']=='1') {
					unset($vP['showRow']);		
					foreach ($vP as $kS => $vS) {
						if ($thisColumn>0) {
							$season['###TITLE###']=$cHash[$column++];
							$season_item .= $this->cObj->substituteMarkerArrayCached($template['seasonitem'],$season);
							$thisColumn--;					
						}					
						$whenArray['###FROM###'] = empty($vS[0]) ? '' : $vS[0];
						$whenArray['###TILL###'] = empty($vS[1]) ? '' : $vS[1];
						$when_item .= $this->cObj->substituteMarkerArrayCached($template[$row],$whenArray);
					}					
				}
				$row='whenrow2item';
			}

			unset($price_category1);
			
			# Do full price table
			foreach ($cat as $key => $value) {
				unset($fullPrice);
				unset($price_item);
				
				foreach ($pP as $k => $v) {
					if (!empty($value[$k])) {
						$priceArray['###PRICE###'] = $value[$k];
						$price_item .= $this->cObj->substituteMarkerArrayCached($template['priceitem'],$priceArray);
						$fullPrice=true;
						$showFeature=true;
					} else {
						$priceArray['###PRICE###'] = '';
                        $hidden = $this->cObj->substituteMarkerArrayCached($template['priceitem'],$priceArray);
                        $hidden = preg_replace('/(<div)(.*?)(id=")(.*?)(")(>)/','$1$2$3preis-saison-hidden$5$6',$hidden);
                        $price_item .= $hidden;
                    }
				}
				
				if ($fullPrice) {
                
                	$link_conf = $this->conf['catLink.'];
			        $link_conf['parameter'] = $GLOBALS['TSFE']->id;
			        $link_conf['useCacheHash'] = $this->allowCaching;
			        $link_conf['no_cache'] = !$this->allowCaching;
					
			        $needle = array ('/[\-\-]/',
			        				'/\(/');
			        $replace = array ('–<span class="moz-break"></span>',
			        					'<span class="moz-break"></span>('
			        				);			        			
			        $title = preg_replace($needle,$replace,$value['title']);
                    $priceArray['###TITLE###'] = $title;
					$link_conf['additionalParams'].= '&tx_chtrip_pi1[m]=request&tx_chtrip_pi1[uid]='.intval($this->piVars['uid']).'&tx_chtrip_pi1[id]='.intval($this->piVars['id']).'&tx_chtrip_pi1[cat]='.$value['uid'];
					$link_conf['ATagParams'] .= ' title="'.addslashes($type['title']).' '.addslashes($loc['title']).': '.trim(addslashes($value['title'])).'"';
                    
                    $wrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($link_conf);
					$price_title = $this->cObj->substituteMarkerArrayCached($template['titleitem'],$priceArray, array(), $wrappedSubpartContentArray);
					
					$subpartArray['###TITLEINFO###'] = $price_title;
					$subpartArray['###PRICEINFO###'] = $price_item;
					$price_category1 .= $this->cObj->substituteMarkerArrayCached($template['pricecategory'],array(), $subpartArray);
				}				
			}
			
			# Do line
			if ($price_category1) {
				$fullPrice=true;
			} else {
				$fullPrice=false;
			}
			
			# Get price property of 1st category!
			if ($showFeature==true) {
				$priceFeature = $this->getSubInfo($cat[0]['uid']);
				unset($markerArray);
				if (is_array($priceFeature)) {
					foreach ($priceFeature as $k => $v) {
						$markerArray['###TITLE###'] = $v['title'];
						$price_feature .= $this->cObj->substituteMarkerArrayCached($template['featureitem'],$markerArray);
					}
				}
			}
			
			$subpartArray['###PRICEFEATUREROW###'] = $price_feature; 
			$price_category1 .= $this->cObj->substituteMarkerArrayCached($template['pricefeature'],array(), $subpartArray);

			unset($showFeature);
			unset($price_feature);
			unset($price_category2);
			
			# Do halfboard price table			
			foreach ($cat as $key => $value) {	
				
				unset($halfboard);
				unset($price_item);
				
				foreach ($hP as $k => $v) {
					if (!empty($value[$k])) {
						$priceArray['###PRICE###'] = $value[$k];					
						$price_item .= $this->cObj->substituteMarkerArrayCached($template['priceitem'],$priceArray);
						$halfboard=true;
						$showFeature=true;
					} else {
						$priceArray['###PRICE###'] = '';
                        $hidden = $this->cObj->substituteMarkerArrayCached($template['priceitem'],$priceArray);
                        $hidden = preg_replace('/(<div)(.*?)(id=")(.*?)(")(>)/','$1$2$3preis-saison-hidden$5$6',$hidden);
                        $price_item .=  $hidden;
                    }				
				}
				
				if ($halfboard) {
                
                    $link_conf = $this->conf['catLink.'];
			        $link_conf['parameter'] = $GLOBALS['TSFE']->id;
			        $link_conf['useCacheHash'] = $this->allowCaching;
			        $link_conf['no_cache'] = !$this->allowCaching;                    
				
			        $needle = array  ('/[\-\-]/',
			        				'/\(/');
			        $replace = array ('–<span class="moz-break"></span>',
			        				'<span class="moz-break"></span>('
			        				);			        			
			        $title = preg_replace($needle,$replace,$value['title']);
					$priceArray['###TITLE###'] = $title;
					$link_conf['additionalParams'].= '&tx_chtrip_pi1[m]=request&tx_chtrip_pi1[uid]='.intval($this->piVars['uid']).'&tx_chtrip_pi1[id]='.intval($this->piVars['id']).'&tx_chtrip_pi1[cat]='.$value['uid'];
					$link_conf['ATagParams'] .= ' title="'.addslashes($type['title']).' '.addslashes($loc['title']).': '.trim(addslashes($value['title'])).'"';
                    
                    $wrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($link_conf);
					$price_title = $this->cObj->substituteMarkerArrayCached($template['titleitem'],$priceArray, array(), $wrappedSubpartContentArray);			
					
					$subpartArray['###TITLEINFO###'] = $price_title;
					$subpartArray['###PRICEINFO###'] = $price_item;
					$price_category2 .= $this->cObj->substituteMarkerArrayCached($template['pricecategory'],array(), $subpartArray);			
				}
			}

			# Do line
			if ($price_category2) {
				$halfboard=true;
			} else {
				$halfboard=false;
			}
			
			if ($showFeature) {				
                $markerArray['###TITLE###'] =  $this->pObj->pi_getLL('halfboardinfo');
                $price_feature .= $this->cObj->substituteMarkerArrayCached($template['featureitem'],$markerArray);
			}
			$subpartArray['###PRICEFEATUREROW###'] = $price_feature; 
			$price_category2 .= $this->cObj->substituteMarkerArrayCached($template['pricefeature'],array(), $subpartArray);
		}
        
	
		# Do Booking
		$altTitle = addslashes($type['title']).' '.addslashes($loc['title']);
		 
		$markerArray['###SEASON1###']='';
		$markerArray['###SEASON2###']='';
		
		$wrappedSubpartContentArray['###LINKSEASON1###']='';
		$wrappedSubpartContentArray['###LINKSEASON2###']='';
		
		$temp_piVars=$this->piVars;
		$temp_piVars['mode']='accommodation';
		unset($temp_piVars['s']);
		
		$y = date("Y",time());
		
		if ($showSeason[$y]) {
			$markerArray['###SEASON1###'] =  $this->pObj->pi_getLL('SeasonTabTitle').' '.$y;
			$link_conf = intval($this->piVars['s'])===0 || !intval($this->piVars['s']) ? $this->conf['menuActLink.'] : $this->conf['menuLink.'];
			$link_conf['parameter'] = $GLOBALS['TSFE']->id;
			$link_conf['additionalParams'] .= t3lib_div::implodeArrayForUrl('tx_chtrip_pi1',$temp_piVars).'&tx_chtrip_pi1[s]=0';
	        $link_conf['useCacheHash'] = $this->allowCaching;                            
			$link_conf['no_cache'] = !$this->allowCaching;	
			$link_conf['ATagParams'] .= ' title="'.$altTitle.' '.$this->pObj->pi_getLL('SeasonAltTitle').' '.$y.'"';
			 
			$wrappedSubpartContentArray['###LINKSEASON1###'] = $this->cObj->typolinkWrap($link_conf);
		}

		$y++;
		
		if ($showSeason[$y]) {			
			$markerArray['###SEASON2###'] =  $this->pObj->pi_getLL('SeasonTabTitle').' '.$y;			
			$link_conf = intval($this->piVars['s'])===1 ? $this->conf['menuActLink.'] : $this->conf['menuLink.'];
			$link_conf['parameter'] = $GLOBALS['TSFE']->id;
			$link_conf['additionalParams'] .= t3lib_div::implodeArrayForUrl('tx_chtrip_pi1',$temp_piVars).'&tx_chtrip_pi1[s]=1';
	        $link_conf['useCacheHash'] = $this->allowCaching;                            
			$link_conf['no_cache'] = !$this->allowCaching;	
			$link_conf['ATagParams'] .= ' title="'.$altTitle.' '.$this->pObj->pi_getLL('SeasonAltTitle').' '.$y.'"';
			
			$wrappedSubpartContentArray['###LINKSEASON2###'] = $this->cObj->typolinkWrap($link_conf);			
		}
		$booking = '';
		
		if ($season_item && $when_item) {
			$subpartArray['###SEASON###'] = $season_item;
			$subpartArray['###WHENINFO###'] = $when_item;
			$markerArray['###PRICECATEGORYPART###'] = $price_category1.$line.$price_category2; 
			$booking = $this->cObj->substituteMarkerArrayCached($template['booking'],$markerArray,$subpartArray,$wrappedSubpartContentArray);
		}		
		
		# Do special offer 
		unset($markerArray);
		
		$acc = $this->getAccommodation();        
        for ($i=1;$i<4;$i++) {
            if ($acc['sp_title_'.$i] != '') {
                $markerArray['###TITLE###'] = $acc['sp_title_'.$i];  
                $markerArray['###DESCRIPTION###'] = $this->formatStr($acc['sp_description_'.$i]);               
                $spacc.= $this->cObj->substituteMarkerArrayCached($template['spacc'],$markerArray);          
            }       
        }
        
        unset($markerArray);
        
        $markerArray['###BOOKINGPART###'] = $booking;        
        $markerArray['###SPECIALOFFER###'] = $spacc;       
		$markerArray['###TYPOLOGIE###'] = $this->formatStr($acc['description']);
		$markerArray['###ARRIVALANDDEPARTURE###'] = $this->formatStr($acc['arrivalanddeparture']);
		$markerArray['###MISCELLANEOUS###'] = $this->formatStrRTE($acc['miscellaneous']);		

		$features = explode(',',$this->lConf['featureSelection']);
        
		# Get kitchenlist
		$finds = $this->getObjList(intval($this->piVars['id']),$features[1]);
		
		if ($finds) {
			$markerArray['###KITCHENLIST###']=$finds;	
			$kitchenList = $this->cObj->substituteMarkerArrayCached($template['kitchen'],$markerArray);
		}
		
		# Get equipmentlist
		$finds = $this->getObjList(intval($this->piVars['id']),$features[0]);
		
		if ($finds) {
			$markerArray['###EQUIPMENTLIST###']=$finds;	
			$equipmentList = $this->cObj->substituteMarkerArrayCached($template['equipment'],$markerArray);
		}		

		if ($fullPrice && $halfboard) {
			$line = '<hr>';
		} else {
			$line = '';
		}
		
		unset($subpartArray);	
		$subpartArray['###KITCHEN###'] = $kitchenList;
		$subpartArray['###EQUIPMENT###'] = $equipmentList;
		
		# Get image
		$image = explode(',',$acc['pictures']);
		$captions = explode('|',$acc['captions']);
		unset($galleryWrappedSubpartContentArray);
		$markerArray['###IMAGE_1###'] = '';
		$markerArray['###IMAGE_2###'] = '';
		
		if ($image[0]) {
	        $title = addslashes($type['title']).' '.addslashes($loc['title']).($captions[0]=='' ? '' : ': '.trim(addslashes($captions[0])));
		
			$previewImg = $this->conf['popUpImg.'];
			$previewImg['file'] = $this->uploadPath().$image[0];
			$popUpImg = $this->cObj->cObjGetSingle($this->conf['popUpImg'], $previewImg);
	
			$previewImg = $this->conf['previewImg.'];
			$previewImg['file'] = $this->uploadPath().$image[0];
	        $previewImg['altText'] = $title;
	        $previewImg['titleText'] = $title;
			
			$galleryArray['###IMAGE###'] = $this->cObj->cObjGetSingle($this->conf['previewImg'], $previewImg);
			$galleryArray['###CAPTION###'] = $captions[0];
	        
	        $link_conf = $this->conf['popUpLink.'];
	        $link_conf['useCacheHash'] = $this->allowCaching;
	        $link_conf['no_cache'] = !$this->allowCaching;
			$link_conf['ATagParams'] .= ' title="'.$title.'"';
	        
			$link_conf['parameter'] = $this->lConf['PIDpopUpDisplay'].$this->resizePopUpParams($popUpImg);
			$link_conf['additionalParams'].= '&tx_chtrip[m]=popup&tx_chtrip_pi1[uid]='.intval($this->piVars['uid']).'&tx_chtrip_pi1[id]='.intval($this->piVars['id']).'&tx_chtrip_pi1[pic]=1&tx_chtrip_pi1[t]=accommodation';
			$galleryWrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($link_conf);
			
			$markerArray['###IMAGE_1###'] = $this->cObj->substituteMarkerArrayCached($template['imageitem'],$galleryArray, array(), $galleryWrappedSubpartContentArray);			
		}

		if ($image[1]) {	
	        $title = addslashes($type['title']).' '.addslashes($loc['title']).($captions[1]=='' ? '' : ': '.trim(addslashes($captions[1])));
		
			$previewImg = $this->conf['popUpImg.'];
			$previewImg['file'] = $this->uploadPath().$image[1];
			$popUpImg = $this->cObj->cObjGetSingle($this->conf['popUpImg'], $previewImg);
	
			$previewImg = $this->conf['previewImg.'];
			$previewImg['file'] = $this->uploadPath().$image[1];
	        $previewImg['altText'] = $title;
	        $previewImg['titleText'] = $title;
			
			$galleryArray['###IMAGE###'] = $this->cObj->cObjGetSingle($this->conf['previewImg'], $previewImg);
			$galleryArray['###CAPTION###'] = $captions[1];
	        
	        $link_conf = $this->conf['popUpLink.'];
	        $link_conf['useCacheHash'] = $this->allowCaching;
	        $link_conf['no_cache'] = !$this->allowCaching;
			$link_conf['ATagParams'] .= ' title="'.$title.'"';
	        
			$link_conf['parameter'] = $this->lConf['PIDpopUpDisplay'].$this->resizePopUpParams($popUpImg);
			$link_conf['additionalParams'].= '&tx_chtrip[m]=popup&tx_chtrip_pi1[uid]='.intval($this->piVars['uid']).'&tx_chtrip_pi1[id]='.intval($this->piVars['id']).'&tx_chtrip_pi1[pic]=2&tx_chtrip_pi1[t]=accommodation';
			$galleryWrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($link_conf);
			
			$markerArray['###IMAGE_2###'] = $this->cObj->substituteMarkerArrayCached($template['imageitem'],$galleryArray, array(), $galleryWrappedSubpartContentArray);	
		}
		
		# Do image gallery link
		$markerArray['###IMAGEGALLERY###'] = '';
		$markerArray['###IMAGEGALLERYPREFIX###'] = '';
		$wrappedSubpartContentArray['###LINK###'] ='';
		
		if (count($image)>2) {
			$markerArray['###IMAGEGALLERY###'] = $this->pObj->pi_getLL('ImageGallery');
			$markerArray['###IMAGEGALLERYPREFIX###'] = $this->pObj->pi_getLL('ImageGalleryPrefix');
			$temp_piVars=$this->piVars;
			$temp_piVars['mode']='accommodation';
			$temp_piVars['type']='accommodation';
			
			$link_conf = $this->conf['moreLink.'];
			$link_conf['parameter'] = $GLOBALS['TSFE']->id;
			$link_conf['additionalParams'].= t3lib_div::implodeArrayForUrl('tx_chtrip_pi1',$temp_piVars);
			$link_conf['useCacheHash'] = $this->allowCaching;                            
			$link_conf['no_cache'] = !$this->allowCaching;
	        $link_conf['ATagParams'] .= ' title="'.addslashes($type['title']).' '.addslashes($loc['title']).'"';
	        
			$wrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($link_conf);
		}
					
		# Do template
		$this->content .= $this->cObj->substituteMarkerArrayCached($template['accommodationtab'],$markerArray, $subpartArray, $wrappedSubpartContentArray);	
		return $this->content;   
    
    }
    
    function image() {
    	
    	$template['imagetab'] = $this->cObj->getSubpart($this->pObj->templateCode,'###IMAGETAB###');
		$template['imageitem'] = $this->cObj->getSubpart($template['imagetab'],'###IMAGEITEM###');
		
        # Get type icon
		$type = $this->getObjType(intval($this->piVars['uid']));
        
        # Get location
		$loc = $this->getLocation();
        
		# Get object
		$obj = $this->getObject($this->piVars['type']);
		
		# Get image
		$image = explode(',',$obj['pictures']);
		$captions = explode('|',$obj['captions']);
		
		$switch=0;

		for ($i=0;$i<sizeOf($image);$i++) {
        
            $title = addslashes($type['title']).' '.addslashes($loc['title']).($captions[$i]=='' ? '' : ': '.trim(addslashes($captions[$i])));
		
			$previewImg = $this->conf['popUpImg.'];
			$previewImg['file'] = $this->uploadPath().$image[$i];
			$popUpImg = $this->cObj->cObjGetSingle($this->conf['popUpImg'], $previewImg);
	
			$previewImg = $this->conf['previewImg.'];
			$previewImg['file'] = $this->uploadPath().$image[$i];
            $previewImg['altText'] = $title;
            $previewImg['titleText'] = $title;
			
			$markerArray['###IMAGE###'] = $this->cObj->cObjGetSingle($this->conf['previewImg'], $previewImg);
			$markerArray['###CAPTION###'] = $captions[$i];
            
            $link_conf = $this->conf['popUpLink.'];
            $link_conf['useCacheHash'] = $this->allowCaching;
            $link_conf['no_cache'] = !$this->allowCaching;
			$link_conf['ATagParams'] .= ' title="'.$title.'"';
            
			$link_conf['parameter'] = $this->lConf['PIDpopUpDisplay'].$this->resizePopUpParams($popUpImg);
			
			if ($this->piVars['type']=='location') {	
				$link_conf['additionalParams'].= '&tx_chtrip[m]=popup&tx_chtrip_pi1[uid]='.intval($this->piVars['uid']).
												 '&tx_chtrip_pi1[pic]='.($i+1).
												 '&tx_chtrip_pi1[t]='.$this->piVars['type'];
			} else {
				$link_conf['additionalParams'].= '&tx_chtrip[m]=popup&tx_chtrip_pi1[uid]='.intval($this->piVars['uid']).'&tx_chtrip_pi1[id]='.intval($this->piVars['id']).
												 '&tx_chtrip_pi1[pic]='.($i+1).
												 '&tx_chtrip_pi1[t]='.$this->piVars['type'];			
			}
			$wrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($link_conf);
		
			$switch = $switch ^1;
 			if ($switch==1) {
				$content_item .= $this->cObj->substituteMarkerArrayCached($template['imageitem'],$markerArray, array(), $wrappedSubpartContentArray);
			} else {
				$content_item .= $this->cObj->substituteMarkerArrayCached($template['imageitem'],$markerArray, array(), $wrappedSubpartContentArray);
				$content_item .= '<div style="clear:both"></div>';
			}

		}		
		
		$subpartArray['###IMAGELIST###'] = $content_item;
		$this->content .= $this->cObj->substituteMarkerArrayCached($template['imagetab'],array(), $subpartArray);
		
		return $this->content;    
    }
    
    
    function map() {
        
    	$template['imagetab'] = $this->cObj->getSubpart($this->pObj->templateCode,'###IMAGETAB###');
		$template['imageitem'] = $this->cObj->getSubpart($template['imagetab'],'###IMAGEITEM###');
		
        # Get type icon
		$type = $this->getObjType(intval($this->piVars['uid']));
        
        # Get location
		$loc = $this->getLocation();
        
		# Get object
		$obj = $this->getObject($this->piVars['type']);
		
		# Get map
		$map = explode(',',$obj['wheremap']);

		for ($i=0;$i<sizeOf($map);$i++) {
		
            $title = addslashes($type['title']).' '.addslashes($loc['title']).($captions[$i]=='' ? '' : ': '.trim(addslashes($captions[$i])));
            
			$previewImg = $this->conf['mapImg.'];
			$previewImg['file'] = $this->uploadPath().$map[$i];
			$popUpImg = $this->cObj->cObjGetSingle($this->conf['mapImg'], $previewImg);
	
			$previewImg = $this->conf['mapImg.'];
			$previewImg['file'] = $this->uploadPath().$map[$i];
			$previewImg['altText'] = $title;
            $previewImg['titleText'] = $title;
            
			$markerArray['###IMAGE###'] = $this->cObj->cObjGetSingle($this->conf['mapImg'], $previewImg);
			$markerArray['###CAPTION###'] = '';
			
            $link_conf = $this->conf['popUpLink.'];
		    $link_conf['useCacheHash'] = $this->allowCaching;
		    $link_conf['no_cache'] = !$this->allowCaching;
        
            $link_conf['ATagParams'] .= ' title="'.$title.'"';
			$link_conf['parameter'] = $this->lConf['PIDpopUpDisplay'].$this->resizePopUpParams($popUpImg);
			
			if ($piVars['type']=='location') {	
				$link_conf['additionalParams'].= '&tx_chtrip[m]=popup&tx_chtrip_pi1[uid]='.intval($this->piVars['uid']).
												 '&tx_chtrip_pi1[pic]='.($i+1).
												 '&tx_chtrip_pi1[t]='.$this->piVars['type'].
												 '&tx_chtrip_pi1[special]=map';
			} else {
				$link_conf['additionalParams'].= '&tx_chtrip[m]=popup&tx_chtrip_pi1[uid]='.intval($this->piVars['uid']).'&tx_chtrip_pi1[id]='.intval($this->piVars['id']).
												 '&tx_chtrip_pi1[pic]='.($i+1).
												 '&tx_chtrip_pi1[t]='.$this->piVars['type'].
												 '&tx_chtrip_pi1[special]=map';			
			}
			$wrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($link_conf);
			
			$content_item .= $this->cObj->substituteMarkerArrayCached($template['imageitem'],$markerArray, array(), $wrappedSubpartContentArray);	
		}		
		
		$subpartArray['###IMAGELIST###'] = $content_item;
		$this->content .= $this->cObj->substituteMarkerArrayCached($template['imagetab'],array(), $subpartArray);
		
		return $this->content;   
    }
    
    
    function video()  {
    
		$template['videotab'] = $this->cObj->getSubpart($this->pObj->templateCode,'###VIDEOTAB###');
		
		# Get object
		$row = $this->getObject($this->piVars['type']);
		
		# Get video
		$video = explode(',',$row['video']);
        $vFile = str_replace('.'.$this->vType(),'',$video[0]);
        
        $markerArray['###OBJSWFFILE###'] = $this->vLoader().'?timestamp='.time().'&flv='.$this->uploadPath().$vFile.'&type='.$this->vType();
        $markerArray['###EMBEDSWFFILE###'] = $this->vLoader().'?timestamp='.time().'&flv='.$this->uploadPath().$vFile.'&type='.$this->vType();
        
		$this->content .= $this->cObj->substituteMarkerArrayCached($template['videotab'],array(), $markerArray);		
		return $this->content;     
    }   
}


?>