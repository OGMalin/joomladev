 <nav class="navbar">
<!--   <div class="navbar navbar-inverse"> -->
 	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a onClick="jQuery('#about').modal(); return false;" class="brand" href="#">PolarBook</a>
			<div class="nav-collapse">
				<ul class="nav">
					<li class="dropdown">
						<a id="menufile" href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_POLARBOOK_MENU_FILE'); ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a id="menufilenew" onclick="if (jQuery(this).hasClass('disabled'))return false;jQuery('#filenew').modal();jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_FILE_NEW'); ?></a></li>
							<li><a id="menufileopen" onclick="if (jQuery(this).hasClass('disabled'))return false;menuFileOpen(0);jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_FILE_OPEN'); ?></a></li>
							<li><a id="menufileclose" onclick="if (jQuery(this).hasClass('disabled'))return false;menuFileClose();jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_FILE_CLOSE'); ?></a></li>
							<li class="divider"></li>
							<li><a id="menufiledelete" onclick="if (jQuery(this).hasClass('disabled'))return false;menuFileDelete(0);jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_FILE_DELETE'); ?></a></li>
							<li class="divider"></li>
							<li><a id="menufiletrash" onclick="if (jQuery(this).hasClass('disabled'))return false;menuFileTrash(0);jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_FILE_TRASH'); ?></a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a id="menubook" href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_POLARBOOK_MENU_BOOK'); ?> <b class="caret"></b></a>
						<ul id="sub-menu" class="dropdown-menu">
							<li><a id="menubookwritemode" onclick="if (jQuery(this).hasClass('disabled'))return false;menuBookWritemode();jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_BOOK_WRITEMODE'); ?></a></li>
 							<li class="divider"></li>
							<li><a id="menubookimportbook" onclick="if (jQuery(this).hasClass('disabled'))return false;menuBookImportBook(0);jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_BOOK_IMPORTBOOK'); ?></a></li>
							<li><a id="menubookimportfile" onclick="if (jQuery(this).hasClass('disabled'))return false;menuBookImportFile();jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_BOOK_IMPORTFILE'); ?></a></li>
 							<li class="divider"></li>
							<li><a id="menubookexport" onclick="if (jQuery(this).hasClass('disabled'))return false;jQuery('#bookExport').modal();jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_BOOK_EXPORT'); ?></a></li>
							<li class="divider"></li>
							<li><a id="menubookproperty" onclick="if (jQuery(this).hasClass('disabled'))return false;menuBookProperty(0);jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_BOOK_PROPERTY'); ?></a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a id="menuboard" href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_POLARBOOK_MENU_BOARD'); ?> <b class="caret"></b></a>
						<ul id="sub-menu" class="dropdown-menu">
							<li><a id="menuboardsizebigger" onclick="if (jQuery(this).hasClass('disabled'))return false;pbboard.resize(1);return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_BOARD_SIZE_BIGGER'); ?></a></li>
							<li><a id="menuboardsizesmaller" onclick="if (jQuery(this).hasClass('disabled'))return false;pbboard.resize(-1);return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_BOARD_SIZE_SMALLER'); ?></a></li>
							<li class="divider"></li>
							<li><a id="menuboardflip" onclick="if (jQuery(this).hasClass('disabled'))return false;pbboard.invert(pbboard.inverted?false:true);jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_BOARD_FLIP'); ?></a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a id="menupractice" href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_POLARBOOK_MENU_PRACTICE'); ?> <b class="caret"></b></a>
						<ul id="sub-menu" class="dropdown-menu">
							<li><a id="menupracticestart" onclick="if (jQuery(this).hasClass('disabled'))return false;jQuery('#startPractice').modal();jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_PRACTICE_START'); ?></a></li>
							<li class="divider"></li>
							<li><a id="menupracticestop" onclick="if (jQuery(this).hasClass('disabled'))return false;menuPractice(0);jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_PRACTICE_STOP'); ?></a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a id="menutools" href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_POLARBOOK_MENU_TOOLS'); ?> <b class="caret"></b></a>
						<ul id="sub-menu" class="dropdown-menu">
							<li><a id="menutoolsrepertoire" onclick="if (jQuery(this).hasClass('disabled'))return false;jQuery('#toolsRepertoire').modal();jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_TOOLS_REPERTOIRE'); ?></a></li>
							<li><a id="menutoolsstatistics" onclick="if (jQuery(this).hasClass('disabled'))return false;menuToolsStatistics();jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_TOOLS_STATISTICS'); ?></a></li>
							<li><a id="menutoolscompress" onclick="if (jQuery(this).hasClass('disabled'))return false;menuToolsCompress();jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_TOOLS_COMPRESS'); ?></a></li>
							<li><a id="menutoolslink" onclick="if (jQuery(this).hasClass('disabled'))return false;menuToolsLink();jQuery('[data-toggle=\'dropdown\']').parent().removeClass('open');return false;" href="#"><?php echo JText::_('COM_POLARBOOK_MENU_TOOLS_LINK'); ?></a></li>
						</ul>
					</li>
				</ul>
				<div class="pull-right"><i class="icon-user"></i> <a id="username" href="#" onclick="if (jQuery(this).hasClass('disabled'))return false;jQuery('#userProperty').modal();return false;"></a></div>
				<div style="padding-right:10px;" class="pull-right"><i class="icon-file"></i> <a id="bookname" href="#" onclick="if (jQuery(this).hasClass('disabled'))return false;menuBookProperty(0);return false;"></a></div>
			</div>
		</div>
	</div>
</nav>