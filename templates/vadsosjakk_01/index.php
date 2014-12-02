<?php
/**
* @package Joomla.Site
* @subpackage Templates.vadsosjakk_01
*
* @copyright Copyright (C) 2014.
*/
defined('_JEXEC') or die;
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$user = JFactory::getUser();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option = $app->input->getCmd('option', '');
$view = $app->input->getCmd('view', '');
$layout = $app->input->getCmd('layout', '');
$task = $app->input->getCmd('task', '');
$itemid = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');
if($task == "edit" || $layout == "form" )
{
	$fullWidth = 1;
}else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
//$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/template.js');

// Add Stylesheets
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template.css');

// Adjusting content width
if ($this->countModules('right') && $this->countModules('left'))
{
	$span = "span6";
}elseif ($this->countModules('right') && !$this->countModules('left'))
{
	$span = "span9";
}elseif (!$this->countModules('right') && $this->countModules('left'))
{
	$span = "span9";
}else
{
	$span = "span12";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<jdoc:include type="head" />
		<!--[if lt IE 9]>
			<script src="<?php echo $this->baseurl; ?>/media/jui/js/html5.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="body">
			<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
			<header class="header" role="banner">
				<div class="header-inner clearfix">
					<div class="header-search pull-right">
						<jdoc:include type="modules" name="search" style="none" />
					</div>
				</div>
			</header>
				<?php if ($this->countModules('navigation')) : ?>
				<nav class="navigation" role="navigation">
					<jdoc:include type="modules" name="navigation" style="none" />
				</nav>
				<?php endif; ?>
				<jdoc:include type="modules" name="banner" style="xhtml" />
				<div class="row">
					<?php if ($this->countModules('left')) : ?>
					<div id="sidebar" class="span3">
						<div class="sidebar-nav">
							<jdoc:include type="modules" name="left" style="xhtml" />
						</div>
					</div>
					<?php endif; ?>
					<main id="content" class="<?php echo $span; ?>">
						<jdoc:include type="modules" name="top" style="xhtml" />
						<jdoc:include type="message" />
						<jdoc:include type="component" />
						<jdoc:include type="modules" name="breadcrumb" style="none" />
					</main>
					<?php if ($this->countModules('right')) : ?>
					<div id="aside" class="span3">
						<jdoc:include type="modules" name="right" style="well" />
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<footer class="footer">
			<div class="container">
				<hr />
				<jdoc:include type="modules" name="footer" style="none" />
				<p>&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?></p>
			</div>
		</footer>
		<jdoc:include type="modules" name="debug" style="none" />
	</body>
</html>