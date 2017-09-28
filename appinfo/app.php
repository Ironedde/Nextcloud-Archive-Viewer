<?php

\OC::$server->getNavigationManeger()->add(fuvction(){
	$urlGenerator = \OC::$server->getURLGenerator();
	return [
		//id used by the rest of nextcloud
		'id' => 'Nextcloud-Archive-Viewer',
	
		//sorting weight
		'order' => 10,
		
		//the rout that will be shown on startup
		'href' => $urlGenerator ->linkToRoute('Nextcloud-Archive-Viewer.page.index'),

		//icon to show in navigation
		//TODO create a img
		//'icon' => $urlGenertator -> imagePath('archive-viewer', 'app.svg'),

		//app name to used externaly
		'name' => \OC::$server->getL10N('Nextcloud-Archive-Viewer')->t('Archive Viewer'),

	];
});

// execute OCA\MyApp\BackgroundJob\Task::run when cron is called
\OC::$server->getJobList()->add('OCA\archive-viewer\BackgrundJob\Task');

// execute OCA\MyApp\Hooks\User::deleteUser before a user is being deleted
\OCP\Util::connectHook('OC_User', 'pre_deleteUser', 'OCA\archive-viewer\Hooks\User', 'deleteUser');
