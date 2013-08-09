<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/ChessGame.php'; 
require_once JPATH_COMPONENT.'/helpers/Pgn.php';
require_once JPATH_COMPONENT.'/helpers/utility.php';

jimport('joomla.filesystem.file');

class ImportPgnHelper
{
	public $games=array();
	public $book_id;
	public $db;
	
	public function read($filename)
	{
		$file=JFile::read($filename);
		$file=str_replace("\n\r", "\n", $file);
		$file=utf8_encode($file);
		$i=stripos($file,"[Event ",0);
		while ($i!==FALSE){
			$j=stripos($file,"[Event ",$i+7);
			if ($j==FALSE){
				$game=trim(substr($file,$i));
				array_push($this->games,$game);
				break;
			}
			$game=trim(substr($file,$i,$j-$i));
			array_push($this->games,$game);
			$i=$j;
		}
	}
	
	public function import($book_id)
	{
		$this->book_id=$book_id;
		$this->db = JFactory::getDbo();
		$pgn = new Pgn();
		$game = new ChessGame();
		foreach($this->games as $gamestring){
			$pgn->read($gamestring, $game);
			$this->importGame($game);
		}
	}
	
	function importGame(&$game)
	{
		foreach ($game->pos as $pos){
			$this->updatePosition($pos);
			foreach ($pos->variation as $var){
				$this->importGame($var);
			}
		}
	}
	
	/**
	 * Update a moves, comment etc. for a position. If the position don't exist it will be created.
	 * @param mixed $pos The new position array.
	 * @return mixed The updated position. On a new position the id is updated too.
	 */
	public function updatePosition(&$pos)
	{
		if (($pos->move<1) && ($pos->comment==''))
			return; // Nothing to add
		
		$fen=$pos->board->getFen();

		$id=0;
		$moves='';
		$comment='';
		$query=$this->db->getQuery(true);
		$query->select(array('id','moves','comment'));
		$query->from('#__polarbook_data');
		$query->where('fen='.$this->db->quote($fen));
		$query->where('book_id='.$this->book_id);
		$this->db->setQuery($query);
		if ($this->db->execute()){
			$res=$this->db->loadRow();
			if ($res){
				$id=$res[0];
				$moves=$res[1];
				$comment=$res[2];
			}
		}
			
		$query = $this->db->getQuery(true);
		if ($id==0){
			$query->insert('#__polarbook_data');
			$query->set('fen='.$this->db->quote($fen));
			$query->set('book_id='.$this->book_id);
		}else{
				$query->update('#__polarbook_data');
				$query->where('id='.$id);
		}
		if ($pos->move>0){
			$moves=combineMoves($moves, $pos->move . '|' . $pos->movecomment . '|0|1');
			$query->set('moves='.$this->db->quote($moves));
		}
		if (($pos->comment!='') || ($pos->comment != $comment))
			$query->set('comment='.$this->db->quote($comment . "\n" . $pos->comment));
		$this->db->setQuery($query);
		$this->db->execute();
	}
	
}