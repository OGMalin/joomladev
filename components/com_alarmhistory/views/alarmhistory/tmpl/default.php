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

JFactory::getDocument()->addScriptDeclaration("
		responseUrl='" . $this->baseurl . "/index.php?option=com_alarmhistory&amp;';
		window.onload=function(){init();};
");
	
?>
<div id='polarbook'>
	<?php echo $this->loadTemplate('navbar'); ?>
Tidsfølgemelderlisting
</div>
<!--  ?php echo $this->loadTemplate('modals'); ?>  -->