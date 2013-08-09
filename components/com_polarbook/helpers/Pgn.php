<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/ChessGame.php';

class Pgn
{
	public function read($s, &$game)
	{
		$game->clear();

    $lines=explode("\n",$s);
    $nr=count($lines);
    $n=0;
    $setup="";
    $movetext="";

    while ($n<$nr)
    {
      $line=trim($lines[$n]);
      if (substr($line,0,7)=="[Event ")
        $game->event=$this->getTagText($line);
      elseif (substr($line,0,6)=="[Site ")
        $game->site=$this->getTagText($line);
      elseif (substr($line,0,6)=="[Date ")
        $game->date=$this->getTagText($line);
      elseif (substr($line,0,7)=="[Round ")
        $game->round=$this->getTagText($line);
      elseif (substr($line,0,7)=="[White ")
        $game->white=$this->getTagText($line);
      elseif (substr($line,0,7)=="[Black ")
        $game->black=$this->getTagText($line);
      elseif (substr($line,0,8)=="[Result ")
        $game->result=$this->getTagText($line);
//       elseif (substr($line,0,8)=="[Remark ")
//         $game->remark=$this->getTagText($line);
//       elseif (substr($line,0,5)=="[ECO ")
//         $game->eco=$this->getTagText($line);
      elseif (substr($line,0,5)=="[FEN ")
        $setup=$this->getTagText($line);
//       elseif (substr($line,0,10)=="[WhiteElo ")
//         $game->welo=$this->getTagText($line);
//       elseif (substr($line,0,10)=="[BlackElo ")
//         $game->belo=$this->getTagText($line);
//       elseif (substr($line,0,11)=="[Annotator ")
//         $game->annotator=$this->getTagText($line);
      elseif (substr($line,0,1)!="[")
        $movetext .= $line . " ";
      ++$n;
    }
    if ($setup=="")
      $setup="rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";
    $game->startposition($setup);

    $this->parseMovetext($game,$movetext,0);
	}
  
	function parseMovetext(&$gm, $str,$current)
	{
		$index=0;
		while (($token=$this->nextToken($str,$value,$index))!=-1)
		{
			switch ($token){
				case 1 : // move
					$m=$gm->pos[$current]->board->getMoveFromText($value);
					if ($m){
						$gm->pos[$current]->move=$m;
						++$current;
						$gm->pos[$current]= new ChessPosition;
						$gm->pos[$current]->board->copy($gm->pos[$current-1]->board);
						$gm->pos[$current]->board->doMove($m);
					}
					break;
				case 2 : // Bracket
					break;
				case 3 : // Variation
					if ($current<1)
						break;
					if (!isset($gm->pos[$current-1]->variation))
						$i=0;
					else
						$i=count($gm->pos[$current-1]->variation);
					$gm->pos[$current-1]->variation[$i]= new ChessGame;
					$gm->pos[$current-1]->variation[$i]->pos[0]= new ChessPosition;
					$gm->pos[$current-1]->variation[$i]->pos[0]->board->copy($gm->pos[$current-1]->board);
					$this->parseMovetext($gm->pos[$current-1]->variation[$i], $value,0);
					break;
				case 4 : // comment
					$this->expandNags($value);
					$this->splitComment($mc,$c,$value);
					if ($mc!='')
						$gm->pos[$current]->movecomment .= $mc;
					if ($c!='')
						$gm->pos[$current]->comment .= ' ' . $c;
					break;
				case 5 : // shortcomment
					$gm->pos[$current]->movecomment .= $value;
					break;
				case 6 : // nag
					$this->expandNags($value);
					$this->splitComment($mc,$c,$value);
					if ($mc!='')
						$gm->pos[$current]->movecomment .= $mc;
					if ($c!='')
						$gm->pos[$current]->comment .= ' ' . $c;
					break;
				case 7 : // symbol
					break;
				case 8 : //Number
					if ($gm->firstmove==-1)
						$gm->firstmove=$value*2-1-($gm->pos[0]->board->toMove?0:1);
					break;
			}
		}
		if ($gm->firstmove==-1)
			$gm->firstmove=0;
  }

  function getTagText($line)
  {
  	$first=strpos($line,"\"");
  	$last=strrpos($line,"\"");
  	if ($first===false || $last===false)
  		return "";
  	$first++;
  	$last--;
  	if ($first>$last)
  		return "";
  	$res=trim(substr($line,$first,$last-$first+1));
  	if ($res=="?")
  		return "";
  	return $res;
  }
  
  function getValue(&$s, &$value, $start, $end, &$index)
  {
  	$value="";
  	if (strlen($s)<=$index)
  		return;
  	while (($c=$s[$index])!=$start)
  	{
  		if (++$index>=strlen($s)) return;
  		if ($s[$index]=='\\')
  			if (++$index>=strlen($s)) return;
  		if (strlen($s)==$index)
  			return;
  	}
  	if (++$index>=strlen($s)) return;
  	while (($c=$s[$index])!=$end)
  	{
  		if (++$index>=strlen($s)) return;
  		if ($c=='\\')
  		{
  			$c=$s[$index];
  			if (++$index>=strlen($s)) return;
  		}
  		$value .= $c;
  	}
  	$index++;
  }
  
  function nextToken($s, &$value, &$index)
  {
  	if (strlen($s)<=$index)
  		return -1;
  	$value="";
  	while (1)
  	{
  		$c=$s[$index];
  		switch ($c)
  		{
  			case '{' :                      // Comment
  				$this->getValue($s,$value,'{','}',$index);
  				return 4;
  			case '<':                       // Bracket
  				$this->getValue($s,$value,'<','>',$index);
  				return 2;
  			case '(':                       // Variation
  				$this->ravValue($s,$value,$index);
  				return 3;
  			case '$':                       // NAG value
  				if (++$index>=strlen($s)) return 6;
  				$this->integerValue($s,$value,$index);
  				return 6;
  			case '!': // Short comment
  			case '?':
  				while (($c=='!') || ($c=='?'))
  				{
  					$value .= $c;
  					if (++$index>=strlen($s)) return 5;
  					$c=$s[$index];
  				}
  				return 5;
  			case '0': case '1': case '2': case '3': case '4':
  			case '5': case '6': case '7': case '8': case '9':
  				$this->integerValue($s,$value,$index);
  				return 8;
  			case '.':
  				$value=$c;
  				++$index;
  				return 9;
  			default: // Move or symbol
  				if ($this->isSymbol($c))
  				{
  					if ($this->isMove($s,$index))
  					{
  						while (stripos('NBRQKabcdefgh12345678xoO-=', $c)!==FALSE)
  						{
  							$value .= $c;
  							if (++$index>=strlen($s)) return 1;
  							$c=$s[$index];
  							// Check for promotion since this could also be equal position.
  							if ($c=='=')
  							{
  								if (($index<strlen($s))&&(stripos('NBRQ',$s[$index+1])!==FALSE))
  								{
  									++$index;
  									$c=$s[$index];
  								}
  							}
  						}
  						return 1; // move;
  					}
  					while ($this->isSymbol($c))
  					{
  						$value .= $c;
  						if (++$index>=strlen($s)) return 7;
  						$c=$s[$index];
  					}
  					return 7; // symbol;
  				}
  				break;
  		}
  		if (++$index>=strlen($s)) return -1;
  	}
  	return -1;
  }
  
  function ravValue(&$s, &$value, &$index)
  {
  	$value="";
  	while (($c=$s[$index])!='(')
  	{
  		$index++;
  		if ($s[$index]=='\\')
  			$index++;
  		if (strlen($s)==index) return;
  	}
  	if (++$index>=strlen($s)) return;
  	$count=1;
  	while (1)
  	{
  		$c=$s[$index];
  		switch ($c)
  		{
  			case ')' :
  				$count--;
  				if (!$count)
  				{
  					$index++;
  					return;
  				}
  				$value .= $c;
  				break;
  			case '(':
  				$count++;
  				$value .= $c;
  				break;
  			case '{':
  				$value .= $c;
  				if (++$index>=strlen($s)) return;
  				while (($d=$s[$index])!='}')
  				{
  					$value .= $d;
  					if (++$index>=strlen($s)) return;
  				}
  				$value .= $d;
  				break;
  			case '<':
  				$value .= $c;
  				if (++$index>=strlen($s)) return;
  				while (($d=$s[$index])!='>')
  				{
  					$value .= $d;
  					if (++$index>=strlen($s)) return;
  				}
  				$value .= $d;
  				break;
  			default :
  				$value .= $c;
  		}
  		if (++$index>=strlen($s)) return;
  	}
  }
  
  function integerValue(&$s, &$value, &$index)
  {
  	$len=0;
  	while (is_numeric(substr($s,$index+$len,1)))
  		++$len;
  	$value= 0 + substr($s,$index,$len);
  	$index+=$len;
  }
  
  function isSymbol($c)
  {
    if (($c>='A') && ($c<='Z')) return true;
    if (($c>='a') && ($c<='z')) return true;
    if (($c>='0') && ($c<='9')) return true;
    if ($c=='_') return true;
    if ($c=='+') return true;
    if ($c=='#') return true;
    if ($c=='=') return true;
    if ($c==':') return true;
    if ($c=='-') return true;
    return false;
  }

  function isMove(&$s, &$index)
  {
    if (strlen($s)<$index+2) return false;
    $c1=$s[$index];
    $c2=$s[$index+1];
    if (stripos('NBRQKabcdefgh',$c1)!==FALSE)
      if (stripos('abcdefgh12345678x',$c2)!==FALSE)
        return true;
    if (substr($s,$index,3)=='O-O')
      return true;
    return false;
  }
  
  function convertNag(&$s)
  {
  	$s=JText::_('POLARCHESS_NAG_' . $s);
  	return strlen($s);
  }
  
  function expandNags(&$s)
  {
  	$i=strpos($s,'$');
  	while ($i!==FALSE)
  	{
  		$j=strpos($s,' '.$i);
  		if (j===FALSE)
  			$sNag=substr($s,$i+1);
  		else
  			$sNag=substr($s,$i+1,$j-$i+1);
  		$nagSize=strlen($sNag)+1;
  		if ($this->convertNag($sNag))
  			substr_replace($s,$sNag,$i,$nagSize);
  		if ($j!==FALSE)
  			$i=$j;
  		else
  			++$i;
  		$i=strpos($s,'$',$i);
  	}
  }
  
  function splitComment(&$mc,&$c,$s)
  {
  	$mc='';
  	$c='';
  	$s=trim ($s);
  	$len=strlen($s);
  	if ($len==0)
  		return;
  	$i=0;
  
  	if (($s[0]=='!') || ($s[0]=='?'))
  	{
  		if (($len>1)&&(($s[0]=='!') || ($s[0]=='?')))
  		{
  			$mc=substr($s,0,2);
  			if ($len>2)
  				$c=trim(substr($s,2));
  			return;
  		}
  		$mc=substr($s,0,1);
  		if ($len>1)
  			$c=trim(substr($s,1));
  		return;
  	}
  	if (substr($s,0,3)=='(=)')
  	{
  		$mc=substr($s,0,3);
  		if ($len>3)
  			$c=trim(substr($s,3));
  		return;
  	}
  	$c=$s;
  }
}