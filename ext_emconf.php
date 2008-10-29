<?php

########################################################################
# Extension Manager/Repository config file for ext: "rgnewsce"
#
# Auto generated 29-10-2008 16:34
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Content elements in tt_news',
	'description' => 'This extension allows to add any number of any content elements to a news record.
	Additionally it allows to divide tt_news fields into more tabs and to format standard news text and images using css_styled_content.
	Some screenshots: http://forge.typo3.org/wiki/extension-rgnewsce/Quick_screenshots
	Screencast (a liitle bit old): http://screencast.com/t/epJcCG0cpsk
	Full project documentation: http://forge.typo3.org/wiki/extension-rgnewsce',
	'category' => 'fe',
	'author' => 'Georg Ringer / Krystian Szymukowicz',
	'author_email' => 'http://www.ringer.it - http://typo3.prolabium.com',
	'shy' => '',
	'dependencies' => 'tt_news,css_styled_content',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.4',
	'constraints' => array(
		'depends' => array(
			'tt_news' => '',
			'css_styled_content' => '',
			'typo3' => '4.1.0-4.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:15:{s:9:"ChangeLog";s:4:"226e";s:24:"class.tx_rgnewsce_fe.php";s:4:"4984";s:21:"ext_conf_template.txt";s:4:"3bb9";s:12:"ext_icon.gif";s:4:"5e2a";s:17:"ext_localconf.php";s:4:"af44";s:14:"ext_tables.php";s:4:"ad26";s:14:"ext_tables.sql";s:4:"8d0e";s:16:"locallang_db.xml";s:4:"61cd";s:14:"doc/manual.sxw";s:4:"6fda";s:16:"res/rgnewsce.css";s:4:"8eec";s:44:"res/icons/icon_tt_news_ext_icon_extended.gif";s:4:"313d";s:47:"res/icons/icon_tt_news_ext_icon_extended__h.gif";s:4:"32a6";s:42:"res/icons/icon_tx_timtab_post_extended.gif";s:4:"70e7";s:20:"static/constants.txt";s:4:"4a84";s:16:"static/setup.txt";s:4:"87d5";}',
	'suggests' => array(
	),
);

?>