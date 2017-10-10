<?php

	require_once 'NOT_REAL/readHead.php';
		class FileObject {
		public $name;
		private $fullPath;
		public $depth = 0;
		public function __construct($name,$path){
			$this->name = $name;
			$this->fullPath = $path;
		}
		public function getName(){
			return $this->name;
		}
		public function getPath(){
			return $this->fullPath;
		}
	}
	class Folder extends FileObject{
		public $subFolders = [];
		public $files = [];

		public function __construct($name,$path){
			parent::__construct($name,$path);
		}
		public function hasSubFolders(){
			if (isset($this->subFolders)){
				return True;
			}
			else{
				return False;
			}
		}
		public function hasSubFolder($name){
			if(array_key_exists($name,$this->subFolders)){
				return true;
			}
			else{
				return false;
			}
		}
		public function getSubFolders(){
			return $this->subFolders;
		}
		public function getSubFolder($name){
			try{
				return $this->subFolders[$name];
			}
			catch (Exception $e){
				throw new Exception("No folder found.");
			}
		}
		public function addSubFolder(Folder $folder,$depth=0){
			$this->subFolders[$folder->getName()] = $folder;
			$folder->depth=$depth;
		}
		public function addFile(BaseFile $file){
			$file->depth=$this->depth+1;
			array_push($this->files,$file);
		}
		public function getFiles(){
			return $this->files;
		}
		public function hasFiles(){
			return count($this->files) != 0;
		}
		public function addStructureToFolder($segments){
			if ($_['debug']){
				echo "<br><br>Adding structure<br><br>";
				echo "Working with:       ";
				print_r(array_values($segments));
				echo "Current subs:    ";
				print_r(array_values($this->getSubFolders()));
			}
			if (count($segments) == 1 && $segments[0] != ""){
				if ($_['debug']) {
					echo "<br>Adding new file to base of " . $this->name . "<br>";
				}
				$this->addFile(new BaseFile($segments[0],""));
			}
			else if(count($segments) > 1){
				$subFolderFound = false;
				$folders = $this->getSubFolders();
				foreach ($folders as $folder){
					if ($folder->getName() == $segments[0]){
						$subFolderFound = true;
					}
				}
				if ($subFolderFound != true){
					if ($_['debug']) {
						echo "<br>Adding new Folder to base of " . $this->name . "<br>";
					}
					$newFolder = new Folder($segments[0],"");
					$this->addSubFolder($newFolder,$this->depth+1);
				}
				$tmpFolder = $this->getSubFolder($segments[0]);
				array_shift($segments);
				$tmpFolder->addStructureToFolder($segments);
			}
		}
		public function printPaths(){
			$arr = [];
			printHTML($this);
			if ($this->hasSubFolders() == true){
				$folders = $this->getSubFolders();
				foreach ($folders as $folder){
					$folder->printPaths();
				}
			}
			if ($this->hasFiles()){
				$_files = $this->getFiles();
				foreach ($_files as $file){
					printHTML($file);
				}
			}
		}
	}
	function printHTML(FileObject $obj){
		echo '
		<tr>
			<td class="filename" style="padding-left:' . $obj->depth*50 . 'px">
				<input id="select-' . $obj->getName() . $obj->depth . '" type="checkbox" class="selectCheckBox checkbox">
				<label for="select-' . $obj->getName() . $obj->depth . '">
					<div class="thumbnail"';
						if($obj instanceof Folder){
							echo 'style="background-image:url(/index.php/apps/theming/img/core/filetypes/folder.svg?v=0); background-size: 32px;">';

						} else {
							echo 'style="background-image:url(/index.php/apps/theming/img/core/filetypes/package-x-generic.svg?v=0); background-size: 32px;">';
						}
					echo '</div>
					<span class="hidden-visually">Select</span>
				</label>
				<a class="name" href="#">
					<span class="nametext">
						<span class="innernametext">' . str_replace(pathinfo($obj->getName())['extension'],"",$obj->getName()) . '</span>
						<span class="extension">' . pathinfo($obj->getName())['extension'] . '</span>
					</span>
				</a>
			</td>
			<td class="filesize" style="color:rgb(160,160,160)">3 KB</td>
			<td></td>
		</tr>';

	}
	class BaseFile extends FileObject{
		public function __construct($name,$path){
			parent::__construct($name,$path);
		}
	}
	class FileParser{
		function Parse($filePath){
		}
		function printList(){
		}
	}
	$obj = new readHead();
	$files = explode(";",$obj->read($_["filePath"]));

	$directory = [];
	$filesInRoot = [];
	foreach ($files as $file){
		if ($_["debug"]){
			echo "<br><b>Starting new read</b><br>";
			echo "Current dir: ";
			print_r(array_values($directory));
			echo "<br>";
			echo "Looking at file (146):   ";
			print_r($file);
		}
		$arr = explode("/",$file);
		if (strlen($arr[0]) == strlen($file)){
			$arr = explode("\\",$file);
		}
		if ($_["debug"]) {
			echo "<br><br>";
			print_r($arr);
		}
		if (count($arr) == 1 && strlen($file) >0){
			if ($_["debug"]) {echo "<br>Pushing as file<br>"; }
			array_push($filesInRoot,new BaseFile($file,$file));
		}
		else if(array_key_exists($arr[0],$directory)){
			if ($_["debug"]) {echo "<br>Pushing as Existing Root Folder<br>"; }
			$tmpFolder = $directory[$arr[0]];
			array_shift($arr);
			$tmpFolder->addStructureToFolder($arr);
			if ($_["debug"]) {echo "<br>DONE<br>"; }
		}
		else{
			if ($_["debug"]) {echo "<br>Pushing as New Root Folder<br>"; }
			$tmpFolder = new Folder($arr[0],$arr[0]);
			$directory[$tmpFolder->getName()] = $tmpFolder;
			array_shift($arr);
			$tmpFolder-> addStructureToFolder($arr);
		}
	}

	if ($_["debug"]) {
		print_r(array_keys($directory));

		echo "<h1>Using file: ";
		print_unescaped(p($_["filePath"]));
		echo "<h1>";
	}
?>
<table id="filestable" data-preview-x="32" data-preview-y="32">
	<thead>
		<tr>
			<th id='headerName' class="column-name">
				<input type="checkbox" id="select_all_files" class="select-all checkbox">
				<label for="select_all_files">
					<span class="hidden-visually">Select all</span>
				</label>
				<div id="headerName-container">
					<input type="checkbox" id="select_all_files" class="select-all checkbox"/>
					<a class="name sort columntitle" data-sort="name"><span>Name</span></a>
				</div>
			</th>
			<th id="headerSize" class=" column-size">
				<a class="size sort columntitle" data-sort="size"><span>Size</span></a>
			</th>
			<th id="headerDate" class="column-mtime">
				<a id="modified" class="columntitle" data-sort="mtime"><span>Modified</span></a>
			</th>
		</tr>
	</thead>
	<tbody id="fileList">
<?php
	foreach (array_keys($directory) as $name){
		$directory[$name]->printPaths();
	}
	foreach ($filesInRoot as $file){
		printHTML($file);
	}
?>
	</tbody>
	<tfoot>
	</tfoot>
</table>
