<?php
defined('_JEXEC') or die;

class FilearchiveModelFile extends JModelAdmin
{
	protected $text_prefix = 'COM_FILEARCHIVE';

	public function getTable($type = 'File', $prefix = 'FilearchiveTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();

		$form = $this->loadForm('com_filearchive.file', 'file', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_filearchive.edit.file.data', array());
		
		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	protected function prepareTable($table)
	{
		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);
	}
}