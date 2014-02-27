<?php
/**
 * @package     Alarmhistory for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// Ingen direkte åpning av denne filen
defined('_JEXEC') or die;

// Sjekk at brukeren har lov å administrere.
if (!JFactory::getUser()->authorise('core.manage', 'com_alarmhistory'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Importer 'controller' biblioteket
jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('Alarmhistory');

$controller->execute(JFactory::getApplication()->input->get('task'));

$controller->redirect();

