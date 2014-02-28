<?php
/**
 * @package     Alarmhistory for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class AlarmhistoryViewtYPE extends JViewLegacy
{
 	protected $item;
	protected $form;
	
	public function display($tpl=null)
	{
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');
		
			// Check for errors.
 		if (count($errors = $this->get('Errors')))
 		{
 			JError::raiseError(500, implode("\n", $errors));
 			return false;
 		}

		$this->addToolbar();
		
		parent::display($tpl);
		
	}
	
	protected function addToolbar()
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu',true);
		
		// Add the admin view title
		JToolbarHelper::title(JText::_('COM_ALARMHISTORY_MANAGE_TYPE'), '');
		JToolbarHelper::save('type.save');
		
		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('type.cancel');
		}else
		{
			JToolbarHelper::cancel('type.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	
}
