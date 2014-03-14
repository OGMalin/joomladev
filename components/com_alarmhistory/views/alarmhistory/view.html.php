<?php
/**
 * @package     Alarmhistory for Joomla 3.x
 * @version     1.0.0
 * @author      Odd Gunnar Malin
 * @copyright   Copyright 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Dette er standard visningen.
 */
class AlarmhistoryViewAlarmhistory extends JViewLegacy
{
	protected $sections = array();
	
	public function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$db = JFactory::getDbo();
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raisError(500, implode("\n", $errors));
			return false;
		}
		
		$doc->addScriptDeclaration("
				var sections = new Array();
				var sites = new Array();
				var types = new Array();
		");
		
		// Hent områder
		$query=$db->getQuery(true);
		$query->select('id, title, DISTRICT');
		$query->from('#__alarmhistory_section');
		$query->order('ordering');
		$db->setQuery($query);
		if ($db->execute())
		{
			$results=$db->loadRowList();
			foreach ($results as $result)
			{
				$doc->addScriptDeclaration("sections.push(new Array('".$result[0]."','".$result[1]."','".$result[2]."'));");
			}
		};
		
		// Hent stasjoner
		$query=$db->getQuery(true);
		$query->select('id, title, LOCATION, section');
		$query->from('#__alarmhistory_site');
		$query->order('ordering');
		$db->setQuery($query);
		if ($db->execute())
		{
			$results=$db->loadRowList();
			foreach ($results as $result)
			{
				$doc->addScriptDeclaration("sites.push(new Array('".
						$result[0]."','".
						$result[1]."','".
						$result[2]."','".
						$result[3]."'));");
			}
		};
		
		// Hent meldingstype
		$query=$db->getQuery(true);
		$query->select('id, title, style, UNIT, ALMSTATUS, MSGTYPE, PRIORITY');
		$query->from('#__alarmhistory_type');
		$query->order('ordering');
		$db->setQuery($query);
		if ($db->execute())
		{
			$results=$db->loadRowList();
			foreach ($results as $result)
			{
				$doc->addScriptDeclaration("types.push(new Array('".
						$result[0]."','".
						$result[1]."','".
						$result[2]."','".
						$result[3]."','".
						$result[4]."','".
						$result[5]."','".
						$result[6]."'));");
			}
		};
		
		$doc->addScript( $this->baseurl . '/media/com_alarmhistory/js/AlarmHistory.js' );

		$doc->addStyleSheet($this->baseurl . '/media/com_alarmhistory/css/template.css');
		
		parent::display($tpl);
	}
}