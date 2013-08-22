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

class AlarmhistoryModelSection extends JModelAdmin
{
	protected $text_prefix = 'COM_ALARMHISTORY';

	public function getTable($type = 'Site', $prefix = 'AlarmhistoryTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();

		$form = $this->loadForm('com_alarmhistory.alarmhistory', 'alarmhistory', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_alarmhistory.edit.alarmhistory.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	protected function prepareTable($table)
	{
		$table->title    = htmlspecialchars_decode($table->title, ENT_QUOTES);
	}
}