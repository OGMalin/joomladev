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
	// Not needed when the component name and the default view are the same 
//	protected $default_view = 'alarmhistory';
	
 	function display($cachable = false, $urlparams = false)
 	{
		require_once JPATH_COMPONENT.'/helpers/alarmhistory.php';
		
// 		$view 	= $this->input->get('view', 'alarmhistory');
		$view		= JFactory::getApplication()->input->getCmd('view', 'sections');
		JFactory::getApplication()->input->set('view', $view);

		// 		$layout = $this->input->get('layout', 'default');
// 		$id     = $this->input->getInt('id');
		
// 		if ($view == 'site' && $layout == 'edit' && !$this->checkEditId('com_alarmhistory.edit.alarmhistory', $id))
// 		{
// 			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
// 			$this->setMessage($this->getError(), 'error');
// 			$this->setRedirect(JRoute::_('index.php?option=com_alarmhistory&view=alarmhistory', false));
// 			return false;
// 		} else if ($view == 'section' && $layout == 'edit' && !$this->checkEditId('com_alarmhistory.edit.alarmhistory', $id))
// 		{
// 			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
// 			$this->setMessage($this->getError(), 'error');
// 			$this->setRedirect(JRoute::_('index.php?option=com_alarmhistory&view=alarmhistory', false));
// 		}
		
		// Load submenu
// 		AlarmhistoryHelper::addSubmenu($view);
		
		
		parent::display($cachable, $urlparams);

//		return $this;
	}
}

