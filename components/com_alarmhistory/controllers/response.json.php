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
		$limit=$this->input->getInt('limit',10);
		$filter=urldecode($this->input->getString('filter',''));
		
		$res=$model->queryAlarmhistory($start, $limit, $filter);
		$res=array('error' => 1);
		$doc->setMimeEncoding('application/json');
		echo json_encode($res);
		$app->close();
	}
	
}