 <?php
 /**
 * @package Hoxa - Responsive Multipurpose Joomla Template
 * @author Cththemes - www.cththemes.com
 * @date: 01-10-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
 // No direct access
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.form.formfield');
 
 /**
  * Book form field class
  */
 class JFormFieldColorReset extends JFormField
 {
        /**
         * field type
         * @var string
         */
        protected $type = 'ColorReset';


        /**
   * Method to get the field input markup
   */
  protected function getInput()
  {
      $doc = JFactory::getDocument();
  
  			$scripts = array();

        $scripts[] = 'jQuery(document).ready(function($){';
            $scripts[] = '$(\'#resetColorButton\').click(function(){';
                $scripts[] = '$(\'#jform_params_baseColor\').val(\'#13AFEB\');';
                // $scripts[] = '$(\'#jform_params_bodyColor\').val(\'#666666\');';
                // $scripts[] = '$(\'#jform_params_headingColor\').val(\'#000000\');';
                // $scripts[] = '$(\'#jform_params_paragraphColor\').val(\'#999999\');';

                // $scripts[] = '$(\'#jform_params_whiteTextColor\').val(\'#ffffff\');';
                // $scripts[] = '$(\'#jform_params_headerColor\').val(\'#000000\');';
                // $scripts[] = '$(\'#jform_params_borderColor\').val(\'#dddddd\');';
                // $scripts[] = '$(\'#jform_params_yellowClassColor\').val(\'#ffda3a\');';
                // $scripts[] = '$(\'#jform_params_purpleClassColor\').val(\'#a085c6\');';
                // $scripts[] = '$(\'#jform_params_blueClassColor\').val(\'#4cc3e9\');';
                // $scripts[] = '$(\'#jform_params_redishClassColor\').val(\'#C71C77\');';
            $scripts[] = '});';
        $scripts[] = '});';


        $doc->addScriptDeclaration(implode("\n", $scripts));

$html[] = '<a role="button" id="resetColorButton" class="btn btn-warning">Reset Colors</a>';
 
 
       
 
          return implode("\n", $html);
  }
 
 }