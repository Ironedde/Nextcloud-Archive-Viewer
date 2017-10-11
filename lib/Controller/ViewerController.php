<?php
namespace OCA\ArchiveViewer\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

use OCP\ILogger;


use OCP\Files\IRootFolder;
use OCP\Files\FileInfo;
use OC\Files\Filesystem;
use OC\Files\View;

use OCP\IUserSession;

use OCP\IURLGenerator;


class ViewerController extends Controller {
	private $userId;
	private $root;
	private $logger;
	private $userSession;
	private $server_userSession;
	private $home;


	public function __construct($AppName, IRequest $request,ILogger $logger,IRootFolder $root, IUserSession $userSession){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
        $this->userSession = $userSession;
		$this->root = $root;
		$this->logger = $logger;
	}

	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index($fileId) {
        list ($file, $error) = $this->getFile($fileId);

        if (isset($error))
        {
            $this->logger->error("Load: " . $fileId . " " . $error, array("app" => $this->appName));
            return ["error" => $error];
        }

        $uid = $this->userSession->getUser()->getUID();
		$home = $this->userSession->getUser()->getHome();
        $baseFolder = $this->root->getUserFolder($uid);
		//
		$params = [
			"webRoot" => $home,
			"root" => $this->root->getPath(),
			"fileSize" => $file->getSize(),
			"readableSize" => $this->getReadableSize($file->getSize()),
			"fileName" => $file->getName(),
			"mimeType" => $file->getMimetype(),
            "filePath" => $home . "/files" . $baseFolder->getRelativePath($file->getPath()),
        ];

        //"filePath" => $baseFolder->getRelativePath($file->getPath()),
		$response = new TemplateResponse($this->appName, "index", $params);
		return $response;
		//return new TemplateResponse('archive-viewer', 'index');  // templates/index.php
	}

    /**
     * @NoAdminRequired
    */
    private function getFile($fileId)
    {
        if (empty($fileId))
        {
            return [null, "FileId is empty"];
        }

        $files = $this->root->getById($fileId);
        if (empty($files))
        {
            return [null, "File not found"];
        }
        $file = $files[0];

        if (!$file->isReadable())
        {
            return [null, "You do not have enough permissions to view the file"];
        }
        return [$file, null];
	}

	private function getReadableSize($bytes){
		$count;
		$returnStr;
		while (strlen($bytes) > 3){
			$bytes = substr($bytes,0,-3);
			$count += 1;
		}
		switch($count){
			case 0:
				$returnStr = $bytes . " B";
				break;
			case 1:
				$returnStr = $bytes . " KiB";
				break;
			case 2:
				$returnStr = $bytes . " MiB";
				break;
			case 3:
				$returnStr = $bytes . " GiB";
				break;
			case 4:
				$returnStr = $bytes . " TiB";
				break;
		}
		return $returnStr;
	}
}
