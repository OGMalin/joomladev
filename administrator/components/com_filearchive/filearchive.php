<?php
defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_filearchive')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Filearchive');
$controller->execute(JFactory::getApplication()->input->get('task')); // default filter: cmd
$controller->redirect();
