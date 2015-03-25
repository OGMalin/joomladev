<?php
/**
 * @package     Polarbook for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

class PolarbookController extends JControllerLegacy
{
	protected $default_view = 'books';
	
	function display($cachable = false, $urlparams = false)
 	{
		require_once JPATH_COMPONENT.'/helpers/polarbook.php';
		
		$view = $this->input->get('view', 'books');
		$layout = $this->input->get('layout', 'default');
		$id = $this->input->getInt('id');
		
		if ($view == 'polarbook' && $layout == 'edit' && !$this->checkEditId('com_polarbook.edit.book', $id))
		{
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_polarbook&view=books', false));
			return false;
		}
		
    parent::display();
 		return $this;
 	}
}
