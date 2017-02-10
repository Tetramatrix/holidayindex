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
 
require_once(t3lib_extMgm::extPath('ch_trip').'lib/class.tx_chtrip_static2.php');

class tx_chtrip_sp extends tx_chtrip_static2 {

    function teaser() {
    
		$template['spteaser'] = $this->cObj->getSubpart($this->pObj->templateCode,'###SPTEASER###');
		$template['so_item'] = $this->cObj->getSubpart($template['spteaser'],'###ITEM###');
      
        # Do query array		
        $this->doObjArray();      
        
        $this->piVars = array_merge($this->piVars, array ( 'region' => $this->findAll,
                                                           'specialOffer' => 'on',
                                                           'allTypes' => 'on',
                                                         )
                                    );        
        # Do search        
        $finds = $this->find($this->piVars);
        if (sizeOf($finds)>0) {
            $finds = $this->bubbleSort($finds,1);
            $finds = $this->indexFinds($finds);
        }            
		$skip = 0;
		foreach ($finds as $k => $v) {        
        
			if ($skip<$this->lConf['special_offer_at_a_time']) {
				$subpartArray['###TITLE###'] = $v['title'];
				
                $this->region = array();
	            $this->regionTitle = array();
    
				# Get region				
				$this->getRegion($v['location_f04338f846']);
                
				$subpartArray['###REGION###'] = $this->regionTitle[1];				
				
				# Get type
				$result = $this->getObjType($v['uid'],$this->lConf['sysfolder']); 
				$subpartArray['###TYPE###'] = $result['title'];						
				
				# Get accommodations
				$accommodations = $this->getAllAccommodation($v['uid']);               
                
				$p = array (&$this->priceInfo,&$this->altPriceInfo);				
				$s = intval($this->piVars['s']);				
				$pP = $p[$s];
				
                # Get special offer (price)
                unset($specialOffer);
				$specialOffer['lowestprice']=9999;
				if (is_array($accommodations)) {		
					foreach ($accommodations as $kA => $vA) {
						# Lookup categories
						$categories = $this->getCategory($vA['uid']);
						foreach ($categories as $kC => $vC) {
							foreach($pP as $a => $b) {
								if ($vC[$a] < $specialOffer['lowestprice'] && $vC[$a] != '') {
									$specialOffer['lowestprice'] = $vC[$a];
									$specialOffer['uid'] = $vC['uid'];
									$specialOffer['id'] = $vA['uid'];
								}
							}							
						}
					}
					if ($specialOffer['uid']) {
						$res = $this->getSubInfo($specialOffer['uid']);
                        if (is_array($res)) {
                            foreach ($res as $kI => $vI) {
                                $subpartArray['###PRICEINFO###'] .= $vI['title'].'<br>';
                            }
                        } else {
                            $subpartArray['###PRICEINFO###'] = '';
                        }
					}
				}
                
				$subpartArray['###PRICE###'] = $specialOffer['lowestprice'];
                $subpartArray['###DISCOUNT###'] = $accommodations[0]['sp_title_1'];                
                
				# Make link
				$temp_conf = $this->conf['specialOfferLink.'];
				$temp_conf['parameter'] = $this->lConf['PIDspecialOfferDisplay'];
				$temp_conf['additionalParams'].= '&tx_chtrip_pi1[m]=general&tx_chtrip_pi1[uid]='.$v['uid'];
				$temp_conf['useCacheHash'] = $this->allowCaching;                            
				$temp_conf['no_cache'] = !$this->allowCaching;
        
                $title = addslashes($result['title']).' '.trim(addslashes($v['title']));
                $temp_conf['ATagParams'] .= ' title="'.$title.'"';
        
                
				$wrappedSubpartContentArray['###LINK###'] = $this->cObj->typolinkWrap($temp_conf);									
				$content_item .= $this->cObj->substituteMarkerArrayCached($template['so_item'],array(), $subpartArray, $wrappedSubpartContentArray);				
			}
			unset($subpartArray);
			$skip++;
		}
		$subpartArray['###LOCATION###'] = $content_item;
		$content = $this->cObj->substituteMarkerArrayCached($template['spteaser'],array(),$subpartArray);		
		return $content;
    }


    function listNo() {
        
        # Do query array		
        $this->doObjArray();
        
        $this->piVars = array_merge($this->piVars, array ( 'region' => $this->findAll,
                                                           'specialOffer' => 'on',
                                                           'allTypes' => 'on',
                                                         )
                                    );
        
        $finds = $this->find($this->piVars,false);
        if (sizeOf($finds)>0) {
            $finds = $this->bubbleSort($finds,1);
            $finds = $this->indexFinds($finds);
            $finds = $this->filterFinds($this->piVars,$finds);
        }
        if (sizeOf($finds)>0) {        
            $this->totalFinds = sizeOf($finds);

            # Get accommodations        
            foreach ($finds as $k => $v) {
                $finds[$k]['accommodations'] = $this->getAllAccommodation($v['uid']);
            }
            
            # Merge finds with regions
            $tree = $this->mergeFindsRegions($finds);
            
            # Do total template
            $template['total'] = $this->cObj->getSubpart($this->pObj->templateCode,'###FINDSSPECIALOFFER###');
            $content = $this->cObj->substituteMarkerArrayCached($template['total'],$markerArray);       
        
            $this->initSub();
            
            # Do finds
            $this->getTree($tree,'');
            
            if ($this->finds) { 
                if(sizeof($this->pageBrowserArray)>$this->maxPages && $this->page>0) {
                    $tempArray[] = $this->pageBrowserArray[$this->page-1]['prev'];
                }
                foreach ($this->pageBrowserArray as $key => $value) {
                    if ($key > $this->page && $key < $this->page+$this->maxPages || 
                        $this->page+$this->maxPages>sizeof($this->pageBrowserArray) && 
                        $key >= sizeof($this->pageBrowserArray)-$this->maxPages &&
                        $key != $this->page) {
                        $tempArray[] = $value['page'];
                    } elseif ($key == $this->page) {
                        $tempArray[] = preg_replace('/'.$this->conf['pageBrowserLink.']['ATagParams'].'/',$this->conf['pageBrowserLinkAct.']['ATagParams'],$value['page']);
                    }
                }		
                if(sizeof($this->pageBrowserArray)>$this->page+$this->maxPages) {
                    $tempArray[] = $this->pageBrowserArray[$this->page+1]['next'];
                }			
                $template['pagebrowser'] = $this->cObj->getSubpart($this->pObj->templateCode,'###PAGEBROWSER###');
                unset($markerArray);			
                $markerArray['###PAGECURSOR###'] = $this->page*$this->findsAtATime+1;			
                $markerArray['###FINDS###'] = $this->totalFinds;
                $markerArray['###PAGEEND###'] = $this->page*$this->findsAtATime+$this->findsAtATime > $this->totalFinds ? $this->totalFinds : $this->page*$this->findsAtATime+$this->findsAtATime;			
                unset($subpartArray);
                $subpartArray['###SINGLEPAGE###'] = implode('',$tempArray);			
                $this->finds .= $this->cObj->substituteMarkerArrayCached($template['pagebrowser'],$markerArray, $subpartArray);
            }
            
            $content = $content.$this->finds;
        }    
		return $content;
    }
    
    
}

?>