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

class AlarmhistoryModelSites extends JModelList
{
 	public function __construct($config = array())
 	{
 		if (empty($config['filter_fields']))
 		{
 			$config['filter_fields'] = array(
 					'id', 'a.id',
 					'title', 'a.title',
 					'LOCATION', 'a.LOCATION',
 					'section', 'a.section',
 					'ordering', 'a.ordering'
 			);
 		}

 		parent::__construct($config);
 	}

 	protected function populateState($ordering = null, $direction = null)
 	{
 		parent::populateState('a.ordering', 'asc');
 	}
 	
 	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__alarmhistory_site') . ' AS a');
		
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		
		return $query;
	}

}
