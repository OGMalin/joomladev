<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/ifix.php';

jimport('joomla.application.component.modelitem');

class AlarmhistoryModelResponse extends JModelItem
{
	
	public function queryAlarmhistory($start, $limit, $eventdate)
	{
		$iFix=new iFixHelper();
		
		$ifix->limit=$limit;
		$ifix->eventdate=$eventdate;
		return $iFix->getData();
	}
}
