<?php

	// DO NOT REMOVE OR CHANGE THESE 3 LINES:
define('TYPO3_MOD_PATH', '../typo3conf/ext/ch_trip/mod_region/');
$BACK_PATH='../../../../typo3/';

$MCONF["name"]="txchtripM1_region";
	
$MCONF["access"]="user,group";
$MCONF["script"]="index.php";

$MLANG["default"]["tabs_images"]["tab"] = "moduleicon.gif";
$MLANG["default"]["ll_ref"]="LLL:EXT:ch_trip/mod_region/locallang_mod.php";

$MCONF['navFrameScriptParam']='&tree=region';
?>