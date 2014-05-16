<?php
class Uploader extends CCTemplate
{
	public function bytesToSize($bytes, $precision = 2, $lang = 'eng') {  
		$kilobyte = 1024;
		$megabyte = $kilobyte * 1024;
		$gigabyte = $megabyte * 1024;
		$terabyte = $gigabyte * 1024;
		
		if (($bytes >= 0) && ($bytes < $kilobyte)) {
			if ($lang == "eng") {
				return $bytes . ' B';
			} else {
				return $bytes . ' b';
			}
		} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
			if ($lang == "eng") {
				return round($bytes / $kilobyte, $precision) . ' KB';
			} else {
				return round($bytes / $kilobyte, $precision) . ' kb';
			}
		} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
			if ($lang == "eng") {
				return round($bytes / $megabyte, $precision) . ' MB';
			} else {
				return round($bytes / $megabyte, $precision) . ' mb';
			}
		} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
			if ($lang == "eng") {
				return round($bytes / $gigabyte, $precision) . ' GB';
			} else {
				return round($bytes / $gigabyte, $precision) . ' gb';
			}
		} elseif ($bytes >= $terabyte) {
			if ($lang == "eng") {
				return round($bytes / $terabyte, $precision) . ' TB';
			} else {
				return round($bytes / $terabyte, $precision) . ' tb';
			}
		} else {
			return $bytes . ' B';
		}
	}
	
	public function simple_upload($file, $file_name, $_accepted_extensions_, $max_size, $destination_folder, $_file_error_ = '', $lang = 'eng') {		
		//Check length of Extension Var
		if(strlen($_accepted_extensions_) > 0){
			$_accepted_extensions_ = @explode(",",$_accepted_extensions_);
		//Non Exist...
		} else {
			$_accepted_extensions_ = array();
		}
		//Set Some Vars
		$errStr = "";
		//Check if file exists
		if ((is_uploaded_file($file['tmp_name'])) && ($file['error'] == 0)) {
			//Set Some Vars
			$_name_ = $file['name'];
			$_type_ = $file['type'];
			$_tmp_name_ = $file['tmp_name'];
			$_size_ = $file['size'];
			//Check File Sizes
			if($_size_ > $max_size && $max_size > 0){
				$generate_errors = 1;
				if ($lang == 'fra') {
					$errStr .= "Le fichier dépasse la limite permise et ne peut pas être sauvegardé. (max ".$this->bytesToSize($max_size, 2, 'fra').")<br />";
				} else {
					$errStr .= "The file exceeds the limit allowed and cannot be saved. (maximum ".$this->bytesToSize($max_size, 2, 'eng').")<br />";	
				}
			}
			//Get our Extensions from filename
			$_ext_ = explode(".", $_name_);
			$_ext_ = strtolower($_ext_[count($_ext_)-1]);
			//Generate a New
			$_generated_name_ = $file_name;
			//Check if the extension exists in an array, and make sure accepted is more then 0
			if(!in_array($_ext_, $_accepted_extensions_) && count($_accepted_extensions_) > 0) {
				$generate_errors = 1;
				$accepted_extension_string = '';
				$extension_count = count($_accepted_extensions_);
				$i = 0;
				foreach ($_accepted_extensions_ as $values) {
					$i++;
					if ($i == $extension_count) {
						$accepted_extension_string .= $values;
					} else {
						$accepted_extension_string .= $values . ', ';
					}
					
				}
				if ($lang == 'fra') {
					$errStr .= "L'extension du fichier n'est pas autorisée. Assurez-vous de bien sauvegarder votre document en format ".strtoupper($accepted_extension_string).".<br />";
				} else {
					
					$errStr .= "The file extension is not permitted. Please ensure to save your document in a ".strtoupper($accepted_extension_string)." format.<br />";
				}
			}
			//Check if the directory is writable and exists
			if(!is_dir($destination_folder) && is_writeable($destination_folder)){
				$generate_errors = 1;
				if ($lang == 'fra') {
					$errStr .= "Erreur : le dossier n'existe pas. Veuillez aviser l'équipe d'Intranet de cette problématique.<br />";
				} else {
					$errStr .= "Error: the directory does not exist. Please advise the Intranet Web Team of this issue.<br />";
				}
			}
			//Make sure error string is empty and proceed with copy to the destination folder
			if(empty($errStr)){
				// Everything is ok...
				if(@copy($_tmp_name_, $destination_folder . "" . $_generated_name_)){
					$errStr = true;
				} else {
					if ($lang == 'fra') {
						$errStr .= "Erreur : le fichier n'a pas pu être sauvegardé. Essayer de nouveau.<br />";
					} else {
						$errStr .= "Error: the file could not be saved. Please try again.<br />";
					}
				}
				
			} else {
				//Error String Exists...
				return $errStr;
			}
		} else {
			$errStr = 1;
		}
		return $errStr;
	}
}
?>