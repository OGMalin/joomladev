<?php
defined('_JEXEC') or die;

/**
 * Polartour view
 * @author oddg
 *
 */
class PolartourViewTournaments extends JViewLegacy
{
	protected $items;
	
	public function display($tpl=null)
	{
		$this->items = $this->get('Items');
		
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
		$canDo = PolartourHelper::getActions();
		
		// Add the admin view title
		JToolbarHelper::title(JText::_('COM_POLARTOUR_POLARTOUR_TITLE'));
		
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('polartour.edit');
		}
		
		if ($canDo->get('core.admin')){
			JToolbarHelper::preferences('com_polartour');
		}
	}
	
	
}