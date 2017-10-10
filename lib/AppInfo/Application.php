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
use OCA\ArchiveViewer\Controller\ViewerController;

use \OCP\IContainer;
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
		$server = $container->getServer();
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
		$container->registerService("ViewerController",function(IContainer $c) use ($server)
		{
			return new ViewerController(
				$c->query("AppName"),
				$c->query("Request"),
				$c->query("Logger"),
				$server->getRootFolder(),
                $c->query("OCP\IUserSession"),
				$server->getUserSession(),
				$server->getConfig()->getSystemValue("datadirectory")
			);
			//$c->getRootFolder(),
			//$c->query('OCP\Files\IRootFolder'),
			//$c->query("RootStorage"),
		});
	}
}
