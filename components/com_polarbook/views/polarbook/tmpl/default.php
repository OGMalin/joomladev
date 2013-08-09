<?php
defined('_JEXEC') or die;
$user=JFactory::getUser();
JFactory::getDocument()->addScriptDeclaration("
	jQuery(document).ready(function(){init();});

	function init()
	{
		currentUser.user=" . $user->id . ";
		if ({$user->guest})
			currentUser.user=0;
 		if (currentUser.user<1)
 			currentUser.name='" . JText::_('COM_POLARBOOK_USER_GUEST') . "';
 		else
			currentUser.name='" . $user->name . "';
		jQuery('#username').text(currentUser.name);
		
		pbboard = new ChessBoardView();
		pbboard.setPieceChar('" . JText::_('COM_POLARBOOK_PIECECHAR') . "');
		pbboard.id='chessboard';
		pbboard.setDefaultSize();
		pbboard.imagedir='" . $this->baseurl . "/media/com_polarbook/images/1/';
		imageUrl='" . $this->baseurl . "/media/com_polarbook/images/';
		responseUrl='" . $this->baseurl . "/index.php?option=com_polarbook&amp;';
		pbboard.moveCallback=moveFromBoard;
		pbboard.boardCallback=positionFromBoard;
		
		pbboard.create();
		addMovePath(0);
		setMenu(0);
		writing(0);
		reading(0);
		var book=" . $this->book . ";
		var moves='" . $this->moves . "';
		var status='" . $this->status . "';
		if (moves!='')
			setMovePathList(moves);
		if (book)
			openBook(book);
		if (status!='')
			jQuery('#status').text(status);
				
	};
");
	
?>
<div id='polarbook'>
	<?php echo $this->loadTemplate('navbar'); ?>
	<div class="row">
		<div class="span7" id="chessboard"></div>
		<div class="well span5">
			<div class="movepath" id="movepath"></div>
			<hr />
			<div class="movelist" id="movelist"></div>
		</div>
	</div>
	<hr />
	<div>
		<textarea class="span12" readonly="readonly" onchange="textCommentChanged();return false;" id="comment"></textarea>
	</div>
	<table class="well">
		<tr>
			<td class="span1"><b>Status:</b></td>
			<td class="span1" id="read" align="center"><b>Read</b></td>
			<td class="span1" id="write" align="center"><b>Write</b></td>
			<td class="span9" id="status"></td>
		</tr>
	</table>
</div>
<?php echo $this->loadTemplate('modals'); ?>