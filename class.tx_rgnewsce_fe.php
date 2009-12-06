<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) Georg Ringer <http://www.ringer.it/>
 *  (c) Krystian Szymukowicz <http://www.prolabium.com/>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * Hook for the 'rgnewsce' extension.
 *
 * @author	Georg Ringer <http://www.ringer.it/>, Krystian Szymukowicz <http://www.prolabium.com/>
 */

class tx_rgnewsce_fe {

	function extraItemMarkerProcessor($markerArray, $row, $lConf, &$pObj) {

		if( $pObj->conf['rgnewsce.']['enable'] == 1 ){

			if( $pObj->config['code'] == 'LIST' || $pObj->config['code'] == 'LATEST' || $pObj->config['code'] == 'SINGLE') {

				//make $imgList - it will be needed in LIST, LATEST and SINGLE
				if(t3lib_extMgm::isLoaded('dam_ttnews')){

					if($row['_LOCALIZED_UID']){
						$recordUid = $row['_LOCALIZED_UID'];
					} else {
						$recordUid = $row['uid'];
					}
					$damImgList = tx_dam_db::getReferencedFiles('tt_news', $recordUid, 'tx_damnews_dam_images');


					$imgList = implode(',',$damImgList['files']);

					if($imgList) {
						//clear imgPath if DAM is on and dam images found
						$pObj->conf['rgnewsce.']['displaySingle.']['csc-imagetxt.']['20.']['imgPath'] = '';
					} else {
						// if there is no dam images show standard images
						$imgList = $row['image'];
					}
				} else {
					$imgList = $row['image'];
				}

				$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['rgnewsce']);

				// only if SINGLE VIEW or LATEST/LIST VIEW but then plugin.tt_news.renderSingleInListLatest must be set to 1
				if( ($pObj->config['code'] == 'SINGLE' || $pObj->conf['rgnewsce.']['renderSingleInListAndLatest']) ){

					// forceFirstImageIsPreview option
					$imgListSingle = $imgList;
					if( $imgListSingle && ($GLOBALS['TSFE']->tmpl->setup['plugin.']['tt_news.']['forceFirstImageIsPreview'] || $GLOBALS['TSFE']->tmpl->setup['plugin.']['tt_news.']['firstImageIsPreview'])) {

						$imgListSingle = t3lib_div::trimExplode(',', $imgListSingle);
						if( !(count($imgListSingle) === 1 && $GLOBALS['TSFE']->tmpl->setup['plugin.']['tt_news.']['firstImageIsPreview']) ) {

							$imagecaption = t3lib_div::trimExplode(',', $row['imagecaption']);
							$alttext = t3lib_div::trimExplode(',', $row['alttext']);
							$titletext = t3lib_div::trimExplode(',', $row['titletext']);

							array_shift($imgListSingle);
							array_shift($imagecaption);
							array_shift($alttext);
							array_shift($titletext);

							$imagecaption = is_array($imagecaption)? implode(',', $imagecaption) : '';
							$alttext =  is_array($alttext)? implode(',', $alttext) : '';
							$titletext =  is_array($titletext)? implode(',', $titletext) : '';
						}
						$imgListSingle = is_array($imgListSingle)? implode(',', $imgListSingle) : '';
					}

					$content = '';
	
					if( $row['type'] == 0 && $pObj->conf['rgnewsce.']['displaySingle.']['renderWithCssStyledContent'] ){

						$this->local_cObj = t3lib_div::makeInstance('tslib_cObj');

						// reender only when there is at least image or bodytext
						if($imgListSingle || $row['bodytext']) {

							$ce_row['bodytext'] = $row['bodytext'];

							if( $imgListSingle ) {

								$ce_row['image'] = $imgListSingle;
								$ce_row['imagecaption'] = $row['imagecaption'];
								$ce_row['alttext'] = $row['imagealttext'];
								$ce_row['titletext'] = $row['imagetitletext'];								 
								$ce_row['imagewidth'] = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tt_news.']['displaySingle.']['image.']['file.']['maxW'];
								$ce_row['imageorient'] = $pObj->conf['rgnewsce.']['displaySingle.']['image.']['imageorient'];
								$ce_row['imagecols'] = $pObj->conf['rgnewsce.']['displaySingle.']['image.']['imagecols'];
								$ce_row['imageborder'] = $pObj->conf['rgnewsce.']['displaySingle.']['image.']['imageborder'];
								$ce_row['image_zoom'] = $pObj->conf['rgnewsce.']['displaySingle.']['image.']['image_zoom'];
								$object = $pObj->conf['rgnewsce.']['displaySingle.']['csc-imagetxt'];
								$config = $pObj->conf['rgnewsce.']['displaySingle.']['csc-imagetxt.'];

							} else {
								//$pObj->conf['rgnewsce.']['displaySingle.']['csc-imagetxt.']['20'] = '>';
								//$pObj->conf['rgnewsce.']['displaySingle.']['csc-imagetxt.']['20'] = '< tt_content.text.20';
								$object = $pObj->conf['rgnewsce.']['displaySingle.']['csc-txt'];
								$config = $pObj->conf['rgnewsce.']['displaySingle.']['csc-txt.'];
							}

							$this->local_cObj->start($ce_row, 'tt_content');
							$content = $this->local_cObj->cObjGetSingle($object, $config);
						}
					}

					// render CE
					$content_ce = '';
					if(	( ($row['type'] == 0 && $confArr['extraTabForTTContent'] == 1) || $row['type'] == 4	|| $row['type'] == 5) && $row['tx_rgnewsce_ce'] ) {

						$this->local_cObj = t3lib_div::makeInstance('tslib_cObj');

						$ceList = explode(',', $row['tx_rgnewsce_ce']);
						foreach ($ceList as $key => $singleCEuid) {
							// render the single content element
							$ceConf =  array('tables' => 'tt_content', 'source' => $singleCEuid, 'dontCheckPid' => 1);
							$content_ce .= $this->local_cObj->RECORDS($ceConf);
						}
					}

					if($content || $content_ce){
						if($pObj->conf['rgnewsce.']['displaySingle.']['renderWithCssStyledContent']) {
							$markerArray['###NEWS_CONTENT###'] = $content . $content_ce;
							$markerArray['###NEWS_IMAGE###'] = '';
						} else {
							$markerArray['###NEWS_CONTENT###'] .=  $content_ce;
						}
					}
				} // end of render SINGLE


				// only if LIST VIEW
				if( $pObj->config['code'] == 'LIST' || $pObj->config['code'] == 'LATEST' ){

					$this->local_cObj = t3lib_div::makeInstance('tslib_cObj');
						
					if($row['tx_rgnewsce_ce']){

						$where = ' uid IN('.$row['tx_rgnewsce_ce'].') AND deleted = 0 AND hidden = 0';
						$ce_rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid, bodytext, image', 'tt_content', $where, '', ' FIELD(uid, '.$row['tx_rgnewsce_ce'] .')');
						
						// if there is no standard bodytext then get it from CE
						if(!strlen($row['bodytext'])) {

							// get ###NEWS_SUBHEADER### marker in LIST VIEW
							foreach($ce_rows as $ce_row){
								//get first content element with non empty bodytext and format it with tt_news TS
								if(strlen($ce_row['bodytext'])){
									$pObj->local_cObj->data['bodytext'] = $ce_row['bodytext'];
									$markerArray['###NEWS_SUBHEADER###'] = $pObj->formatStr($pObj->local_cObj->stdWrap($row['short'], $lConf['subheader_stdWrap.']));
									break;
								}
							}
						}
						
						// get ###NEWS_IMAGE###' marker in LIST VIEW
						if( ($row['type'] == 4 || $row['type'] == 5 || $confArr['extraTabForTTContent'] == 1)  && !$imgList ) {

							$gotImage = 0;
							foreach($ce_rows as $ce_row){
								//get first content element with non empty "image" field and format it with tt_news TS for image
								if(t3lib_extMgm::isLoaded('dam_ttcontent')){
									$damImgList = tx_dam_db::getReferencedFiles('tt_content', $ce_row['uid'], 'tx_damttcontent_files');
									if(count($damImgList['files'])){
										$lConf['image.']['file'] = array_shift($damImgList['files']);
										$gotImage = 1;
									}
								} else {
									if(strlen($ce_row['image'])){
										$images = explode(',' , $ce_row['image']);
										$lConf['image.']['file'] = 'uploads/pics/' . $images[0];
										$gotImage = 1;
									}
								}

								if($gotImage) {
									$theImgCode = $this->local_cObj->IMAGE($lConf['image.']);
									$markerArray['###NEWS_IMAGE###'] = $this->local_cObj->wrap(trim($theImgCode), $lConf['imageWrapIfAny']);
									break;
								}

							}
							//no images in existing content elements - fire noImage_stdWrap.
							if(!$gotImage){
								$markerArray['###NEWS_IMAGE###'] = $this->local_cObj->stdWrap($markerArray['###NEWS_IMAGE###'],$lConf['image.']['noImage_stdWrap.']);
							}

						} else {
							// no content elements - fire noImage_stdWrap.
							$markerArray['###NEWS_IMAGE###'] = $this->local_cObj->stdWrap($markerArray['###NEWS_IMAGE###'],$lConf['image.']['noImage_stdWrap.']);
						}
					}
				} // only LIST

			} // list, latest, single mode

		} //rgnewsce.enable = 1

		return $markerArray;

	} //function
} //class


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rgnewsce/class.tx_rgnewsce_fe.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rgnewsce/class.tx_rgnewsce_fe.php']);
}

?>
