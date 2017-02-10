<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2008 Chi Hoang
*  All rights reserved
*
***************************************************************/


unset($MCONF);
include ('conf.php');
include ($BACK_PATH.'init.php');
include ($BACK_PATH.'template.php');

define('PATH_txva', t3lib_extMgm::extPath('ch_trip'));
require_once(PATH_txva.'mod_main/class.tx_chtrip_navframe.php');


/**
 * Main script class for the tree navigation frame
 * 
 * @author	@author	Ren� Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_chtrip_mainnavframe extends tx_chtrip_navframe {

		// Constructor:
	function init()	{
		global $MCONF;

		list($this->mainModule) = explode('_', $MCONF['name']); 
        
		parent::init();
	}

}

// Include extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/mod_main/tx_chtrip_navframe.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/mod_main/tx_chtrip_navframe.php']);
}




// Make instance:

$SOBE = t3lib_div::makeInstance('tx_chtrip_mainnavframe');
$SOBE->init();
$SOBE->main();
$SOBE->printContent();


?>