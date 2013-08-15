<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Polarstrap
 */

defined('_JEXEC') or die;

class plgSystemPolarstrap extends JPlugin
{

	public function onAfterDispatch()
	{
		$useInadmin = (int) $this->params->get('inadmin', false);
		
		if (!$useInadmin && JFactory::getApplication()->isAdmin())
			return;
		
		$useCssfiles = (int) $this->params->get('cssfiles', false);
		$useBootstrap = (int) $this->params->get('bootstrap', false);
		$useJquery = (int) $this->params->get('jquery', false);
		$useJquery_ui = (int) $this->params->get('jquery_ui', false);
		$useNoconflict = (int) $this->params->get('noconflict', false);
		
		$doc = JFactory::getDocument();

		if ($useCssfiles)
			$doc->addStyleSheet('media/polarstrap/css/polarstrap.min.css');
		if ($useJquery)
			$doc->addScript('media/polarstrap/js/jquery.min.js');
		if ($useNoconflict)
			$doc->addScript('media/polarstrap/js/jquery-noconflict.js');
		if ($useJquery_ui)
		{
//			$doc->addStyleSheet('media/polarstrap/css/jquery-ui.min.css');
//			$doc->addScript('media/polarstrap/js/jquery-ui.min.js');
			$doc->addStyleSheet('media/polarstrap/css/jquery-ui-1.10.3.custom.min.css');
			$doc->addScript('media/polarstrap/js/jquery-ui.min.js');
		}
		if ($useBootstrap)
			$doc->addScript('media/polarstrap/js/bootstrap.min.js');
	}
}
