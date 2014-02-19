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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_alarmhistory'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('Alarmhistory');

$controller->execute(JFactory::getApplication()->input->getCmd('task'));

$controller->redirect();

