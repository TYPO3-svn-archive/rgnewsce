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

		$this->local_cObj = t3lib_div::makeInstance('tslib_cObj'); // Local cObj

		// only if SINGLE VIEW
		if($pObj->config['code'] == 'SINGLE'){

			$content = '';
			if ($row['tx_rgnewsce_ce'] != '') {
				$ceList = explode(',',$row['tx_rgnewsce_ce']);
				foreach ($ceList as $key=>$singleCEuid) {
					// render the single content element
					$ceConf =  array('tables' => 'tt_content','source' => $singleCEuid,'dontCheckPid' => 1);
					$content .= $this->local_cObj->RECORDS($ceConf);
				}
			}
			$markerArray['###NEWS_CONTENT###'] = $content;
			$markerArray['###NEWS_IMAGE###'] = '';
		}

		// only if LIST VIEW
		if($pObj->config['code'] == 'LIST' || $pObj->config['code'] == 'LATEST'){

			// get all content element of the news record
			$get_ce = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('tx_rgnewsce_ce','tt_news',' uid='.$row['uid']. ' AND deleted = 0 AND hidden = 0');
			$where = ' uid IN('.$get_ce[0]['tx_rgnewsce_ce'].') AND deleted = 0 AND hidden = 0';
			$ce_rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,bodytext,image','tt_content',$where,'','');

			// check if there is any content and register to use in TS
			if(count($ce_rows) || strlen($row['bodytext'])){
				$this->local_cObj->LOAD_REGISTER(array('bodytext' => '1'),'');
			}


			// get ###NEWS_SUBHEADER### marker in LIST VIEW
			if(!strlen($row['short'])){
					
				foreach($ce_rows as $ce_row){
					//get first content element with non empty bodytext and format it with tt_news TS
					if(strlen($ce_row['bodytext'])){
						$markerArray['###NEWS_SUBHEADER###'] = $pObj->formatStr($this->local_cObj->stdWrap($ce_row['bodytext'], $lConf['subheader_stdWrap.']));
						break;
					}
				}
			}else{
				$markerArray['###NEWS_SUBHEADER###'] = $pObj->formatStr($this->local_cObj->stdWrap($row['short'], $lConf['subheader_stdWrap.']));
			}

				
			// get ###NEWS_IMAGE###' marker in LIST VIEW
			if(count($ce_rows)){
				$gotImage = 0;
				foreach($ce_rows as $ce_row){
					//get first content element with non empty "image" field and format it with tt_news TS for image
					if(t3lib_extMgm::isLoaded('dam_ttcontent')){
						$damImgList = tx_dam_db::getReferencedFiles('tt_content', $ce_row['uid'], 'tx_damttcontent_files');
						if(count($damImgList)){
							$lConf['image.']['file'] = array_shift($damImgList['files']);
							$gotImage = 1;
						}
					}else{
						if(strlen($ce_row['image'])){
							$images = explode(',' , $ce_row['image']);
							$lConf['image.']['file'] = 'uploads/pics/' . $images[0];
							$gotImage = 1;
						}
					}

					if($gotImage){
						$theImgCode = $this->local_cObj->IMAGE($lConf['image.']);
						$markerArray['###NEWS_IMAGE###'] = $this->local_cObj->wrap(trim($theImgCode), $lConf['imageWrapIfAny']);
					}
					break;
				}
				//no images in existing content elements - fire noImage_stdWrap.
				if(!$gotImage){
					$markerArray['###NEWS_IMAGE###'] = $this->local_cObj->stdWrap($markerArray['###NEWS_IMAGE###'],$lConf['image.']['noImage_stdWrap.']);
				}
			}else{
				// no content elements - fire noImage_stdWrap.
				$markerArray['###NEWS_IMAGE###'] = $this->local_cObj->stdWrap($markerArray['###NEWS_IMAGE###'],$lConf['image.']['noImage_stdWrap.']);
			}
		}

		return $markerArray;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rgnewsce/class.tx_rgnewsce_fe.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rgnewsce/class.tx_rgnewsce_fe.php']);
}

?>
