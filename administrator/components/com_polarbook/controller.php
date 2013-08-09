<?php
defined('_JEXEC') or die;

class PolarbookController extends JControllerLegacy
{
 	function display($cachable = false, $urlparams = false) {
		require_once JPATH_COMPONENT.'/helpers/polarbook.php';
 		parent::display();
// 		return $this;
 	}
}
