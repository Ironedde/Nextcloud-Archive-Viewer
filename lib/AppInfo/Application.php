<?php

/**
 * This file is for Dependancy injection into the main application
 *
 *
 *
 **/

namespace OCA\ArchiveViewer\AppInfo;

use OCP\AppFramework\App;
use OCA\ArchiveViewer\Controller\ConfigController;

/**
 * Class Application
 *
 * @package OCA\ArchiveViewer\AppInfo
 */
class Application extends App {

	/**
	 *  Constructor
	 *
	 *  @param array $urlParams
	 */
    public function __construct(array $urlParams = [])
    {
        $appName = "archive-viewer";

        parent::__construct($appName, $urlParams);

        $container = $this->getContainer();
		
		/** 
		 * Controllers
		 */
        $container->registerService("ConfigController", function($c)
        {
            return new ConfigController(
                $c->query("AppName"),
                $c->query("Request"),
                $c->query("Logger")
            );
		});
	}
}
