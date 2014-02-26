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

jimport('joomla.application.component.modeladmin');

class AlarmhistoryModelSection extends JModelAdmin
{
	protected $text_prefix = 'COM_ALARMHISTORY';

	public function getTable($type = 'Section', $prefix = 'AlarmhistoryTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();

		$form = $this->loadForm('com_alarmhistory.section', 'section', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_alarmhistory.edit.section.data', array());

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