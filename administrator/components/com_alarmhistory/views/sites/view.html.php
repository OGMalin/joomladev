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

class AlarmhistoryViewSites extends JViewLegacy
{
	protected $items;
	
	public function display($tpl=null)
	{
		$this->items = $this->get('Items');
		
		AlarmhistoryHelper::addSubmenu('sites');

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
		JToolbarHelper::title(JText::_('COM_ALARMHISTORY_SITES_TITLE'),'site');
		
		JToolbarHelper::addNew('site.add','JTOOLBAR_NEW');
		JToolbarHelper::editList('site.edit','JTOOLBAR_EDIT');
		JToolBarHelper::deleteList('', 'site.delete','JTOOLBAR_EMPTY_TRASH');
		
		JToolBarHelper::preferences('com_alarmhistory');
		
	}
	
}
