
<?php
/**
 * @version     $Id$
 * @package     Joomla.Admin
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

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
	protected $form;
// 	protected $pagination;
// 	protected $state;
	
	public function display($tpl=null)
	{
		$this->items		= $this->get('Items');
		$this->form = $this->get('Form');
// 		$this->pagination	= $this->get('Pagination');
// 		$this->state		= $this->get('State');
		
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
//		$state = $this->get('State');
		$canDo = AlarmhistoryHelper::getActions();
		
		// Add the admin view title
		JToolbarHelper::title(JText::_('COM_ALARMHISTORY_SECTIONS_TITLE'));
		
		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('section.add');
		}

 		if ($canDo->get('core.edit'))
 		{
 			JToolbarHelper::editList('section.edit');
 		}
		
//  		if ($canDo->get('core.edit.state'))
//  		{
//  			JToolbarHelper::publishList('sections.publish','JTOOLBAR_PUBLISH');
//  			JToolbarHelper::publishList('sections.unpublish','JTOOLBAR_UNPUBLISH');
//  			JToolbarHelper::publishList('sections.archive','JTOOLBAR_ARCHIVE');
//  		}
 		
//  		if (($state->get('filter.published') == -2) && ($canDo->get('core.delete')))
//  		{
//  			JToolbarHelper::deleteList('','sections.delete','JTOOLBAR_EMPTY_TRASH');
//  		}else if ($canDo->get('core.edit.state'))
//  		{
//  			JToolbarHelper::trash('sections.delete','JTOOLBAR_EMPTY_TRASH');
//  		}
 		
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_alarmhistory');
		}
	}
}
