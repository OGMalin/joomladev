<?php
defined('_JEXEC') or die;

function stringMovesToArray($s)
{
	// m|c|r|s;m|c|r|s;...
	$ms=explode(';',$s);
	$nms=array();
	foreach ($ms as $m){
		$nm=explode('|',$m);
		if ($nm[0] && $nm[0]!='')
			array_push($nms,$nm);
	}
	return $nms;
}

function arrayMovesToString($a)
{
	$moves='';
	$first=true;
	foreach ($a as $m){
		if ($m[0]!=''){
			if (!$first)
				$moves .= ';';
			else
				$first=false;
			$moves .= $m[0] . '|' . $m[1] . '|' . $m[2] . '|' . $m[3];
		}
	}
	return $moves;
}

function combineMoves($ms1,$ms2)
{
	$am=array();
	if ($ms1=='')
		return $ms2;
	if ($ms2=='')
		return $ms1;
	$am1=stringMovesToArray($ms1);
	$am2=stringMovesToArray($ms2);
	$len2=count($am2);
	foreach ($am1 as $m1){
		for ($x=0;$x<$len2;$x++){
			if (($am2[$x][0]!='') && ($m1[0]==$am2[$x][0])){
				// Copy comment
				if ($m1[1]=='')
					$m1[1]==$am2[$x][1];
				// copy repertoire
				$r1=($m1[2]==''?0:$m1[2]);
				$r2=($am2[$x][2]==''?0:$am2[$x][2]);
				if ($r1==0)
					$m1[2]=$r2;
				else if ($r2!=0)
					$m1[2]=($r1<$r2?$r1:$r2);
				// add statistics
				$m1[3]+=$am2[$x][3];
				// Mark as used
				$am2[$x][0]='';
			}
		}
		array_push($am, $m1);
	}
	// Add unused moves.
	foreach ($am2 as $m2){
		if ($m2[0]!='')
			array_push($am, $m2);
	}
	$ms=arrayMovesToString($am);
	return $ms;
}