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

class AlarmhistoryControllerResponse extends JControllerLegacy
{
	/**
	 * Ajax response
	 */
	function queryalarmhistory()
	{
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();

		$this->input = $app->input;
		$start=$this->input->getInt('start',0);
		$limit=$this->input->getInt('limit',20);
		$eventdate=$this->input->getInt('eventdate',0);
		$district=$this->input->getInt('district',0);
		$location=$this->input->getInt('location',0);
		$eventindex=$this->input->getInt('eventindex',0);
		$searchtext=$this->input->getString('searchtext','');
		$res=$model->queryAlarmhistory($start, $limit, $eventdate, $district, $location, $eventindex, $searchtext);
		
		$doc->setMimeEncoding('application/json');
		echo json_encode($res);
		$app->close();
	}
	
}