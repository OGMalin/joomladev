<?php
/**
 * @package     Alarmhistory for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class AlarmhistoryModelSections extends JModelList
{
 	public function __construct($config = array())
 	{
 		if (empty($config['filter_fields']))
 		{
 			$config['filter_fields'] = array(
 					'id', 'a.id',
 					'title', 'a.title',
 					'SEC1', 'a.SEC1',
 					'SEC2', 'a.SEC2',
 					'SEC3', 'a.SEC3',
 			);
 		}

 		parent::__construct($config);
 	}

 	protected function populateState($ordering = null, $direction = null)
 	{
// 		$app = JFactory::getApplication('administrator');
		
// 		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
// 		$this->setState('filter.search', $search);

// 		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
// 		$this->setState('filter.state', $published);

// 		$params = JComponentHelper::getParams('com_alarmhistory');
// 		$this->setState('params', $params);
		
 		parent::populateState('a.title', 'asc');
 	}

// 	protected function getStoreId($id = '')
// 	{
// 		// Compile the store id.
// 		$id.= ':' . $this->getState('filter.search');
// 		$id.= ':' . $this->getState('filter.state');

// 		return parent::getStoreId($id);
// 	}
	
	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__alarmhistory_section') . ' AS a');
		
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		
		return $query;
	}

// 	public function getItems()
// 	{
// 		$items = parent::getItems();

// 		return $items;
// 	}
}
