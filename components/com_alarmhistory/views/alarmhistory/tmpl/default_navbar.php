<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<form class="navbar-form">
 				<select class='span1' id='section' onchange='sectionChanged()'>
				</select>
 				<select class='span1' id='site'>
 				</select>
 				<input class='span2' type='text' placeholder='Søketekst' id="searchtext">
 				<?php echo JHtml::_('calendar',date('d.m.Y',time()),'Dato','setdate','%d.%m.%Y', array('onchange' => 'dateChanged();return false;')); ?>
 				<div  class="input-prepend input-append">
 					<span class="add-on"><a href="#" onclick="getPage(false);return false;"><i class="icon-arrow-left"></i></a></span>
 					<select class='span1' id='limit'>
 						<option value="10">10</option>
	 					<option value="20" selected>20</option>
 						<option value="50">50</option>
 						<option value="100">100</option>
 						<option value="1000">1000</option>
 					</select>
 					<span class="add-on"><a href="#" onclick="getPage(true);return false;"><i class="icon-arrow-right"></i></a></span>
 				</div>
 				<div style="padding-right:10px;" class="pull-right"><a href="#" onclick="searchList();return false;"><i class="icon-search"></i></a></div>
				<div style="padding-right:10px;" class="pull-right" id="refreshing"><i class="icon-refresh"></i></div>
			</form>
		</div>
	</div>
</div>
