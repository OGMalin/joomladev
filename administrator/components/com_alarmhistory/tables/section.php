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

jimport('joomla.database.table');

/**
 * section table.
 *
 * @package     Alarmhistory
 * @subpackage  com_alarmhistory
 * @since       1.0
 */
class AlarmhistoryTableSection extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__alarmhistory_section', 'id', $db);
	}
	
	public function bind($array, $ignore='')
	{
		return parent::bind($array, $ignore);
	}
	
	public function store($updateNulls=false)
	{
		return parent::store($updateNulls);
	}
}