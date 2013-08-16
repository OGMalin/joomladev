<?php
defined('_JEXEC') or die;

class AlarmhistoryController extends JControllerLegacy
{
	// Not needed when the component name and the default view are the same 
	//protected $default_view = 'alarmhistory';
	
 	function display($cachable = false, $urlparams = false)
 	{
		require_once JPATH_COMPONENT.'/helpers/alarmhistory.php';
		
		$view 	= $this->input->get('view', 'alarmhistory');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');
		
		if ($view == 'site' && $layout == 'edit' && !$this->checkEditId('com_alarmhistory.edit.alarmhistory', $id))
		{
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_alarmhistory&view=alarmhistory', false));
			return false;
		}
		
    parent::display();

		return $this;
	}
}
