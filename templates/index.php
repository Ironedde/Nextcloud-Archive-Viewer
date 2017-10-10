<?php
script('archive-viewer', 'script');
style('archive-viewer', 'style');

//Load breadcrumb script from files app
//style('files','files')

style('files','merged');
style('files','mobile');
style('files','upload');
style('files','detailsView');
?>



<div id="app">
	<div id="app-navigation">
		<?php print_unescaped($this->inc('navigation/index')); ?>
		<?php print_unescaped($this->inc('settings/index')); ?>
	</div>

	<div id="app-content">
		<div id="app-content-wrapper">
			<?php print_unescaped($this->inc('content/index')); ?>
		</div>
	</div>
</div>

