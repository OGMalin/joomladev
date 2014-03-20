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

class AlarmhistoryViewTypes extends JViewLegacy
{
	protected $items;
	protected $state;
	protected $pagination;
	
	public function display($tpl=null)
	{
		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		$this->pagination = $this->get('Pagination');
		
		AlarmhistoryHelper::addSubmenu('types');
		
		// Check for errors.
 		if (count($errors = $this->get('Errors')))
 		{
 			JError::raiseError(500, implode("\n", $errors));
 			return false;
 		}

 		$this->addToolbar();
 		
 		$this->sidebar = JHtmlSidebar::render();
		
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		// Add the admin view title
		JToolbarHelper::title(JText::_('COM_ALARMHISTORY_TITLE_TYPES'),'type');
		
		JToolbarHelper::addNew('type.add','JTOOLBAR_NEW');
		JToolbarHelper::editList('type.edit','JTOOLBAR_EDIT');
		JToolBarHelper::deleteList('', 'types.delete','JTOOLBAR_EMPTY_TRASH');
		
		JToolBarHelper::preferences('com_alarmhistory');
	}
	
	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
