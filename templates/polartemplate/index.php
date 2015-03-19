<?php
/**
 * @package     Joomla.Site
 * @subpackage  templates.polartemplate
 * @copyright   Copyright 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// Ingen direkte aksess
defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$user = JFactory::getUser();
$this->language = $doc->language;

// Hen parametrer som er satt opp
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
//$option = $app->input->getCmd('option', '');
//$view = $app->input->getCmd('view', '');
$layout = $app->input->getCmd('layout', '');
$task = $app->input->getCmd('task', '');
//$itemid = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');

// Legg til JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Egne script
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/template.js');

// Legg til Stylesheets
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template.css');
$doc->addStyleSheet($this->baseurl . '/templates/'.$this->template.'/css/custom.css');

// Finn bredden på Innholdsboksen
$useleft=($this->countModules('left')>0)?$this->params->get('leftsize'):0;
$useright=($this->countModules('right')>0)?$this->params->get('rightsize'):0;
$center='span'.(12-$useleft-$useright);

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" >
	<head>
		<meta charset="utf-8">
		<jdoc:include type="head" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
 		<!--[if lt IE 9]>
			<script src="<?php echo $this->baseurl; ?>/media/jui/js/html5.js"></script>
		<![endif]-->
	</head>

	<body>
		<div class="body">
			<div class="container main">
				<?php if ($this->countModules('banner')) : ?>
				<div class="row">
					<jdoc:include type="modules" name="banner" style="none" />
					<?php if ($this->params->get('datebanner')) : ?>
						<span class="bannerdato"><?php echo date('d.m.Y'); ?></span>
					<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php if ($this->countModules('navigation')) : ?>
				<nav class="navbar navbar-inverse">
					<div class="navbar-inner">
						<div class="container">
							<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</a>
							<?php if ($this->params->get('menusite')) : ?>
							<a class="brand" href="index.php"><?php echo $sitename ?></a>
							<?php endif; ?>
  	          <div class="nav-collapse collapse">
								<jdoc:include type="modules" name="navigation" style="none" />
								<?php if ($this->params->get('menulogin')) : ?>
									<?php if ($user->guest) : ?>
										<a href="index.php?option=com_users&view=login" class="userlogin pull-right">Logg inn</a>
									<?php else: ?>
										<div class="userlogin pull-right">
										<span class="icon-user"></span> <?php echo $user->name ?> - 
										<a href="index.php?option=com_users&view=login" class="userlogin">Logg ut</a>
										</div>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</nav>
				<?php endif; ?>
				<?php if ($this->countModules('top')) : ?>
				<div class="row">
					<jdoc:include type="modules" name="top" style="none" />
				</div>
				<?php endif; ?>
				<?php if ($this->countModules('breadcrumb')) : ?>
				<div class="row">
					<jdoc:include type="modules" name="breadcrumb" style="none" />
				</div>
				<?php endif; ?>
				
				<div class="row">
					<?php if ($useleft) : ?>
		  		<div class="span<?php echo $useleft; ?>">
			 			<jdoc:include type="modules" name="left" style="html5" />
		 			</div>
		  		<?php endif; ?>
		  		<div class="<?php echo $center; ?>">
		  			<?php if ($this->countModules('above')) : ?>
			 			<jdoc:include type="modules" name="above" style="none" />
						<?php endif; ?>
						<jdoc:include type="message" />
						<jdoc:include type="component" />
			 			<?php if ($this->countModules('below')) : ?>
			 			<jdoc:include type="modules" name="below" style="none" />
						<?php endif; ?>
					</div>
					<?php if ($useright) : ?>
		  		<div class="span<?php echo $useright; ?>">
						<jdoc:include type="modules" name="right" style="html5" />
					</div>
					<?php endif; ?>
				</div>
				<?php if ($this->countModules('bottom')) : ?>
				<div class="row">
					<jdoc:include type="modules" name="bottom" style="none" />
				</div>
				<?php endif; ?>
				<hr />
	 			<?php if ($this->countModules('footer')) : ?>
				<div class="row">
					<p class="pull-right">
					&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
					</p>
					<jdoc:include type="modules" name="footer" style="none" />
				</div>
				<?php endif; ?>
				</div>
			</div>
			<jdoc:include type="modules" name="debug" style="none" />
		</div>
	</body>
</html>