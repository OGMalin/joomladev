<?php
defined('_JEXEC') or die;

/**
 * Polarbook view
 * @author oddg
 *
 */
class PolarbookViewBooks extends JViewLegacy
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
		$canDo = PolarbookHelper::getActions();
		
		// Add the admin view title
		JToolbarHelper::title(JText::_('COM_POLARBOOK_POLARBOOK_TITLE'));
		
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('polarbook.edit');
		}
		
		if ($canDo->get('core.admin')){
			JToolbarHelper::preferences('com_polarbook');
		}
	}
	
	
}