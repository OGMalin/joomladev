<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;


class iFixHelper
{
	protected $maxrow=10;
	protected $username="SCADA";
	protected $password="SCADA";
	protected $connection="10.96.100.3/REPO.NTS41";
	protected $searchstring='';
	protected $district=0;
	protected $location=0;
	protected $fromdate=0;
	protected $todate=0;
	protected $sort='EVENTTIME';
	
	public function getData()
	{
		$this->fromdate=time()-(7*24*60*60);
		$this->todate=time();
		
		$data1=$this->readFromScada('EVENTS',$this->maxrow);
		$i=count($data1);
		$data2=$this->readFromScada('EVENTS_HIS',$this->maxrow-$i);
		$i+=count($data2);
		if ($i>=$this->maxrow)
		{
			$line[0]="operator_alarm";
			$line[1]="";
			$line[2]="Over $this->maxrow linjer, endre søket for å vise alle meldingene.";
			$line[3]="";
			$data1[0]=$line;
		}
		return array_merge($data1,$data2);
	}
	
	protected function readFromScada($table, $max)
	{
		$data=array();
		$line=array();
		$conn=oci_connect($this->username,$this->password,$this->connection,"AL32UTF8");
		if (!$conn)
		{
			$line[0]='unknown';
			$line[1]='';
			$line[3]='';
			$line[2]="Får ikke koplet til databasen.";
			$this->_data[0]=$line;
			return $this->_data;
		}
		$sql="SELECT";
		$sql.=" TO_CHAR(CAST((EVENTTIME AT LOCAL) AS DATE),'DD-MM-YYYY HH24:MI:SS')";
		$sql.=",DESCRIPTION";
		$sql.=",VALUEASC";
		$sql.=",MSGTYPE";
		$sql.=",PRIORITY";
		$sql.=",UNIT";
		$sql.=" FROM";
		$sql.=" $table";
		$sql.=" WHERE";
		$sql.=" (ROWNUM <=$max)";
		if ($this->district>0)
			$sql.=" AND (DISTRICT=$this->district)";
		if ($this->location>0)
			$sql.=" AND (LOCATION=$this->location)";
		if ($this->searchstring!='')
			$sql.=" AND ((UPPER(DESCRIPTION) LIKE '%" . $this->searchstring . "%') OR (UPPER(VALUEASC) LIKE '%" . $this->searchstring . "%'))";
		$sql.=" AND (EVENTTIME >='".date("d.m.Y H:i:s,0",$this->fromdate)."')";
		$sql.=" AND (EVENTTIME <'".date("d.m.Y H:i:s,0",$this->todate+(mktime(0,0,0,1,2,1980)-mktime(0,0,0,1,1,1980)))."')";
		$sql.=" AND (MSGTYPE <> 'OPERATOR')";
		$sql.=" ORDER BY " . $this->sort . " DESC";
		$stid=oci_parse($conn, $sql);
		if (!$stid)
		{
			$line[0]='unknown';
			$line[1]='';
			$line[3]='';
			$line[2]="Feil i sql spørring.";
			$data[0]=$line;
			return $data;
		}
		if (!oci_execute($stid))
		{
			$line[0]='unknown';
			$line[1]='';
			$line[3]='';
			$line[2]="Feil i kjøring spørring:<br /> $sql";
			$data[0]=$line;
			return $data;
		}
		$i=0;
		while ($row=oci_fetch_row($stid))
		{
			$j=0;
			$line[$j++]='';//$this->messageType($row[3],$row[4],$row[5]);
			$line[$j++]=$row[0];
			$line[$j++]=$row[1];
			$line[$j++]=$row[2];
			$data[$i++]=$line;
		}
		return $data;
	}
}
