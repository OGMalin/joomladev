
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
//	protected $form;
 	protected $pagination;
// 	protected $state;
	
	public function display($tpl=null)
	{
		$this->items		= $this->get('Items');
//		$this->form = $this->get('Form');
 		$this->pagination	= $this->get('Pagination');
// 		$this->state		= $this->get('State');
		
		// Check for errors.
 		if (count($errors = $this->get('Errors')))
 		{
 			JError::raiseError(500, implode("\n", $errors));
 			return false;
 		}

// 		$this->addToolbar();
		
// 		$this->sidebar = JHtmlSidebar::render();
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
/*
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/eksempel.php';

		$state	= $this->get('State');
		$canDo	= EksempelHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_EKSEMPEL_TITLE_SECTIONS'), 'sections.png');

		//Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/section';
		if (file_exists($formPath)) {

			if ($canDo->get('core.create')) {
				JToolBarHelper::addNew('section.add','JTOOLBAR_NEW');
			}

			if ($canDo->get('core.edit') && isset($this->items[0])) {
				JToolBarHelper::editList('section.edit','JTOOLBAR_EDIT');
			}

		}

		if ($canDo->get('core.edit.state')) {

			if (isset($this->items[0]->state)) {
				JToolBarHelper::divider();
				JToolBarHelper::custom('sections.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('sections.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			} else if (isset($this->items[0])) {
				//If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'sections.delete','JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state)) {
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('sections.archive','JTOOLBAR_ARCHIVE');
			}
			if (isset($this->items[0]->checked_out)) {
				JToolBarHelper::custom('sections.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		//Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state)) {
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
				JToolBarHelper::deleteList('', 'sections.delete','JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			} else if ($canDo->get('core.edit.state')) {
				JToolBarHelper::trash('sections.trash','JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_eksempel');
		}

		//Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_eksempel&view=sections');

		$this->extra_sidebar = '';


	}

*/
}
//******************************''


/*
	protected function getSortFields()
	{
		return array(
				'a.id' => JText::_('JGRID_HEADING_ID'),
				'a.title' => JText::_('COM_EKSEMPEL_SECTIONS_TITLE'),
				'a.sec1' => JText::_('COM_EKSEMPEL_SECTIONS_SEC1'),
				'a.sec2' => JText::_('COM_EKSEMPEL_SECTIONS_SEC2'),
				'a.sec3' => JText::_('COM_EKSEMPEL_SECTIONS_SEC3'),
		);
	}


}
*/