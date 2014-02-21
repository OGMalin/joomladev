<?php
/**
 * @version     $Id$
 * @package     Alarmhistory
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
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
	var $id =null;
	var $title = null;
	var $SEC1 = null;
	var $SEC2 = null;
	var $SEC3 = null;
	
	public function __construct($db)
	{
		parent::__construct('#__alarmhistory_section', 'id', $db);
	}
}