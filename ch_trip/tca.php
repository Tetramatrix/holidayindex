<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_chtrip_hotel"] = Array (
	"ctrl" => $TCA["tx_chtrip_hotel"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,title,location,id_code,postcode,weathercode,room,teaser,description,pictures,captions,video,related,location_f04338f846,object",
		"always_description" => 1,
	),
	"feInterface" => $TCA["tx_chtrip_hotel"]["feInterface"],
	"columns" => Array (
		'sys_language_uid' => Array (		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => Array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
				'foreign_table' => 'tx_chtrip_hotel',
				'foreign_table_where' => 'AND tx_chtrip_hotel.pid=###CURRENT_PID### AND tx_chtrip_hotel.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => Array (		
			'config' => Array (
				'type' => 'passthrough'
			)
		),
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"location" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.location",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"id_code" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.id_code",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"postcode" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.postcode",		
			"config" => Array (
				"type" => "input",	
				"size" => "6",	
				"max" => "10",	
				"eval" => "required",
			)
		),
		"weathercode" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.weathercode",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"room" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.room",
			"config" => Array (
				"type" => "inline",		
				"size" => 5,	
				"minitems" => 0,
				"maxitems" => 100,
				"foreign_table" => "tx_chtrip_room",
				'appearance' => Array(
					'expandSingle' => 1,
				),
			)			
		),
      		"metakeywords" => Array (
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.metakeywords",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",
			)
		),
		"teaser" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.teaser",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",
			)
		),
		"description" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.description",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"pictures" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.pictures",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 5000,	
				"uploadfolder" => "uploads/tx_chtrip",
				"show_thumbs" => 1,	
				"size" => 4,	
				"minitems" => 0,
				"maxitems" => 100,
			)
		),
		"captions" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.captions",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",	
				"wizards" => Array(
					"_PADDING" => 2,
					"example" => Array(
						"title" => "Example Wizard:",
						"type" => "script",
						"notNewRecords" => 1,
						"icon" => t3lib_extMgm::extRelPath("ch_trip")."tx_chtrip_hotel_captions/wizard_icon.gif",
						"script" => t3lib_extMgm::extRelPath("ch_trip")."tx_chtrip_hotel_captions/index.php",
					),
				),
			)
		),
		"video" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.video",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => 'avi,swf,mov,wmv,flv',	
				"max_size" => 50000,	
				"uploadfolder" => "uploads/tx_chtrip",
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 2,
			)
		),
		"location_f04338f846" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.location_f04338f846",		
			'config' => Array (
				'type' => 'select',
				'form_type' => 'user',
				'userFunc' => 'tx_ch_treeview->displayCategoryTree',
				'treeView' => 1,
				'treeName' => 'txchtripregion',
				'treeTable' => 'tx_chtrip_hotel',
				'treeMaxDepth' => 5,
				'treeNavi' => true,
				'size' => 10,
				'autoSizeMax' => 10,
				'selectedListStyle' => 'width:250px',
				'minitems' => 0,
				'maxitems' => 2,
				'foreign_table' => 'tx_chtrip_region',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hotel.createNewParentCategory',
						'icon' => 'add.gif',
						'params' => Array(
							'table'=>'tx_chtrip_region',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'set'
						),
						'script' => 'wizard_add.php',
					),
					'list' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hotel.listCategories',
						'icon' => 'list.gif',
						'params' => Array(
							'table'=>'tx_chtrip_region',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
					"edit" => Array(
						"type" => "popup",
						"title" => "Edit",
						"script" => "wizard_edit.php",
						"popup_onlyOpenIfSelected" => 1,
						"icon" => "edit2.gif",
						"JSopenParams" => "height=800,width=630,status=0,menubar=0,scrollbars=1",
					),
				),
			)
		),
		"hotelproperties" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.hotelproperties",
			'config' => Array (
				'type' => 'select',
				'form_type' => 'user',
				'userFunc' => 'tx_ch_treeview->displayCategoryTree',
				'treeView' => 1,
				'treeName' => 'txchtriphotelproperties',
				'treeMaxDepth' => '5',
				'treeField' => 'treeField',
				'treeTable' => 'tx_chtrip_hotel',
				'treeRadio' => false,
				'treeParentUid' => '92,93,94,136',
				'exclTreeParentUid' =>'172,173,186,273',
				'treeNavi' => true,
				'size' => 10,
				'autoSizeMax' => 10,
				'selectedListStyle' => 'width:250px',
				'minitems' => 0,
				'maxitems' => 100,
				'foreign_table' => 'tx_chtrip_properties',
				'MM' => 'tx_chtrip_hotel_properties_mm',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hotel.createNewParentCategory',
						'icon' => 'add.gif',
						'params' => Array(
							'table'=>'tx_chtrip_properties',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'append'
						),
						'script' => 'wizard_add.php',
					),
					'list' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hotel.listCategories',
						'icon' => 'list.gif',
						'params' => Array(
							'table'=>'tx_chtrip_properties',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
					"edit" => Array(
						"type" => "popup",
						"title" => "Edit",
						"script" => "wizard_edit.php",
						"popup_onlyOpenIfSelected" => 1,
						"icon" => "edit2.gif",
						"JSopenParams" => "height=800,width=630,status=0,menubar=0,scrollbars=1",
					),
				),
			)			
		),
		"season" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.season",
			'config' => Array (
				'type' => 'select',
				'form_type' => 'user',
				'userFunc' => 'tx_ch_treeview->displayCategoryTree',
				'treeMaxDepth' => '5',
				'treeView' => 1,
				'treeName' => 'txchtripseasonxxx1',
				'treeNavi' => true,
				'treeRadio' => false,
				'treeTable' => 'tx_chtrip_season',
				'size' => 10,
				'autoSizeMax' => 10,
				'selectedListStyle' => 'width:250px',
				'minitems' => 0,
				'maxitems' => 100,
				'foreign_table' => 'tx_chtrip_season',
				'MM' => 'tx_chtrip_hotel_season_mm',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hotel.createNewParentCategory',
						'icon' => 'add.gif',
						'params' => Array(
							'table'=>'tx_chtrip_season',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'append'
						),
						'script' => 'wizard_add.php',
					),
					'list' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hotel.listCategories',
						'icon' => 'list.gif',
						'params' => Array(
							'table'=>'tx_chtrip_season',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
					"edit" => Array(
						"type" => "popup",
						"title" => "Edit",
						"script" => "wizard_edit.php",
						"popup_onlyOpenIfSelected" => 1,
						"icon" => "edit2.gif",
						"JSopenParams" => "height=800,width=630,status=0,menubar=0,scrollbars=1",
					),
				),
			),					
		),
		"category" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hotel.category",
			'config' => Array (		
				"type" => "inline",		
				'size' => 25,
				"minitems" => 0,
				"maxitems" => 100,				
				'foreign_table' => 'tx_chtrip_category',
				'appearance' => Array(
							'collapseAll' => 1,
							'expandSingle' => 1,
				),
			)
		),
		'treeField' => Array (
			'label' => 'treeField',
			'config' => Array (
				'type' => 'passthrough',
				'items' => Array (
							Array('absolute (root) / ', 0),
							Array('relative ../fileadmin/', 1)
						),
						'default' => 0
				)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "sys_language_uid; Hotel,title;;;;1-1-1,description;;;richtext[paste|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts];1-1-1,metakeywords;;;;1-1-1,--div--;Kurzbeschreibung,teaser;;;;1-1-1,--div--;Adresse,location,postcode,location_f04338f846,--div--;ID/Code, id_code,--div--;Yahoo-Wheathercode,weathercode;;;;1-1-1,--div--;Hotel-Typ,hoteltype,--div--;Hotel-Eigenschaften,hotelproperties,--div--; Hotel-Bilder, pictures,--palette--;LLL:EXT:cms/locallang_ttc.php:ALT.imgLinks;7, captions, map;;;;1-1-1, video;;;;1-1-1,--div--; Zimmerbeschreibung, room;;;;1-1-1,--div--; Zimmerkategorie, category,--div--;Verwandte Objekte, related,--div--; Season, season")
	),
	"palettes" => Array (
		//"1" => Array("showitem" => "fe_group")
	)
);

$TCA["tx_chtrip_room"] = Array (
	"ctrl" => $TCA["tx_chtrip_room"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,title,description,pictures,category,miscellaneous,arrivalanddeparture"
	),
	"feInterface" => $TCA["tx_chtrip_room"]["feInterface"],
	"columns" => Array (
		'sys_language_uid' => Array (		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => Array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
				'foreign_table' => 'tx_chtrip_room',
				'foreign_table_where' => 'AND tx_chtrip_room.pid=###CURRENT_PID### AND tx_chtrip_room.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => Array (		
			'config' => Array (
				'type' => 'passthrough'
			)
		),
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		'treeField' => Array (
			'label' => 'treeField',
			'config' => Array (
				'type' => 'passthrough',
				'items' => Array (
							Array('absolute (root) / ', 0),
							Array('relative ../fileadmin/', 1)
						),
						'default' => 0
				)
		),
		"roomproperties" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_room.object",
			'config' => Array (
				'type' => 'select',
				'form_type' => 'user',
				'userFunc' => 'tx_ch_treeview->displayCategoryTree',
				'treeMaxDepth' => '5',
				'treeView' => 1,
				'treeName' => 'txchtripobjectroom',
				'treeField' => 'treeField',
				'treeTable' => 'tx_chtrip_room',
				'treeRadio' => false,
				'treeParentUid' => '172,173,186',
				'exclTreeParentUid' =>'92,93,94,136,273',
				'size' => 10,
				'autoSizeMax' => 10,
				'selectedListStyle' => 'width:250px',
				'minitems' => 0,
				'maxitems' => 100,
				'foreign_table' => 'tx_chtrip_properties',
				'MM' => 'tx_chtrip_room_mm',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_room.createNewParentCategory',
						'icon' => 'add.gif',
						'params' => Array(
							'table'=>'tx_chtrip_properties',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'append'
						),
						'script' => 'wizard_add.php',
					),
					'list' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_room.listCategories',
						'icon' => 'list.gif',
						'params' => Array(
							'table'=>'tx_chtrip_properties',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
				),
			)			
		),	
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_room.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"description" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_room.description",		
			"config" => Array (
				"type" => "text",
				"cols" => "48",
				"rows" => "5",
			)
		),
		"pictures" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_room.pictures",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 5000,	
				"uploadfolder" => "uploads/tx_chtrip",
				"show_thumbs" => 1,	
				"size" => 4,	
				"minitems" => 0,
				"maxitems" => 100,
			)
		),
		"captions" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_room.captions",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",	
				"wizards" => Array(
					"_PADDING" => 2,
					"example" => Array(
						"title" => "Example Wizard:",
						"type" => "script",
						"notNewRecords" => 1,
						"icon" => t3lib_extMgm::extRelPath("ch_trip")."tx_chtrip_hotel_captions/wizard_icon.gif",
						"script" => t3lib_extMgm::extRelPath("ch_trip")."tx_chtrip_hotel_captions/index.php",
					),
				),
			)
		),
		"miscellaneous" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_room.miscellaneous",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"arrivalanddeparture" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_room.arrivalanddeparture",		
			"config" => Array (
				"type" => "text",
				"cols" => "48",	
				"rows" => "5",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "sys_language_uid,--div--;Zimmerbeschreibung,title;;;;1-1-1, description;;;;1-1-1,--div--;Ankunft & Abfahrt,arrivalanddeparture;;;;1-1-1,--div--;Sonstiges,miscellaneous;;;richtext[paste|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts];1-1-1, --div--;Zimmer-Eigenschaften,roomproperties,--div--;Zimmer-Bilder,pictures,captions,--div--; Angebote,Angebot")
	),
	"palettes" => Array (
		//"1" => Array("showitem" => "")
	)
);


$TCA["tx_chtrip_category"] = Array (
	"ctrl" => $TCA["tx_chtrip_category"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,title,allseason,price,requestperunit,requestperperson,property"
	),
	"feInterface" => $TCA["tx_chtrip_category"]["feInterface"],
	"columns" => Array (
		'sys_language_uid' => Array (		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => Array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
				'foreign_table' => 'tx_chtrip_category',
				'foreign_table_where' => 'AND tx_chtrip_category.pid=###CURRENT_PID### AND tx_chtrip_category.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => Array (		
			'config' => Array (
				'type' => 'passthrough'
			)
		),
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_category.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"specialoffer" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_category.specialoffer",		
			"config" => Array (
				"type" => "check",
			)
		),
		"price" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_category.price",		
			"config" => Array (
				"type" => "input",
				"size" => "4",
				"max" => "4",
				"eval" => "int",
				"checkbox" => "0",
				"range" => Array (
					"upper" => "1000",
					"lower" => "10"
				),
				"default" => 0
			)
		),
		"requestperunit" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_category.requestperunit",		
			"config" => Array (
				"type" => "check",
			)
		),
		"requestperperson" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_category.requestperperson",		
			"config" => Array (
				"type" => "check",
			)
		),
		"halfboard" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_category.halfboard",		
			"config" => Array (
				"type" => "check",
			)
		),
		'treeField' => Array (
			'label' => 'treeField',
			'config' => Array (
				'type' => 'passthrough',
				'items' => Array (
							Array('absolute (root) / ', 0),
							Array('relative ../fileadmin/', 1)
						),
						'default' => 0
				)
		),
		"property" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_category.property",		
			'config' => Array (
				'type' => 'select',
				'form_type' => 'user',
				'userFunc' => 'tx_ch_treeview->displayCategoryTree',
				'treeView' => 1,
				'treeName' => 'txchtripcategory',
				'treeMaxDepth' => '5',
				'treeField' => 'treeField',
				'treeTable' => 'tx_chtrip_category',
				'treeRadio' => false,
				'treeParentUid' => '273',
				'exclTreeParentUid' => '92,93,94,136,172,173,186',
				'size' => 10,
				'autoSizeMax' => 10,
				'selectedListStyle' => 'width:250px',
				'minitems' => 0,
				'maxitems' => 100,
				'foreign_table' => 'tx_chtrip_properties',
				'MM' => 'tx_chtrip_category_mm',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hotel.createNewParentCategory',
						'icon' => 'add.gif',
						'params' => Array(
							'table'=>'tx_chtrip_properties',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'append'
						),
						'script' => 'wizard_add.php',
					),
					'list' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hotel.listCategories',
						'icon' => 'list.gif',
						'params' => Array(
							'table'=>'tx_chtrip_properties',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
					"edit" => Array(
						"type" => "popup",
						"title" => "Edit",
						"script" => "wizard_edit.php",
						"popup_onlyOpenIfSelected" => 1,
						"icon" => "edit2.gif",
						"JSopenParams" => "height=800,width=630,status=0,menubar=0,scrollbars=1",
					),
				),
			)	
		),
		"seasonprice" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_category.seasonprice",
			"config" => Array (
				"type" => "inline",		
				"size" => 5,	
				"minitems" => 0,
				"maxitems" => 100,
				"foreign_table" => "tx_chtrip_price",	
				'appearance' => Array(
					'collapseAll' => 1,
					'expandSingle' => 1,
				),
			)			
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "sys_language_uid,--div--;Zimmerkategorie,title;;;;1-1-1,price;;;;1-1-1,property;;;;1-1-1,requestperunit;;;;1-1-1,requestperperson,halfboard,--div--; Sonderangebot, specialoffer;;;;1-1-1,--div--;Preis,seasonprice	
		")
	),
	"palettes" => Array (
		//"1" => Array("showitem" => "fe_group")
	)
);

$TCA["tx_chtrip_season"] = Array (
	"ctrl" => $TCA["tx_chtrip_season"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,title,parent_uid"
	),
	"feInterface" => $TCA["tx_chtrip_season"]["feInterface"],
	"columns" => Array (
		'sys_language_uid' => Array (		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => Array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
				'foreign_table' => 'tx_chtrip_season',
				'foreign_table_where' => 'AND tx_chtrip_season.pid=###CURRENT_PID### AND tx_chtrip_season.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => Array (		
			'config' => Array (
				'type' => 'passthrough'
			)
		),
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_season.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"from_a1" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_season.from_a1",		
			"config" => Array (
				"type" => "input",
				"eval" => "date",
				"size" => "8",
				"max" => "20",			
			)
		),
		"till_a1" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_season.till_a1",		
			"config" => Array (
				"type" => "input",
				"eval" => "date",
				"size" => "8",
				"max" => "20",
			)
		),
		"parent_uid" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_season.parent_uid",		
			'config' => Array (
				'type' => 'select',
				'form_type' => 'user',
				'userFunc' => 'tx_ch_treeview->displayCategoryTree',
				'treeMaxDepth' => '5',
				'treeView' => 1,
				'treeName' => 'txchtripseasonseason',
				'treeNavi' => true,
				'treeTable' => 'tx_chtrip_season',
				'size' => 10,
				'autoSizeMax' => 10,
				'selectedListStyle' => 'width:250px',
				'minitems' => 0,
				'maxitems' => 2,
				'foreign_table' => 'tx_chtrip_season',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_season.createNewParentCategory',
						'icon' => 'add.gif',
						'params' => Array(
							'table'=>'tx_chtrip_season',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'set'
						),
						'script' => 'wizard_add.php',
					),
					'list' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_season.listCategories',
						'icon' => 'list.gif',
						'params' => Array(
							'table'=>'tx_chtrip_season',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
				),
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "sys_language_uid,title;;;;1-1-1,from_a1;;;;1-1-1,till_a1;;;;1-1-1, parent_uid;;;;1-1-1")
	),
	"palettes" => Array (
		//"1" => Array("showitem" => "")
	)
);

$TCA["tx_chtrip_price"] = Array (
	"ctrl" => $TCA["tx_chtrip_price"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,title"
	),
	"feInterface" => $TCA["tx_chtrip_season"]["feInterface"],
	"columns" => Array (
		'sys_language_uid' => Array (		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => Array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
				'foreign_table' => 'tx_chtrip_price',
				'foreign_table_where' => 'AND tx_chtrip_price.pid=###CURRENT_PID### AND tx_chtrip_price.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => Array (		
			'config' => Array (
				'type' => 'passthrough'
			)
		),
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_price.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"a_baseprice" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_price.a_baseprice",		
			"config" => Array (
				"type" => "input",
				"size" => "8",
				"max" => "20",				
			)
		),
		"a_halfboard" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_price.a_halfboard",		
			"config" => Array (
				"type" => "input",
				"size" => "8",
				"max" => "20",				
			)
		),		
		"season" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_price.season",
			'config' => Array (
				'type' => 'select',
				'form_type' => 'user',
				'userFunc' => 'tx_ch_treeview->displayCategoryTree',
				'treeMaxDepth' => '5',
				'treeView' => 1,
				'treeName' => 'txchtrippriceseason',
				'size' => 10,
				'autoSizeMax' => 10,
				'selectedListStyle' => 'width:250px',
				'minitems' => 0,
				'maxitems' => 100,
				'foreign_table' => 'tx_chtrip_season',
				'MM' => 'tx_chtrip_price_mm',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_price.createNewParentCategory',
						'icon' => 'add.gif',
						'params' => Array(
							'table'=>'tx_chtrip_season',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'append'
						),
						'script' => 'wizard_add.php',
					),
					'list' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_price.listCategories',
						'icon' => 'list.gif',
						'params' => Array(
							'table'=>'tx_chtrip_season',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
				),
			)			
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "sys_language_uid,--div--;Preiskategorie,title;;;;1-1-1,a_baseprice,a_halfboard,--div--;Season,season")
	),
	"palettes" => Array (
		//"1" => Array("showitem" => "fe_group")
	)
);

$TCA["tx_chtrip_properties"] = Array (
	"ctrl" => $TCA["tx_chtrip_properties"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,title,parent_uid,hierarchy"
	),
	"feInterface" => $TCA["tx_chtrip_properties"]["feInterface"],
	"columns" => Array (
		'sys_language_uid' => Array (		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => Array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
				'foreign_table' => 'tx_chtrip_properties',
				'foreign_table_where' => 'AND tx_chtrip_properties.pid=###CURRENT_PID### AND tx_chtrip_properties.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => Array (		
			'config' => Array (
				'type' => 'passthrough'
			)
		),
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_properties.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"icon" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_properties.icon",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 5000,	
				"uploadfolder" => "uploads/tx_chtrip",
				"show_thumbs" => 1,	
				"size" => 4,	
				"minitems" => 0,
				"maxitems" => 4,
			)
		),
		"parent_uid" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_properties.parent_uid",
			'config' => Array (
				'type' => 'select',
				'form_type' => 'user',
				'userFunc' => 'tx_ch_treeview->displayCategoryTree',
				'treeMaxDepth' => '5',
				'treeView' => 1,
				'treeTable' => 'tx_chtrip_properties',
				'treeName' => 'txchtrippropertiess',
				'size' => 10,
				'autoSizeMax' => 10,
				'selectedListStyle' => 'width:250px',
				'minitems' => 1,
				'maxitems' => 40,
				'foreign_table' => 'tx_chtrip_properties',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_properties.createNewParentCategory',
						'icon' => 'add.gif',
						'params' => Array(
							'table'=>'tx_chtrip_properties',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'append'
						),
						'script' => 'wizard_add.php',
					),
					'list' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_properties.listCategories',
						'icon' => 'list.gif',
						'params' => Array(
							'table'=>'tx_chtrip_properties',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
				),
			)
		),
		"hierarchy" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_properties.hierarchy",	
			'config' => Array (
				'type' => 'select',
				'form_type' => 'user',
				'userFunc' => 'tx_ch_treeview->displayCategoryTree',
				'treeMaxDepth' => '5',
				'treeView' => 1,
				'treeName' => 'txchtripprophierarchy',
				'treeNavi' => true,
				'size' => 10,
				'autoSizeMax' => 10,
				'selectedListStyle' => 'width:250px',
				'minitems' => 0,
				'maxitems' => 2,
				'foreign_table' => 'tx_chtrip_hierarchy',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hierarchy.createNewParentCategory',
						'icon' => 'add.gif',
						'params' => Array(
							'table'=>'tx_chtrip_hierarchy',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'append'
						),
						'script' => 'wizard_add.php',
					),
					'list' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hierarchy.listCategories',
						'icon' => 'list.gif',
						'params' => Array(
							'table'=>'tx_chtrip_hierarchy',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
				),
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "sys_language_uid, title;;;;1-1-1, parent_uid;;;;1-1-1, icon;;;;1-1-1,--div--;Formular,hierarchy;;;;1-1-1")
	),
	"palettes" => Array (
		//"1" => Array("showitem" => "")
	)
);

$TCA["tx_chtrip_hierarchy"] = Array (
	"ctrl" => $TCA["tx_chtrip_hierarchy"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,title,parent_uid"
	),
	"feInterface" => $TCA["tx_chtrip_hierarchy"]["feInterface"],
	"columns" => Array (
		'sys_language_uid' => Array (		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => Array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
				'foreign_table' => 'tx_chtrip_hierarchy',
				'foreign_table_where' => 'AND tx_chtrip_hierarchy.pid=###CURRENT_PID### AND tx_chtrip_hierarchy.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => Array (		
			'config' => Array (
				'type' => 'passthrough'
			)
		),
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hierarchy.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"icon" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hierarchy.icon",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 5000,	
				"uploadfolder" => "uploads/tx_chtrip",
				"show_thumbs" => 1,	
				"size" => 4,	
				"minitems" => 0,
				"maxitems" => 4,
			)
		),
		"parent_uid" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_hierarchy.parent_uid",
			'config' => Array (
				'type' => 'select',
				'form_type' => 'user',
				'userFunc' => 'tx_ch_treeview->displayCategoryTree',
				'treeMaxDepth' => '5',
				'treeView' => 1,
				'treeName' => 'txchtriphierarchy',
				'treeTable' => 'tx_chtrip_hierarchy',
				'treeNavi' => true,
				'size' => 10,
				'autoSizeMax' => 10,
				'selectedListStyle' => 'width:250px',
				'minitems' => 0,
				'maxitems' => 2,
				'foreign_table' => 'tx_chtrip_hierarchy',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hierarchy.createNewParentCategory',
						'icon' => 'add.gif',
						'params' => Array(
							'table'=>'tx_chtrip_hierarchy',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'append'
						),
						'script' => 'wizard_add.php',
					),
					'list' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_hierarchy.listCategories',
						'icon' => 'list.gif',
						'params' => Array(
							'table'=>'tx_chtrip_hierarchy',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
				),
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "sys_language_uid, title;;;;1-1-1, parent_uid;;;;1-1-1, icon;;;;1-1-1")
	),
	"palettes" => Array (
		//"1" => Array("showitem" => "")
	)
);

$TCA["tx_chtrip_request"] = Array (
    "ctrl" => $TCA["tx_chtrip_request"]["ctrl"],
    "interface" => Array (
        "showRecordFieldList" => "hidden,fe_group,hidden,datefrom,datetill,region,objtype,objname,category,salutation,name,forename,street,where1,phone,fax,email,name2,message,billingaddress"
    ),
    "feInterface" => $TCA["tx_chtrip_request"]["feInterface"],
    "columns" => Array (
		'sys_language_uid' => Array (		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => Array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
			)
		),
		'l18n_diffsource' => Array (		
			'config' => Array (
				'type' => 'passthrough'
			)
		),
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"datefrom" => Array (        
		"exclude" => 1,
		"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.datefrom",
		"config" => Array (
			"type" => "input",
			"size" => "8",
			"max" => "20",
			"eval" => "date",
			"default" => "0",
			"checkbox" => "0"
		)
		),
			"datetill" => Array (        
		"exclude" => 1,
		"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.datetill",
		"config" => Array (
			"type" => "input",
			"size" => "8",
			"max" => "20",
			"eval" => "date",
			"default" => "0",
			"checkbox" => "0"
		)
		),
		"region" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.region",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"objtype" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.objtype",		
			"config" => Array (
				"type" => "input",	
				"size" => "20",
			)
		),
		"objname" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.objname",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"objaccommodation" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.objaccommodation",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),		
		"category" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.category",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"idcode" => Array (		
				"exclude" => 1,		
				"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.idcode",		
				"config" => Array (
					"type" => "input",	
					"size" => "15",
				)
			),
		"salutation" => Array (        
		"exclude" => 1,        
		"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.salutation",        
		"config" => Array (
					"type" => "radio",
					"items" => Array (
					Array("LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.salutation.I.0", "0"),
					Array("LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.salutation.I.1", "1"),
					),
				)
		),
		"name" => Array (        
		"exclude" => 1,        
		"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.name",        
		"config" => Array (
			"type" => "input",    
					"size" => "20",
					"eval" => "trim, required",
		)
		),
		"forename" => Array (        
		"exclude" => 1,        
		"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.forename",        
		"config" => Array (
			"type" => "input",    
					"size" => "20",
					"eval" => "trim, required",
		)
		),
        "street" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.street",        
            "config" => Array (
                "type" => "input",    
				"size" => "20",
				"eval" => "trim, required",
            )
        ),
        "where1" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.where",        
            "config" => Array (
                "type" => "input",    
				"size" => "20",
				"eval" => "trim, required",
            )
        ),
        "phone" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.phone",        
            "config" => Array (
                "type" => "input",    
				"size" => "20",
				"eval" => "trim",
            )
        ),
		"fax" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.fax",        
            "config" => Array (
                "type" => "input",    
				"size" => "20",
				"eval" => "trim",
            )
        ),
        "email" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.email",        
            "config" => Array (
                "type" => "input",    
				"size" => "30",
				"eval" => "trim, required",
            )
        ),
        "name2" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.name2",        
            "config" => Array (
                "type" => "input",    
                "size" => "20",
				"eval" => "trim",
            )
        ),
        "message" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.message",        
            "config" => Array (
                "type" => "text",    
				"cols" => "30",
				"size" => "20",
				"eval" => "trim",
            )
        ),
        "billingaddress" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.billingaddress",        
            "config" => Array (
				"type" => "radio",
                "items" => Array (
                    Array("LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.billingaddress.I.0", "0"),
                    Array("LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.billingaddress.I.1", "1"),
                ),
            )
        ),
        "nl" => Array (        
            "exclude" => 1,        
            "label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.nl",        
            "config" => Array (
				"type" => "radio",
                "items" => Array (
                    Array("LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.nl.I.0", "0"),
                    Array("LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_request.nl.I.1", "1"),
                ),
            )
        ),
    ),
    "types" => Array (
        "0" => Array("showitem" => "sys_language_uid,--div--;Objekt,region;;;;1-1-1,objtype,objname,objaccommodation,category,idcode,--div--; Reisezeitraum,datefrom,datetill,--div--; Adresse, name;;;;1-1-1, forename, street, where1, phone,fax, email, billingaddress;;;;1-1-1, name2;;;;1-1-1,nl;;;;1-1-1,--div--;Nachricht,message")
    ),
    "palettes" => Array (
        //"1" => Array("showitem" => "fe_group")
    )
);

$TCA["tx_chtrip_region"] = Array (
	"ctrl" => $TCA["tx_chtrip_region"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,title,parent_uid"
	),
	"feInterface" => $TCA["tx_chtrip_region"]["feInterface"],
	"columns" => Array (
		'sys_language_uid' => Array (		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => Array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
				'foreign_table' => 'tx_chtrip_region',
				'foreign_table_where' => 'AND tx_chtrip_region.pid=###CURRENT_PID### AND tx_chtrip_region.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => Array (		
			'config' => Array (
				'type' => 'passthrough'
			)
		),
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_region.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"parent_uid" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ch_trip/locallang_db.php:tx_chtrip_region.parent_uid",		
			'config' => Array (
				'type' => 'select',
				'form_type' => 'user',
				'userFunc' => 'tx_ch_treeview->displayCategoryTree',
				'treeMaxDepth' => '5',
				'treeView' => 1,
				'treeName' => 'txchtripregionparentuid',
				'treeNavi' => true,
				'size' => 10,
				'autoSizeMax' => 10,
				'selectedListStyle' => 'width:250px',
				'minitems' => 0,
				'maxitems' => 2,
				'foreign_table' => 'tx_chtrip_region',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_region.createNewParentCategory',
						'icon' => 'add.gif',
						'params' => Array(
							'table'=>'tx_chtrip_region',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'set'
						),
						'script' => 'wizard_add.php',
					),
					'list' => Array(
						'type' => 'script',
						'title' => 'LLL:EXT:ch_chtrip/locallang_db.php:tx_chtrip_region.listCategories',
						'icon' => 'list.gif',
						'params' => Array(
							'table'=>'tx_chtrip_region',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
				),
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "sys_language_uid,title;;;;1-1-1, parent_uid;;;;1-1-1")
	),
	"palettes" => Array (
		//"1" => Array("showitem" => "")
	)
);


?>
