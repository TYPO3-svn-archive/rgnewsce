<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 Georg Ringer <http://www.ringer.it/>
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
 * Hook for the 'rgmediaimages' extension.
 *
 * @author	Georg Ringer <http://www.ringer.it/>
 */
class tx_rgnewsce_fe {

	function extraMediaProcessor($video, $config, $width, $height, &$pObj) {
		return $video;
	}

	// hook for tt_news
	function extraItemMarkerProcessor($markerArray, $row, $lConf, &$pObj) {
		$this->cObj = t3lib_div::makeInstance('tslib_cObj'); // local cObj.	
		$this->pObj = &$pObj;
		$this->myConf = $pObj->conf['rgnewsce.'];
		
		if ($pObj->conf['rgnewsce']==1) {
		
			// if there are any content elements available
			if ($row['tx_rgnewsce_ce']!='') {
				$ceOutput = array();
				
				$divList = explode(chr(10),$row['tx_rgnewsce_style']);
				

				$ceList = explode(',',$row['tx_rgnewsce_ce']);
				foreach ($ceList as $key=>$singleCEuid) {
						// render the single content element
						$ceConf =  array('tables' => 'tt_content','source' => $singleCEuid,'dontCheckPid' => 1);
						$ce = $pObj->cObj->RECORDS($ceConf);
						
						// add it to the array to output it later
						$cssParts = $divList[$key];
						$cssList = '';
						if ($cssParts!='') {
							$tmp =  explode(' ',$cssParts);
							foreach ($tmp as $key=>$value) {
       					$cssList.= 'rgnc'.$value.' ';
       				}
						}

						$ceOutput[] = '<div class="rgnc '.$cssList.'">'.$pObj->cObj->stdWrap($ce, $this->myConf['singleCe.']).'</div>';
	   		}
			
			      
				// loop through the content elements and search for a marker inside the news record which should be substituted
				foreach ($ceOutput as $key=>$singleCE) {
					$key++;
					$markerArray['###NEWS_CE_'.$key.'###'] = $singleCE;
					if (strpos($markerArray['###NEWS_CONTENT###'], '###NEWS_CE_'.$key.'###')) {
						// replace the marker with the video
						$markerArray['###NEWS_CONTENT###'] = str_replace('###NEWS_CE_'.$key.'###', $singleCE, $markerArray['###NEWS_CONTENT###']);
						// unset the video to prevent displaying it at the normal marker for the 2nd time 
						unset($ceOutput[$key--]);
					}
	
				}
				$markerArray['###NEWS_CE###'] = $pObj->cObj->stdWrap(implode('',$ceOutput), $this->myConf['ceWrapIfAny.']);
		 
	
			
			
			}
		} else {
			for ($i=0;$i<10 ;$i++ ) {
     		$markerArray['###NEWS_CONTENT###'] = str_replace('###NEWS_CE_'.$i.'###', '', $markerArray['###NEWS_CONTENT###']);
    	}
    	$markerArray['###NEWS_CE###'] = '';

		}
		
		return $markerArray;
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rgnewsce/class.tx_rgnewsce_fe.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rgnewsce/class.tx_rgnewsce_fe.php']);
}

?>
