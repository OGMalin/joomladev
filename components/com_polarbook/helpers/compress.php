<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/ChessBoard.php'; 

class CompressHelper
{
	public $pos=array(); // array([id,board,move,beenhere])
	
	public function add($id, $fen, $moves)
	{
		$board=new ChessBoard();
		$ma=stringMovesToArray($moves);
		$board->setFen($fen);
		$this->pos[$fen]=array($id,$board,$ma,0);
	}
	
	public function run()
	{
		$this->path=array();
		$start='rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -';
		$this->iterate($start);
	}
	
	protected function iterate($fen)
	{
		$p=&$this->pos[$fen];
		
		if (!isset($p) || (count($p[2])<1) || ($p[3]>0))
			return;
		
		$p[3]=1; // Been here
			
		$cb=new ChessBoard();
		foreach ($p[2] as $move)
		{
			$cb->copy($p[1]);
			$cb->doMove($move[0]);
			$this->iterate($cb->getFen());
		}
	}
}