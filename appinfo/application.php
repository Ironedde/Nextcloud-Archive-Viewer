<?php
namespace OCA\ArchiveViewer\AppInfo;
use \OCP\AppFramework\App;

use \OCA\ArchiveViewer\Controller\PageController;


class Application extends App {

    public function __construct(array $urlParams=array()){
        parent::__construct('Nextcloud-Archive-Viewer', $urlParams);

	        $container = $this->getContainer();

	        /**
		 * Controllers
		 */
	        $container->registerService('PageController', function($c) {
			return new PageController(
				$c->query('Archive Viewer'),
				$c->query('Request')
			);
		});
	}

}
