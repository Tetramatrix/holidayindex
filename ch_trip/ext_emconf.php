<?php

########################################################################
# Extension Manager/Repository config file for ext: "ch_trip"
#
# Auto generated 14-05-2009 14:25
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'TRIP-Travel-Information-Presenter',
	'description' => '',
	'category' => 'plugin',
	'shy' => 0,
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'tx_chtrip_location_captions',
	'state' => 'alpha',
	'internal' => 0,
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author' => 'Chi Hoang',
	'author_email' => 'chibo@gmx.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.0.49',
	'_md5_values_when_last_written' => 'a:106:{s:9:"ChangeLog";s:4:"8e2b";s:12:"default.html";s:4:"9330";s:21:"ext_conf_template.txt";s:4:"e4ff";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"68fd";s:14:"ext_tables.php";s:4:"a919";s:14:"ext_tables.sql";s:4:"0191";s:25:"ext_tables_static+adt.sql";s:4:"f403";s:24:"ext_typoscript_setup.txt";s:4:"f7c1";s:19:"flexform_ds_pi1.xml";s:4:"7ec2";s:32:"icon_tx_chtrip_accommodation.gif";s:4:"475a";s:27:"icon_tx_chtrip_category.gif";s:4:"475a";s:28:"icon_tx_chtrip_hierarchy.gif";s:4:"475a";s:27:"icon_tx_chtrip_location.gif";s:4:"475a";s:28:"icon_tx_chtrip_objecttyp.gif";s:4:"475a";s:24:"icon_tx_chtrip_price.gif";s:4:"475a";s:25:"icon_tx_chtrip_region.gif";s:4:"475a";s:26:"icon_tx_chtrip_request.gif";s:4:"475a";s:25:"icon_tx_chtrip_season.gif";s:4:"475a";s:13:"locallang.php";s:4:"bde6";s:24:"locallang_csh_chtrip.php";s:4:"aaff";s:16:"locallang_db.php";s:4:"07fd";s:7:"tca.php";s:4:"08dd";s:32:"gb_weather/060814_gbweather.diff";s:4:"6e42";s:20:"mod_object/clear.gif";s:4:"cc11";s:19:"mod_object/conf.php";s:4:"decf";s:20:"mod_object/index.php";s:4:"dcd4";s:24:"mod_object/locallang.php";s:4:"ee8c";s:28:"mod_object/locallang_mod.php";s:4:"1976";s:25:"mod_object/moduleicon.gif";s:4:"adc5";s:11:"res/cat.gif";s:4:"0e9a";s:12:"res/cat2.gif";s:4:"89a7";s:18:"res/cat2folder.gif";s:4:"b7f4";s:12:"res/cat3.gif";s:4:"2ed1";s:18:"res/cat3folder.gif";s:4:"2797";s:17:"res/catfolder.gif";s:4:"a16b";s:43:"deprecated/class.tx_chtrip_tcaFormField.php";s:4:"7f7f";s:42:"deprecated/class.tx_chtrip_tcaTypeUser.php";s:4:"4848";s:39:"deprecated/class.tx_chtrip_treeview.php";s:4:"5e59";s:41:"deprecated/class.tx_chtrip_treeviewMM.php";s:4:"231e";s:18:"deprecated/dca.php";s:4:"15df";s:18:"mod_prop/clear.gif";s:4:"cc11";s:17:"mod_prop/conf.php";s:4:"6a3f";s:18:"mod_prop/index.php";s:4:"940b";s:22:"mod_prop/locallang.php";s:4:"bb15";s:26:"mod_prop/locallang_mod.php";s:4:"7192";s:23:"mod_prop/moduleicon.gif";s:4:"8d3f";s:19:"mod_order/clear.gif";s:4:"cc11";s:18:"mod_order/conf.php";s:4:"3a0e";s:19:"mod_order/index.php";s:4:"f5ab";s:23:"mod_order/locallang.php";s:4:"c01c";s:27:"mod_order/locallang_mod.php";s:4:"6004";s:24:"mod_order/moduleicon.gif";s:4:"adc5";s:37:"realurl/class.tx_realurl_userfunc.php";s:4:"d917";s:39:"pagebrowser/class.tx_ch_pagebrowser.php";s:4:"d3f7";s:44:"pagebrowser/class.tx_chtrip_tslib_pibase.php";s:4:"6455";s:38:"mod_main/080714_tx_chtrip_navframe.php";s:4:"5b96";s:35:"mod_main/class.tx_chtrip_beform.php";s:4:"3edf";s:39:"mod_main/class.tx_chtrip_browseTree.php";s:4:"179c";s:37:"mod_main/class.tx_chtrip_navframe.php";s:4:"c740";s:39:"mod_main/class.tx_chtrip_treeConfig.php";s:4:"09fb";s:37:"mod_main/class.tx_chtrip_treeView.php";s:4:"12fd";s:17:"mod_main/conf.php";s:4:"c0e5";s:22:"mod_main/locallang.php";s:4:"8016";s:26:"mod_main/locallang_mod.php";s:4:"8e0f";s:23:"mod_main/moduleicon.gif";s:4:"3833";s:19:"mod_setup/clear.gif";s:4:"cc11";s:18:"mod_setup/conf.php";s:4:"ce8a";s:19:"mod_setup/index.php";s:4:"0bf5";s:23:"mod_setup/locallang.php";s:4:"9f20";s:27:"mod_setup/locallang_mod.php";s:4:"035d";s:24:"mod_setup/moduleicon.gif";s:4:"cce3";s:37:"tx_chtrip_location_captions/clear.gif";s:4:"cc11";s:36:"tx_chtrip_location_captions/conf.php";s:4:"9cf2";s:37:"tx_chtrip_location_captions/index.php";s:4:"ea9c";s:41:"tx_chtrip_location_captions/locallang.php";s:4:"750b";s:43:"tx_chtrip_location_captions/wizard_icon.gif";s:4:"1bdc";s:37:"lib/070703_class.tx_chtrip_object.php";s:4:"f645";s:37:"lib/070709_class.tx_chtrip_object.php";s:4:"381a";s:31:"lib/class.tx_chtrip_db_list.php";s:4:"47a6";s:29:"lib/class.tx_chtrip_email.php";s:4:"7bea";s:28:"lib/class.tx_chtrip_list.php";s:4:"9fe0";s:32:"lib/class.tx_chtrip_metatags.php";s:4:"fc66";s:29:"lib/class.tx_chtrip_popup.php";s:4:"b95c";s:37:"lib/class.tx_chtrip_scriptclasses.php";s:4:"420c";s:26:"lib/class.tx_chtrip_sp.php";s:4:"3c13";s:30:"lib/class.tx_chtrip_static.php";s:4:"a765";s:31:"lib/class.tx_chtrip_static2.php";s:4:"990d";s:33:"lib/class.tx_chtrip_t3lib_xml.php";s:4:"818a";s:33:"patch/class.tx_chtrip_tcemain.php";s:4:"9de3";s:33:"patch/class.ux_t3lib_TCEforms.php";s:4:"203d";s:27:"patch/class.ux_template.php";s:4:"1ada";s:14:"doc/manual.sxw";s:4:"b41c";s:27:"pi2/class.tx_chtrip_pi2.php";s:4:"a931";s:14:"pi1/ce_wiz.gif";s:4:"02b6";s:27:"pi1/class.tx_chtrip_pi1.php";s:4:"47a5";s:35:"pi1/class.tx_chtrip_pi1_wizicon.php";s:4:"1129";s:13:"pi1/clear.gif";s:4:"cc11";s:17:"pi1/locallang.php";s:4:"ab4c";s:24:"pi1/static/editorcfg.txt";s:4:"5f2f";s:20:"mod_region/clear.gif";s:4:"cc11";s:19:"mod_region/conf.php";s:4:"674c";s:20:"mod_region/index.php";s:4:"b20d";s:24:"mod_region/locallang.php";s:4:"5122";s:28:"mod_region/locallang_mod.php";s:4:"221f";s:25:"mod_region/moduleicon.gif";s:4:"adc5";}',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'dynaflex' => '',
			'php' => '3.0.0-0.0.0',
			'typo3' => '3.5.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
);

?>
