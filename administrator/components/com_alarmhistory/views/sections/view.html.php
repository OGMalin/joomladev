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

/**
 * alarmhistory view.
 *
 * @package     Joomla.Admin
 * @subpackage  component
 * @since       1.0
 */
class AlarmhistoryViewSections extends JViewLegacy
{
	protected $items;
	protected $state;
	
	public function display($tpl=null)
	{
		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		
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
//		require_once JPATH_COMPONENT . '/helpers/alarmhistory.php';
		
		$canDo = AlarmhistoryHelper::getActions();
		$bar = JToolbar::getInstance('toolbar');
	
		// Add the admin view title
		JToolbarHelper::title(JText::_('COM_ALARMHISTORY_SECTIONS_TITLE'),'section');
		
		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('section.add','JTOOLBAR_NEW');
		}
		
		if ($canDo->get('core.edit') && isset($this->items[0]))
		{
			JToolbarHelper::editList('section.edit','JTOOLBAR_EDIT');
		}
		
// 		if (isset($this->items[0]->state))
// 		{
// 			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
// 			{
// 				JToolBarHelper::deleteList('', 'sections.delete','JTOOLBAR_EMPTY_TRASH');
// 				JToolBarHelper::divider();
// 			} else if ($canDo->get('core.edit.state'))
// 			{
// 				JToolBarHelper::trash('sections.trash','JTOOLBAR_TRASH');
// 				JToolBarHelper::divider();
// 			}
// 		}
		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_alarmhistory');
		}
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
