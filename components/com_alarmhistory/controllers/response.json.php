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
		$sec1=$this->input->getString('sec1','');
		
		$res=$model->queryAlarmhistory($start, $limit, $eventdate, $sec1);
		
		$doc->setMimeEncoding('application/json');
		echo json_encode($res);
		$app->close();
	}
	
}