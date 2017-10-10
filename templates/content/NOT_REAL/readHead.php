<?php
class readHead {
	
	public function read($file2read) {
	#$file2read = 'test.zip';
	$json = '';
	
	$file_parts = pathinfo($file2read);
		switch ($file_parts['extension']) {     #Check file extension and decide RAR or ZIP
			case "rar":
				try {
					require_once 'RarArchiver.php';
					$rar = new RarArchiver($file2read); #Tries to read an archive. Prints an error if it fails.
					foreach ($rar->getFileList() as $file) { #For each file do:
						$json .= (string)$file . ';';
					}
                    return substr($json, 0, -1);
				} catch (Exception $e) {
					echo 'Caught exception: ', $e->getMessage(), "\n";
				}
				break;

			case "zip":
				try {
					require_once 'ZipArchiver.php';
					$zip = new ZipArchive(); #Tries to read an archive. Prints an error if it fails.

					$zip->open($file2read);

					for ($i = 0; $i < $zip->numFiles; $i++) {
                        $json .= $zip->getNameIndex($i) . ';';
					}
                    return substr($json, 0, -1);
				} catch (Exception $e) {
					echo 'Caught exception: ', $e->getMessage(), "\n";
				}
				break;

			case "": // Handle file extension for files ending in '.'
				echo "Error! File ends in .!";
				break;
			case NULL: // Handle no file extension
				echo "Error! No extenstion!";
				break;
		}
	}
}

?>