
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
 	protected $pagination;
 	protected $state;
	
	public function display($tpl=null)
	{
		$state		= $this->get('State');
		$items		= $this->get('Items');
 		$pagination	= $this->get('Pagination');
		
		// Check for errors.
 		if (count($errors = $this->get('Errors')))
 		{
 			JError::raiseError(500, implode("\n", $errors));
 			return false;
 		}

 		$this->state = $state;
 		$this->items = $items;
 		$this->pagination = $pagination;
 		
 		AlarmhistoryHelper::addSubmenu('sections');
 		
 		$this->addToolbar();
		
// 		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	
	protected function addToolbar($total=null)
	{
		require_once JPATH_COMPONENT . '/helpers/alarmhistory.php';
		
		$state = $this->get('State');
		$canDo = AlarmhistoryHelper::getActions($state->get('filter.category_id'));
	
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
		
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'sections.delete','JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			} else if ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('sections.trash','JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}
		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_alarmhistory');
		}
	}
}
