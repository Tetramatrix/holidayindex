<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (!defined ('PATH_txchva')) {
	define('PATH_txchva', t3lib_extMgm::extPath('ch_trip'));
}

if (!defined ('PATH_txchva_rel')) {
	define('PATH_txchva_rel', t3lib_extMgm::extRelPath('ch_trip'));
}

// add save and new button to the form
t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_chtrip_region=1');
t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_chtrip_category=1');
t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_chtrip_properties=1');
t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_chtrip_season=1');
t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_chtrip_hierarchy=1');
t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_chtrip_price=1');

include_once(t3lib_extMgm::extPath($_EXTKEY).'pagebrowser/class.tx_ch_pagebrowser.php');

$TCA["tx_chtrip_hotel"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		'dividers2tabs' => TRUE,
		"languageField" => "sys_language_uid",	
		"transOrigPointerField" => "l18n_parent",	
		"transOrigDiffSourceField" => "l18n_diffsource",	
		"default_sortby" => "ORDER BY tstamp DESC",	
		"delete" => "deleted",		
		"enablecolumns" => Array (		
			"disabled" => "hidden",	
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_chtrip_hotel.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "sys_language_uid, l18n_parent, l18n_diffsource, hidden, fe_group, title, location, id_code, postcode, weathercode, specialoffer, room, teaser, description, pictures, captions, video, related, location_f04338f846,hoteltype,hotelproperties,metakeywords",
	)
);

$TCA["tx_chtrip_room"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_room",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		'dividers2tabs' => TRUE,
		"languageField" => "sys_language_uid",	
		"transOrigPointerField" => "l18n_parent",	
		"transOrigDiffSourceField" => "l18n_diffsource",	
		"default_sortby" => "ORDER BY tstamp DESC",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_chtrip_room.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "sys_language_uid, l18n_parent, l18n_diffsource, hidden, title, description, pictures, category,sp_title_1,sp_description_1,sp_title_2,sp_description_2,sp_title_3,sp_description_3, miscellaneous, arrivalanddeparture, objectinfo",
	)
);

$TCA["tx_chtrip_properties"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_properties",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		'dividers2tabs' => TRUE,
		"languageField" => "sys_language_uid",	
		"transOrigPointerField" => "l18n_parent",	
		"transOrigDiffSourceField" => "l18n_diffsource",	
		"default_sortby" => "ORDER BY crdate DESC",
		'treeParentField' => 'parent_uid',			
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_chtrip_properties.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "sys_language_uid, l18n_parent, l18n_diffsource, hidden, title, parent_uid, hierarchy",
	)
);

$TCA["tx_chtrip_category"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_category",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		'dividers2tabs' => TRUE,
		"languageField" => "sys_language_uid",	
		"transOrigPointerField" => "l18n_parent",	
		"transOrigDiffSourceField" => "l18n_diffsource",	
		"default_sortby" => "ORDER BY tstamp DESC",
		'treeParentField' => 'parent_uid',		
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",	
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_chtrip_category.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "sys_language_uid, l18n_parent, l18n_diffsource, hidden, fe_group, title, bookingperiod, parent_uid",
	)
);

$TCA["tx_chtrip_price"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_price",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		'dividers2tabs' => TRUE,
		"languageField" => "sys_language_uid",	
		"transOrigPointerField" => "l18n_parent",	
		"transOrigDiffSourceField" => "l18n_diffsource",	
		"default_sortby" => "ORDER BY tstamp DESC",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_chtrip_price.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "sys_language_uid, l18n_parent, l18n_diffsource, hidden, title",
	)
);

$TCA["tx_chtrip_season"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_season",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		'dividers2tabs' => TRUE,
		"languageField" => "sys_language_uid",	
		"transOrigPointerField" => "l18n_parent",	
		"transOrigDiffSourceField" => "l18n_diffsource",	
		"default_sortby" => "ORDER BY crdate DESC",	
		"delete" => "deleted",
		'treeParentField' => 'parent_uid',			
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_chtrip_season.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "sys_language_uid, l18n_parent, l18n_diffsource, hidden, title, parent_uid",
	)
);

$TCA["tx_chtrip_region"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_region",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		'dividers2tabs' => TRUE,
		"languageField" => "sys_language_uid",	
		"transOrigPointerField" => "l18n_parent",	
		"transOrigDiffSourceField" => "l18n_diffsource",	
		"default_sortby" => "ORDER BY crdate DESC",	
		"delete" => "deleted",
		'treeParentField' => 'parent_uid',		
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_chtrip_region.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "sys_language_uid, l18n_parent, l18n_diffsource, hidden, title, parent_uid",
	)
);

$TCA["tx_chtrip_request"] = Array (
    "ctrl" => Array (
        "title" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request",        
        "label" => "name",    
        "tstamp" => "tstamp",
        "crdate" => "crdate",
        "cruser_id" => "cruser_id",
		'dividers2tabs' => TRUE,
		"languageField" => "sys_language_uid",	
		"transOrigPointerField" => "l18n_parent",	
		"transOrigDiffSourceField" => "l18n_diffsource",
		"default_sortby" => "ORDER BY crdate DESC",    
		"delete" => "deleted",    
		"enablecolumns" => Array (        
		"disabled" => "hidden",    
			"fe_group" => "fe_group",
        ),
        "dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
        "iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_chtrip_request.gif",
    ),
    "feInterface" => Array (
        "fe_admin_fieldList" => "sys_language_uid, l18n_parent, l18n_diffsource, hidden, fe_group, hidden, datefrom, datetill, region, objtype, objname, category, idcode, salutation, name, forename, street, where1, phone, fax, email, name2, message, billingaddress",
    )
);

$TCA["tx_chtrip_hierarchy"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hierarchy",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		'dividers2tabs' => TRUE,
		"languageField" => "sys_language_uid",	
		"transOrigPointerField" => "l18n_parent",	
		"transOrigDiffSourceField" => "l18n_diffsource",	
		"default_sortby" => "ORDER BY crdate ASC",
		'treeParentField' => 'parent_uid',			
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_chtrip_hierarchy.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "sys_language_uid, l18n_parent, l18n_diffsource, hidden, title, parent_uid",
	)
);

t3lib_div::loadTCA('tt_content');

$temp[0] = $TCA['tt_content']['columns']['header_layout']['config']['items']['0'];
$temp[1] = $TCA['tt_content']['columns']['header_layout']['config']['items']['1'];
$temp[2] = $TCA['tt_content']['columns']['header_layout']['config']['items']['2'];
$temp[3] = $TCA['tt_content']['columns']['header_layout']['config']['items']['3'];
unset($TCA['tt_content']['columns']['header_layout']['config']['items']);

$TCA['tt_content']['columns']['header_layout']['config']['items']['0'] = $temp[0];
$TCA['tt_content']['columns']['header_layout']['config']['items']['1'] = $temp[1];
$TCA['tt_content']['columns']['header_layout']['config']['items']['2'] = $temp[2];
$TCA['tt_content']['columns']['header_layout']['config']['items']['3'] = $temp[3];

$TCA['tt_content']['columns']['header_layout']['config']['items']['2']['0'] = 'Spiral-Header';
$TCA['tt_content']['columns']['header_layout']['config']['items']['3']['0'] = 'Seiten-Header';


$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';

$temp = array(
			'label' => 'treeField',
			'exclude' => 0,	
			'config' => Array (
				'type' => 'passthrough',
				'items' => Array (
						Array('absolute (root) / ', 0),
						Array('relative ../fileadmin/', 1)
					),
					'default' => 0
			),
		);


$TCA['tt_content']['columns']['tx_chtrip_treeField'] = $temp;
$temp = array(
			'label' => 'treeAltField',
			'exclude' => 0,	
			'config' => Array (
				'type' => 'passthrough',
				'items' => Array (
						Array('absolute (root) / ', 0),
						Array('relative ../fileadmin/', 1)
					),
					'default' => 0
			),
		);

$TCA['tt_content']['columns']['tx_chtrip_treeAltField'] = $temp;

$extKey='perfectlightbox';
if( t3lib_extMgm::isLoaded($extKey) ) {
	$tempColumns = Array (
		"tx_perfectlightbox_activate" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:perfectlightbox/locallang_db.xml:tt_content.tx_perfectlightbox_activate",		
			"config" => Array (
				"type" => "check",
			)
		),
		"tx_perfectlightbox_imageset" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:perfectlightbox/locallang_db.xml:tt_content.tx_perfectlightbox_imageset",		
			"config" => Array (
				"type" => "check",
			)
		),
		"tx_perfectlightbox_presentation" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:perfectlightbox/locallang_db.xml:tt_content.tx_perfectlightbox_presentation",		
			"config" => Array (
				"type" => "check",
			)
		),
		"tx_perfectlightbox_slideshow" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:perfectlightbox/locallang_db.xml:tt_content.tx_perfectlightbox_slideshow",		
			"config" => Array (
				"type" => "check",
			)
		),
	);
	
	
	t3lib_div::loadTCA("tx_chtrip_hotel");
	t3lib_extMgm::addTCAcolumns("tx_chtrip_hotel",$tempColumns,1);
	
	$GLOBALS['TCA']['tx_chtrip_hotel']['palettes']['7']['showitem'] .= ', tx_perfectlightbox_activate, tx_perfectlightbox_imageset, tx_perfectlightbox_presentation, tx_perfectlightbox_slideshow';
}


t3lib_extMgm::addPlugin(Array('LLL:EXT:ch_trip/locallang_db.php:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:ch_trip/flexform_ds_pi1.xml');

t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/","TRIP - Travel Information Presenter");

	// initalize "context sensitive help" (csh)
t3lib_extMgm::addLLrefForTCAdescr('tx_chtrip_hotel','EXT:ch_trip/locallang_csh_chtrip.php');


if (TYPO3_MODE=="BE") {
    $TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_chtrip_pi1_wizicon"] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_chtrip_pi1_wizicon.php';

    		// add module after 'Tools'
	if (!isset($TBE_MODULES['txchtripM1']))	{
		$temp_TBE_MODULES = array();
		foreach($TBE_MODULES as $key => $val) {
			if ($key=='web') {
				$temp_TBE_MODULES[$key] = $val;
				$temp_TBE_MODULES['txchtripM1'] = $val;
			} else {
				$temp_TBE_MODULES[$key] = $val;
			}
		}
		$TBE_MODULES = $temp_TBE_MODULES;
	}
    
        	// add module
    t3lib_extMgm::addModule('txchtripM1','','',t3lib_extMgm::extPath($_EXTKEY)."mod_main/");

        	// add region module
	t3lib_extMgm::addModule('txchtripM1','region','',PATH_txchva.'mod_region/');
    
        	// add properties module
	t3lib_extMgm::addModule('txchtripM1','prop','',PATH_txchva.'mod_prop/');
 
           // add object module
    t3lib_extMgm::addModule('txchtripM1','object','',PATH_txchva.'mod_object/');
 
       		// add order module
	t3lib_extMgm::addModule('txchtripM1','order','',PATH_txchva.'mod_order/');

       		// add setup module
	t3lib_extMgm::addModule('txchtripM1','setup','',PATH_txchva.'mod_setup/');

}

t3lib_extMgm::addPlugin(Array("LLL:EXT:ch_trip/locallang_db.php:tt_content.menu_type_pi2", $_EXTKEY."_pi2"),"menu_type");
?>
