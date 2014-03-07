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

require_once JPATH_COMPONENT.'/helpers/ifix.php';

jimport('joomla.application.component.modelitem');

class AlarmhistoryModelResponse extends JModelItem
{
	
	public function queryAlarmhistory($start, $limit, $eventdate, $sec, $district, $field, $location, $region, $searchtext)
	{
		$iFix=new iFixHelper();
		
		$iFix->limit = $limit;
		$iFix->eventdate = $eventdate;
		$iFix->sec = $sec;
		$iFix->field=$field;
		$iFix->district=$district;
		$iFix->location=$location;
		$iFix->region=$region;
		$iFix->searchtext=$searchtext;
		return $iFix->getData();
	}
}
