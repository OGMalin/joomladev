<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/BasicBoard.php';

class MoveList
{
	var $size;
	var $move;
  
	function __construct()
	{
		$this->clear();
	}

	public function clear()
	{
		$this->size=0;
	}
  
	function add($m)
	{
		$this->move[$this->size]=$m;
		++$this->size;
		return $this->size-1;
	}
  
	function erease($i)
	{
		if (($i>=$this->size) || ($this->size<1))
			return;
		--$this->size;
		$this->move[$i]=$this->move[$this->size];
	}
  
	function trunc()
	{
		$i=0;
		while ($i<$this->size){
			if ($this->move[$i]==0)
				$this->erease($i);
			else
				++$i;
		}
	}
}

class ChessBoard extends BasicBoard
{
  public $knightPath;
  public $kingPath;
  public $bishopPath;
  public $rookPath;
  
  function __construct()
  {
  	parent::__construct();
  	$this->knightPath = array ( -2,1, -1,2, 1,2, 2,1, 2,-1, 1,-2, -1,-2, -2,-1 );
  	$this->kingPath   = array ( -1,0, -1, 1, 0,1, 1,1,  1,0, 1,-1, 0,-1, -1,-1 );
  	$this->bishopPath = array ( -1,1, 1,1, 1,-1, -1,-1 );
  	$this->rookPath   = array ( -1,0, 0,1, 1,0, 0,-1 );
  }
  
  public function clear()
  {
  	parent::clear();
  }

  public function isAttacked($sq,$color)
  {
    $pawn=$color?7:1;
    $knight=$color?8:2;
    $bishop=$color?9:3;
    $rook=$color?10:4;
    $queen=$color?11:5;
    $king=$color?12:6;
    $file=$sq%8;
    // Attacked from diagonals
    $i=0;
    while ($i<8)
    {
      $tofile=$file+$this->bishopPath[$i];
      $torow=(int)($sq/8)+$this->bishopPath[($i+1)];
      $tosq=$tofile+$torow*8;
      while (($tofile>=0) && ($tofile<8) && ($torow>=0) && ($torow<8))
      {
        if ($this->pieceColor($this->board[$tosq])!=-1)
        {
          if (($this->board[$tosq]==$bishop) || ($this->board[$tosq]==$queen))
            return true;
          break;
        }
        $tofile+=$this->bishopPath[$i];
        $torow+=$this->bishopPath[($i+1)];
        $tosq=$tofile+$torow*8;
      }
      $i+=2;
    }

    // Attacked from rank and file
    $i=0;
    while ($i<8)
    {
      $tofile=$file+$this->rookPath[$i];
      $torow=(int)($sq/8)+$this->rookPath[($i+1)];
      $tosq=$tofile+$torow*8;
      while (($tofile>=0) && ($tofile<8) && ($torow>=0) && ($torow<8))
      {
        if ($this->pieceColor($this->board[$tosq])!=-1)
        {
          if (($this->board[$tosq]==$rook) || ($this->board[$tosq]==$queen))
            return true;
          break;
        }
        $tofile+=$this->rookPath[$i];
        $torow+=$this->rookPath[($i+1)];
        $tosq=$tofile+$torow*8;
      }
      $i+=2;
    }
    // Attacked from knight and king
    $i=0;
    while ($i<16)
    {
      $tofile=$file+$this->knightPath[$i];
      $torow=(int)($sq/8)+$this->knightPath[($i+1)];
      $tosq=$tofile+$torow*8;
      if (($tofile>=0) && ($tofile<8) && ($torow>=0) && ($torow<8))
        if ($this->board[$tosq]==$knight)
          return true;
      $tofile=$file+$this->kingPath[$i];
      $torow=(int)($sq/8)+$this->kingPath[($i+1)];
      $tosq=$tofile+$torow*8;
      if (($tofile>=0) && ($tofile<8) && ($torow>=0) && ($torow<8))
        if ($this->board[$tosq]==$king)
          return true;
      $i+=2;
    }

    // Pawns
    $tofile=$file-1;
    $torow=(int)($sq/8)+($color?1:-1);
    $tosq=$tofile+$torow*8;
    for ($i=0;$i<2;$i++)
    {
      if (($tofile>=0) && ($tofile<8) && ($torow>=0) && ($torow<8))
        if ($this->board[$tosq]==$pawn)
          return true;
      $tofile=$file+1;
      $tosq=$tofile+$torow*8;
    }
    return false;
  }

  public function addPawnMoves(&$ml,$sq)
  {
    $pawnRow=($this->toMove)?-8:8;

    // One square forward;
    $tosq=$sq+$pawnRow;
    if ($this->board[$tosq]==0)
    {
      if (($tosq<8)||($tosq>55))
        $this->addPawnPromote($ml,$sq|($tosq<<8));
      else
        $ml->add($sq|($tosq<<8));
    }

    // Capture
    $file=$sq%8;
    $capfile=-1; // Look first to left
    for ($i=0;$i<2;$i++)
    {
      if ((($file+$capfile)>=0) && (($file+$capfile)<8))
      {
        $tosq=$sq+$capfile+$pawnRow;
        if (($this->board[$tosq]!=0) && ($this->pieceColor($this->board[$tosq])!=$this->toMove))
			  {
          if (($tosq<8)||($tosq>55))
            $this->addPawnPromote($ml,$sq+($tosq<<8));
          else
            $ml->add($sq+($tosq<<8));
        }elseif ($tosq==$this->enPassant)
        {
          $ml->add($sq|($tosq<<8));
        }
      }
	    $capfile=1;  // Look to the right
    }

    // Two square forward
    if ((((int)($sq/8)==1)&&($this->toMove==0)) || (((int)($sq/8)==6)&&($this->toMove==1)))
      if (($this->board[$sq+$pawnRow]==0) && ($this->board[$sq+$pawnRow*2]==0))
        $ml->add($sq|(($pawnRow*2+$sq)<<8));
  }

  public function addPawnPromote(&$ml, $m)
  {
    $c=$this->toMove?6:0;
    $ml->add($m|((2+$c)<<16));
    $ml->add($m|((3+$c)<<16));
    $ml->add($m|((4+$c)<<16));
    $ml->add($m|((5+$c)<<16));
  }

  public function addNoSlideMoves(&$ml, $sq, &$sqadd)
  {
    $file=$sq%8;
    $i=0;
    while($i<16)
    {
      $tofile=$file+$sqadd[$i];
      $torow=(int)($sq/8)+$sqadd[($i+1)];
      if (($tofile>=0) && ($tofile<8) && ($torow>=0) && ($torow<8))
      {
        $tosq=$tofile+$torow*8;
        if ($this->pieceColor($this->board[$tosq])!=$this->toMove)
          $ml->add($sq|($tosq<<8));
      }
      $i+=2;
    }
  }

  function addSlideMoves(&$ml, $sq, &$sqadd)
  {
    $file=$sq%8;
    $i=0;
    $other=$this->toMove?0:1;
    while ($i<8)
    {
      $tofile=$file+$sqadd[$i];
      $torow=(int)($sq/8)+$sqadd[($i+1)];
      $tosq=$tofile+$torow*8;
      while (($tofile>=0) && ($tofile<8) && ($torow>=0) && ($torow<8) && ($this->pieceColor($this->board[$tosq])!=$this->toMove))
      {
        $ml->add($sq|($tosq<<8));
        if ($this->pieceColor($this->board[$tosq])==$other)
          break;
        $tofile+=$sqadd[$i];
        $torow+=$sqadd[($i+1)];
        $tosq=$tofile+$torow*8;
      }
      $i+=2;
    }
  }

  public function addKingMoves(&$ml,$sq)
  {
    $this->addNoSlideMoves($ml,$sq,$this->kingPath);

    // castle
    // Make black and white castle rights look the same.
    $ctl=($this->toMove)?$this->castle>>2:$this->castle;

    if ($ctl&1)
      if (($this->board[$sq-1]==0) && ($this->board[$sq-2]==0) && ($this->board[$sq-3]==0))
        $ml->add($sq+(($sq-2)<<8));
    if ($ctl&2)
      if (($this->board[$sq+1]==0) && ($this->board[$sq+2]==0))
        $ml->add($sq+(($sq+2)<<8));
  }
  
  public function makeMoves(&$ml)
  {
    $sq=0;
    $ml->clear();
    $player=$this->toMove;
    $other=$player?0:1;

    // Add all possible moves without testing for check
    while (1)
    {
      $piece=$this->board[$sq];
      if ($this->pieceColor($piece)==$player)
      {
        if ($piece>6)
          $piece-=6;
        switch ($piece)
        {
          case 1:
            $this->addPawnMoves($ml,$sq);
            break;
          case 2:
            $this->addNoSlideMoves($ml,$sq,$this->knightPath);
            break;
          case 3:
            $this->addSlideMoves($ml,$sq,$this->bishopPath);
            break;
          case 4:
            $this->addSlideMoves($ml,$sq,$this->rookPath);
            break;
          case 5:
            $this->addSlideMoves($ml,$sq,$this->bishopPath);
            $this->addSlideMoves($ml,$sq,$this->rookPath);
            break;
          case 6:
            $this->addKingMoves($ml,$sq);
            break;
        }
      }
      ++$sq;
      if ($sq>63)
        break;
    }
    // Check legality
    $btemp=new ChessBoard();
    if ($this->toMove)
      $king=12;
    else
      $king=6;
    $sq=0;
    while ($sq<64)
      if ($this->board[$sq++]==$king)
        break;
    $ksq=$sq-1;
    $i=0;
    while ($i<$ml->size)
    {
      $m=$ml->move[$i];

      if ($this->fromSquare($m)==$ksq) // King move
      {
        // Castle
        if (($ksq+2==$this->toSquare($m)) || ($ksq-2==$this->toSquare($m)))
        {
          if ($this->isAttacked($ksq,$other)) // In check before move
          {
            $ml->move[$i]=0;
          }else
          {
            // Test the square the king is passing.
            if ($ksq+2==$this->toSquare($m))
            {
              if ($this->isAttacked($ksq+1,$other))
                $ml->move[$i]=0;
            }else
            {
              if ($this->isAttacked($ksq-1,$other))
                $ml->move[$i]=0;
            }
          }
        }
        $tsq=$this->toSquare($m); // Check the new king square
      }else
      {
        $tsq=$ksq;
      }
      // Do a move and test if this lead to setting the player in check.
      $btemp->copy($this);
      $btemp->doMove($m);
      if ($btemp->isAttacked($tsq,$other))
        $ml->move[$i]=0;
      ++$i;
    }
    $ml->trunc();
  }

function getMoveFromText($s)
  {
    $ml = new MoveList();
    $allmoves = new MoveList();
    $fRow=-1;
    $fFile=-1;
    $tRow=-1;
    $tFile=-1;
    $mt=$this->stripMoveText($s);
    $len=strlen($mt);
    if ($len<2)
      return 0;
    $piece=0;
    $ppiece=0;

    // Castle
    if (strncmp($mt,'OO',2)==0)
    {
      $fFile=4;
      if (strncmp($mt,'OOO',3)==0)
        $tFile=2;
      else
        $tFile=6;
      if ($this->toMove==0)
      {
        $fRow=0;
        $tRow=0;
      }else
      {
        $fRow=7;
        $tRow=7;
      }
      $piece=6;
    }else
    {
      $piece=$this->getPieceFromChar($mt[0]);
      $i=$len-1;
      if ($this->isPieceChar($mt[$i]))
      {
        $ppiece=$this->getPieceFromChar($mt[$i]);
        --$i;
      }
      while ($i>=0)
      {
        if ($this->isFileChar($mt[$i]))
        {
          if ($tFile==-1)
            $tFile=ord($mt[$i])-ord('a');
          else
            $fFile=ord($mt[$i])-ord('a');
        }
        if ($this->isRowChar($mt[$i]))
        {
          if ($tRow==-1)
            $tRow=ord($mt[$i])-ord('1');
          else
            $fRow=ord($mt[$i])-ord('1');
        }
        --$i;
      }
      if (($fFile>7) || ($fRow>7) || ($tFile>7) || ($tRow>7))
        return 0;
      if (($piece==0) && ($fFile==-1))
        $fFile=$tFile;
      if (($piece==1) && ($fFile==-1))
        $fFile=$tFile;
      if (($fFile>=0) && ($fRow>=0))
        $piece=$this->pieceValue($this->board[$fFile+$fRow*8]);
      if ($piece==0)
        $piece=1;
    }

    $piece=($this->toMove)?$piece+6:$piece;
    if ($ppiece)
      $ppiece=($this->toMove)?$ppiece+6:$ppiece;
    $this->makeMoves($allmoves);
    $moveit=0;
    $ml->clear();
    while ($moveit<$allmoves->size)
    {
      $m=$allmoves->move[$moveit];
      $fsq=$this->fromSquare($m);
      $tsq=$this->toSquare($m);
      if ($piece==$this->board[$fsq])
      {
        if ($fFile>=0)
        {
          if ($fFile!=$fsq%8)
          {
            ++$moveit;
            continue;
          }
        }
        if ($tFile>=0)
        {
          if ($tFile!=($tsq%8))
          {
            ++$moveit;
            continue;
          }
        }
        if ($fRow>=0)
        {
          if ($fRow!=(int)($fsq/8))
          {
            ++$moveit;
            continue;
          }
        }
        if ($tRow>=0)
        {
          if ($tRow!=(int)($tsq/8))
          {
            ++$moveit;
            continue;
          }
        }
        if ($ppiece!=$this->promotePiece($m))
        {
          ++$moveit;
          continue;
        }
        $ml->add($m);
      }
      ++$moveit;
    }
    if ($ml->size==1)
      $m=$ml->move[0];
    else
      $m=0;
    return $m;
  }

 	function stripMoveText(&$s)
  {
    $i=0;
    $j=0;
    $len=strlen($s);
    $mt="";
    while (!$this->isFileChar($s[$i]) && !$this->isPieceChar($s[$i]) && ($s[$i]!='O'))
    {
      if ($i>=strlen($s))
        return "";
      ++$i;
    }
    while ($i<$len)
    {
      if ($this->isFileChar($s[$i]) || $this->isRowChar($s[$i]) || $this->isPieceChar($s[$i]) || $s[$i]=='O')
        $mt .= $s[$i];
      ++$i;
    }

    $len=strlen($mt);
    // For a move like exd6 e.p. the last 'e' would still exist in the movetext (ed6e).
    if ($len<2)
      return "";
    elseif (($len>2)&&($mt[$len-1]=='e'))
      return substr($mt,0,$len-1);
    return $mt;
  }

  function pieceValue($piece){return (($piece>6)?($piece-6):($piece));}
  function fromSquare($m){return ($m&0xff);}
  function toSquare($m){return (($m&0xff00)>>8);}
  function promotePiece($m){return (($m&0xff0000)>>16);}
  function isRowChar($c){return (stripos('12345678',$c)!==FALSE);}
  function isFileChar($c){return (stripos('abcdefgh',$c)!==FALSE);}
  function isPieceChar($c){return (stripos('NBRQK',$c)!==FALSE);}
  function getPieceFromChar($c)
  {
  	switch ($c)
  	{
  		case 'N': return 2;
  		case 'B': return 3;
  		case 'R': return 4;
  		case 'Q': return 5;
  		case 'K': return 6;
  	}
  	return 0;
  }
}


