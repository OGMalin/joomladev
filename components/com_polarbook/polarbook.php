<?php
defined('_JEXEC') or die;

// Correction for magic quotes
if (get_magic_quotes_gpc()) {
	$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	while (list($key, $val) = each($process)) {
		foreach ($val as $k => $v) {
			unset($process[$key][$k]);
			if (is_array($v)) {
				$process[$key][stripslashes($k)] = $v;
				$process[] = &$process[$key][stripslashes($k)];
			} else {
				$process[$key][stripslashes($k)] = stripslashes($v);
			}
		}
	}
	unset($process);
}

//jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('Polarbook');
 
$controller->execute(JFactory::getApplication()->input->get('task'));

$controller->redirect();

