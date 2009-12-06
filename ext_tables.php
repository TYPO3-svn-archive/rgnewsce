<?php

if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
//debug($GLOBALS['TYPO3_CONF_VARS']); die;

if( TYPO3_MODE == 'BE' ) {

	// get tt_news version information
	$file = t3lib_extMgm::extPath('tt_news') . 'ext_emconf.php';
	if (@is_file($file))	{
		$EM_CONF = array();
		include($file);
		$tt_news_version = $EM_CONF[$_EXTKEY]['version'];
	}


	t3lib_extMgm::addStaticFile($_EXTKEY, 'static/', 'tt_news extended');

	$thisExtRelPath = t3lib_extMgm::extRelPath($_EXTKEY);

	$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['rgnewsce']);

	// reorganize tt_content "hide" position
	if(isset($confArr['reorganizeHide']) && $confArr['reorganizeHide']){
		foreach($GLOBALS['TCA']['tt_content']['types'] as $type=>$value){
			$GLOBALS['TCA']['tt_content']['types'][$type]['showitem'] = t3lib_div::rmFromList(' hidden', $GLOBALS['TCA']['tt_content']['types'][$type]['showitem']);
			$GLOBALS['TCA']['tt_content']['types'][$type]['showitem'] = t3lib_div::rmFromList('hidden', $GLOBALS['TCA']['tt_content']['types'][$type]['showitem']);
		}
		$GLOBALS['TCA']['tt_content']['palettes']['4']['showitem'] .= ',hidden';
	}

	// add css to change the looks of IRRE to easier distinguish CE
	if(isset($confArr['cssPath']) && strlen(trim($confArr['cssPath'])) > 0){
		$GLOBALS['TBE_STYLES']['stylesheet2'] = trim($confArr['cssPath']);
	}

	t3lib_div::loadTCA('tt_news');

	// adds new type of news "News extended"
	$GLOBALS['TCA']['tt_news']['columns']['type']['config']['items'][] = Array('LLL:EXT:rgnewsce/locallang_db.xml:extended-news', 4);
	$GLOBALS['TCA']['tt_news']['ctrl']['typeicons']['4'] = $thisExtRelPath.'res/icons/icon_tt_news_ext_icon_extended.gif';
	$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = $GLOBALS['TCA']['tt_news']['types']['0']['showitem'];

	$tempColumns = Array (
				'tx_rgnewsce_ce' => Array (
				'exclude' => 1,
				'label' => 'LLL:EXT:rgnewsce/locallang_db.xml:main-label',
				'config' => Array (
						'type' => 'inline',
						'languageMode' => 'inherit',

						'foreign_table' => 'tt_content',
						'foreign_table_where' => 'ORDER BY tt_content.uid',
						'size' => 1,
						'minitems' => 0,
						'maxitems' => 1000,

						'behaviour' => array(
							'localizationMode' => 'select',
							'localizeChildrenAtParentLocalization' => 1,
	),
						'appearance' => array(
							'showPossibleLocalizationRecords' => 1,
							'showAllLocalizationLink' => 1,
							'showSynchronizationLink' => 1,
							'useSortable' => 1,
							'newRecordLinkPosition' => 'bottom',
	),
	)
	),
	);

	t3lib_extMgm::addTCAcolumns('tt_news', $tempColumns, 1);


	// get different versions for 3.0.0 and different for below
	if(version_compare($tt_news_version, '3.0.0', '>=')) {

		// reorganize type = 0  - 'News'
		t3lib_extMgm::addToAllTCAtypes('tt_news','tx_rgnewsce_ce','0','');
		$GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = t3lib_div::rmFromList( 'links;;;;2-2-2', $GLOBALS['TCA']['tt_news']['types']['0']['showitem']);
		t3lib_extMgm::addToAllTCAtypes('tt_news', 'links;;;;2-2-2', '0', 'after:related');

		// Reorganzie type = 4  - 'News extended'
		t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-content,tx_rgnewsce_ce', '4','after:short');
		$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = t3lib_div::rmFromList(' image;;;;1-1-1',$GLOBALS['TCA']['tt_news']['types']['4']['showitem']);
		$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = t3lib_div::rmFromList('image;;;;1-1-1',$GLOBALS['TCA']['tt_news']['types']['4']['showitem']);
		$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = t3lib_div::rmFromList(' imagecaption;;5;;',$GLOBALS['TCA']['tt_news']['types']['4']['showitem']);
		$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = t3lib_div::rmFromList('imagecaption;;5;;',$GLOBALS['TCA']['tt_news']['types']['4']['showitem']);
		$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = t3lib_div::rmFromList('bodytext;;2;richtext:rte_transform[flag=rte_enabled|mode=ts];4-4-4',$GLOBALS['TCA']['tt_news']['types']['4']['showitem']);
		$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = t3lib_div::rmFromList(' bodytext;;2;richtext:rte_transform[flag=rte_enabled|mode=ts];4-4-4',$GLOBALS['TCA']['tt_news']['types']['4']['showitem']);


		// make separate 'Short' tab
		if(isset($confArr['shortAsTabWithRTE']) && $confArr['shortAsTabWithRTE']) {
			$GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = t3lib_div::rmFromList(' short',$GLOBALS['TCA']['tt_news']['types']['0']['showitem']);
			$GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = t3lib_div::rmFromList('short',$GLOBALS['TCA']['tt_news']['types']['0']['showitem']);
			t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-short,short;;;richtext:rte_transform[flag=rte_enabled|mode=ts];4-4-4,', '0', 'after:bodytext');

			$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = t3lib_div::rmFromList(' short',$GLOBALS['TCA']['tt_news']['types']['4']['showitem']);
			$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = t3lib_div::rmFromList('short',$GLOBALS['TCA']['tt_news']['types']['4']['showitem']);
			t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-short,short;;;richtext:rte_transform[flag=rte_enabled|mode=ts];4-4-4,', '4', 'after:tx_rgnewsce_ce');

		}

	} else {
		// version for tt_news below 3.0.0
		include($thisExtRelPath . 'ext_tables-2.5.2.php');
	}

	
	// timtab compatibility
	if( t3lib_extMgm::isLoaded('timtab') ) {
		$GLOBALS['TCA']['tt_news']['columns']['type']['config']['items'][] = Array('LLL:EXT:rgnewsce/locallang_db.xml:extended-blog', 5);
		$GLOBALS['TCA']['tt_news']['types']['5']['showitem'] = $GLOBALS['TCA']['tt_news']['types']['4']['showitem'];
		$GLOBALS['TCA']['tt_news']['ctrl']['typeicons']['5'] = $thisExtRelPath.'res/icons/icon_tx_timtab_post_extended.gif';
		t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-blog,tx_timtab_trackbacks;;;;1-1-1,tx_timtab_comments_allowed;;;;2-2-2,tx_timtab_ping_allowed;;;;', '5', 'after:news_files');

		$GLOBALS['TCA']['tt_news']['types']['3']['showitem'] = $GLOBALS['TCA']['tt_news']['types']['0']['showitem'];
		t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-blog,tx_timtab_trackbacks;;;;1-1-1,tx_timtab_comments_allowed;;;;2-2-2,tx_timtab_ping_allowed;;;;', '3', 'after:news_files');
	}

}
?>
