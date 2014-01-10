<?php
defined('_JEXEC') or die;

class FilearchiveControllerFiles extends JControllerAdmin
{
	public function getModel($name = 'File', $prefix = 'FilearchiveModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}