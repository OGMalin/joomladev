<?php
/**
 * @package     Alarmhistory for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

class AlarmhistoryController extends JControllerLegacy
{
//	protected $default_view = 'alarmhistory';
	
 	function display($cachable = false, $urlparams = false)
 	{
		require_once JPATH_COMPONENT.'/helpers/alarmhistory.php';
		
// 		$view 	= $this->input->get('view', 'alarmhistory');
		$view		= JFactory::getApplication()->input->getCmd('view', 'sections');
		JFactory::getApplication()->input->set('view', $view);

		$layout = $this->input->get('layout', 'default');
		
		parent::display($cachable, $urlparams);

		return $this;
	}
}

