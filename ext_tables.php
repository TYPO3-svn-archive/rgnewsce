<?php
	if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

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


	$confArr = unserialize($_EXTCONF);

	t3lib_div::loadTCA('tt_news');
	t3lib_extMgm::addTCAcolumns('tt_news',$tempColumns,1);
	
	//add tx_rgnewsce_ce with or without special tab for it
	if(isset($confArr['moreTabs']) && $confArr['moreTabs']){
		t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;Content,tx_rgnewsce_ce','','after:no_auto_pb');
	}else{
		t3lib_extMgm::addToAllTCAtypes('tt_news','tx_rgnewsce_ce','','after:no_auto_pb');
	}

	// a border with IRRE header to easly distingush between CEs
	if(isset($confArr['cssPath']) && strlen(trim($confArr['cssPath'])) > 0){
		$GLOBALS['TBE_STYLES']['stylesheet2'] = trim($confArr['cssPath']);
	}
	
	// reorganize tt_content "hide" position
	if(isset($confArr['reorganizeHide']) && $confArr['reorganizeHide']){
		foreach($TCA['tt_content']['types'] as $type=>$value){
			$TCA['tt_content']['types'][$type]['showitem'] = t3lib_div::rmFromList('hidden',$TCA['tt_content']['types'][$type]['showitem']);
		}
		$TCA['tt_content']['palettes']['4']['showitem'] = 'sys_language_uid, l18n_parent, colPos, spaceBefore, spaceAfter, section_frame, sectionIndex, hidden';
	}
	
	// move keywords from palette to separate field 
	$TCA['tt_news']['types']['0']['showitem'] = preg_replace('/;;4;;/', ';;;;' ,$TCA['tt_news']['types']['0']['showitem']);
	t3lib_extMgm::addToAllTCAtypes('tt_news','keywords','','after:short');
	
	//remove fields that we no longer need
	$TCA['tt_news']['types']['0']['showitem'] = t3lib_div::rmFromList(' image;;;;1-1-1',$TCA['tt_news']['types']['0']['showitem']);
	$TCA['tt_news']['types']['0']['showitem'] = t3lib_div::rmFromList(' imagecaption;;5;;',$TCA['tt_news']['types']['0']['showitem']);
	$TCA['tt_news']['types']['0']['showitem'] = t3lib_div::rmFromList(' bodytext;;4;richtext:rte_transform[flag=rte_enabled|mode=ts];4-4-4',$TCA['tt_news']['types']['0']['showitem']);
	
	//introduce tabs
	if(isset($confArr['moreTabs']) && $confArr['moreTabs']){
		$TCA['tt_news']['types']['0']['showitem'] = preg_replace('/--div--;Relations/', '' ,$TCA['tt_news']['types']['0']['showitem']);
		t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-category,','','after:tx_rgnewsce_ce');
		t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-special,','','before:links');	  	
		}

  	// make separate Short tab
	if(isset($confArr['shortAsTabWithRTE']) && $confArr['shortAsTabWithRTE']) {
		$TCA['tt_news']['types']['0']['showitem'] = t3lib_div::rmFromList(' short',$TCA['tt_news']['types']['0']['showitem']);
		t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-short,short;;;richtext:rte_transform[flag=rte_enabled|mode=ts];4-4-4,','','after:tx_rgnewsce_ce');
	}
	
?>