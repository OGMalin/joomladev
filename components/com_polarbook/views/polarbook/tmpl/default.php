<?php
defined('_JEXEC') or die;
?>
<div id='polarbook'>
	<?php echo $this->loadTemplate('navbar'.$this->bootstrapv); ?>
	<div class="row">
		<div class="<?php echo $this->bootstrapv==3?"col-lg-7":"span7"; ?>" id="chessboard"></div>
		<div class="well <?php echo $this->bootstrapv==3?"col-lg-5":"span5"; ?>">
			<div class="movepath" id="movepath"></div>
			<hr />
			<div class="movelist" id="movelist"></div>
		</div>
	</div>
	<hr />
	<div>
		<textarea class="<?php echo $this->bootstrapv==3?"col-lg-12":"span12"; ?>" readonly="readonly" onchange="textCommentChanged();return false;" id="comment"></textarea>
	</div>
	<table class="well">
		<tr>
			<td class="<?php echo $this->bootstrapv==3?"col-lg-1":"span1"; ?>"><b>Status:</b></td>
			<td class="<?php echo $this->bootstrapv==3?"col-lg-1":"span1"; ?>" id="read" align="center"><b>Read</b></td>
			<td class="<?php echo $this->bootstrapv==3?"col-lg-1":"span1"; ?>" id="write" align="center"><b>Write</b></td>
			<td class="<?php echo $this->bootstrapv==3?"col-lg-9":"span9"; ?>" id="status"></td>
		</tr>
	</table>
</div>
<?php echo $this->loadTemplate('modals'.$this->bootstrapv); ?>