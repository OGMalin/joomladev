<?php
defined('_JEXEC') or die;
?>
<!-- About dialog -->
<div id="about" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="aboutLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="aboutLabel">PolarBook</h3>
	</div>
	<div class="modal-body">
		<p><b>PolarBook</b> v. <?php echo $this->component['version'] ?></p>
		<p><?php echo JText::_('COM_POLARBOOK_DESC') ?></p>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_OK') ?></button>
	</div>
</div>

<!-- File New dialog -->
<div id="filenew" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="fileNewLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="fileNewLabel"><?php echo JText::_('COM_POLARBOOK_FILENEW_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<label><?php echo JText::_('COM_POLARBOOK_FILENEW_NAME') ?><br /><input id='filenewname' type='text'></input></label>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_CANCEL') ?></button>
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick='menuFileNew();return false;'><?php echo JText::_('COM_POLARBOOK_SAVE') ?></button>
	</div>
</div>

<!-- File Open dialog -->
<div id="fileopen" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="fileOpenLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="fileOpenLabel"><?php echo JText::_('COM_POLARBOOK_FILEOPEN_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<label><?php echo JText::_('COM_POLARBOOK_FILEOPEN_NAME') ?></label>
		<div id='fileopenselect'></div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_CANCEL') ?></button>
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="menuFileOpen(1);return false;"><?php echo JText::_('COM_POLARBOOK_OK') ?></button>
	</div>
</div>

<!-- File delete dialog -->
<div id="filedelete" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="fileDeleteLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="fileDeleteLabel"><?php echo JText::_('COM_POLARBOOK_FILEDELETE_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<label><?php echo JText::_('COM_POLARBOOK_FILEDELETE_NAME') ?><br />
		<input id='filedeletename' readonly='readonly' type='text' value=''></input></label>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_CANCEL') ?></button>
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="menuFileDelete(1);return false;"><?php echo JText::_('COM_POLARBOOK_DELETE') ?></button>
	</div>
</div>

<!-- File Trash dialog -->
<div id="filetrash" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="fileTrashLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="fileTrashLabel"><?php echo JText::_('COM_POLARBOOK_FILETRASH_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<label><?php echo JText::_('COM_POLARBOOK_FILETRASH_NAME') ?><br />
 		<div id='filetrashselect'></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="menuFileTrash(2);return false;"><?php echo JText::_('COM_POLARBOOK_RECOVER') ?></button>
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_CANCEL') ?></button>
		<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true" onclick="menuFileTrash(1);return false;"><?php echo JText::_('COM_POLARBOOK_DELETE') ?></button>
	</div>
</div>

<!-- Book ImportBook dialog -->
<div id="bookImportBook" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="bookImportBookLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="bookImportBookLabel"><?php echo JText::_('COM_POLARBOOK_BOOKIMPORTBOOK_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<label><?php echo JText::_('COM_POLARBOOK_BOOKIMPORTBOOK_NAME') ?></label>
		<div id='bookimportbookselect'></div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_CLOSE') ?></button>
		<button id="bookimportbooksave" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="menuBookImportBook(1);return false;"><?php echo JText::_('COM_POLARBOOK_SAVE') ?></button>
	</div>
</div>

<!-- Book ImportFile dialog -->
<div id="bookImportFile" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="bookImportFileLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="bookImportFileLabel"><?php echo JText::_('COM_POLARBOOK_BOOKIMPORTFILE_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<form name="upload" method="post" enctype="multipart/form-data">
			<input id="bookimportfilebook" type="hidden" name="book_id" value=""></input>
			<input type="hidden" name="task" value="import.file"></input>
			<label><?php echo JText::_('COM_POLARBOOK_BOOKIMPORTFILE_NAME') ?></label>
			<input type="file" name="userfile"><br>
			<input type="submit"></input>
		</form>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_CLOSE') ?></button>
	</div>
</div>

<!-- Book Property dialog -->
<div id="bookProperty" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="bookPropertyLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="bookPropertyLabel"><?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<table class="well" cellpadding="5">
			<tr>
				<td>
					<?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_NAME') ?>:<br />
					<input id='bookpropertyname' type='text' value=''></input>
				</td>
				<td>
					<?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_AUTHOR') ?>:<br />
					<p id='bookpropertyauthor'></p>
				</td>
			</tr>
		</table>
		<div><?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_COMMENT') ?>:<br />
		<textarea id='bookpropertycomment'></textarea></div>
		<table id="bookpropertyaccess" class="well" cellpadding="5">
			<tr>
				<th colspan='2'>
					<?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_ACCESS') ?>
				</th>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_PUBLIC') ?> :<br />
					<select class='span2' id='bookpropertypublic'>
					<option value='0'><?php echo JText::_('COM_POLARBOOK_NONE') ?></option>
						<option value='1'><?php echo JText::_('COM_POLARBOOK_READ') ?></option>
					</select>
				</td>
				<td>
					<?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_MEMBER') ?> :<br />
					<select class='span2' id='bookpropertymember'>
						<option value='0'><?php echo JText::_('COM_POLARBOOK_NONE') ?></option>
						<option value='1'><?php echo JText::_('COM_POLARBOOK_READ') ?></option>
						<option value='2'><?php echo JText::_('COM_POLARBOOK_WRITE') ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_USERREAD') ?>
					<div id='bookpropertyuserreadselect' title='<?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_ACCESSCOMMENT') ?>'></div>
				</td>
				<td>
					<?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_USERWRITE') ?>
					<div id='bookpropertyuserwriteselect' title='<?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_ACCESSCOMMENT') ?>'></div>
				</td>
			</tr>
		</table>
		<table cellpadding="5">
			<tr>
				<td>
					<?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_CREATED') ?> :
					<p id='bookpropertycreated'></p>
				</td>
				<td align="center">
					<?php echo JText::_('COM_POLARBOOK_BOOKPROPERTY_POSITIONS') ?> :
					<p id='bookpropertypositions'></p>
				</td>
			</tr>
		</table>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_CLOSE') ?></button>
		<button id="bookpropertysave" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="menuBookProperty(1);return false;"><?php echo JText::_('COM_POLARBOOK_SAVE') ?></button>
	</div>
</div>

<!-- Tools Repertoire dialog -->
<div id="toolsRepertoire" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="toolsRepertoireLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="toolsRepertoireLabel"><?php echo JText::_('COM_POLARBOOK_TOOLSREPERTOIRE_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<table>
		<tr>
		<td class="well" valign="top">
			<label><?php echo JText::_('COM_POLARBOOK_TOOLSREPERTOIRE_WHO') ?></label>
			<label><input id='toolsrepertoirewhite' type='radio' name='repertoirecolor' value='1' checked></input>
			<?php echo JText::_('COM_POLARBOOK_TOOLSREPERTOIRE_WHITE') ?></label>
			<label><input id='toolsrepertoireblack' type='radio' name='repertoirecolor' value='1'></input> 
			<?php echo JText::_('COM_POLARBOOK_TOOLSREPERTOIRE_BLACK') ?></label>
		</td><td class="well"  valign="top">
		<label><?php echo JText::_('COM_POLARBOOK_TOOLSREPERTOIRE_LEVEL') ?><BR />
			<select id="toolsrepertoireselect">
				<option value='0'>0</option>
				<option value='1' selected>1</option>
				<option value='2'>2</option>
				<option value='3'>3</option>
				<option value='4'>4</option>
				<option value='5'>5</option>
			</select>
		</label>
		</td>
		</tr>
		</table>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_CANCEL') ?></button>
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="menuToolsRepertoire();return false;"><?php echo JText::_('COM_POLARBOOK_SAVE') ?></button>
	</div>
</div>

<!-- Tools Link dialog -->
<div id="toolsLink" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="toolsRLinkLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="toolsLinkLabel"><?php echo JText::_('COM_POLARBOOK_TOOLSLINK_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<label><?php echo JText::_('COM_POLARBOOK_TOOLSLINK_BOOKLINK') ?><br /><input class='span5' id='booklink' type='text'></input></label>
		<label><?php echo JText::_('COM_POLARBOOK_TOOLSLINK_POSITIONLINK') ?><br /><input class='span5' id='positionlink' type='text'></input></label>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_OK') ?></button>
	</div>
</div>

<!-- User property dialog -->
<div id="userProperty" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="userPropertyLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="userPropertyLabel"><?php echo JText::_('COM_POLARBOOK_USERPROPERTY_LABEL') ?></h3>
	</div>
	<div class="modal-body">
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_OK') ?></button>
	</div>
</div>

<!-- EditMove dialog -->
<div id="editmove" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="editMoveLabel" aria-hidden="true">
	<input id='moveindex' type='hidden' />
	<input id='movemove' type='hidden' />
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="editMoveLabel"><?php echo JText::_('COM_POLARBOOK_EDITMOVE_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<label><input id='movecomment' maxlength='16' type='text' value=''></input>
		<?php echo JText::_('COM_POLARBOOK_EDITMOVE_COMMENT') ?></label>
		<label><input id='moverepertoire' type='number' value=0></input>
		<?php echo JText::_('COM_POLARBOOK_EDITMOVE_REPERTOIRE') ?></label>
		<label><input id='movestatistics' type='number' value=0></input>
		<?php echo JText::_('COM_POLARBOOK_EDITMOVE_STATISTICS') ?></label>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_CANCEL') ?></button>
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="editMove(-1);return false;"><?php echo JText::_('COM_POLARBOOK_SAVE') ?></button>
	</div>
</div>

<!-- Start practice -->
<div id="startPractice" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="startPracticeLable" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="startPracticeLable"><?php echo JText::_('COM_POLARBOOK_STARTPRACTICE_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<table>
		<tr>
		<td class="well" valign="top">
			<label><?php echo JText::_('COM_POLARBOOK_STARTPRACTICE_WHO') ?></label>
			<label><input id='startpracticewhite' type='radio' name='practicecolor' value='1' checked></input>
			<?php echo JText::_('COM_POLARBOOK_STARTPRACTICE_WHITE') ?></label>
			<label><input id='startpracticeblack' type='radio' name='practicecolor' value='1'></input> 
			<?php echo JText::_('COM_POLARBOOK_STARTPRACTICE_BLACK') ?></label>
		</td><td class="well"  valign="top">
		<label><?php echo JText::_('COM_POLARBOOK_STARTPRACTICE_LEVEL') ?><BR />
			<select id="startpracticeselect">
				<option value='1'>1</option>
				<option value='2'>2</option>
				<option value='3'>3</option>
				<option value='4'>4</option>
				<option value='5'>5</option>
			</select>
		</label>
		</td>
		</tr>
		</table>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_CANCEL') ?></button>
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="menuPractice(1);return false;"><?php echo JText::_('COM_POLARBOOK_START') ?></button>
	</div>
</div>

<!-- restart Practice dialog -->
<div id="restartpractice" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="restartpracticeLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="restartpracticeLabel"><?php echo JText::_('COM_POLARBOOK_PRACTICE_RESTART_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<label><?php echo JText::_('COM_POLARBOOK_PRACTICE_RESTART_COMMENT') ?></label>
		<textarea class="span5" readonly="readonly" id="restartpracticecomment"></textarea>
	</div>
	<div class="modal-footer">
		<p><?php echo JText::_('COM_POLARBOOK_PRACTICE_RESTART') ?>?</p>
	<button class="btn" data-dismiss="modal" aria-hidden="true" onclick="menuPractice(0);return false;"><?php echo JText::_('COM_POLARBOOK_NO') ?></button>
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="menuPractice(4);return false;"><?php echo JText::_('COM_POLARBOOK_YES') ?></button>
	</div>
</div>

<!-- Book Export -->
<div id="bookExport" class="modal hide fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="bookExportLable" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="bookExportLable"><?php echo JText::_('COM_POLARBOOK_BOOKEXPORT_LABEL') ?></h3>
	</div>
	<div class="modal-body">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="menuBookExport(1);return false;"><?php echo JText::_('COM_POLARBOOK_BOOKEXPORT_PGN') ?></button>
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="menuBookExport(2);return false;"><?php echo JText::_('COM_POLARBOOK_BOOKEXPORT_EPD') ?></button>
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="menuBookExport(3);return false;"><?php echo JText::_('COM_POLARBOOK_BOOKEXPORT_BACKUP') ?></button>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_POLARBOOK_CANCEL') ?></button>
	</div>
</div>

