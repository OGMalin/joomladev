<?php
/**
 * @version     $Id$
 * @package     Joomla.Admin
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

class AlarmhistoryModelSections extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
					'id', 'a.id',
					'title', 'a.title',
					'alias', 'a.alias',
					'SEC1', 'a.SEC1'
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = 'a.title', $direction = 'asc')
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $published);

		parent::populateState($ordering, $direction);
	}

	protected function getListQuery()
	{
		$db    = $this->getDbo();
		$query  = $db->getQuery(true);

		$query->select(
				$this->getState(
						'list.select',
						'a.id, a.title, a.alias, a.SEC1'
				)
		);
		$query->from($db->quoteName('#__alarmhistory_section').' AS a');
		

		return $query;
	}
}