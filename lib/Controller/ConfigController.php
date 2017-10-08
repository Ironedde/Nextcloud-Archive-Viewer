<?php
namespace OCA\ArchiveViewer\Controller;

use OCP\IRequest;
use OCP\ILogger;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;

/**
 * Class ConfigController
 *
 * @package OCA\ArchiveViewer\Controller
 */
class ConfigController extends Controller {
	
	use Config;

	/**
	 * Constructor
	 *
     * @param string $AppName - application name
     * @param IRequest $request - request object
     * @param ILogger $logger - logger
	 */
	public function __construct($appName, IRequest $request, ILogger $logger){
		parent::__construct($appName, $request);
		$this->logger = $logger;
	}

    /**
	 * Get supported formats
	 *
	 * @return array
	 *
     * @NoAdminRequired
     */	
	public function get(){
		try {
			return $this->getConfig();
		} catch (\Exception $e) {
			//TODO:Add prettier error & Add logger functionality
			return new JSONResponse(
				[
					'message' => "Something unexpected happend, sorry.",
					'success' => false,
				]
			);
		}
	}
}
