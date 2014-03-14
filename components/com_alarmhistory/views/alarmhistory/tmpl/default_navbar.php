<form class="navbar-form">
	<select class="pull-left input-medium btn" id='section' onchange='sectionChanged()'></select>
	<div class="pull-left">&nbsp;</div>
	<select class="pull-left input-medium btn"  id='site' onchange='searchList()'></select>
	<div class="pull-left">&nbsp;</div>
	<div  class="pull-left"><?php echo JHtml::_('calendar',date('d.m.Y',time()),'Dato','setdate','%d.%m.%Y', array('class' => 'input-small','onchange' => 'searchList();return false;')); ?></div>
	<div class="pull-left">&nbsp;</div>
	<div  class="form-search input-append pull-left">
		<input class='input-small' type='text' onchange="searchList();return false;" placeholder='Søketekst' id="searchtext" />
		<span class="add-on"><a href="#" onclick="searchList();return false;"><i class="icon-search"></i></a></span>
	</div>
	<div class="pull-left">&nbsp;</div>
	<div class="input-prepend input-append pull-left">
		<div class="add-on"><a href="#" onclick="getPage(false);return false;"><i class="icon-arrow-left"></i></a></div>
		<select class="input-small btn" style="margin-top: 0px" id='limit' onchange="getList();return false;">
			<option value="10">10</option>
			<option value="20" selected>20</option>
			<option value="50">50</option>
			<option value="100">100</option>
			<option value="1000">1000</option>
		</select>
		<div class="add-on"><a href="#" onclick="getPage(true);return false;"><i class="icon-arrow-right"></i></a></div>
	</div>
	<div class="pull-left">&nbsp;</div>
	<div class="pull-left btn btn-small" style="line-height: 7px; margin-top: 5px; ">
		<small>Auto</small><br />
		<input type="checkbox" id="auto" onchange="autoChanged();return false;"/>
	</div>
	<div class="pull-right">&nbsp;</div>
	<div class="pull-right" id="refreshing"><i class="icon-refresh"></i></div>
</form>
