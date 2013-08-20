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
	protected $maxrow=100;
	protected $username="";
	protected $password="";
	protected $connection="";
	protected $searchstring='';
	protected $district=0;
	protected $location=0;
	protected $fromdate=0;
	protected $todate=0;
	protected $sec1='NETT';
	protected $first=0;
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
		$this->fromdate=time()-(1*24*60*60);
		$this->todate=time();
		
		$data1=$this->readFromScada('EVENTS',$this->maxrow);
//		return $data1;
		$i=count($data1);
		$data2=$this->readFromScada('EVENTS_HIS',$this->maxrow-$i);
		$i+=count($data2);
		return array_merge($data1,$data2);
	}
	
	protected function readFromScada($table, $max)
	{
		$data=array();
		$line=array();
		$conn=oci_connect($this->username,$this->password,$this->connection,"AL32UTF8");
		if (!$conn)
		{
			return $data;
		}
		$sql="SELECT *";
		$sql.=" FROM";
		$sql.=" $table";
		$sql.=" WHERE";
		$sql.=" (ROWNUM <= " . $max . ")";
 		if ($this->first>0)
 			$sql.=" AND (EVENTINDEX<" . $this->first . ")";
		if ($this->sec1!='')
 			$sql.=" AND (SEC1 LIKE '" . $this->sec1. "')";
		// 		if ($this->district>0)
// 			$sql.=" AND (DISTRICT=$this->district)";
// 		if ($this->location>0)
// 			$sql.=" AND (LOCATION=$this->location)";
// 		if ($this->searchstring!='')
// 			$sql.=" AND ((UPPER(DESCRIPTION) LIKE '%" . $this->searchstring . "%') OR (UPPER(VALUEASC) LIKE '%" . $this->searchstring . "%'))";
//  		$sql.=" AND (EVENTTIME >='".date("d.m.Y H:i:s,0",$this->fromdate)."')";
//  		$sql.=" AND (EVENTTIME <'".date("d.m.Y H:i:s,0",$this->todate+(mktime(0,0,0,1,2,1980)-mktime(0,0,0,1,1,1980)))."')";
//  		$sql.=" AND (MSGTYPE <> 'OPERATOR')";
		$sql.=" ORDER BY EVENTINDEX DESC";
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
		return $data;
	}
}
