<?php
/**
 * @package     Alarmhistory for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;
?>

<form action="<?php echo JRoute::_('index.php?option=com_alarmhistory&view=sections'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<div class="clearfix"> </div>
		<table class="table table-striped" id="sectionList">
			<thead>
				<tr>
					<th width="1%">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th class="title">
						<?php echo JText::_('JGLOBAL_TITLE'); ?>
					</th>
					<th class="nowrap">
						<?php echo JText::_('COM_ALARMHISTORY_HEADING_SEC1'); ?>
					</th>
					<th class="nowrap">
						<?php echo JText::_('COM_ALARMHISTORY_HEADING_SEC2'); ?>
					</th>
					<th class="nowrap">
						<?php echo JText::_('COM_ALARMHISTORY_HEADING_SEC3'); ?>
					</th>
					<th class="nowrap">
						<?php echo JText::_('JGRID_HEADING_ID'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->items as $i => $item) :
				 ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td class="nowrap has-context">
							<a href="<?php echo JRoute::_('index.php?option=com_alarmhistory&task=section.edit&id='.(int) $item->id); ?>">
								<?php echo $this->escape($item->title); ?>
							</a>
						</td>
						<td><?php echo $this->escape($item->SEC1); ?></td>
						<td><?php echo $this->escape($item->SEC2); ?></td>
						<td><?php echo $this->escape($item->SEC3); ?></td>
						<td><?php echo $this->escape($item->id); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>