<?php
defined('_JEXEC') or die;

/**
 * 
 * @author oddg
 *
 */
class PolarbookControllerResponse extends JControllerLegacy
{
	/**
	 * Remove position that can be reached by any book moves
	 */
	function compress()
	{
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$id=$this->input->getInt('id',0);
	
		if (($id>0)&&($model->getBookAccess($id)==2)){
			$res=$model->compress($id);
			$res['access']=2;
		}else{
			$res=array('error' => 2031);
		}
		$doc->setMimeEncoding('application/json');
		echo json_encode($res);
		$app->close();
	}
	
	function countposition()
	{
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$id=$this->input->getInt('id',0);
		
		if ($id>0){
			$access=$model->getBookAccess($id);
			if ($access)
				$res=$model->countPosition($id);
			else
				$res=array("error" => 2002);
			$res['access']=$access;
		}else{
			$res=array("error" => 2001);
		}
		$doc->setMimeEncoding('application/json');
		echo json_encode($res);
		$app->close();
	}
	
	/**
	 * Ajax response
	 * Create a book. It will send back to the client the book info as an JSON string.
	 */
	function createbook()
	{
		$in=array();
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$name=$this->input->getString('name');
		if (isset($name))
			$name=urldecode($name);
	
		if ($model->getBookAccess(0)==2){
			$result=$model->createBook($name);
			$result['access']=2;
		}else{
			$result=array("error" => 2061);
		}
				
		$doc->setMimeEncoding('application/json');
		echo json_encode($result);
		$app->close();
	}
	
/**
 * Ajax response
 * Lists book that a user can view.
 * @return mixed An multible array of books ('id','name','access')
 * @since  1.0
 */
	function getbooklist()
	{
		$model=$this->getModel('Response');
 		$doc = JFactory::getDocument();
 		$app = JFactory::getApplication();
		$result=$model->getBookList();
		$doc->setMimeEncoding('application/json');
		echo json_encode($result);
		$app->close();
	}

/**
 * Ajax response
 * Lists book that a user can remove.
 * @return mixed An multible array of books ('id','name','access')
 * @since  1.0
 */
	function gettrashlist()
	{
		$model=$this->getModel('Response');
 		$doc = JFactory::getDocument();
 		$app = JFactory::getApplication();
		$result=$model->getTrashList();
		$doc->setMimeEncoding('application/json');
		echo json_encode($result);
		$app->close();
	}

/**
 * Ajax response
 * Get a list of (Joomla) users.
 * @return mixed An multiple array of users ('id','name')
 * @since  1.0
 */
	function getuserlist()
	{
		$model=$this->getModel('Response');
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();
		$result=$model->getUserList();
		$doc->setMimeEncoding('application/json');
		echo json_encode($result);
		$app->close();
	}

/**
 * Ajax reponse
 * Create a repertoire
 * @return Bookinfo
 * @since  1.0
 */
	function createrepertoire()
	{
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$id=$this->input->getInt('id',0);
		$white=$this->input->getInt('white',0);
		$black=$this->input->getInt('black',0);
		$level=$this->input->getInt('level',1);
		
		if (($id>0)&&($model->getBookAccess($id)==2)){
			$res=$model->createRepertoire($id,$level,$white,$black);
			$res['access']=2;
		}else{
			$res=array('error' => 2041);
		}
		$doc->setMimeEncoding('application/json');
		echo json_encode($res);
		$app->close();
	}
	
	function createstatistics()
	{
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$id=$this->input->getInt('id',0);
	
		if (($id>0)&&($model->getBookAccess($id)==2)){
			$res=$model->createStatistics($id);
			$res['access']=2;
		}else{
			$res=array('error' => 2031);
		}
		$doc->setMimeEncoding('application/json');
		echo json_encode($res);
		$app->close();
	}
	
		/**
	 * Ajax response
	 * Remove a book. The book must be marked trashed=1.
	 * First call mark the book as deleted, The next call removes the book with all its positions.
	 */
	function trashbook()
	{
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$id=$this->input->getInt('id',0);
	
		if (($id>0)&&($model->getBookAccess($id)==2))
			$res=$model->trashBook($id);
		else
			$res=array('error' => 2011);
		$doc->setMimeEncoding('application/json');
		echo json_encode($res);
		$app->close();
	}
	
	/**
	 * Ajax response
	 */
	function getposition()
	{
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$id=$this->input->getInt('id',0);
		$book_id=$this->input->getInt('book_id',0);
		$practice=$this->input->getInt('practice',0);
		$level=$this->input->getInt('level',0);
		$fen=urldecode($this->input->getString('fen',''));
		//		$res=array('error'=>1, 'id'=>$id, 'book_id'=>$book_id, 'fen' => $fen);
		if (($book_id==0) && (($fen=='') || ($id==0)))
			$res=array('error' => 2021);
		else
			$res=$model->getPosition($id,$book_id,$practice,$level,$fen);
		$doc->setMimeEncoding('application/json');
		echo json_encode($res);
		$app->close();
	}
	
	/**
	 * Ajax response
	 */
	function importbook()
	{
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$id=$this->input->getInt('id',0);
		$import=$this->input->getInt('import',0);
		
		if (($id>0) && ($import>0)){
			$access=$model->getBookAccess($id);
			if ($access==2)
				$res=$model->importBook($id, $import);
			else
				$res=array("error" => 2002);
			$res['access']=$access;
		}else{
			$res=array("error" => 2001);
		}
		$doc->setMimeEncoding('application/json');
		echo json_encode($res);
		$app->close();
	}
	
	/**
	 * Ajax response
	 */
	function openbook()
	{
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$id=$this->input->getInt('id',0);
	
		if ($id>0){
			$access=$model->getBookAccess($id);
			if ($access)
				$res=$model->getBook($id);
			else
				$res=array("error" => 2002);
			$res['access']=$access;
		}else{
			$res=array("error" => 2001);
		}
		$doc->setMimeEncoding('application/json');
		echo json_encode($res);
		$app->close();
	}
	
	/**
	 * Ajax response
	 * Update book info. It will send back to the client the book info as an JSON string.
	 */
	function updatebook()
	{
		$in=array();
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
	
		$in['id']=$this->input->getInt('id',0);
		$in['trashed']=$this->input->getInt('trashed');
		$in['public']=$this->input->getInt('public');
		$in['member']=$this->input->getInt('member');
		$in['name']=$this->input->getString('name');
		if (isset($in['name']))
			$in['name']=urldecode($in['name']);
		$in['readusers']=$this->input->getString('readusers');
		if (isset($in['readusers']))
			$in['readusers']=urldecode($in['readusers']);
		$in['writeusers']=$this->input->getString('writeusers');
		if (isset($in['writeusers']))
			$in['writeusers']=urldecode($in['writeusers']);
		$in['comment']=$this->input->getString('comment');
		if (isset($in['comment']))
			$in['comment']=urldecode($in['comment']);
		
		if ($model->getBookAccess($in['id'])==2){
			$result=$model->updateBook($in);
			$result['access']=2;
		}else{
			$result=array("error" => 2051);
		}
		$doc->setMimeEncoding('application/json');
		echo json_encode($result);
		$app->close();
	}
	
	function updateposition()
	{
		$in=array();
		$model=$this->getModel('Response');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
	
		$in['id']=$this->input->getInt('id',0);
		$in['book_id']=$this->input->getInt('book_id',0);
		$in['fen']=urldecode($this->input->getString('fen',''));
		$in['moves']=$this->input->getString('moves');
		if (isset($in['moves']))
			$in['moves']=urldecode($in['moves']);
		$in['comment']=$this->input->getString('comment');
		if (isset($in['comment']))
			$in['comment']=urldecode($in['comment']);
	
		if (($in['book_id']>0)&&($model->getBookAccess($in['book_id'])==2)){
			$result=$model->updatePosition($in);
			$result['access']=2;
		}else{
			$result=array("error" => 2051);
		}
				
		$doc->setMimeEncoding('application/json');
		echo json_encode($result);
		$app->close();
	}
}