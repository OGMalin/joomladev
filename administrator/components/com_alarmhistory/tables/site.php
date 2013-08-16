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

class AlarmhistoryTableSite extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__alarmhistory_site', 'id', $db);
	}

	public function bind($array, $ignore = '')
	{
		return parent::bind($array, $ignore);
	}

	public function store($updateNulls = false)
	{
		return parent::store($updateNulls);
	}
}