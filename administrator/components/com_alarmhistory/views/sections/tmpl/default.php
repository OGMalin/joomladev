<?php
/**
 * @version     $Id$
 * @package     Joomla.Admin
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

$listOrder = '';
$listDirn = '';
?>
<form action="<?php echo JRoute::_('index.php?option=com_alarmhistory&view=sections'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div id="j-main-container" class="span10">
		<div class="clearfix"></div>
		<table class="table table-striped" id="sectionList">
			<thead>
				<tr>
					<th with="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td class="nowrap has-context">
							<a href="<?php echo JRoute::_('index.php?option=com_alarmhistory&task=section.edit&id='.(int) $item->id); ?>">
								<?php echo $this->escape($item->title); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
	</div>
</form>