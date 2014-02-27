<?php
/**
 * @package     Polarbook for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

class PolarbookModelBooks extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
					'id', 'a.id',
					'name', 'a.name',
					'user', 'a.user',
					'trashed', 'a.trashed',
					'public', 'a.public',
					'member', 'a.member',
					'readuser', 'a.readuser',
					'writeuser', 'a.writeuser',
					'created', 'a.created',
					'comment', 'a.comment'
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
						'a.id, a.name','a.user','s.public','a.member','a.readuser','a.writeuser','a.created','a.comment'
				)
		);
		$query->from($db->quoteName('#__polarbook_books').' AS a');

		return $query;
	}
}
