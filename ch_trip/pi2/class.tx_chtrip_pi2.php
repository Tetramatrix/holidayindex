<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Chi Hoang (chibo@gmx.de)
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
/**
 * Plugin 'TRIP-Travel-Information-Presenter' for the 'ch_trip' extension.
 *
 * @author	Chi Hoang <chibo@gmx.de>
 */
require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('ch_trip').'lib/class.tx_chtrip_base.php');

class tx_chtrip_pi2 extends tslib_pibase {
	var $prefixId = 'tx_chtrip_pi2';		// Same as class name
	var $scriptRelPath = 'pi2/class.tx_chtrip_pi2.php';	// Path to this script relative to the extension dir.
	var $extKey = 'ch_trip';	// The extension key.
	var $pi_checkCHash = TRUE;
	var $allowCaching = True;   
    
    var $findAll = '9999';  

	var $sitemap;

	var $topLevelTitle;	
	var $midLevelTitle;
	var $prevMidLevelTitle;

	var $page = 0;
	var $maxPages = 3;
	var $pageTotalItem = 0;	
	
	var $findsAtATime = 6;	
	var $tempFindsAtATime = 0;
	var $tempFindsAtATime2 = 0;
	
	var $currentPage = 0;
    
    var $titleLink;
	var $titleLinkpiVars;
    
    var $totalFinds;

	function init(&$pObj) {	
		$this->maxPages = $pObj->lConf['maxPages'];		
		$this->findsAtATime = $pObj->lConf['results_at_a_time'];
		$this->tempFindsAtATime = $pObj->lConf['results_at_a_time'];
		$this->tempFindsAtATime2 = $pObj->lConf['results_at_a_time'];
		$this->page = 0;        
    }
    
	function sitemap($tree,$title='') {	

		$crazyRecursionLimiter = 999;

		while ($crazyRecursionLimiter>0 && list($key,$val) = each($tree)) {	
			
			$crazyRecursionLimiter--;
			
			switch ($val['_SUB_LEVEL']) {
				
				case true:				
					switch ($val['invertedDepth']) {
						case 2:
							$this->topLevelTitle = $val['row']['title'];
						break;
						case 1:
							$this->midLevelTitle = $val['row']['title'];
						break;
					}					
					$nextLevel = $this->sitemap($val['_SUB_LEVEL'],$val['row']['title']);				
				break;
				
				default:
				
					if (!$val['row']) {
                    
						$this->tempFindsAtATime2--;
						
						# Do pagebrowser on every page, i.e in totalfinds
						if ($this->tempFindsAtATime2===0 || $this->pageTotalItem >= $this->currentPage*$this->findsAtATime) {							
							$this->tempFindsAtATime2 = $this->findsAtATime;
							$this->currentPage++;							
						}
						
						# Do finds only in current page, i.e in current finds
						if ($this->tempFindsAtATime>0 && $this->pageTotalItem >= $this->page*$this->findsAtATime) {						
							$this->tempFindsAtATime--;
						}
    
                        if ($this->prevMidLevelTitle != $this->midLevelTitle) {
                            $this->prevMidLevelTitle = $this->midLevelTitle;	
                            $this->sitemap .= $this->cObj->stdWrap($this->midLevelTitle,$this->conf['sitemapTitle_stdWrap.']);                          
                        }
                        
                        # Title Link piVars
                        $this->titleLinkpiVars['page'] = $this->currentPage-1;
                        $this->titleLinkpiVars['item'] = $this->pageTotalItem+1;                        
                     
                        # Title Link                     
                        $this->titleLink = $this->conf['titleLink.'];
                        
                        $this->titleLinkpiVars['total'] = $this->totalFinds;
                        $this->titleLinkpiVars['mode'] = 'general';
                        $this->titleLinkpiVars['region'] = '9999';
                        $this->titleLinkpiVars['allTypes'] = 'on';
                        
                        $this->titleLink['parameter'] = $this->uid;
                        $this->titleLink['useCacheHash'] = $pObj->allowCaching;                            
                        $this->titleLink['no_cache'] = !$pObj->allowCaching;        
                        $this->titleLink['additionalParams'] = '&tx_chtrip_pi1[uid]='.$val['uid'].t3lib_div::implodeArrayForUrl('tx_chtrip_pi1',$this->titleLinkpiVars);
                        $this->titleLink['ATagParams'] .= ' title="'.addslashes($val['title']).'"';
                        
                        $this->sitemap .= $this->cObj->typolink($this->cObj->stdWrap($val['title'],$this->conf['sitemapLink_stdWrap.']),$this->titleLink).$val['teaser'].'<br><br>';
						$this->pageTotalItem++;	
					}
				break;
			}			
		}

	}
		
        
	function main($content,$conf)	{   
     
        $this->conf = $conf;        
        $this->uid = $this->cObj->data["pages"];
          
        $obj = t3lib_div::makeInstance('tx_chtrip_base');        
        $obj->init($this,$this);
        $obj->lConf = $obj->getExtConf($this->uid);       
        
        # Do query array
        $obj->doObjArray();
        
        $this->piVars = array( 'region' => $this->findAll,
                               'allTypes' => 'on',
                             );
                        
        # Do search
        $finds = $obj->find($this->piVars,false);
        if (sizeOf($finds)>0) {
            $finds = $obj->indexFinds($finds);
            $finds = $obj->filterFinds($this->piVars,$finds);
        }
        if (sizeOf($finds)>0) {        
            $this->totalFinds = sizeOf($finds);
           
            # Merge finds with regions
            $tree = $obj->mergeFindsRegions($finds);
        }        
        
        $this->init($this);
        $this->sitemap($tree);
        
		return $this->sitemap;
	}
}
?>