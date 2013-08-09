<?php
defined('_JEXEC') or die;

/**
 * Polarbook view
 * @author oddg
 *
 */
class PolarbookViewPolarbook extends JViewLegacy
{
	/**
	 * (non-PHPdoc)
	 * @see JViewLegacy::display()
	 */
	public function display($tpl=null)
	{
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		$canDo = PolarbookHelper::getActions();
		
		// Add the admin view title
		JToolbarHelper::title(JText::_('COM_POLARBOOK_POLARBOOK_TITLE'));
		
		if ($canDo->get('core.admin')){
			JToolbarHelper::preferences('com_polarbook');
		}
	}
	
	
}