<?php
/**
 * @version     $Id$
 * @package     Alarmhistory
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;


/**
 * @package     Alarmhistory
 * @subpackage  com_alarmhistory
 * @since       1.0
 */
class Com_AlarmhistoryInstallerScript
{
	/**
	 * Runs after files are installed and database scripts executed.
	 *
	 * @param   JInstaller  $parent  The installer object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	function install($parent)
	{
		$parent->getParent()->setRedirectURL('index.php?option=com_alarmhistory');
	}

	/**
	 * Runs after files are removed and database scripts executed.
	 *
	 * @param   JInstaller  $parent  The installer object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	function uninstall($parent)
	{
		echo '<p>' . JText::_('COM_ALARMHISTORY_UNINSTALL_TEXT') . '</p>';
	}

	/**
	 * Runs after files are updated and database scripts executed.
	 *
	 * @param   JInstaller  $parent  The installer object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	function update($parent)
	{
		echo '<p>' . JText::_('COM_ALARMHISTORY_UPDATE_TEXT') . '</p>';
	}

	/**
	 * Runs before anything is run.
	 *
	 * @param   string      $type    The type of installation: install|update.
	 * @param   JInstaller  $parent  The installer object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	function preflight($type, $parent)
	{
		echo '<p>' . JText::_('COM_ALARMHISTORY_PREFLIGHT' . $type . '_TEXT') . '</p>';
	}

	/**
	 * Runs after an extension install or update.
	 *
	 * @param   string      $type    The type of installation: install|update.
	 * @param   JInstaller  $parent  The installer object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	function postflight($type, $parent)
	{
		// Note: this file is executed in the tmp folder if using the upload method.
		echo '<p>' . JText::_('COM_ALARMHISTORY_POSTFLIGHT' . $type . '_TEXT') . '</p>';
	}
}

