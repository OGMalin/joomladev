<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/Pgn.php';

/**
 * 
 * @author oddg
 *
 */
class PolarbookControllerTest extends JControllerLegacy
{
	public function BasicBoard()
	{
		echo "<h2>BasicBoard</h2>";
	}

	public function ChessBoard()
	{
		echo "<h2>ChessBoard</h2>";
		$fen="rnbqkb1r/pp1p1ppp/2p5/4P3/2B5/8/PPP1NnPP/RNBQK2R w KQkq - 0 6";
		
		echo "\$cb = new ChessBoard()<br/>";
		$cb = new ChessBoard();
		
		echo "\$cb->setFen('" . $fen . "')<br/>";
		$cb->setFen($fen);
		echo "\$cb->getFen()<br/>";
		echo $cb->getFen() . "<br/>";
		
		echo "Move generator<br/>";
		echo $this->movegenTest(3, $cb);
	}
	
	public function ChessGame()
	{
		echo "<h2>ChessGame</h2>";
		$game=new ChessGame();
		$game->startposition('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
		var_dump($game);
	}
	
	public function Pgn()
	{
		echo "<h2>Pgn</h2>";
		$pgn=new Pgn();
		$game=new ChessGame();
		$s=
			"[Event \"Turnering\"]\n"
			."[Site \"Spilested\"]\n"
			."[Round \"1\"\n"
			."[White \"Hvit\"\n"
			."[Black \"Sort\"\n"
			."[Date \"2013.05.27\"\n"
			."[Result \"*\"\n\n"
			."1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 *";
		$pgn->read($s,$game);
		var_dump($game);
	}
	
	function movegenTest($depth, $cb)
	{
		return $this->rec_movegen($depth, 0, $cb, 0);
	}
	
	function rec_movegen($depth, $ply, $cp, $nodes)
	{
		$moveit = 0;
		$b = new ChessBoard();
		$ml = new MoveList();
		$cp->makeMoves($ml);
		
		while ($moveit<$ml->size)
		{
			if ($depth > 1)
			{
				$b->copy($cp);
				$b->doMove($ml->move[$moveit]);
				$nodes = $this->rec_movegen($depth - 1, $ply + 1, $b, $nodes);
			}
			else
			{
				++$nodes;
			}
			++$moveit;
		}
		return $nodes;
	}
}
