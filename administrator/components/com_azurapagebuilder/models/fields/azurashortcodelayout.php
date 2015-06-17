<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');

/**
 * Form Field to display a list of the layouts for shortcode display from the plugin or template overrides.
 *
 * @package     Joomla.Legacy
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldAzuraShortcodelayout extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'AzuraShortcodelayout';

	/**
	 * Method to get the field input for module layouts.
	 *
	 * @return  string  The field input.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		//return '<input type="text" name="shortcode" />';
		// Get the client id.
		$clientId = $this->element['client_id'];

		if (is_null($clientId) && $this->form instanceof JForm)
		{
			$clientId = $this->form->getValue('client_id');
		}
		$clientId = (int) $clientId;

		$client = JApplicationHelper::getClientInfo($clientId);

		// Get the shortcode.
		$shortcode = (string) $this->element['shortcode'];

		if (empty($shortcode) && ($this->form instanceof JForm))
		{
			$shortcode = $this->form->getValue('shortcode');
		}

		$shortcode = preg_replace('#\W#', '', $shortcode);

		// Get the template.
		$template = (string) $this->element['template'];
		$template = preg_replace('#\W#', '', $template);

		// Get the style.
		if ($this->form instanceof JForm)
		{
			$template_style_id = $this->form->getValue('template_style_id');
		}

		$template_style_id = preg_replace('#\W#', '', $template_style_id);

		// If an extension and view are present build the options.
		if ($shortcode && $client)
		{

			// Load language file
			// $lang = JFactory::getLanguage();
			// $lang->load($module . '.sys', $client->path, null, false, true)
			// 	|| $lang->load($module . '.sys', $client->path . '/modules/' . $module, null, false, true);

			// Get the database object and a new query object.
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			// Build the query.
			$query->select('element, name')
				->from('#__extensions as e')
				->where('e.client_id = ' . (int) $clientId)
				->where('e.type = ' . $db->quote('template'))
				->where('e.enabled = 1');

			if ($template)
			{
				$query->where('e.element = ' . $db->quote($template));
			}

			if ($template_style_id)
			{
				$query->join('LEFT', '#__template_styles as s on s.template=e.element')
					->where('s.id=' . (int) $template_style_id);
			}

			// Set the query and load the templates.
			$db->setQuery($query);
			$templates = $db->loadObjectList('element');

			// Build the search paths for shortcode layouts.
			$shortcode_path = JPath::clean($client->path . '/plugins/system/cthshortcodes/shortcodes_template');

			// Prepare array of shortcode layouts
			$shortcode_layouts = array();

			$template_layouts = array();

			// Prepare the grouped list
			$groups = array();

			// Loop on all templates
			if ($templates)
			{
				foreach ($templates as $template)
				{
					// Load language file
					//$lang->load('tpl_' . $template->element . '.sys', $client->path, null, false, true)
					//	|| $lang->load('tpl_' . $template->element . '.sys', $client->path . '/templates/' . $template->element, null, false, true);

					$template_path = JPath::clean($client->path . '/templates/' . $template->element . '/html/com_azurapagebuilder/plugin/shortcodes_template');

					// Add the layout options from the template path.
					if (is_dir($template_path) && ($template_layouts = JFolder::files($template_path, '^'.$shortcode.'([-]+[^_]*\.php|\.php)$')))
					{
						/*foreach ($files as $i => $file)
						{
							// Remove layout that already exist in shortcode ones
							if (in_array($file, $shortcode_layouts))
							{
								unset($files[$i]);
							}
						}*/

						// if (count($template_layouts))
						// {
							// Create the group for the template
							$groups[$template->element] = array();
							$groups[$template->element]['id'] = $this->id . '_' . $template->element;
							$groups[$template->element]['text'] = JText::sprintf('JOPTION_FROM_TEMPLATE', $template->name);
							$groups[$template->element]['items'] = array();

							foreach ($template_layouts as $file)
							{
								// Add an option to the template group
								$value = basename($file, '.php');
								$text = $value;
								$groups[$template->element]['items'][] = JHtml::_('select.option', $template->element . ':' . $value, $text);
							}
						//}
					}
				}
			}


			// Add the layout options from the shortcode path.
			if (is_dir($shortcode_path) && ($shortcode_layouts = JFolder::files($shortcode_path, '^'.$shortcode.'([-]+[^_]*\.php|\.php)$')))
			{
				if(count($template_layouts)){
					foreach ($shortcode_layouts as $i => $file)
					{
						// Remove layout that already exist in shortcode ones
						if (in_array($file, $template_layouts))
						{
							unset($shortcode_layouts[$i]);
						}
					}

				}
				if (count($shortcode_layouts))
				{

					// Create the group for the shortcode
					$groups['_'] = array();
					$groups['_']['id'] = $this->id . '__';
					$groups['_']['text'] = JText::sprintf('JOPTION_FROM_SHORTCODE', $shortcode);
					$groups['_']['items'] = array();

					foreach ($shortcode_layouts as $file)
					{
						// Add an option to the shortcode group
						$value = basename($file, '.php');
						$text = $value;
						$groups['_']['items'][] = JHtml::_('select.option', '_:' . $value, $text);
					}
				}
			}

			
			// Compute attributes for the grouped list
			$attr = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

			// Prepare HTML code
			$html = array();

			// Compute the current selected values
			$selected = array($this->value);

			// Add a grouped list
			$html[] = JHtml::_(
				'select.groupedlist', $groups, $this->name,
				array('id' => $this->id, 'group.id' => 'id', 'list.attr' => $attr, 'list.select' => $selected)
			);

			return implode($html);
		}
		else
		{

			return '';
		}
	}
}
