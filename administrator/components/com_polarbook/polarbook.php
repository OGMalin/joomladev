<?php 
/**
 * @package     Polarbook for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */ 

defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_polarbook'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$controller	= JControllerLegacy::getInstance('Polarbook');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
