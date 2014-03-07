/**
 * @package     Alarmhistory for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

window.onload=function(){init();};

var debug=true;
var msgList=new Array();

function init()
{
//	var defSection=0;
	// Oppdater områdeliste
	var s="<option value='0'></option>\n";
	for (var i=0; i<sections.length; i++)
	{
		s += "<option value='"+sections[i][0]+"'" + (defSection==sections[i][0]?" selected":"")+">"+sections[i][1]+"</options>\n";
	}
	jQuery('#section').html(s);
	
	updateSiteList(defSection);
	
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

function updateSiteList(section)
{
	// Oppdater stasjonsliste
	s="<option value='0'></option>\n";
	for (var i=0; i<sites.length; i++)
	{
		if (!section || (section==sites[i][6]))
			s += "<option value='"+sites[i][0]+"'>"+sites[i][1]+"</options>\n";
	}
	jQuery('#site').html(s);
}

function dateChanged()
{
	var s = jQuery('#setdate').val();
//	var d = new Date(parseInt(s.substr(6,4)),parseInt(s.substr(3,2))-1,parseInt(s.substr(0,2)),0,0,0,0);
	var d = Date.UTC(parseInt(s.substr(6,4)),parseInt(s.substr(3,2))-1,parseInt(s.substr(0,2)));
	var t = d/1000;
	getList('eventdate='+t);
}

function sectionChanged()
{
	var sec=jQuery('#section option:selected').val();
	updateSiteList(sec);
//	getList();
}

function getList()
{
	jQuery('#refreshing').html("<i class='icon-refresh'></i>");
	var limit=100;
	var secI=getSectionIndex(jQuery('#section option:selected').val());
	var sitI=getSiteIndex(jQuery('#site option:selected').val());
	var sec='';
	var sit='';
	if (secI>=0)
		sec="&sec="+sections[secI][2];
	if (sitI>=0)
		sit="&field="+sites[sitI][2]+"&region="+sites[sitI][3]+"&district="+sites[sitI][4]+"&location="+sites[sitI][5];
	var setdate=jQuery('#setdate').val();
	var search=jQuery('#searchtext').val();
	jQuery.ajax({
		cache : false,
		type : 'POST',
		dataType : 'json',
		url : responseUrl + 'task=response.queryalarmhistory&format=json',
		data : 'limit=' + limit + sec + sit + "&setdate=" + setdate + "&searchtext=" + search,
		timeout : 60000,
		success : function(json)
		{
			if (!json || (json.error && (json.error>0)))
			{
				/* Skriv feilmelding */
			} else
			{
				/* Response ok */
				msgList=json;
				showList();
			}
			jQuery('#refreshing').html("");
		}
	});
}

function getSectionIndex(id)
{
	for (var i=0; i<sections.length;i++)
	{
		if (sections[i][0]==id)
			return i;
	}
	return -1;
}

function getSiteIndex(id)
{
	for (var i=0; i<sites.length;i++)
	{
		if (sites[i][0]==id)
			return i;
	}
	return -1;
}

function showList()
{
	var list = "<table class='table-hover table-condensed'>";
	for (var i=0;i<msgList.length;i++)
	{
		list+="<tr class=\"" + messageClass(i) + "\" onclick='showProperty(" + i + ");return false;'>";
		list+="<td>" + msgList[i].EVENTDATE + "</td>";
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

/**
 * Viser alle egenskapene til en melding for bruk til � finne ukjente meldinger.
 */
function showProperty(index)
{
	jQuery('#listProperty').modal();
	// Sjekk treff i databasen
	// Section
	var i;
	var s='';
	for (i=0;i<sections.length;i++)
	{
		if ((sections[i][2]==msgList[index].SEC1) ||
			(sections[i][2]==msgList[index].SEC2) ||
			(sections[i][2]==msgList[index].SEC3))
		{
			s+=sections[i][1]+'('+sections[i][0]+') ';
		}
	}
	jQuery('#msgSection').html(s);
	// Site
	s='';
	for (i=0;i<sites.length;i++)
	{
		if ((sites[i][2]==msgList[index].FIELD) &&
			(sites[i][3]==msgList[index].REGION) &&
			(sites[i][4]==msgList[index].DISTRICT) &&
			(sites[i][5]==msgList[index].LOCATION))
		{
			s+=sites[i][1]+'('+sites[i][0]+') ';
		}
	}
	jQuery('#msgSite').html(s);
	// Type
	s='';
	for (i=0;i<types.length;i++)
	{
		if ((types[i][3]==msgList[index].UNIT) &&
			(types[i][4]==msgList[index].ALMSTATUS) &&
			(types[i][5]==msgList[index].MSGTYPE) &&
			(types[i][6]==msgList[index].PRIORITY))
		{
			s+=types[i][1]+'('+types[i][0]+') ';
		}
	}
	jQuery('#msgType').html(s);
	
	UNIT, ALMSTATUS, MSGTYPE, PRIORITY	
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
	jQuery('#EVENTDATE').html(msgList[index].EVENTDATE);
	jQuery('#EVENTTIME').html(msgList[index].EVENTTIME);
	jQuery('#COMMENTED').html(msgList[index].COMMENTED);
	jQuery('#SYNT').html(msgList[index].SYNT);
	jQuery('#SEC1').html(msgList[index].SEC1);
	jQuery('#SEC2').html(msgList[index].SEC2);
	jQuery('#SEC3').html(msgList[index].SEC3);
}
