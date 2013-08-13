<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;


class AlarmhistoryViewAlarmhistory extends JViewLegacy
{
	protected $items;
	
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