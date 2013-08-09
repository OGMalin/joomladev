<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/ChessBoard.php';

class ChessPosition
{
	public $board;
	public $comment;
	public $movecomment;
	public $move;
	public $variation;
	
	function __construct()
	{
		$this->board=new ChessBoard();
		$this->clear();
	}
	
	public function clear()
	{
		$this->board->clear();
		$this->comment="";
		$this->move=0;
		$this->movecomment="";
		$this->variation=array();
  }
}

class ChessGame
{
	public $event;
	public $site;
	public $date;
	public $round;
	public $white;
	public $black;
	public $result;
	public $pos;
  public $firstmove; // First halfmove in the game

	function __construct()
	{
		$this->clear();
	}

	public function clear()
	{
		$this->event='';
		$this->site='';
		$this->date='';
		$this->round='';
		$this->white='';
		$this->black='';
		$this->result='';
		$this->firstmove=-1;
		$this->pos=array();
		array_push($this->pos, new ChessPosition());
	}
	
	public function startposition($fen)
	{
		$this->pos[0]->board->setFen($fen);
	}
  
}