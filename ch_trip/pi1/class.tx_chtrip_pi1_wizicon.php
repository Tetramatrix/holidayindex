<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Chi Hoang 
*  All rights reserved
*
***************************************************************/
/**
 * Class that adds the wizard icon.
 *
 * @author	Chi Hoang 
 */



class tx_chtrip_pi1_wizicon {
	function proc($wizardItems)	{
		global $LANG;

		$LL = $this->includeLocalLang();

		$wizardItems['plugins_tx_chtrip_pi1'] = array(
			'icon'=>t3lib_extMgm::extRelPath('ch_trip').'pi1/ce_wiz.gif',
			'title'=>$LANG->getLLL('pi1_title',$LL),
			'description'=>$LANG->getLLL('pi1_plus_wiz_description',$LL),
			'params'=>'&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=ch_trip_pi1'
		);

		return $wizardItems;
	}
	function includeLocalLang()	{
		include(t3lib_extMgm::extPath('ch_trip').'locallang.php');
		return $LOCAL_LANG;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/pi1/class.tx_chtrip_pi1_wizicon.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/pi1/class.tx_chtrip_pi1_wizicon.php']);
}

?>