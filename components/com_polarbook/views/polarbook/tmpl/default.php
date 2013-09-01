<?php
defined('_JEXEC') or die;
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