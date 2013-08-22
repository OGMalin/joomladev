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

class AlarmhistoryControllerSections extends JControllerAdmin
{
	public function getModel($name = 'Section', $prefix = 'AlarmhistoryModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}
