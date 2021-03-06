<?php
/**
 * @version     $Id$
 * @package     Joomla.Admin
 * @subpackage  com_polartour
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

class com_polartourInstallerScript
{
	function install($parent)
	{
		$parent->getParent()->setRedirectURL('index.php?option=com_polartour');
	}

	function uninstall($parent)
	{
		echo '<p>' . JText::_('COM_POLARTOUR_UNINSTALL_TEXT') . '</p>';
	}

	function update($parent)
	{
		echo '<p>' . JText::_('COM_POLARTOUR_UPDATE_TEXT') . '</p>';
	}

	function preflight($type, $parent)
	{
		echo '<p>' . JText::_('COM_POLARTOUR_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	function postflight($type, $parent)
	{
		echo '<p>' . JText::_('COM_POLARTOUR_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
	}
}