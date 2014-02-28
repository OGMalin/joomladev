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

jimport('joomla.application.component.controllerform');

class AlarmhistoryControllerType extends JControllerForm
{
	function __construct()
	{
		$this->view_list = 'types';
		parent::__construct();
	}
	
}
