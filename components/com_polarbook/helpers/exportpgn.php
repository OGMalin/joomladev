<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/ChessBoard.php'; 

class ExportPgnHelper
{
	public $pos=array(); // array([board,comment,move,beenhere])
	var $path; // array([array(fen,move)])
	var $variation;
  var	$squares=array(
  			"a1","b1","c1","d1","e1","f1","g1","h1",
  			"a2","b2","c2","d2","e2","f2","g2","h2",
  			"a3","b3","c3","d3","e3","f3","g3","h3",
  			"a4","b4","c4","d4","e4","f4","g4","h4",
  			"a5","b5","c5","d5","e5","f5","g5","h5",
  			"a6","b6","c6","d6","e6","f6","g6","h6",
  			"a7","b7","c7","d7","e7","f7","g7","h7",
  			"a8","b8","c8","d8","e8","f8","g8","h8");
	public function add($fen, $comment, $moves)
	{
		$board=new ChessBoard();
		$ma=stringMovesToArray($moves);
		$board->setFen($fen);
		$this->pos[$fen]=array($board,$comment,$ma,0);
	}
	
	public function run()
	{
		$this->path=array();
		$this->variation=0;
		$start='rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -';
		$this->iterate($start);
	}
	
	protected function iterate($fen)
	{
		$p=&$this->pos[$fen];
		
		if (!isset($p) || (count($p[2])<1) || ($p[3]>0)){
			$this->printPath();
			return;
		}
		
		$p[3]=1; // Been here
			
		$cb=new ChessBoard();
		foreach ($p[2] as $move)
		{
			$cb->copy($p[0]);
			array_push($this->path,array($fen,$move[0]));
			$cb->doMove($move[0]);
			$this->iterate($cb->getFen());
			array_pop($this->path);
		}
	}

	protected function printPath()
	{
		$this->variation++;
		echo "[Event \"" . $this->variation . "\"]\n";
		echo "[Site \"?\"]\n";
		echo "[Date \"????.??.??\"]\n";
		echo "[Round \"?\"]\n";
		echo "[White \"?\"]\n";
		echo "[Black \"?\"]\n";
		echo "[Result \"*\"]\n\n";
		$nr=1;
		foreach ($this->path as $moverec){
			$p=&$this->pos[$moverec[0]];
			if ($p[1]!='')
			{
				echo " { " . $p[1] . " } ";
				$p[1]='';
			} 
			if ($nr%2)
				echo ($nr+1)/2 . ". ";
			echo $this->squares[$this->fromSquare($moverec[1])] . "-" . $this->squares[$this->toSquare($moverec[1])] . " ";
			$nr++;
		}
		echo " *\n\n";
	}
	
  protected function pieceValue($piece){return (($piece>6)?($piece-6):($piece));}
  protected function fromSquare($m){return ($m&0xff);}
  protected function toSquare($m){return (($m&0xff00)>>8);}
  protected function promotePiece($m){return (($m&0xff0000)>>16);}
}