<?php


//*********************************
// IRRE NEWS TYPE
//

t3lib_extMgm::addToAllTCAtypes('tt_news','tx_rgnewsce_ce','4','after:no_auto_pb');

// move keywords from palette to separate field
$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = preg_replace('/;;4;;/', ';;;;', $GLOBALS['TCA']['tt_news']['types']['4']['showitem']);
t3lib_extMgm::addToAllTCAtypes('tt_news', 'keywords', '4', 'after:short');

//introduce tabs
if(isset($confArr['moreTabs']) && $confArr['moreTabs']){
	$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = preg_replace('/--div--;Relations/', '', $GLOBALS['TCA']['tt_news']['types']['4']['showitem']);
	t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-content,', '4','after:no_auto_pb');
	t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-category,', '4', 'after:tx_rgnewsce_ce');
	t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-special,', '4', 'before:links');
}

// make separate Short tab
if(isset($confArr['shortAsTabWithRTE']) && $confArr['shortAsTabWithRTE']) {
	$GLOBALS['TCA']['tt_news']['types']['4']['showitem'] = t3lib_div::rmFromList(' short',$GLOBALS['TCA']['tt_news']['types']['4']['showitem']);
	t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-short,short;;;richtext:rte_transform[flag=rte_enabled|mode=ts];4-4-4,', '4', 'after:tx_rgnewsce_ce');
}




//*********************************
// OLD NEWS TYPE

//introduce tabs
if(isset($confArr['moreTabs']) && $confArr['moreTabs'] == 1){
	$GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = preg_replace('/--div--;Relations/', '' ,$GLOBALS['TCA']['tt_news']['types']['0']['showitem']);
	t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-content,', '0', 'before:bodytext');
	t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-category,', '0', 'after:no_auto_pb');
	t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-images,', '0', 'after:category');
	t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-special,', '0', 'before:links');

	// make separate Extra tab for tt_content
	if(isset($confArr['extraTabForTTContent']) && $confArr['extraTabForTTContent'] == 1) {
		t3lib_extMgm::addToAllTCAtypes('tt_news', '--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-extra,tx_rgnewsce_ce', '0', 'after:news_files');
	}

}

// make separate Short tab
if(isset($confArr['shortAsTabWithRTE']) && $confArr['shortAsTabWithRTE'] == 1) {
	$GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = t3lib_div::rmFromList(' short',$GLOBALS['TCA']['tt_news']['types']['0']['showitem']);
	$GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = t3lib_div::rmFromList('short',$GLOBALS['TCA']['tt_news']['types']['0']['showitem']);
	t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;LLL:EXT:rgnewsce/locallang_db.xml:tab-short,short;;4;richtext:rte_transform[flag=rte_enabled|mode=ts];4-4-4,','0','after:no_auto_pb');
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

?>