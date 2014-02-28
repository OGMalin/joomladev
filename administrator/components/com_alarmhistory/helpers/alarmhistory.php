<?php
/**
 * @version     $Id$
 * @package     Joomla.admin
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

class AlarmhistoryHelper
{
	public static function addSubmenu($vName='')
	{
//		JHtmlSidebar::addEntry(JText::_('COM_ALARMHISTORY_TITLE_ALARMHISTORY'),'index.php?option=com_alarmhistory&view=alarmhistory',$vName=='alarmhistory');
		JHtmlSidebar::addEntry(JText::_('COM_ALARMHISTORY_TITLE_SECTIONS'),'index.php?option=com_alarmhistory&view=sections',$vName=='sections');
		JHtmlSidebar::addEntry(JText::_('COM_ALARMHISTORY_TITLE_SITES'),'index.php?option=com_alarmhistory&view=sites',$vName=='sites');
		JHtmlSidebar::addEntry(JText::_('COM_ALARMHISTORY_TITLE_TYPES'),'index.php?option=com_alarmhistory&view=types',$vName=='types');
	}
	
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_alarmhistory';

		$actions = array('core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete');

		foreach ($actions as $action)
		{
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}
