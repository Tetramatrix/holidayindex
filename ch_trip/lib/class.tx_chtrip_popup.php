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