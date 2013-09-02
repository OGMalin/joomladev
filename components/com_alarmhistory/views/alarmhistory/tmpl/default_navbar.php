<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<form class="navbar-form">
				<select class='span2' id='section'>
					<option value=''>Alt</option>
					<option value='KRAFT'>Produksjon</option>
					<option value='NETT'>Nett</option>
				</select>
				<select class='span2' id='site'>
					<option value=''>Alt</option>
				</select>
				<input class='span2' type='text' placeholder='Søketekst'>
				<?php echo JHtml::_('calendar',date('m.d.Y',time()),'Fra','fromdate');//,'%d.%m.%Y'); ?>
    	</form>
		</div>
	</div>
</div>
