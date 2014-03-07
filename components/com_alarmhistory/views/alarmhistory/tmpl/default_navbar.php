<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<form class="navbar-form">
 				<select class='span2' id='section' onchange='sectionChanged()'>
				</select>
 				<select class='span2' id='site'>
 				</select>
 				<input class='span2' type='text' placeholder='Søketekst' id="searchtext">
				<!--  ?php echo JHtml::_('calendar',date('d.m.Y',time()),'Fra','setdate','%d.%m.%Y', 'onchange="dateChanged();return false;"'); ? -->
				<?php echo JHtml::_('calendar',date('d.m.Y',time()),'','setdate','%d.%m.%Y'); ?>
 				<div style="padding-right:10px;" class="pull-right"><a href="#" onclick="getList();return false;"><i class="icon-search"></i></a></div>
				<div style="padding-right:10px;" class="pull-right" id="refreshing"><i class="icon-refresh"></i></div>
			</form>
		</div>
	</div>
</div>
