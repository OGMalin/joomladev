<?php
defined('_JEXEC') or die;

class FilearchiveModelFiles extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(	'id', 'a.id',
																				'name', 'a.name',
																				'alias', 'a.alias',
																				'description', 'a.description',
																				'file', 'a.file', 
																			);
		}

		parent::__construct($config);
	}

	protected function getListQuery()
	{
		$db    = $this->getDbo();
		$query  = $db->getQuery(true);

		$query->select($this->getState('list.select','a.*'));
		$query->from($db->quoteName('#__filearchive').' AS a');

		return $query;
	}
}