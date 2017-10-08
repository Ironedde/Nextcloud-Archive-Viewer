<?php
namespace OCA\ArchiveViewer\AppInfo;

use OCP\Util;
use OCA\ArchiveViewer\AppInfo;

$app = new Application();
$c = $app->getContainer();
$appName = $c->query('AppName');

$eventDispatcher = \OC::$server->getEventDispatcher();
$eventDispatcher->addListener('OCA\Files::loadAdditionalScripts', function(){
    Util::addScript('archive-viewer', 'archiveMain' );
    Util::addScript('archive-viewer', 'archiveMain' );
});
