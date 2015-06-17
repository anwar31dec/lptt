<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die();

class CthModuleHelper extends JModuleHelper {

    /**
     * Load published modules.
     *
     * @return  array
     *
     * @since   3.2
     */
    protected static function &load()
    {
        static $clean;

        if (isset($clean))
        {
            return $clean;
        }

        $app = JFactory::getApplication();
        $Itemid = $app->input->getInt('Itemid');
        $user = JFactory::getUser();
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $lang = JFactory::getLanguage()->getTag();
        $clientId = (int) $app->getClientId();

        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('m.id, m.title, m.module, m.position, m.content, m.showtitle, m.params, mm.menuid')
            ->from('#__modules AS m')
            ->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = m.id')
            ->where('m.published = 1')

            ->join('LEFT', '#__extensions AS e ON e.element = m.module AND e.client_id = m.client_id')
            ->where('e.enabled = 1');

        $date = JFactory::getDate();
        $now = $date->toSql();
        $nullDate = $db->getNullDate();
        $query->where('(m.publish_up = ' . $db->quote($nullDate) . ' OR m.publish_up <= ' . $db->quote($now) . ')')
            ->where('(m.publish_down = ' . $db->quote($nullDate) . ' OR m.publish_down >= ' . $db->quote($now) . ')')

            ->where('m.access IN (' . $groups . ')')
            ->where('m.client_id = ' . $clientId);
            //->where('(mm.menuid = ' . (int) $Itemid . ' OR mm.menuid <= 0)');

        // Filter by language
        if ($app->isSite() && $app->getLanguageFilter())
        {
            $query->where('m.language IN (' . $db->quote($lang) . ',' . $db->quote('*') . ')');
        }

        $query->order('m.position, m.ordering');

        // Set the query
        $db->setQuery($query);
        $clean = array();

        try
        {
            $modules = $db->loadObjectList();
        }
        catch (RuntimeException $e)
        {
            JLog::add(JText::sprintf('JLIB_APPLICATION_ERROR_MODULE_LOAD', $e->getMessage()), JLog::WARNING, 'jerror');

            return $clean;
        }

        // Apply negative selections and eliminate duplicates
        $negId = $Itemid ? -(int) $Itemid : false;
        $dupes = array();

        for ($i = 0, $n = count($modules); $i < $n; $i++)
        {
            $module = &$modules[$i];

            // The module is excluded if there is an explicit prohibition
            $negHit = ($negId === (int) $module->menuid);

            if (isset($dupes[$module->id]))
            {
                // If this item has been excluded, keep the duplicate flag set,
                // but remove any item from the cleaned array.
                if ($negHit)
                {
                    unset($clean[$module->id]);
                }

                continue;
            }

            $dupes[$module->id] = true;

            // Only accept modules without explicit exclusions.
            if (!$negHit)
            {
                $module->name = substr($module->module, 4);
                $module->style = null;
                $module->position = strtolower($module->position);
                $clean[$module->id] = $module;
            }
        }

        unset($dupes);

        // Return to simple indexing that matches the query order.
        $clean = array_values($clean);

        return $clean;
    }

}