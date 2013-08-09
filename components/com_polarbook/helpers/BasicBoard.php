<?php
defined('_JEXEC') or die;

class BasicBoard
{
	// empty=0
	// wp=1, wn=2, wb=3, wr=4, wq=5, wk=6
	// bp=7, bn=8, bb=9, br=10, bq=11, bk=12
	public $board=array();
	public $enPassant;
	public $castle; //wq=1, wk=2, bq=4, bk=8
	public $toMove;

	public function __construct()
	{
		$this->clear();
	}

	public function clear()
	{
		for ($sq=0;$sq<64;$sq++)
			$this->board[$sq]=0;
		$this->enPassant=0;
		$this->castle=0;
		$this->toMove=0;
	}
	
	public function copy(&$b)
	{
		for ($sq=0;$sq<64;$sq++)
			$this->board[$sq]=$b->board[$sq];
	
		$this->enPassant=$b->enPassant;
		$this->castle=$b->castle;
		$this->toMove=$b->toMove;
	}

	public function pieceValue($piece){return (($piece>6)?($piece-6):($piece));}
  
	public function fromSquare($m){return ($m&0xff);}
  
	public function toSquare($m){return (($m&0xff00)>>8);}
  
	public function promotePiece($m){return (($m&0xff0000)>>16);}
	public function setFen($fen)
	{
		$this->clear();
		$len = strlen($fen);
		$idx = 0;

		while (($idx < $len) && ($fen[$idx] == ' '))
			++$idx;
		if ($idx >= $len)
			return;

		$file = 0;
		$row = 7;
		while (($idx < $len) && ($fen[$idx] != ' ')){
			$piece=0;
			switch ($fen[$idx]){
				case 'P': $piece=1;break;
				case 'N': $piece=2; break;
				case 'B': $piece=3; break;
				case 'R': $piece=4; break;
				case 'Q': $piece=5; break;
				case 'K': $piece=6; break;
				case 'p': $piece=7; break;
				case 'n': $piece=8; break;
				case 'b': $piece=9; break;
				case 'r': $piece=10; break;
				case 'q': $piece=11; break;
				case 'k': $piece=12; break;
				case '1': case '2': case '3': case '4':
				case '5': case '6': case '7': case '8':
					$file+=$fen[$idx];
					break;
				case '/':
					if ($file>0)
						$file=8;
					break;
			}
			if ($piece!=0){
				$this->board[($row*8)+$file]=$piece;
				++$file;
			}
			++$idx;
			if ($file>7){
				$file=0;
				--$row;
			}
			if ($row < 0)
				break;
		}
		if ($idx>=$len)
			return;

		while (($idx < $len) && ($fen[$idx] == ' '))
			++$idx;
		if ($idx >= $len)
			return;

		if ($fen[$idx]=='b')
			$this->toMove=1;
		else
			$this->toMove=0;

		++$idx;

		while (($idx < $len) && ($fen[$idx] == ' '))
			++$idx;
		if ($idx >= $len)
			return;

		while (($idx<$len)&&($fen[$idx]!=' ')){
			switch ($fen[$idx]){
				case 'Q':
					$this->castle|=1;
					break;
				case 'K':
					$this->castle|=2;
					break;
				case 'q':
					$this->castle|=4;
					break;
				case 'k':
					$this->castle|=8;
					break;
			}
			++$idx;
		}

		++$idx;

		while (($idx < $len) && ($fen[$idx] == ' '))
			++$idx;
		if ($idx >= $len)
			return;

		$file=0;
		$row=0;
		if (($fen[$idx]>='a') && ($fen[$idx]<='h'))
			$file=$fen[$idx]-'a';
		++$idx;
		if ($idx >= $len)
			return;
		if (($fen[$idx]>='1') && ($fen[$idx]<='8'))
			$row=$fen[$idx]-'1';
		if ($row>0)
			$this->enPassant=$row*8+$file;
	}

	public function getFen()
	{
		$piecechar=" PNBRQKpnbrqk";
		$fen="";
		for ($row=7;$row>=0;$row--){
			$empty=0;
			for ($file=0;$file<8;$file++){
				$piece=$this->board[($row*8)+$file];
				if ($piece!=0){
					if ($empty){
						$fen.=$empty;
						$empty=0;
					}
					$fen.=$piecechar[$piece];
				}else{
					++$empty;
				}
			}
			if ($empty){
				$fen.=$empty;
				$empty=0;
			}
			if ($row)
				$fen.="/";
		}

		$fen.=' ';
		if ($this->toMove==0)
			$fen.='w';
		else
			$fen.='b';

		$fen.=' ';
		if ($this->castle){
			if ($this->castle&2)
				$fen.='K';
			if ($this->castle&1)
				$fen.='Q';
			if ($this->castle&8)
				$fen.='k';
			if ($this->castle&4)
				$fen.='q';
		} else {
			$fen.='-';
		}

		$fen.=' ';
		if ($this->enPassant!=0)
			$fen.= chr(($this->enPassant)%8+ord('a')) . chr(intval($this->enPassant/8)+ord('1'));
		else
			$fen.='-';

		return $fen;
	}
	
	public function doMove($move)
	{
		$sq=$this->fromSquare($move);
		$tosq=$this->toSquare($move);
		$pawnRow=($this->toMove)?-8:8;
		
		$other=$this->toMove?0:1;
		$piece=$this->pieceValue($this->board[$sq]);
		
		// Move piece
		$this->board[$tosq]=$this->board[$sq];
		$this->board[$sq]=0;
		
		// Pawn handling
		if ($piece==1){
			// Promoting
			if ($this->promotePiece($move))
				$this->board[$tosq]=$this->promotePiece($move);
		
			// Remove pawn on EnPassant move
			if (($this->enPassant!=0) && ($tosq==$this->enPassant))
				$this->board[$tosq-$pawnRow]=0;
		
			// Set new ep for doble pawnmoves. (only if possible)
			$this->enPassant=0;
			if ($tosq==($sq+(2*$pawnRow))){
				if (((($tosq%8)-1)>=0) && ($this->board[$tosq-1]==($other*6+1)))
					$this->enPassant=$sq+$pawnRow;
				elseif ((($tosq%8+1)<8) && ($this->board[$tosq+1])==($other*6+1))
					$this->enPassant=$sq+$pawnRow;
			}
		}else{
			$this->enPassant=0;
		}
		
		// King specific
		if ($piece==6)
		{
			// Move rook on castle
			if (($tosq-$sq)==2) // Kingside castle
			{
				$this->board[$sq+1]=$this->board[$tosq+1];
				$this->board[$tosq+1]=0;
			}
			if (($sq-$tosq)==2) // Queenside castle
			{
				$this->board[$tosq+1]=$this->board[$tosq-2];
				$this->board[$tosq-2]=0;
			}
			// No more castle rights
			if ($this->toMove==0)
				$this->castle&=12;
			else
				$this->castle&=3;
		}
		
		// Rook specific (capture or moved)
		if (($sq==0) || ($tosq==0)) // a1
			$this->castle&=14;
		if (($sq==7) || ($tosq==7)) // h1
			$this->castle&=13;
		if (($sq==56) || ($tosq==56)) // a8
			$this->castle&=11;
		if (($sq==63) || ($tosq==63)) // h8
			$this->castle&=7;
		
		// Hands over the move
		$this->toMove=$other;
	}

  public function pieceColor($piece)
  {
    if ($piece>6)
      return 1;
    if ($piece>0)
      return 0;
    return -1;
  }

}

