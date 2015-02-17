<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tabstate');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.modal', 'a.modal_jform_contenthistory');

// Create shortcut to parameters.
$params = $this->state->get('params');
//$images = json_decode($this->item->images);
//$urls = json_decode($this->item->urls);

// This checks if the editor config options have ever been saved. If they haven't they will fall back to the original settings.
$editoroptions = isset($params->show_publishing_options);
if (!$editoroptions)
{
	$params->show_urls_images_frontend = '0';
}

JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'article.cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			" . $this->form->getField('articletext')->save() . "
			Joomla.submitform(task);
		}
	}
");
?>
<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
	<?php if ($params->get('show_page_heading', 1)) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_content&a_id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('article.save')">
					<span class="icon-ok"></span><?php echo JText::_('JSAVE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn" onclick="Joomla.submitbutton('article.cancel')">
					<span class="icon-cancel"></span><?php echo JText::_('JCANCEL') ?>
				</button>
			</div>
			<?php if ($params->get('save_history', 0)) : ?>
			<div class="btn-group">
				<?php echo $this->form->getInput('contenthistory'); ?>
			</div>
			<?php endif; ?>
		</div>
		<fieldset>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#editor" data-toggle="tab"><?php echo JText::_('COM_CONTENT_ARTICLE_CONTENT') ?></a></li>
				<?php if ($params->get('show_urls_images_frontend') ) : ?>
				<li><a href="#images" data-toggle="tab"><?php echo JText::_('COM_CONTENT_IMAGES_AND_URLS') ?></a></li>
				<?php endif; ?>
				<li><a href="#publishing" data-toggle="tab"><?php echo JText::_('COM_CONTENT_PUBLISHING') ?></a></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="editor">
					<?php echo $this->form->renderField('title'); ?>

					<?php if (is_null($this->item->id)) : ?>
						<?php echo $this->form->renderField('alias'); ?>
					<?php endif; ?>
					<?php echo $this->form->getInput('articletext'); ?>
				</div>
				<div class="tab-pane" id="publishing">
					<?php echo $this->form->renderField('catid'); ?>
					<?php if ($this->item->params->get('access-change')) : ?>
						<?php echo $this->form->renderField('state'); ?>
						<?php echo $this->form->renderField('featured'); ?>
						<?php echo $this->form->renderField('publish_up'); ?>
						<?php echo $this->form->renderField('publish_down'); ?>
					<?php endif; ?>
					<?php echo $this->form->renderField('access'); ?>
					<?php if (is_null($this->item->id)):?>
						<div class="control-group">
							<div class="control-label">
							</div>
							<div class="controls">
								<?php echo JText::_('COM_CONTENT_ORDERING'); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>
