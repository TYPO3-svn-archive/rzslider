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



class tx_rzslider_dbrelation {



  function dbselect($PA, $config) {

    global $LANG; 

    $LL = $this->includeLocalLang(); 

      // Get the extConf values

      $this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['rzslider']);

      $pid = $this->extConf['storage_folder'];

      if(empty($pid)) {

        $pid = '###CURRENT_PID###';

      }

      $table = 'tt_content';

                                        

      // Get the old value to set into the field

      $flexform = t3lib_div::xml2array($PA['row']['pi_flexform']);

      $flexOld = (is_array($flexform)) ? $flexform['data']['sDEF']['lDEF']['ce']['vDEF'] : '';

      

      // Configuration of the field (TCA)

      $config = Array(

        'fieldConf' => Array(

          'label' => 'Content Elements',

          'config' => Array(

            'type' => 'group',

            'internal_type' => 'db',

            'allowed' => $table,

            'show_thumbs' => 1,

            'size' => 10,

            'autoSizeMax' => 20,

            'maxitems' => 100,

            'minitems' => 0,

            'wizards' => Array(

              'add' => Array(

                'type' => 'script',

                'title' => $LANG->getLLL('pi1_add',$LL),

                'icon' => 'add.gif',

                'params' => Array(

                  'table' => $table,

                  'pid' => $pid,

                  'setValue' => 'prepend',

                ),

                'script' => 'wizard_add.php'

              ),

            ),

          ),

        ),

        'onFocus' => '',

        'label' => 'Plugin Options',

        'itemFormElValue' => $flexOld,

        'itemFormElName' => 'data[tt_content]['.$PA['row']['uid'].'][pi_flexform][data][sDEF][lDEF][ce][vDEF]',

        'itemFormElName_file' => 'data_files[tt_content]['.$PA['row']['uid'].'][pi_flexform][data][sDEF][lDEF][ce][vDEF]'

      );



      // Create the element

      $form = t3lib_div::makeInstance('t3lib_TCEforms');

      $form->initDefaultBEMode();

      $form->backPath = $GLOBALS['BACK_PATH'];



      $element = $form->getSingleField_typeGroup( 'tt_content', 'pi_flexform',$PA['row'], $config );

      return $form->printNeededJSFunctions_top().$element.$form->printNeededJSFunctions();      

  } 

  // Include LocalLang         

  function includeLocalLang()	{

    $llFile = t3lib_extMgm::extPath('rzslider').'locallang.xml';

    $LOCAL_LANG = t3lib_div::readLLXMLfile($llFile, $GLOBALS['LANG']->lang);

  

    return $LOCAL_LANG;

  }            

}



if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS']['XCLASS']['ext/rzslider/class.tx_rzslider_dbrelation.php'])	{

  include_once($GLOBALS['TYPO3_CONF_VARS']['XCLASS']['ext/rzslider/class.tx_rzslider_dbrelation.php']);

}



?>