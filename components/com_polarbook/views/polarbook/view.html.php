<?php
defined('_JEXEC') or die;
jimport( 'joomla.html.html' );
class PolarbookViewPolarbook extends JViewLegacy
{
	protected $book;
	protected $moves;
	protected $status;
//	protected $bootstrapv;
	
	function display($tpl = null)
	{
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$db = JFactory::getDbo();
		$params=JComponentHelper::getParams('com_polarbook');
		$this->input = $app->input;
		$this->book=$this->input->getInt('book',0);
		$this->moves=urldecode($this->input->getString('moves',''));
		$this->status=urldecode($this->input->getString('status',''));
//		$this->bootstrapv=$params->get('bootstrapv','2');
		
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

		JHtml::_('bootstrap.framework');
//		JHtml::_('jquery.ui');
// 		$doc->addScript($this->baseurl . '/media/com_polarbook/js/jquery-ui.min.js','text/javascrpt');
// 		$doc->addScript( $this->baseurl . '/media/com_polarbook/js/ChessBoard.js','text/javascrpt',false);
// 		$doc->addScript( $this->baseurl . '/media/com_polarbook/js/ChessBoardView.js','text/javascrpt',false);
// 		$doc->addScript( $this->baseurl . '/media/com_polarbook/js/PolarBook.js','text/javascrpt',false);
// 		$doc->addScript( $this->baseurl . '/media/com_polarbook/js/jquery-ui.min.js');
// 		$doc->addScript( $this->baseurl . '/media/com_polarbook/js/ChessBoard.js');
// 		$doc->addScript( $this->baseurl . '/media/com_polarbook/js/ChessBoardView.js');
// 		$doc->addScript( $this->baseurl . '/media/com_polarbook/js/PolarBook.js');
		JHtml::script('com_polarbook/jquery-ui.min.js',false,true);
		JHtml::script('com_polarbook/ChessBoard.js',false,true);
		JHtml::script('com_polarbook/ChessBoardView.js',false,true);
		JHtml::script('com_polarbook/PolarBook.js',false,true);
		
		$user=JFactory::getUser();
		JFactory::getDocument()->addScriptDeclaration("
			var book=" . $this->book . ";
			var moves='" . $this->moves . "';
			var status='" . $this->status . "';
			var userid=" . $user->id . ";
			var username='';
			var imageUrl='" . $this->baseurl . "/media/com_polarbook/images/';
			var responseUrl='" . $this->baseurl . "/index.php?option=com_polarbook&amp;';
			var imagedir='" . $this->baseurl . "/media/com_polarbook/images/1/';
			var piecechar='" . JText::_('COM_POLARBOOK_PIECECHAR') . "';
		
			if ({$user->guest})
				userid=0;
					
			if (userid<1)
				username='" . JText::_('COM_POLARBOOK_USER_GUEST') . "';
			else
				username='" . $user->name . "';
		");
		
		
		$doc->addStyleSheet($this->baseurl . '/media/com_polarbook/css/template.css');
		
	
		parent::display($tpl);
	}
}
