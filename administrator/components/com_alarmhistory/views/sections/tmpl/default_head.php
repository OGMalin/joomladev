<?php
/**
 * @package     Alarmhistory for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;
$listOrder = '';
$listDirn = '';
?>
<tr>
	<th width="20">
		<?php echo JHtml::_('grid.checkall');?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'title', $listDirn, $listOrder); ?>
	</th>
	<th>
	</th>
</tr>