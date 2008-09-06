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

		// only if SINGLE VIEW
		if($pObj->config['code'] == 'SINGLE'){

			$this->local_cObj = t3lib_div::makeInstance('tslib_cObj'); // Local cObj

			if ($row['tx_rgnewsce_ce'] && $row['type'] == 3) {
				$content = '';
				$ceList = explode(',',$row['tx_rgnewsce_ce']);
				foreach ($ceList as $key=>$singleCEuid) {
					// render the single content element
					$ceConf =  array('tables' => 'tt_content','source' => $singleCEuid,'dontCheckPid' => 1);
					$content .= $this->local_cObj->RECORDS($ceConf);
				}
				$markerArray['###NEWS_CONTENT###'] = $content;
				$markerArray['###NEWS_IMAGE###'] = '';
			}

		}

		// only if LIST VIEW
		if($pObj->config['code'] == 'LIST' || $pObj->config['code'] == 'LATEST'){

			$this->local_cObj = t3lib_div::makeInstance('tslib_cObj'); // Local cObj

			if($row['type'] == 3){

				if($row['tx_rgnewsce_ce']){
					$where = ' uid IN('.$row['tx_rgnewsce_ce'].') AND deleted = 0 AND hidden = 0';
					$ce_rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid, bodytext, image', 'tt_content', $where, '', '');

					// get ###NEWS_SUBHEADER### marker in LIST VIEW
					foreach($ce_rows as $ce_row){
						//get first content element with non empty bodytext and format it with tt_news TS
						if(strlen($ce_row['bodytext'])){
							$pObj->local_cObj->data['bodytext'] = $ce_row['bodytext'];
							$markerArray['###NEWS_SUBHEADER###'] = $pObj->formatStr($pObj->local_cObj->stdWrap($row['short'], $lConf['subheader_stdWrap.']));
							break;
						}
					}

					// get ###NEWS_IMAGE###' marker in LIST VIEW
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
		return $markerArray;
	} // function extraItemMarkerProcessor
} //class

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rgnewsce/class.tx_rgnewsce_fe.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rgnewsce/class.tx_rgnewsce_fe.php']);
}

?>
