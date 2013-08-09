<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/ChessBoard.php'; 

class StatisticsHelper
{
	public $pos=array(); // array([id,board,move,beenhere])
	var $path; // array([array(fen,move)])
	
	public function add($id, $fen, $moves)
	{
		$board=new ChessBoard();
		$ma=stringMovesToArray($moves);
		// Clear statistics
		$len=count($ma);
		for ($i=0;$i<$len;$i++)
			$ma[$i][3]=0;
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
		
		if (!isset($p) || (count($p[2])<1) || ($p[3]>0)){
			$this->addStatToPath();
			return;
		}
		
		$p[3]=1; // Been here
			
		$cb=new ChessBoard();
		foreach ($p[2] as $move)
		{
			$cb->copy($p[1]);
			array_push($this->path,array($fen,$move[0]));
			$cb->doMove($move[0]);
			$this->iterate($cb->getFen());
			array_pop($this->path);
		}
	}
	
	protected function addStatToPath()
	{
		foreach ($this->path as $moverec){
			$this->addStatToMove($moverec);
		}
	}
	
	protected function addStatToMove(&$moverec)
	{
		$p=&$this->pos[$moverec[0]];
		for ($i=0;$i<count($p[2]);$i++){
			if ($p[2][$i][0]==$moverec[1]){
				$p[2][$i][3]=$p[2][$i][3]+1;
				return;
			}
		}
	}
}