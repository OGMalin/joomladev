<?php
/**
 * @package     Alarmhistory for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
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
