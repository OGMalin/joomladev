
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
class AlarmhistoryViewAlarmhistory extends JViewLegacy
{
	public function display($tpl=null)
	{
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
//		$canDo = PolarbookHelper::getActions();
		
		// Add the admin view title
		JToolbarHelper::title(JText::_('COM_ALARMHISTORY_ALARMHISTORY_TITLE'));
		
//		if ($canDo->get('core.admin')){
//			JToolbarHelper::preferences('com_alarmhistory');
//		}
	}
}
