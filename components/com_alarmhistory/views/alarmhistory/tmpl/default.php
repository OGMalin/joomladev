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

JHtml::_('behavior.calendar');	
JFactory::getDocument()->addScriptDeclaration("
		var responseUrl='" . $this->baseurl . "/index.php?option=com_alarmhistory&amp;';				
");

// Kalenderfunksjonen trenger mootools
JHtml::_('behavior.framework');
?>
<div id='alarmhstory'>
	<?php echo $this->loadTemplate('navbar'); ?>
	<div id='historylist'>
	</div>
</div>
<?php  echo $this->loadTemplate('modals'); ?>