/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage  com_alarmhistory
 * @copyright   Copyright 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

	var responseUrl = '/';
	
	function init()
	{
		updateList(10,20);
	};
	
	function updateList(start, limit)
	{
		var filter='';
		jQuery.ajax(
		{
			cache : false,
			type : 'POST',
			dataType : 'json',
			url : responseUrl + 'task=response.queryalarmhistory&format=json',
			data : { start : start, limit : limit, filter : filter },
			timeout : 60000,
			success : function(json)
			{
				if (!json || (json.error && (json.error>0)))
				{
					/* Skriv errormelding */
				} else
				{
					/* Response ok */
				}
			}
		}
		);
	}
