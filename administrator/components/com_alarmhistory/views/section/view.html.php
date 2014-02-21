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

class AlarmhistoryViewSection extends JViewLegacy
{
 	protected $item;
	protected $form=null;
	protected $state;
	
	public function display($tpl=null)
	{
		$form = $this->get('Form');
		$item = $this->get('Item');
		$state = $this->get('State');
		
			// Check for errors.
 		if (count($errors = $this->get('Errors')))
 		{
 			JError::raiseError(500, implode("\n", $errors));
 			return false;
 		}

 		$this->form = $form;
 		$this->item = $item;
 		$this->state = $state;
 			
		$this->addToolbar();
		
		parent::display($tpl);
		
	}
	
	protected function addToolbar()
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu',true);
		$isNew = ($this->idem->id == 0);
		
		// Add the admin view title
		JToolbarHelper::title($isNew ? JText::_('COM_ALARMHISTORY_SECTION_NEW')
																 : JText::_('COM_ALARMHISTORY_SECTION_EDIT'));
		JToolbarHelper::save('section.save');
		JToolbarHelper::cancel('sectiob.cancel', $isNew ? 'JTOOLBAR_CANCEL'
																										: 'JTOOLBAR_CLOSE');
		
	}
	
}
