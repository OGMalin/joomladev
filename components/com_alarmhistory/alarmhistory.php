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

$controller = JControllerLegacy::getInstance('alarmhistory');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();