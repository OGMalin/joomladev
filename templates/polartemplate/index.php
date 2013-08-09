<?php
defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();

$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');

$useleft=($this->countModules('left')>0)?3:0;
$useright=($this->countModules('right')>0)?3:0;
$center='span'.(12-$useleft-$useright);

JHtml::_('behavior.modal');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" >
<head>
	<meta charset="utf-8">
	<jdoc:include type="head" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
<body>
	<div class="container">
		<div>
			<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/img/banner_1200.JPG" />
		</div>
		<?php if ($this->countModules('top')) : ?>
		<div class="navigation">
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
		  		<jdoc:include type="component" />
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
	</div>
	<jdoc:include type="modules" name="debug" style="xhtml" />
</body>
</html>
