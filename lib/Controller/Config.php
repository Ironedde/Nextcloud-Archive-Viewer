<?php
namespace OCA\ArchiveViewer\Controller;

trait Config
{
	//TODO: Add support for more formats
	//"tar" => [ "mime" => "application/x-tar", "type" => "text" ],
	private $formats = [
		"zip" => [ "mime" => "application/zip", "type" => "text" ],
		"rar" => [ "mime" => "application/x-rar-compressed", "type" => "text" ],
	];

	private function getConfig() {
		return ['formats' => $this->formats];
	}
}
