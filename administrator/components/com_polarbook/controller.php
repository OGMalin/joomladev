<?php
/**
 * @package     Polarbook for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

class PolarbookController extends JControllerLegacy
{
 	function display($cachable = false, $urlparams = false)
 	{
		require_once JPATH_COMPONENT.'/helpers/polarbook.php';
		$view = $this->input->get('view', 'polarbooks');
		$layout = $this->input->get('layout', 'default');
		$id = $this->input->getInt('id');
		
 		parent::display();
 		return $this;
 	}
}
