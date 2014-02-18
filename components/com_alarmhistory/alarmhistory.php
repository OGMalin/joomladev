<?php
/**
 * @version     1.0.0
 * @package     Joomla.Site
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// Hindrer at filen kan hentes opp direkte med peker
defined('_JEXEC') or die;

// Importer controller biblioteket
jimport('joomla.application.component.controller');

// Last inn AlarmhistoryController fra controller.php
$controller = JControllerLegacy::getInstance('Alarmhistory');

// Kjør rett funksjon (task) i AlarmhistoryController (default=display)
// Denne kan settes med index.php?option=com_alarmhistory&task=ny_funksjon
$controller->execute(JFactory::getApplication()->input->getCmd('task'));

// Redirekt til hva 'controlleren' har satt. 
$controller->redirect();
