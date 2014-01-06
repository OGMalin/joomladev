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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_alarmhistory'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('Alarmhistory');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

