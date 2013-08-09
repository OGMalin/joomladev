<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/utility.php';
require_once JPATH_COMPONENT.'/helpers/statistics.php';
require_once JPATH_COMPONENT.'/helpers/compress.php';

jimport('joomla.application.component.modelitem');

/**
 * This models supports retrieving openingbooks from the database.
 * @package     Joomla
 * @subpackage  com_polarbook
 * @since       1.0
 *
 */
class PolarbookModelResponse extends JModelItem
{
	
	public function compress($id)
	{
		$comp=new CompressHelper();
		
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select(array('id','fen','moves'));
		$query->from('#__polarbook_data');
		$query->where('book_id='.$id);
		$db->setQuery($query);
		$db->execute();
		$results=$db->loadRowList();
		foreach ($results as $result){
			if (!$result)
				break;
			$comp->add($result[0],$result[1],$result[2]);
		}
		$comp->run();
		foreach ($comp->pos as $p) {
			if (!$p)
				break;
			if ($p[3]==0){
				$query=$db->getQuery(true);
				$query->delete('#__polarbook_data');
				$query->where('id='.$p[0]);
				$db->setQuery($query);
				$db->execute();
			}				
		}
		
		return array('status'=>0);
	}
	
	/**
	 * Get data for a given book.
	 * @param integer $bookid Id of the book to search for
	 * @return mixed An array filled med bookdata
	 * @since  1.0
	 */
	public function countPosition($id)
	{
		if ($id==0)
			return array('error'=>1021);
		$db=JFactory::getDbo();
		$result['position']="0";
		$query=$db->getQuery(true);
		$query->select('count(*)');
		$query->from('#__polarbook_data');
		$query->where('book_id='.$id);
		$db->setQuery($query);
		if ($db->execute()){
			$res=$db->loadRow();
			$result['position']=$res[0];
		}else{
			return array('error'=>1022);
		}
		
		return $result;
	}

	public function createRepertoire($id,$level,$white,$black)
	{
		$book=$this->getBook($id);
		if (isset($book['error']) && ($book['error']>0))
			return $book;
		
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select(array('id','fen','moves'));
		$query->from('#__polarbook_data');
		$query->where('book_id='.$id);
		$db->setQuery($query);
		$db->execute();
		$results=$db->loadRowList();
		foreach ($results as $result){
			if (!$result)
				break;
			$changed = false;
			if (((strpos($result[1],'w')!==FALSE) && ($white==1)) ||
					((strpos($result[1],'w')===FALSE) && ($black==1))){
				$moves=stringMovesToArray($result[2]);
				for ($i=0; $i< sizeof($moves);$i++){
					if ((($moves[$i][2]==0) && ($level>0)) ||
							(($moves[$i][2]>0) && ($level==0))){
						$moves[$i][2]=$level;
						$changed=true;
					}
				}
				if ($changed){
					$smove=arrayMovesToString($moves);
					$query = $db->getQuery(true);
					$query->update('#__polarbook_data');
					$query->where('id='.$result[0]);
					$query->set('moves='.$db->quote($smove));
					
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
		
		return $book;
	}

/**
 * Making statistics
 * @param integer $id Id of the book
 * @return book object
 */
	public function createStatistics($id)
	{

		$book=$this->getBook($id);
		if (isset($book['error']) && ($book['error']>0))
			return $book;

		$stat=new StatisticsHelper();
		
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select(array('id','fen','moves'));
		$query->from('#__polarbook_data');
		$query->where('book_id='.$id);
		$db->setQuery($query);
		$db->execute();
		$results=$db->loadRowList();
		foreach ($results as $result){
			if (!$result)
				break;
			$stat->add($result[0],$result[1],$result[2]);
		}
		$stat->run();
		foreach ($stat->pos as $p) {
			if (!$p)
				break;
			
			$moves=arrayMovesToString($p[2]);
			if ($moves) {
				$query = $db->getQuery(true);
				$query->update('#__polarbook_data');
				$query->where('id='.$p[0]);
				$query->set('moves='.$db->quote($moves));

				$db->setQuery($query);
				$db->execute();
			}
		}
		
		return $book;
	}
	
	/**
	 * Put a book into the trash, or if its allredy in the trash delete it and all positions belonging to it.
	 * @param integer $id Id of the book to delete.
	 * @return book object
	 * @since  1.0
	 */
	public function trashBook($id)
	{
		$res = $this->emptyTable('book');
		$db=JFactory::getDbo();
		// Delete all positions
		$query=$db->getQuery(true);
		$query->delete('#__polarbook_data');
		$query->where('book_id='.$id);
		$db->setQuery($query);
		if (!$db->execute()){
			$res['error']=1012;
			return $res;
		}
			// Delete the book info
		$query=$db->getQuery(true);
		$query->delete('#__polarbook_book');
		$query->where('id='.$id);
		$db->setQuery($query);
		if (!$db->execute())
			$res['error']=1013;
		return $res;
	}

	/**
	 * Create an array with default values to be used when a result is missing.
	 * @param  string  $table  The name of the table eg 'book', 'data' , or 'user'
	 * @return  mixed  An array object of the table with defaults.
	 * @since  1.0
	 */
	public function emptyTable($table)
	{
		switch ($table)
		{
			case 'book':
				return array('id'=>0,'name'=>'','user'=>0,'trashed'=>0,'parent'=>'','readusers'=>'','readgroups'=>'','writeusers'=>'','writegroups'=>'','created'=>'0000-00-00 00:00:00','modified'=>'0000-00-00 00:00:00','accessed'=>'0000-00-00 00:00:00','comment'=>'');
			case 'data':
				return array('id'=>0,'book_id'=>0,'fen'=>'','moves'=>'','comment'=>'');
			case 'user':
				return array('id'=>0);
		}
		return array();
	}

	/**
	 * Get data for a given book.
	 * @param integer $bookid Id of the book to search for
	 * @return mixed An array filled med bookdata
	 * @since  1.0
	 */
	public function getBook($id)
	{
		if ($id==0)
			return array('error'=>1021);
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('*');
		$query->from('#__polarbook_book');
		$query->where('id='.$id);
		$query->where('trashed=0');
		$db->setQuery($query);
		if ($db->execute())
			$result=$db->loadAssoc();
		else
			return array('error'=>1022);

		return $result;
	}

	/**
	 * Get a list of books available.
	 * @param integer $trash If $trash=1, it will return the users trash list.
	 * @return mixed An multible array of books ('id','name','access')
	 * @since  1.0
	 */
	public function getBookList()
	{
		$res=array();
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select(array('id','name','user'));
		$query->from('#__polarbook_book');
		$query->where('trashed=0');
		$query->order('name');
		$db->setQuery($query);
		if (!$db->execute())
			return array('error'=>1031);
		$results=$db->loadAssocList();
		$i=0;
		foreach ($results as $result){
			$access=$this->getBookAccess($result['id']);
			if ($access>0)
				$res[$i++]=array('id'=>$result['id'],'name'=>$result['name'],'access'=>$access,'owner'=>$result['user']);
		}
		return $res;
	}

	public function getUserList()
	{
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select(array('id','name'));
		$query->from('#__users');
		$query->where('block=0');
		$query->order('name');
		$db->setQuery($query);
		if (!$db->execute())
			return array('error'=>1071);
		return $db->loadAssocList();
	}
	
	/**
	 * Combine two books
	 * @param integer $id Existing book
	 * @param integer $import Book to import
	 * @since 1.0
	 */
	public function importBook($id, $import)
	{
		$res=array();
		
		// Get all positions from book to import
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select(array('fen','moves','comment'));
		$query->from('#__polarbook_data');
		$query->where('book_id='.$import);
		$db->setQuery($query);
		$db->execute();
		$results=$db->loadAssocList();
		foreach ($results as $result){
			if (!$result)
				break;
			$pos=$this->getPosition(0, $id, 0, 0, $result['fen']);
			if (isset($pos['error']) && ($pos['error']>0)){
				$result['book_id']=$id;
				$this->updatePosition($result);
			}else
			{
				if ($pos['comment']!=''){
					if (($result['comment']!='') && ($pos['comment']!=$result['comment']))
						$pos['comment'].="\n".$result['comment'];
				}else{
					$pos['comment']=$result['comment'];
				}
				$pos['moves'] = combineMoves($pos['moves'], $result['moves']);
				$this->updatePosition($pos);
			}
		}
		return $res;
	}
	
	/**
	 * Get a list of books marked as deleted.
	 * @param integer $trash If $trash=1, it will return the users trash list.
	 * @return mixed An multible array of books ('id','name','access')
	 * @since  1.0
	 */
	public function getTrashList()
	{
		$res=array();
		$user = JFactory::getUser();
		if (!$user->id || ($user->id<1))
			return $res;
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select(array('id','name'));
		$query->from('#__polarbook_book');
		$query->where('trashed=1');
		$db->setQuery($query);
		if (!$db->execute())
			return array('error'=>1031);
		$results=$db->loadAssocList();
		$i=0;
		foreach ($results as $result){
			if ($this->getBookAccess($result['id'])==2)
				$res[$i++]=array('id'=>$result['id'],'name'=>$result['name'],'access'=>2);
		}
		return $res;
	}

	/**
	 * 	Search for a position in the book.
	 * @param  string  $fen  The position in fen natation to search for.
	 * @param  integer  $book_id  The book to search.
	 * @return  mixed  Array with the position, on error check the array[error] field.
	 * @since  1.0
	 */
	public function getPosition($id, $book_id, $practice, $level, $fen)
	{
		if ($this->getBookAccess($book_id)<1)
			return array('error' => 1041);

		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('*');
		$query->from('#__polarbook_data');
		if ($id>0)
			$query->where('id='.$id);
		else
			$query->where('fen LIKE '.$db->quote($fen));
		$query->where('book_id='.$book_id);
		$db->setQuery($query);
		if (!$db->execute())
			return array('error' => 1042);
		if ($db->getNumRows()==0){
			$result=$this->emptyTable('data');
			$result['book_id']=$book_id;
			$result['fen']=$fen;
		}else{
			$result=$db->loadAssoc();
			// Remove moves without a practice move response
			if ($practice && $level)
				$this->checkPractice($id, $book_id, $result, $level, $fen);
		}
		return $result;
	}

	/**
	 * Check if current user have access to this book
	 * @param  integer  $book_id  Id for the book to check
	 * @return  integer  Where 0=No access, 1=read access, 2=write_access
	 * @since  1.0
	 */
	public function getBookAccess($book_id)
	{
		$user = JFactory::getUser();
		if (!isset($user->id))
			$user->id=0;

		// Only members can create new books
		if ($book_id==0)
		{
			if ($user->id>0)
				return 2;
			return 0;
		}

		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select(array('user','public','member','readusers','writeusers','trashed'));
		$query->from('#__polarbook_book');
		$query->where('id='.$book_id);
		$db->setQuery($query);
		$db->execute();
		$result=$db->loadAssoc();

		if (!$result)
			return 0;
		
		$access=$result['public'];
		
		// All visitors can write
		if ($access==2)
			return 2;

		// Public user
		if ($user->id==0)
			return $access;

		// Owner can write
		if ($result['user']==$user->id)
			return 2;
		
		// Keep best access of public and member
		if ($result['member']>$access)
			$access=$result['member'];
		
		// Allready write access?
		if ($access==2)
			return 2;

		// Added as a write user
		if ($result['writeusers'] && in_array($user->id,explode(';',$result['writeusers'])))
			return 2;

		// Allready read access
		if ($access==1)
			return 1;

		// Added as a read user
		if ($result['readusers'] && in_array($user->id,explode(';',$result['readusers'])))
			return 1;
		
		return 0;
	}

	/**
	 * Create book info.
	 * @param mixed $in Array with info of the new/modified book.
	 * @return mixed The resulting array.
	 * @since  1.0
	 */
	public function createBook($name)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		if ($name=='')
			return array('error'=>1051);

		$query->insert('#__polarbook_book');
		$query->set('user='.JFactory::getUser()->id);
		$query->set('trashed=0');
		$query->set('created=NOW()');
		$query->set('name='.$db->quote($name));
		$db->setQuery($query);
		$result=$db->execute();
		if ($result)
			return $this->getBook($db->insertid());
		return array('error'=>1053);
	}

	/**
	 * Add/Update book info.
	 * @param mixed $in Array with info of the new/modified book.
	 * @return mixed The resulting array.
	 * @since  1.0
	 */
	public function updateBook($in)
	{
 		$db = JFactory::getDbo();
 		$query = $db->getQuery(true);
		$query->update('#__polarbook_book');
		$query->where('id='.$in['id']);
		if (isset($in['name']))
			$query->set('name='.$db->quote($in['name']));
		if (isset($in['trashed']))
			$query->set('trashed='.$in['trashed']);
		if (isset($in['public']))
			$query->set('public='.$in['public']);
		if (isset($in['member']))
			$query->set('member='.$in['member']);
		if (isset($in['readusers']))
			$query->set('readusers='.$db->quote($in['readusers']));
		if (isset($in['writeusers']))
			$query->set('writeusers='.$db->quote($in['writeusers']));
		if (isset($in['comment']))
			$query->set('comment='.$db->quote($in['comment']));
		$db->setQuery($query);
		$result=$db->execute();
		if ($result)
			return $this->getBook($in['id']);
		return array('error'=>1053);
	}

	/**
	 * Update a moves, comment etc. for a position. If the position don't exist it will be created.
	 * @param mixed $pos The new position array.
	 * @return mixed The updated position. On a new position the id is updated too.
	 */
	public function updatePosition($in)
	{
		if ($in['book_id']==0)
			return array('error'=>1001);

		$db = JFactory::getDbo();

		// If there is no id, search for the position with use of the fenstring.
		if (!isset($in['id']) || ($in['id']==0)){
			if (!isset($in['fen']) || ($in['fen']==''))
				return array('error'=>1003);
			$in['id']=0;
			$query=$db->getQuery(true);
			$query->select('id');
			$query->from('#__polarbook_data');
			$query->where('fen='.$db->quote($in['fen']));
			$query->where('book_id='.$in['book_id']);
			$db->setQuery($query);
			if ($db->execute()){
				$res=$db->loadAssoc();
				if (!isset($res['id']))
					$in['id']=0;
				else
					$in['id']=$res['id'];
			}
		}
			
		$query = $db->getQuery(true);
		if ($in['id']==0){
			$query->insert('#__polarbook_data');
			$query->set('fen='.$db->quote($in['fen']));
			$query->set('book_id='.$in['book_id']);
		}else{
				$query->update('#__polarbook_data');
				$query->where('id='.$in['id']);
		}
		if (isset($in['moves']))
			$query->set('moves='.$db->quote($in['moves']));
		if (isset($in['comment']))
			$query->set('comment='.$db->quote($in['comment']));
		$db->setQuery($query);
		$result=$db->execute();
		if ($result){
			if ($in['id']>0)
				$res=$this->getPosition($in['id'],$in['book_id'],0,0,'');
			else
				$res=$this->getPosition($db->insertid(),$in['book_id'],0,0,'');
		}else{
			return array('error'=>1004);
		}
		return $res;
	}
	
	/**
	 * Check each moves to see if there is any repertoire move with correct level as response. If not, the move are removed.
	 * @param array $result
	 * @param int $level
	 * @since 1.0
	 */
	public function checkPractice($id, $book_id, &$result, $level, $fen)
	{
		$moves=stringMovesToArray($result['moves']);
		$newmoves=array();
		$cb=new ChessBoard();
		foreach ($moves as $move){
			$cb->setFen($fen);
			$cb->doMove($move[0]);
			$pos=$this->getPosition($id,$book_id, 0,0,$cb->getFen());
			if (isset($pos['moves']) && ($pos['moves']!='')){
				$resmoves=stringMovesToArray($pos['moves']);
				$found=false;
				foreach ($resmoves as $m){
					if (($m[2]!=0) && ($m[2]<=$level)){
						$found=true;
						break;
					}
				}
				if ($found)
					array_push($newmoves,$move);
			}
		}
		$result['moves']=arrayMovesToString($newmoves);
	}
}
