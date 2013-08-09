<?php
defined('_JEXEC') or die;

/**
 * 
 */
class PolarbookHelper
{
	public static function  getActions()
	{
		$user=JFactory::getUser();
		$result=new JObject;
		
		$actions = array('core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.edit.own', 'core.delete');
		
		foreach ($actions as $action){
			$result->set($action, $user->authorise($action, 'com_polarbook'));
		}
		
		return $result;
	}
}