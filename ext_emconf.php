<?php

########################################################################
# Extension Manager/Repository config file for ext "rgnewsce".
#
# Auto generated 23-03-2010 12:55
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Content elements in tt_news',
	'description' => 'This extension allows to add any number of any content elements to a news record.	Idea and first implementation by Georg Ringer. Now maintainer is Krystian Szymukowicz.',
	'category' => 'fe',
	'author' => 'Krystian Szymukowicz / Georg Ringer',
	'author_email' => 'http://www.cms-partner.pl , http://www.ringer.it',
	'shy' => '',
	'dependencies' => 'tt_news,css_styled_content',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.1.1',
	'constraints' => array(
		'depends' => array(
			'tt_news' => '',
			'css_styled_content' => '',
			'typo3' => '4.1.0-4.7.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:16:{s:9:"ChangeLog";s:4:"a930";s:24:"class.tx_rgnewsce_fe.php";s:4:"fa58";s:21:"ext_conf_template.txt";s:4:"1975";s:12:"ext_icon.gif";s:4:"5e2a";s:17:"ext_localconf.php";s:4:"af44";s:20:"ext_tables-2.5.2.php";s:4:"3610";s:14:"ext_tables.php";s:4:"03e5";s:14:"ext_tables.sql";s:4:"8d0e";s:16:"locallang_be.xml";s:4:"7ea3";s:14:"doc/manual.sxw";s:4:"1674";s:16:"res/rgnewsce.css";s:4:"4830";s:44:"res/icons/icon_tt_news_ext_icon_extended.gif";s:4:"313d";s:47:"res/icons/icon_tt_news_ext_icon_extended__h.gif";s:4:"32a6";s:42:"res/icons/icon_tx_timtab_post_extended.gif";s:4:"70e7";s:20:"static/constants.txt";s:4:"9c38";s:16:"static/setup.txt";s:4:"87d5";}',
	'suggests' => array(
	),
);

?>