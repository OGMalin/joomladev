<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage  templates.polartemplate
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();

$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');

$useleft=($this->countModules('left')>0)?3:0;
$useright=($this->countModules('right')>0)?3:0;
$center='span'.(12-$useleft-$useright);

//JHtml::_('behavior.modal');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" >
<head>
	<meta charset="utf-8">
	<jdoc:include type="head" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div class="container">
		<?php if ($this->countModules('top')) : ?>
		<div class="row">
			<jdoc:include type="modules" name="top" style="xhtml" />
		</div>
		<?php endif ?>
		<div class="row">
			<?php if ($useleft) : ?>
		  	<div class="span3">
			 		<jdoc:include type="modules" name="left" style="xhtml" />
		 		</div>
		  <?php endif ?>
		  <div class="<?php echo $center ?>">
		  	<?php if ($this->countModules('above')) : ?>
			 		<jdoc:include type="modules" name="above" style="xhtml" />
				<?php endif ?>
			 	<jdoc:include type="component" />
		  	<?php if ($this->countModules('below')) : ?>
			 		<jdoc:include type="modules" name="below" style="xhtml" />
				<?php endif ?>
			</div>
			<?php if ($useright) : ?>
			  <div class="span3">
					<jdoc:include type="modules" name="right" style="xhtml" />
				</div>
			<?php endif ?>
		</div>
	 	<?php if ($this->countModules('bottom')) : ?>
		 	<div class="row">
				<jdoc:include type="modules" name="bottom" style="xhtml" />
			</div>
	 	<?php endif ?>
	 	<?php if ($this->countModules('footer')) : ?>
		 	<div class="row">
				<jdoc:include type="modules" name="footer" style="xhtml" />
			</div>
	 	<?php endif ?>
	</div>
	<jdoc:include type="modules" name="debug" style="xhtml" />

	<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/jquery.min.js"></script>
	<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/bootstrap.min.js"></script>
	<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/jquery-noconflict.js"></script>
</body>
</html>
