<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

    // add processCmdmap_preProcess to delete nested accommodations
//$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:ch_trip/patch/class.tx_chtrip_tcemain.php:tx_chtrip_tcemain';

  // patch to save automatically all forms
//$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['t3lib/class.t3lib_tceforms.php'] = t3lib_extMgm::extPath('ch_trip').'patch/class.ux_t3lib_TCEforms.php';

  // patch for doc template ??? (ripped from DAM Extensions)
if(t3lib_div::int_from_ver(TYPO3_version) <= t3lib_div::int_from_ver('3.8.1')) {
	$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/template.php']=t3lib_extMgm::extPath('ch_trip').'compat/class.ux_template.php';
}

	// class for rendering form fields by a user function or class method.
//include_once(t3lib_extMgm::extPath($_EXTKEY).'lib/class.tx_chtrip_treeviewMM.php');

    // Extending TypoScript from static template uid=43 to set up userdefined tag:
t3lib_extMgm::addTypoScript($_EXTKEY,'editorcfg','
	tt_content.CSS_editor.ch.tx_chtrip_pi1 = < plugin.tx_chtrip_pi1.CSS_editor
',43);

    // add extension non-cached
t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_chtrip_pi1.php','_pi1','list_type',1);

    // add sitemap menu
//t3lib_extMgm::addPItoST43($_EXTKEY,"pi2/class.tx_chtrip_pi2.php","_pi2","menu_type",0);

//$TYPO3_CONF_VARS['SYS']['enable_DLOG']=1;

?>
