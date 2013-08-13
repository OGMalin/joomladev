<?php
defined('_JEXEC') or die;
$user=JFactory::getUser();
JFactory::getDocument()->addScriptDeclaration("
		var book=" . $this->book . ";
		var moves='" . $this->moves . "';
		var status='" . $this->status . "';
		var userid=" . $user->id . ";
		var username='';
		var imageUrl='" . $this->baseurl . "/media/com_polarbook/images/';
		var responseUrl='" . $this->baseurl . "/index.php?option=com_polarbook&amp;';
		var imagedir='" . $this->baseurl . "/media/com_polarbook/images/1/';
		var piecechar='" . JText::_('COM_POLARBOOK_PIECECHAR') . "';
		
		if ({$user->guest})
			userid=0;
			
		if (userid<1)
			username='" . JText::_('COM_POLARBOOK_USER_GUEST') . "';
		else
			username='" . $user->name . "';
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