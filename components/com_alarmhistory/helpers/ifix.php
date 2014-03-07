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
	public $sec='';
	public $district='';
	public $location='';
	public $field='';
	public $region='';
	public $searchtext='';
	public $start=0;
	protected $username="";
	protected $password="";
	protected $connection="";
	protected $fromdate=0;
	protected $todate=0;
	protected $sort='EVENTINDEX';
	
	public function __construct($config = array())
	{
			$params=JComponentHelper::getParams('com_alarmhistory');
			$this->connection=$params->get('scada_server','');
			$this->username=$params->get('scada_user','');
			$this->password=$params->get('scada_password','');
	}
	
	public function getData()
	{
		if ($this->connection=="test")
			return $this->testdata();

		$this->fromdate=time()-(1*24*60*60);
		$this->todate=time();
		
		$data1=$this->readFromScada('EVENTS',$this->limit);
		$i=count($data1);
		if ($i<$this->limit)
		{
			$data2=$this->readFromScada('EVENTS_HIS',$this->limit-$i);
//			$i+=count($data2);
			return array_merge($data1,$data2);
		}
		return $data1;
	}
	
	protected function readFromScada($table, $max)
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
		$conn=oci_connect($this->username,$this->password,$this->connection,"AL32UTF8");
		if (!$conn)
		{
			return $data;
		}
		$sql="SELECT * FROM ( SELECT";
		$sql.=" EVENTINDEX";
		$sql.=",NODENAME";
		$sql.=",TAG";
		$sql.=",DESCRIPTION";
		$sql.=",VALUEASC";
		$sql.=",UNIT";
		$sql.=",ALMSTATUS";
		$sql.=",MSGTYPE";
		$sql.=",PRIORITY";
		$sql.=",LOCATION";
		$sql.=",DISTRICT";
		$sql.=",REGION";
		$sql.=",FIELD";
		$sql.=",OPERATOR";
		$sql.=",NODEOPER";
		$sql.=",NODEPHYS";
		$sql.=",ALMX1";
		$sql.=",ALMX2";
		$sql.=",TO_CHAR(CAST((EVENTTIME AT LOCAL) AS DATE),'DD-MM-YYYY HH24:MI:SS') AS EVENTDATE";
		$sql.=",EVENTTIME";
		$sql.=",COMMENTED";
		$sql.=",SYNT";
		$sql.=",SEC1";
		$sql.=",SEC2";
		$sql.=",SEC3";

		$sql .= " FROM";
		$sql.=" $table";
		$where = array();
		$whereId = 0;
//  		if ($this->first>0)
//  			$sql.=" AND (EVENTINDEX <" . $this->first . ")";
		if ($this->sec!='')
 			$where[$whereId++]="((SEC1 = '" . $this->sec . "') OR (SEC2 = '" . $this->sec . "') OR (SEC3 = '" . $this->sec . "'))";
 		if ($this->district>0)
 			$where[$whereId++]="(DISTRICT = " . $this->district . ")";
 		if ($this->district>0)
 			$where[$whereId++]="(FIELD = " . $this->field . ")";
 		if ($this->district>0)
 			$where[$whereId++]="(REGION = " . $this->region . ")";
 		if ($this->district>0)
 			$where[$whereId++]="(LOCATION = " . $this->location . ")";
 		if ($this->searchtext!='')
 			$where[$whereId++]="(DESCRIPTION LIKE '%" . $this->searchtext . "%')";
//  		$sql.=" AND (EVENTTIME >='".date("d.m.Y H:i:s,0",$this->fromdate)."')";
//  		$sql.=" AND (EVENTTIME <'".date("d.m.Y H:i:s,0",$this->todate+(mktime(0,0,0,1,2,1980)-mktime(0,0,0,1,1,1980)))."')";
//  		$sql.=" AND (MSGTYPE <> 'OPERATOR')";
		if ($this->eventdate)
			$where[$whereId++] ="(EVENTTIME < '".date("d.m.Y H:i:s,0",$this->eventdate+(24*60*60))."')";
		if (count($where))
		{
			$sql.=" WHERE";
			for ($i=0;$i<count($where);$i++)
			{
				if ($i>0)
					$sql .= " AND";
				$sql .= " " . $where[$i];
			}
		}
		
		$sql.=" ORDER BY EVENTTIME DESC )";
		$sql.=" WHERE ROWNUM <= " . $max;
//		echo $sql;
		$stid=oci_parse($conn, $sql);
		if (!$stid)
		{
			return $data;
		}
		if (!oci_execute($stid))
		{
			return $data;
		}
		$i=0;
		while ($row=oci_fetch_assoc($stid))
		{
			$data[$i++]=$row;
		}
//		$data[0]['DESCRIPTION']=urlencode($sql);
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
