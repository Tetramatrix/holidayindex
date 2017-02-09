<?php

	// DO NOT REMOVE OR CHANGE THESE 3 LINES:
define('TYPO3_MOD_PATH', '../typo3conf/ext/ch_trip/mod_setup/');
$BACK_PATH='../../../../typo3/';

$MCONF["name"]="txchtripM1_setup";
	
$MCONF["access"]="user,group";
$MCONF["script"]="index.php";

$MCONF["uploadPath"]='uploads/tx_chtrip/xmlfiles/';

$MLANG["default"]["tabs_images"]["tab"] = "moduleicon.gif";
$MLANG["default"]["ll_ref"]="LLL:EXT:ch_trip/mod_setup/locallang_mod.php";

$MCONF['navFrameScriptParam']='&tree=setup';
?>