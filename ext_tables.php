<?php

if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['rgnewsce']);

t3lib_div::loadTCA('tt_news');


// manage news types
if(isset($confArr['removeInternalPageType']) && $confArr['removeInternalPageType']){
	unset($GLOBALS['TCA']['tt_news']['columns']['type']['config']['items'][1]);
}
if(isset($confArr['removeExternalUrlType']) && $confArr['removeExternalUrlType']){
	unset($GLOBALS['TCA']['tt_news']['columns']['type']['config']['items'][2]);
}


//*********************************
// IRRE NEWS TYPE
//

// adds 'Extended News' type
$GLOBALS['TCA']['tt_news']['columns']['type']['config']['items'][] = Array('LLL:EXT:rgnewsce/locallang_db.xml:extended-news', 3);

$GLOBALS['TCA']['tt_news']['types']['3']['showitem'] = $GLOBALS['TCA']['tt_news']['types']['0']['showitem'];

$tempColumns = Array (
				'tx_rgnewsce_ce' => Array (
				'exclude' => 1,
				'label' => 'LLL:EXT:rgnewsce/locallang_db.xml:tt_news.tx_rgnewsce_ce',
				'config' => Array (
						'type' => 'inline',
						'languageMode' => 'inherit',

						'foreign_table' => 'tt_content',
						'foreign_table_where' => 'ORDER BY tt_content.uid',
						'size' => 1,
						'minitems' => 0,
						'maxitems' => 10,

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

//add tx_rgnewsce_ce with or without special tab for it
if(isset($confArr['moreTabs']) && $confArr['moreTabs']){
	t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;Content,tx_rgnewsce_ce','3','after:no_auto_pb');
}else{
	t3lib_extMgm::addToAllTCAtypes('tt_news','tx_rgnewsce_ce','3','after:no_auto_pb');
}

// a border with IRRE header to easly distingush between CEs
if(isset($confArr['cssPath']) && strlen(trim($confArr['cssPath'])) > 0){
	$GLOBALS['TBE_STYLES']['stylesheet2'] = trim($confArr['cssPath']);
}

// reorganize tt_content "hide" position
if(isset($confArr['reorganizeHide']) && $confArr['reorganizeHide']){
	foreach($GLOBALS['TCA']['tt_content']['types'] as $type=>$value){
		$GLOBALS['TCA']['tt_content']['types'][$type]['showitem'] = t3lib_div::rmFromList('hidden',$GLOBALS['TCA']['tt_content']['types'][$type]['showitem']);
	}
	$GLOBALS['TCA']['tt_content']['palettes']['4']['showitem'] = 'sys_language_uid, l18n_parent, colPos, spaceBefore, spaceAfter, section_frame, sectionIndex, hidden';
}

// move keywords from palette to separate field
$GLOBALS['TCA']['tt_news']['types']['3']['showitem'] = preg_replace('/;;4;;/', ';;;;', $GLOBALS['TCA']['tt_news']['types']['3']['showitem']);
t3lib_extMgm::addToAllTCAtypes('tt_news', 'keywords', '3', 'after:short');

//remove fields that we no longer need
$GLOBALS['TCA']['tt_news']['types']['3']['showitem'] = t3lib_div::rmFromList(' image;;;;1-1-1',$GLOBALS['TCA']['tt_news']['types']['3']['showitem']);
$GLOBALS['TCA']['tt_news']['types']['3']['showitem'] = t3lib_div::rmFromList(' imagecaption;;5;;',$GLOBALS['TCA']['tt_news']['types']['3']['showitem']);
$GLOBALS['TCA']['tt_news']['types']['3']['showitem'] = t3lib_div::rmFromList(' bodytext;;4;richtext:rte_transform[flag=rte_enabled|mode=ts];4-4-4',$GLOBALS['TCA']['tt_news']['types']['3']['showitem']);

//introduce tabs
if(isset($confArr['moreTabs']) && $confArr['moreTabs']){
	$GLOBALS['TCA']['tt_news']['types']['3']['showitem'] = preg_replace('/--div--;Relations/', '', $GLOBALS['TCA']['tt_news']['types']['3']['showitem']);
	t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-category,', '3', 'after:tx_rgnewsce_ce');
	t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-special,', '3', 'before:links');
}

// make separate Short tab
if(isset($confArr['shortAsTabWithRTE']) && $confArr['shortAsTabWithRTE']) {
	$GLOBALS['TCA']['tt_news']['types']['3']['showitem'] = t3lib_div::rmFromList(' short',$GLOBALS['TCA']['tt_news']['types']['3']['showitem']);
	t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-short,short;;;richtext:rte_transform[flag=rte_enabled|mode=ts];4-4-4,', '3', 'after:tx_rgnewsce_ce');
}



//*********************************
// OLD NEWS TYPE

//introduce tabs
if(isset($confArr['moreTabs']) && $confArr['moreTabs'] == 1){
	$GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = preg_replace('/--div--;Relations/', '' ,$GLOBALS['TCA']['tt_news']['types']['0']['showitem']);
	t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-content,','0','before:bodytext');
	t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-category,','0','after:bodytext');
	t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-images,','0','after:category');
	t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-special,','0','before:links');
}
// make separate Short tab
if(isset($confArr['shortAsTabWithRTE']) && $confArr['shortAsTabWithRTE'] == 1) {
	$GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = t3lib_div::rmFromList(' short',$GLOBALS['TCA']['tt_news']['types']['0']['showitem']);
	t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-short,short;;4;richtext:rte_transform[flag=rte_enabled|mode=ts];4-4-4,','0','after:bodytext');
}

?>
