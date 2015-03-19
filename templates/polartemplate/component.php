<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.polartemplate
 *
* @copyright Copyright (C) 2014.
 */

defined('_JEXEC') or die;

$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$this->language  = $doc->language;

// Legg til JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Egne script
// Legg til Stylesheets
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template.css');
$doc->addStyleSheet($this->baseurl . '/templates/'.$this->template.'/css/custom.css');

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" >
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<jdoc:include type="head" />
		<!--[if lt IE 9]>
			<script src="<?php echo $this->baseurl; ?>/media/jui/js/html5.js"></script>
		<![endif]-->
	</head>
	<body class="contentpane modal">
		<jdoc:include type="message" />
		<jdoc:include type="component" />
	</body>
</html>
