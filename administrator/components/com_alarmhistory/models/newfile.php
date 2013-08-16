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

class AlarmhistoryModelAlarmhistory extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
					'id', 'a.id',
					'title', 'a.title',
			);
		}

		parent::__construct($config);
	}

	protected function getListQuery()
	{
		$db    = $this->getDbo();
		$query  = $db->getQuery(true);

		$query->select(
				$this->getState(
						'list.select',
						'a.id, a.title'
				)
		);
		$query->from($db->quoteName('#__alarmhistory_site').' AS a');

		return $query;
	}
}