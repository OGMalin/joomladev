<?php
defined('_JEXEC') or die;

/**
 * 
 * @author oddg
 *
 */
class PolarbookControllerExport extends JControllerLegacy
{
	public function backup()
	{
		$model=$this->getModel('Export');		
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$id=$this->input->getInt('id',0);
		if ($id>0){
			$access=$model->getBookAccess($id);
			if ($access){
				$filename=$model->getBookName($id).'.pbb';
				ob_end_clean();
				JResponse::clearHeaders();
				JResponse::setHeader("Content-type", "application/BolarBook backup");
				JResponse::setHeader("Content-Disposition", "attachment; filename=" . $filename . ";",true);
				JResponse::sendHeaders();
				$model->exportBackup($id);
			}
		}
		$app->close();
	}
	
	public function epd()
	{
		$model=$this->getModel('Export');		
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$id=$this->input->getInt('id',0);
		if ($id>0){
			$access=$model->getBookAccess($id);
			if ($access){
				$filename=$model->getBookName($id).'.epd';
				ob_end_clean();
				JResponse::clearHeaders();
				JResponse::setHeader("Content-type", "application/Chessposition");
				JResponse::setHeader("Content-Disposition", "attachment; filename=" . $filename . ";",true);
				JResponse::sendHeaders();
				$model->exportEpd($id);
			}
		}
		$app->close();
	}
	
	public function pgn()
	{
		$model=$this->getModel('Export');
		$app=JFactory::getApplication();
		$doc = JFactory::getDocument();
		$this->input = $app->input;
		$id=$this->input->getInt('id',0);
		if ($id>0){
			$access=$model->getBookAccess($id);
			if ($access){
				$filename=$model->getBookName($id).'.pgn';
				ob_end_clean();
				JResponse::clearHeaders();
				JResponse::setHeader("Content-type", "application/Chessgames");
				JResponse::setHeader("Content-Disposition", "attachment; filename=" . $filename . ";",true);
				JResponse::sendHeaders();
				$model->exportPgn($id);
			}
		}
		$app->close();
	}
}
