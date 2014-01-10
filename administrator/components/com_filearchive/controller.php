<?php

defined('_JEXEC') or die;

class FilearchiveController extends JControllerLegacy
{
	protected $default_view = 'files';
	
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/filearchive.php';
		
		$view = $this->input->get('view', 'files');
		$layout = $this->input->get('layout', 'default');
		$id = $this->input->getInt('id');
		
		JFactory::getApplication()->input->set('view', $view);
		
		parent::display($cachable, $urlparams);
		return $this;
	}
}