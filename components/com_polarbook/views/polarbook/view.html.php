<?php
defined('_JEXEC') or die;

class PolarbookViewPolarbook extends JViewLegacy
{
	function display($tpl = null)
	{
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$db = JFactory::getDbo();
		$this->input = $app->input;
		$this->book=$this->input->getInt('book',0);
		$this->moves=urldecode($this->input->getString('moves',''));
		$this->status=urldecode($this->input->getString('status',''));
		
		// Get component description
		$query=$db->getQuery(true);
		$query->select('manifest_cache');
		$query->from('#__extensions');
		$query->where('element LIKE'.$db->quote('com_polarbook'));
		$db->setQuery($query);
		if ($db->execute())
		{
			$result=$db->loadAssoc();
			if ($result['manifest_cache'])
				$this->component=json_decode($result['manifest_cache'],true);
		};
		if (!isset($this->component))
				$this->component=array('version' => '');
		
		$doc->addScript( $this->baseurl . '/media/com_polarbook/js/jquery-ui.min.js','text/javascrpt',true);
		$doc->addScript( $this->baseurl . '/media/com_polarbook/js/ChessBoard.js','text/javascrpt',true);
		$doc->addScript( $this->baseurl . '/media/com_polarbook/js/ChessBoardView.js','text/javascrpt',true);
		$doc->addScript( $this->baseurl . '/media/com_polarbook/js/PolarBook.js','text/javascrpt',true);

		$doc->addStyleSheet($this->baseurl . '/media/com_polarbook/css/template.css');
		
	
		parent::display($tpl);
	}
}
