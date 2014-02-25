<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/utility.php';
require_once JPATH_COMPONENT.'/helpers/importpgn.php';

jimport('joomla.application.component.modelitem');

/**
 * This models supports retrieving openingbooks from the database.
 * @package     Joomla
 * @subpackage  com_polarbook
 * @since       1.0
 *
 */
class PolarbookModelImport extends JModelItem
{
	public function importFile($book_id)
	{
		$pgn=new ImportPgnHelper();
		
		$app=JFactory::getApplication();
		$input = $app->input;
		$file=$input->files->get('userfile');
		if ($file['error']>0)
			return "Error uploading file, error:" .$file['error'];
		if ($file['size']>1000000)
			return "File too big";
		if ($file['size']==0)
			return "File empty";
		$pgn->read($file['tmp_name']);
		$pgn->import($book_id);
		return 'Import ok';
	}
	
	public function getBookAccess($book_id)
	{
		$user = JFactory::getUser();
		if (!isset($user->id))
			$user->id=0;

		// Only members can create new books
		if ($book_id==0)
		{
			if ($user->id>0)
				return 2;
			return 0;
		}

		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select(array('user','public','member','readusers','writeusers'));
		$query->from('#__polarbook_book');
		$query->where('id='.$book_id);
		$db->setQuery($query);
		$db->execute();
		$result=$db->loadAssoc();

		if (!$result)
			return 0;
		
		$access=$result['public'];
		
		// All visitors can write
		if ($access==2)
			return 2;

		// Public user
		if ($user->id==0)
			return $access;

		// Owner can write
		if ($result['user']==$user->id)
			return 2;
		
		// Keep best access of public and member
		if ($result['member']>$access)
			$access=$result['member'];
		
		// Allready write access?
		if ($access==2)
			return 2;

		// Added as a write user
		if ($result['writeusers'] && in_array($user->id,explode(';',$result['writeusers'])))
			return 2;

		// Allready read access
		if ($access==1)
			return 1;

		// Added as a read user
		if ($result['readusers'] && in_array($user->id,explode(';',$result['readusers'])))
			return 1;
		
		return 0;
	}

}