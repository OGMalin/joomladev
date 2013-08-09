<?php
defined('_JEXEC') or die;

/**
 * 
 * @author oddg
 *
 */
class PolarbookControllerImport extends JControllerLegacy
{
	public function file()
	{
		echo "...";
		$status='';
		$model=$this->getModel('Import');		
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$book_id=$this->input->getInt('book_id',0);
		$option=$this->input->getString('option');
		if ($book_id>0){
			$access=$model->getBookAccess($book_id);
			if ($access==2){
				$status=$model->importFile($book_id);
			}else{
				$status="No access";
			}
		}else{
			$status="No book";
		}
		$url=JURI::base();
		$url .= '?option=' . $option;
		$url=str_replace('&amp;', '&', $url);
		$url=JRoute::_($url);
		$url=str_replace('&amp;', '&', $url);
		$url .= "&book=" . $book_id;
		if ($status!='')
			$url .= "&status=" . urlencode($status);
		$app->redirect($url);
	}
}
