<?php
/**
 * @package     Polartour for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

class PolartourController extends JControllerLegacy
{
	protected $default_view = 'tournaments';
	
	function display($cachable = false, $urlparams = false)
 	{
		require_once JPATH_COMPONENT.'/helpers/polartour.php';
		
		$view = $this->input->get('view', 'tournaments');
		$layout = $this->input->get('layout', 'default');
		$id = $this->input->getInt('id');
		
		if ($view == 'polartour' && $layout == 'edit' && !$this->checkEditId('com_polartour.edit.tournament', $id))
		{
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_polartour&view=tournaments', false));
			return false;
		}
		
    parent::display();
 		return $this;
 	}
}

