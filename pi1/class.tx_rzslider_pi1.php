<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Raphael Zschorsch <rafu1987@gmail.com>
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');

/**
 * Plugin 'jQuery Coda-Slider' for the 'rzslider' extension.
 *
 * @author	Raphael Zschorsch <rafu1987@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_rzslider
 */
 
class tx_rzslider_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_rzslider_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_rzslider_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'rzslider';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	 
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		    		
		// Read Flexform	
    $this->pi_initPIflexForm();
    $ce = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'ce', 'sDEF');
    $headlines = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'headlines', 'sDEF');
    $onlytabheadlines = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'onlytabheadlines', 'sDEF');
    $position = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'position', 'options');
    $autoslide = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'autoslide', 'options');
    $autoSlideInterval = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'autoslideinterval', 'options');
    $autoSlideStopWhenClicked = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'autoslidestopwhenclicked', 'options');
    $dynamictabs = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'dynamictabs', 'options');
    $dynamicTabsPosition = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'dynamictabsposition', 'options');
    $dynamicTabsAlign = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'dynamictabsalign', 'options');
    $dynamicarrows = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'dynamicarrows', 'options');
    $slideEaseFunction = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'slideeasefunction', 'options');
    $slideEaseDuration = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'slideeaseduration', 'options');
    $autoheight = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'autoheight', 'options');
    $autoHeightEaseFunction = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'autoheighteasefunction', 'options');
    $autoHeightEaseDuration = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'autoheighteaseduration', 'options');    
    $ce_explode = explode(",",$ce); 
    
    if($dynamictabs != '0') {
      $tabs = 'dynamicTabsPosition: "'.$dynamicTabsPosition.'",
        dynamicTabsAlign: "'.$dynamicTabsAlign.'",
      ';
    }
    
    if($autoslide != '0') {
      $interval = 'autoSlideInterval: '.$autoSlideInterval.',
        autoSlideStopWhenClicked: '.$autoSlideStopWhenClicked.',';
    }
    
    if(empty($slideEaseDuration)) {
      $slideEaseDuration = $conf['slideEaseDuration'];
      if(empty($slideEaseDuration)) {
        $slideEaseDuration = '1000';
      }
    }
    
    if(empty($autoHeightEaseDuration)) {
      $autoHeightEaseDuration = $conf['autoHeightEaseDuration'];
      if(empty($autoHeightEaseDuration)) {
        $autoHeightEaseDuration = '1000';  
      }
    }
    
    if(!empty($conf['slideEaseFunction'])) {
      $slideEaseFunction = $conf['slideEaseFunction'];
    }
    
    if(!empty($conf['autoHeightEaseFunction'])) {
      $autoHeightEaseFunction = $conf['autoHeightEaseFunction'];
    }
    
    if($autoheight == '1') {
      $autoheight_js = 'autoHeightEaseFunction: "'.$autoHeightEaseFunction.'",
        autoHeightEaseDuration: '.$autoHeightEaseDuration.', ';
    }
    
    // CE ID
    $ce_id = $this->cObj->data['uid'];
    
    // Count of items
    $i = $this->getItems();
    
    // Set the position
    if($position == '') {
      $position = '1';
    }
    else if($position == '0') {
      $position = mt_rand(1, $i);
    }
    else {
      $position = $position;
    }
          
    // Add JS
    $GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] .= '
      <script type="text/javascript"> 
  		$().ready(function() {
  			$(\'#coda-slider-'.$ce_id.'\').codaSlider({
  			  crossLinking: false,
          firstPanelToLoad: '.$position.',
          autoSlide: '.$autoslide.',
          dynamicTabs: '.$dynamictabs.',
          dynamicArrows: '.$dynamicarrows.',
          '.$tabs.'
          '.$interval.'
          dynamicArrowLeftText: "'.$this->pi_getLL('dynamicarrowlefttext').'",
          dynamicArrowRightText: "'.$this->pi_getLL('dynamicarrowrighttext').'",
          slideEaseFunction: "'.$slideEaseFunction.'",
          slideEaseDuration: '.$slideEaseDuration.',
          '.$autoheight_js.'          
        });
  		});
  	 </script> 
    ';
              
    // Process CE's
    // Counter
    $i = 0;
    
    // Headlines
    if($onlytabheadlines == '1') {
      $style = 'style="display:none;"';
    }
    else {
      $style = '';
    }
    
    $headlines_explode = explode("\n",$headlines);
    foreach($ce_explode as $c) {
      // Output the CE's    
      $ce_conf = array('tables' => 'tt_content','source' => $c,'dontCheckPid' => 1);
      $row = $this->pi_getRecord('tt_content', $c, 0);      
      // Get the Flexform headlines
      $headline = $headlines_explode[$i];
      if(empty($headline)) {
        // If Flexform is empty, show CE headline
        $headline = $row['header'];
      }                           
      $ce_output .= '
        <div class="panel">
          <div class="panel-wrapper">
            <h2 class="title" '.$style.'>'.$headline.'</h2>
      ';
      $ce_output .= $this->cObj->RECORDS($ce_conf);
      $ce_output .= '
          </div>
        </div>
      ';
      
      $i++;
    }
    	
		$content .= '
      <div class="coda-slider-wrapper">
        <div class="coda-slider preload" id="coda-slider-'.$ce_id.'"> 
          '.$ce_output.'
        </div>
      </div>
    
		';
	
		return $this->pi_wrapInBaseClass($content);
	}
	
	function getItems() {
  	// Read Flexform	
    $this->pi_initPIflexForm();
    $ce = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'ce', 'sDEF');
    $ce_explode = explode(",",$ce);  
    
    $count = count($ce_explode);
    return $count;
  }
}     

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rzslider/pi1/class.tx_rzslider_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rzslider/pi1/class.tx_rzslider_pi1.php']);
}

?>