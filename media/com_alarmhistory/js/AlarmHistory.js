/**
 * @package     Alarmhistory for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

window.onload=function(){init();};

var msgList=new Array();
var start=1;
var auto=0;

function init()
{
	// Oppdater områdeliste
	var s="<option value='0'></option>\n";
	for (var i=0; i<sections.length; i++)
	{
		s += "<option value='"+sections[i][0]+"'" + (defSection==sections[i][0]?" selected":"")+">"+sections[i][1]+"</options>\n";
	}
	jQuery('#section').html(s);
	
	updateSiteList(defSection);
	
	getList();
};

function updateSiteList(section)
{
	// Oppdater stasjonsliste
	s="<option value='0'></option>\n";
	for (var i=0; i<sites.length; i++)
	{
		if (!section || (section==sites[i][3]))
			s += "<option value='"+sites[i][0]+"'>"+sites[i][1]+"</options>\n";
	}
	jQuery('#site').html(s);
}

function autoChanged()
{
	var a=jQuery('#auto').is(':checked');
	if (a)
	{
		auto=setInterval(function(){getList()},refreshinterval*1000);
	}else if (auto)
	{
		clearInterval(auto);
		auto=0;
	}
	msgList=new Array();
	getList();
}

function sectionChanged()
{
	var sec=jQuery('#section option:selected').val();
	updateSiteList(sec);
	searchList();
}

function getPage(forward)
{
	if (auto)
	{
		clearInterval(auto);
		auto=0;
		jQuery('#auto').prop('checked', false);
	}
	var limit=parseInt(jQuery('#limit option:selected').val());
	if (forward)
		start-=limit;
	else
		start+=limit;
	if (start<1)
		start=1;
	getList();
		
}

function searchList()
{
	start=1;
	if (auto)
	{
		clearInterval(auto);
		auto=0;
		jQuery('#auto').prop('checked', false);
	}
	getList();
}

function getList()
{
	jQuery('#refreshing').html("<i class='icon-refresh'></i>");
	var limit=jQuery('#limit option:selected').val();
	var secI=getSectionIndex(jQuery('#section option:selected').val());
	var sitI=getSiteIndex(jQuery('#site option:selected').val());
	var sec='';
	var sit='';
	if (sitI>=0)
		sit="&location="+sites[sitI][2];
	else if (secI>=0)
		sec="&district="+sections[secI][2];
	var searchtext="&searchtext=" + jQuery('#searchtext').val();
	var s = jQuery('#setdate').val();
	var d = Date.UTC(parseInt(s.substr(6,4)),parseInt(s.substr(3,2))-1,parseInt(s.substr(0,2)));
	var t = d/1000;
	var eventdate = '&eventdate='+t;
	var eventindex='';
	if (auto)
		eventindex = '&eventindex=' + (msgList.length?msgList[0].EVENTINDEX:0);
	jQuery.ajax({
		cache : false,
		type : 'POST',
		dataType : 'json',
		url : responseUrl + 'task=response.queryalarmhistory&format=json',
		data : 'limit=' + limit + '&start=' + start + sec + sit + eventdate + eventindex + searchtext,
		timeout : 60000,
		success : function(json)
		{
			if (!json || (json.error && (json.error>0)))
			{
				/* Skriv feilmelding */
			} else
			{
				/* Response ok */
				if (auto)
					msgList=json.concat(msgList).slice(0,limit);
				else
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
	var miss;
	var t;
	var color;
	var interval=0;
	var title;
	var list = "<table class='table-hover table-condensed'>";
	for (var i=0;i<msgList.length;i++)
	{
		t=messageType(i);
		if (t>=0)
		{
			color=types[t][2];
			title=types[t][1];
		}else
		{
			color=defColor;
			title="Ukjent";
		}
		if (debug)
			list+="<tr title=" + title + " style='color:" + color + "' onclick='showProperty(" + i + ");return false;'>";
		else
			list+="<tr title=" + title + " style='color:" + color + "'>";
		list+="<td>";
		if (debug)
		{
			miss=checkIfExist(i);
			if (miss!='')
				list+="<a title='Mangler:\n"+miss+"'>*</a> ";
		}
		list+=msgList[i].EVENTDATE + "</td>";
		list+="<td>" + msgList[i].DESCRIPTION + "</td>";
		list+="<td>" + msgList[i].VALUEASC + "</td>";
		list+="</tr>\n";
	}
	list+="</table>\n"
	jQuery('#historylist').html(list);
}

function checkIfExist(index)
{
	var i;
	var sec='Område\n';
	var typ='Meldingstype\n';
	var sit='Stasjon\n';
	
	// type
	for (i=0;i<types.length;i++)
	{
		if (((types[i][3] == '*') || (types[i][3]==((msgList[index].UNIT==null)?'':msgList[index].UNIT))) &&
			((types[i][4] == '*') || (types[i][4]==((msgList[index].ALMSTATUS==null)?'':msgList[index].ALMSTATUS))) &&
			((types[i][5] == '*') || (types[i][5]==((msgList[index].MSGTYPE==null)?'':msgList[index].MSGTYPE))) &&
			((types[i][6] == '*') || (types[i][6]==((msgList[index].PRIORITY==null)?'':msgList[index].PRIORITY))))
		{
			typ='';
			break;
		}
	}

	// Område
	for (i=0;i<sections.length;i++)
	{
		if (sections[i][2]==msgList[index].DISTRICT)
		{
			sec='';
			break;
		}
	}

	// Site
	for (i=0;i<sites.length;i++)
	{
		if (sites[i][2]==msgList[index].LOCATION)
		{
			sit='';
			break;
		}
	}

	return sec+sit+typ;
}

function messageStyle(index)
{
	for (i=0;i<types.length;i++)
	{
		if (((types[i][3] == '*') || (types[i][3]==((msgList[index].UNIT==null)?'':msgList[index].UNIT))) &&
			((types[i][4] == '*') || (types[i][4]==((msgList[index].ALMSTATUS==null)?'':msgList[index].ALMSTATUS))) &&
			((types[i][5] == '*') || (types[i][5]==((msgList[index].MSGTYPE==null)?'':msgList[index].MSGTYPE))) &&
			((types[i][6] == '*') || (types[i][6]==((msgList[index].PRIORITY==null)?'':msgList[index].PRIORITY))))
		{
			return types[i][2];
		}
	}
	return '#000000';
}

function messageType(index)
{
	for (i=0;i<types.length;i++)
	{
		if (((types[i][3] == '*') || (types[i][3]==((msgList[index].UNIT==null)?'':msgList[index].UNIT))) &&
			((types[i][4] == '*') || (types[i][4]==((msgList[index].ALMSTATUS==null)?'':msgList[index].ALMSTATUS))) &&
			((types[i][5] == '*') || (types[i][5]==((msgList[index].MSGTYPE==null)?'':msgList[index].MSGTYPE))) &&
			((types[i][6] == '*') || (types[i][6]==((msgList[index].PRIORITY==null)?'':msgList[index].PRIORITY))))
		{
			return i;
		}
	}
	return -1;
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
		if (sections[i][2]==msgList[index].DISTRICT)
		{
			s+=sections[i][1]+'('+sections[i][0]+') ';
		}
	}
	jQuery('#msgSection').html(s);
	// Site
	s='';
	for (i=0;i<sites.length;i++)
	{
		if (sites[i][2]==msgList[index].LOCATION)
		{
			s+=sites[i][1]+'('+sites[i][0]+') ';
		}
	}
	jQuery('#msgSite').html(s);
	// Type
	s='';
	for (i=0;i<types.length;i++)
	{
		if (((types[i][3] == '*') || (types[i][3]==((msgList[index].UNIT==null)?'':msgList[index].UNIT))) &&
			((types[i][4] == '*') || (types[i][4]==((msgList[index].ALMSTATUS==null)?'':msgList[index].ALMSTATUS))) &&
			((types[i][5] == '*') || (types[i][5]==((msgList[index].MSGTYPE==null)?'':msgList[index].MSGTYPE))) &&
			((types[i][6] == '*') || (types[i][6]==((msgList[index].PRIORITY==null)?'':msgList[index].PRIORITY))))
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
