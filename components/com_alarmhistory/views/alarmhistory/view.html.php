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
 * Dette er standard visningen.
 */
class AlarmhistoryViewAlarmhistory extends JViewLegacy
{
	protected $items;
	protected $fromCalendar;
	
	public function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$this->items = $this->get('Items');
		if (count($errors = $this->get('Errors')))
		{
			JError::raisError(500, implode("\n", $errors));
			return false;
		}

		$doc->addScript( $this->baseurl . '/media/com_alarmhistory/js/AlarmHistory.js' );

		$doc->addStyleSheet($this->baseurl . '/media/com_alarmhistory/css/template.css');
		
		parent::display($tpl);
	}
}