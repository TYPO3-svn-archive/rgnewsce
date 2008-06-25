<?php
	if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
	$tempColumns = Array (
		"tx_rgnewsce_ce" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:rgnewsce/locallang_db.xml:tt_news.tx_rgnewsce_ce",		
			"config" => Array (
					"type" => "inline",	
					"languageMode" => "inherit",
	        
					"foreign_table" => "tt_content",	
					"foreign_table_where" => "ORDER BY tt_content.uid",		
					"size" => 1,	
					"minitems" => 0,
					"maxitems" => 10,
					
					"behaviour" => array(
							"localizationMode" => "select",
							"localizeChildrenAtParentLocalization" => 1,
					),
					'appearance' => array(
						'showPossibleLocalizationRecords' => 1,
						'showAllLocalizationLink' => 1,
						'showSynchronizationLink' => 1,
	          "useSortable" => 1,
	          "newRecordLinkPosition" => "bottom",
						
					),
			)
		),
    "tx_rgnewsce_style" => Array (        
        "exclude" => 1,        
        "label" => "LLL:EXT:rgnewsce/locallang_db.xml:tt_news.tx_rgnewsce_style",        
        "config" => Array (
            "type" => "text",
            "cols" => "30",    
            "rows" => "5",
        )
    ),		
	);
	
	
	t3lib_div::loadTCA("tt_news");
	t3lib_extMgm::addTCAcolumns("tt_news",$tempColumns,1);
	t3lib_extMgm::addToAllTCAtypes("tt_news","tx_rgnewsce_ce,tx_rgnewsce_style;;;;1-1-1,");

?>