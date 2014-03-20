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

jimport('joomla.application.component.helper');

class iFixHelper
{
	public $limit=10;
	public $eventdate=0;
	public $district=0;
	public $location=0;
	public $searchtext='';
	public $start=1;
	public $eventindex=0;
	protected $username="";
	protected $password="";
	protected $connection="";
	protected $sort='EVENTINDEX';
	protected $where='';
	
	public function __construct($config = array())
	{
			$params=JComponentHelper::getParams('com_alarmhistory');
			$this->connection=$params->get('scada_server','');
			$this->username=$params->get('scada_user','');
			$this->password=$params->get('scada_password','');
	}
	
	public function getData()
	{// **** sjekk om UNION ALL kan brukes.
		if ($this->connection=="test")
			return $this->testdata();

		$conn=oci_connect($this->username,$this->password,$this->connection,"AL32UTF8");
		if (!$conn)
			return 0;
		
		$first=$this->start;
		$last=$this->limit+$first-1;
		
		$this->makeWhere();

		// Søk i begge tabellene (UNION) er veldig tregt.
// 		$data=$this->readFromScadaFull($conn,$first,$last);
// 		oci_close($conn);
// 		return $data;
		
		$data1=array();
		
		$c=$this->countInEvents($conn);
		
		if ($this->eventindex)
		{
			if (!$c)
				return $data1;
			return $this->readFromScada($conn,'EVENTS',$first,$last);
		}
		
		// Det finnes data i første tabell
		if ($first < $c)
			$data1=$this->readFromScada($conn,'EVENTS',$first,$last);
		$i=count($data1);
		
		// Alt er funnet		
		if ($i>=$this->limit)
		{
			oci_close($conn);
			return $data1;
		}
		
		$first-=$c;
//		$first+=$i;
		$last-=$c;
		$data2=$this->readFromScada($conn,'EVENTS_HIS',$first,$last);
		oci_close($conn);
		return array_merge($data1,$data2);
	}
	
	protected function countInEvents($conn)
	{
		
		$stid=oci_parse($conn,"SELECT COUNT(*) FROM EVENTS " . $this->where);
		if (!$stid)
			return -1;
		if (!oci_execute($stid))
			return -1;
		$row=oci_fetch_row($stid);
//		echo $row;
		oci_free_statement($stid);
		return $row[0];
	}
	
	protected function readFromScadaFull($conn, $first, $last)
	{
		// select *
		//   from ( select /*+ FIRST_ROWS(n) */
		//   a.*, ROWNUM rnum
		//       from ( your_query_goes_here,
		//       with order by ) a
		//       where ROWNUM <=
		//       :MAX_ROW_TO_FETCH )
		// where rnum  >= :MIN_ROW_TO_FETCH;
	
		$data=array();
	
		$select = "SELECT";
		$select.=" EVENTINDEX";
		$select.=",NODENAME";
		$select.=",TAG";
		$select.=",DESCRIPTION";
		$select.=",VALUEASC";
		$select.=",UNIT";
		$select.=",ALMSTATUS";
		$select.=",MSGTYPE";
		$select.=",PRIORITY";
		$select.=",LOCATION";
		$select.=",DISTRICT";
		$select.=",REGION";
		$select.=",FIELD";
		$select.=",OPERATOR";
		$select.=",NODEOPER";
		$select.=",NODEPHYS";
		$select.=",ALMX1";
		$select.=",ALMX2";
		$select.=",TO_CHAR(CAST((EVENTTIME AT LOCAL) AS DATE),'DD-MM-YYYY HH24:MI:SS') AS EVENTDATE";
		$select.=",EVENTTIME";
		$select.=",COMMENTED";
		$select.=",SYNT";
		$select.=",SEC1";
		$select.=",SEC2";
		$select.=",SEC3";
	
 		$select.= " FROM";
	
		$sql="SELECT * FROM (SELECT a.*, ROWNUM rnum FROM (";
 		$sql.= "(" . $select . " EVENTS" . $this->where . ")";
 		$sql.=" UNION ALL ";
 		$sql.= "(" . $select . " EVENTS_HIS" . $this->where . ")";
 		$sql.=" ORDER BY EVENTTIME DESC";
		$sql.=") a WHERE ROWNUM <= " . $last . ") WHERE rnum >= " . $first;
	
		$stid=oci_parse($conn, $sql);
		if (!$stid)
		{
			oci_free_statement($stid);
			return $data;
		}
		if (!oci_execute($stid))
		{
			oci_free_statement($stid);
			return $data;
		}
		$i=0;
		while ($row=oci_fetch_assoc($stid))
		{
			$data[$i++]=$row;
		}
		//		$data[0]['DESCRIPTION']=$first . ", " . $last . " - " .$sql;//urlencode($sql);
		oci_free_statement($stid);
		return $data;
	}
	
	
	protected function makeWhere()
	{
		$where = array();
		$whereId = 0;
 		if ($this->district>0)
 			$where[$whereId++]="(DISTRICT = " . $this->district . ")";
 		if ($this->location>0)
 			$where[$whereId++]="(LOCATION = " . $this->location . ")";
 		if ($this->searchtext!='')
 			$where[$whereId++]="(UPPER(DESCRIPTION) LIKE '%" . strtoupper($this->searchtext) . "%')";
 		if ($this->eventindex)
 			$where[$whereId++]="(EVENTINDEX > " . $this->eventindex . ")"; 
 		else if ($this->eventdate)
			$where[$whereId++] ="(EVENTTIME < '".date("d.m.Y H:i:s,0",$this->eventdate+(24*60*60))."')";
		if (count($where))
		{
			$this->where =" WHERE";
			for ($i=0;$i<count($where);$i++)
			{
				if ($i>0)
					$this->where .= " AND";
				$this->where .= " " . $where[$i];
			}
		}
		
	}
	
	protected function readFromScada($conn, $table, $first, $last)
	{
// select * 
//   from ( select /*+ FIRST_ROWS(n) */ 
//   a.*, ROWNUM rnum 
//       from ( your_query_goes_here, 
//       with order by ) a 
//       where ROWNUM <= 
//       :MAX_ROW_TO_FETCH ) 
// where rnum  >= :MIN_ROW_TO_FETCH;
		
		$data=array();
		$line=array();

		$select = "SELECT";
		$select.=" EVENTINDEX";
		$select.=",NODENAME";
		$select.=",TAG";
		$select.=",DESCRIPTION";
		$select.=",VALUEASC";
		$select.=",UNIT";
		$select.=",ALMSTATUS";
		$select.=",MSGTYPE";
		$select.=",PRIORITY";
		$select.=",LOCATION";
		$select.=",DISTRICT";
		$select.=",REGION";
		$select.=",FIELD";
		$select.=",OPERATOR";
		$select.=",NODEOPER";
		$select.=",NODEPHYS";
		$select.=",ALMX1";
		$select.=",ALMX2";
		$select.=",TO_CHAR(CAST((EVENTTIME AT LOCAL) AS DATE),'DD-MM-YYYY HH24:MI:SS') AS EVENTDATE";
		$select.=",EVENTTIME";
		$select.=",COMMENTED";
		$select.=",SYNT";
		$select.=",SEC1";
		$select.=",SEC2";
		$select.=",SEC3";

		$select.= " FROM";
		$select.= " $table";
		
		$select.= $this->where;
		
		$select.=" ORDER BY EVENTTIME DESC";
		
		$sql="SELECT * FROM (SELECT a.*, ROWNUM rnum FROM (" . $select . ") a WHERE ROWNUM <= " . $last . ") WHERE rnum >= " . $first;

		$stid=oci_parse($conn, $sql);
		if (!$stid)
		{
			oci_free_statement($stid);
			return $data;
		}
		if (!oci_execute($stid))
		{
			oci_free_statement($stid);
			return $data;
		}
		$i=0;
		while ($row=oci_fetch_assoc($stid))
		{
			$data[$i++]=$row;
		}
//		$data[0]['DESCRIPTION']=$first . ", " . $last . " - " .$sql;//urlencode($sql);
		oci_free_statement($stid);
		return $data;
	}
	
	protected function testdata()
	{
		$data=array();
		for ($i=1000;$i>0;$i--)
		{
			array_push($data, 
				array(
					"ROW"=>$i,
					"EVENTINDEX"=>$i,
					"NODENAME"=>"Node",
					"TAG"=>"Tag$i",
//					"DESCRIPTION"=>"Melding: $i",
					"DESCRIPTION"=>date("d.m.Y H:i:s,0",$this->eventdate+(24*60*60)),
					"VALUEASC"=>$this->eventdate,
					"UNIT"=>"KV",
					"ALMSTATUS"=>"Node",
					"UNIT"=>"Node",
					"ALMSTATUS"=>"Node",
					"MSGTYPE"=>"Node",
					"PRIORITY"=>"Node",
					"LOCATION"=>"Node",
					"DISTRICT"=>"Node",
					"REGION"=>"Node",
					"FIELD"=>"Node",
					"OPERATOR"=>"Node",
					"NODEOPER"=>"Node",
					"NODEPHYS"=>"Node",
					"ALMX1"=>"Node",
					"ALMX2"=>"Node",
					"EVENTTIME"=>"2013-08-26 15:36:45:0054",
					"COMMENTED"=>"Kommentar",
					"SYNT"=>"Node",
					"SEC1"=>"NETT",
 					"SEC2"=>"NONE",
 					"SEC3"=>"NONE"
				));
		}
		return array_slice($data,$this->first,$this->limit);
		
	}
}
