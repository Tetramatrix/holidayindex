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
 
class tx_chtrip_popup  {

    function init(&$pObj,&$cObj,$confArray=array(),$lConf=array(),$conf=array(),$piVars=array(),$findAll=9999,$allowCaching=1) {    
        $this->pObj = $pObj;
        $this->cObj = $cObj;
        $this->lConf = $lConf;
        $this->confArray = $confArray;
        $this->piVars = $piVars;
        $this->findAll = $findAll;
        $this->allowCaching = $allowCaching;
        $this->conf = $conf;        
    }       
    
    function popUp()  {
    
        $template['popup'] = $this->cObj->getSubpart($this->pObj->templateCode,'###POPUP###');	
   
		# Get object
		$row = $this->getObject($this->piVars['type']);

		if ($this->piVars['special']=='map') {
			$image = explode(',',$row['wheremap']);
		} else {
			$image = explode(',',$row['pictures']);		
			$captions = explode('|',$row['captions']);
		}
		
		$previewImg = $this->conf['popUpImg.'];
		$previewImg['file'] = $this->uploadPath().$image[intval($this->piVars['pic']-1)];
		
		$markerArray['###IMAGE###'] = $this->cObj->cObjGetSingle($this->conf['previewImg'], $previewImg);
		$markerArray['###CAPTION###'] = $captions[intval($this->piVars['pic']-1)];
		
		$this->content .= $this->cObj->substituteMarkerArrayCached($template['popup'],$markerArray);		
		return $this->content;
    
    }


}


?>