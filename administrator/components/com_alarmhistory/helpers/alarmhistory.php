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
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(JText::_('COM_ALARMHISTORY_SUBMENU_SITES'),'index?option=com_alarmhistory&view=sites',$vName=='sites');
		JSubMenuHelper::addEntry(JText::_('COM_ALARMHISTORY_SUBMENU_SECTIONS'),'index?option=com_alarmhistory&view=sections',$vName=='sections');
	}
	
	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($categoryId))
		{
			$assetName = 'com_alarmhistory';
			$level = 'component';
		}else
		{
			$assetName = 'com_alarmhistory.category.'.(int) $categoryId;
			$level = 'category';
		}

		$actions = JAccess::getActions('com_alarmhistory', $level);

		foreach ($actions as $action)
		{
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}

		return $result;
	}
}