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

jimport('joomla.application.component.controlleradmin');

class AlarmhistoryControllerSites extends JControllerAdmin
{
	public function getModel($name = 'site', $prefix = 'AlarmhistoryModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}

