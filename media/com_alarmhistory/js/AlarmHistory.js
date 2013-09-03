/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

window.onload=function(){init();};

var debug=true;
var msgList=new Array();
function init()
{
//	jQuery(function() {
//		jQuery( "#datepicker1" ).datepicker();
//	});
//	jQuery(function() {
//		jQuery( "#datepicker2" ).datepicker();
//	});
//
//	jQuery.datepicker.setDefaults({
//		monthNames: [ "Januar", "Februar", "Mars", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Desember" ],
//		dayNamesMin: [ "Sø", "Ma", "Ti", "On", "To", "Fr", "Lø" ],
//		firstDay: 1
//	});
	
	getList();
};
	

function dateChanged()
{
	var s = jQuery('#setdate').val();
	var d = new Date(parseInt(s.substr(6,4)),parseInt(s.substr(3,2)),parseInt(s.substr(0,2)),0,0,0,0);
	var t = d.getTime()/1000;
	getList('eventdate='+t);
}

function getList(filter='')
{
	var limit=0;
	jQuery.ajax({
		cache : false,
		type : 'POST',
		dataType : 'json',
		url : responseUrl + 'task=response.queryalarmhistory&format=json',
		data : filter ,
		timeout : 60000,
		success : function(json)
		{
			if (!json || (json.error && (json.error>0)))
			{
				/* Skriv errormelding */
			} else
			{
				/* Response ok */
				msgList=json;
				showList();
			}
		}
	});
}

function showList()
{
	var list = "<table class='table-hover table-condensed'>";
	for (var i=0;i<msgList.length;i++)
	{
		list+="<tr class=\"" + messageClass(i) + "\" onclick='showProperty(" + i + ");return false;'>";
		list+="<td>" + msgList[i].EVENTTIME.substr(0,22) + "</td>";
		list+="<td>" + msgList[i].DESCRIPTION + "</td>";
		list+="<td>" + msgList[i].VALUEASC + "</td>";
		list+="</tr>\n";
	}
	list+="</table>\n"
	jQuery('#historylist').html(list);
}

function messageClass(i)
{
	return (msgList[i].MSGTYPE+'-'+msgList[i].PRIORITY+ (msgList[i].UNIT?'-'+msgList[i].UNIT:''));
}

function showProperty(index)
{
	jQuery('#listProperty').modal();
	jQuery('#ROW').html(msgList[index].ROW);
	jQuery('#EVENTINDEX').html(msgList[index].EVENTINDEX);
	jQuery('#NODENAME').html(msgList[index].NODENAME);
	jQuery('#TAG').html(msgList[index].TAG);
	jQuery('#DESCRIPTION').html(msgList[index].DESCRIPTION);
	jQuery('#VALUEASC').html(msgList[index].VALUEASC);
	jQuery('#UNIT').html(msgList[index].UNIT);
	jQuery('#ALMSTATUS').html(msgList[index].ALMSTATUS);
	jQuery('#MSGTYPE').html(msgList[index].MSGTYPE);
	jQuery('#PRIORITY').html(msgList[index].PRIORITY);
	jQuery('#LOCATION').html(msgList[index].LOCATION);
	jQuery('#DISTRICT').html(msgList[index].DISTRICT);
	jQuery('#REGION').html(msgList[index].REGION);
	jQuery('#FIELD').html(msgList[index].FIELD);
	jQuery('#OPERATOR').html(msgList[index].OPERATOR);
	jQuery('#NODEOPER').html(msgList[index].NODEOPER);
	jQuery('#NODEPHYS').html(msgList[index].NODEPHYS);
	jQuery('#ALMX1').html(msgList[index].ALMX1);
	jQuery('#ALMX2').html(msgList[index].ALMX2);
	jQuery('#EVENTTIME').html(msgList[index].EVENTTIME);
	jQuery('#COMMENTED').html(msgList[index].COMMENTED);
	jQuery('#SYNT').html(msgList[index].SYNT);
	jQuery('#SEC1').html(msgList[index].SEC1);
	jQuery('#SEC2').html(msgList[index].SEC2);
	jQuery('#SEC3').html(msgList[index].SEC3);
}
