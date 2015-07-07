<?php
if(isset($param_1)){
	if($param_1=='-all'){
		$mask = "|%10.10s |%-30.30s | x |\n";
		printf($mask, 'Entries', 'Title');
		$databases = glob('data/*.db');
		$number = '0';
		foreach($databases as $database){
			$number = $number + 1;
			$objectCount = '0';
			$objects = glob($database.'/*');
			foreach($objects as $object){
				$objectCount = $objectCount + 1;
			}
			printf($mask, $objectCount, ' '.basename($database));
		}
	}
	if(isset($param_2)){
		if($param_2=='create'){
			// creates a new object and returns the id
			$use_template = 'false';
			if(is_dir('data/'.$param_1.'.db/')){
				if(isset($param_3)){
					if($param_3=='-t'){
						// use template to automatically fill premade fields
						$use_template = 'true';
						$template_object = $param_4;
						$randvalue = db::id_generator($param_1);
					}else{
						$randvalue = db::id_specify($param_1,$param_3);
					}
				}else{
					$randvalue = db::id_generator($param_1);
				}
				if($randvalue=='false'){}else{
					if($use_template=='true'){
						if(isset($template_object)){
							$template = glob('data/_templates.db/'.$template_object.'.do/*.val');
							foreach($template as $templateField){
								$templateFieldValue = dotval::read($templateField);
								dotval::write('data/'.$param_1.'.db/'.$randvalue.'.do/'.basename($templateField),$templateFieldValue);
							}
						}
					}
					if(dotval::read('data/_system.db/db_return.val')=='json'){
						// Return object id of this object in json
						console_log('{"id":"'.$randvalue.'"}');
					}
					if(dotval::read('data/_system.db/db_return.val')=='xml'){
						// Return object id of this object in xml
						console_log("<?xml version='1.0' encoding='utf-8'?><response><id>".$randvalue."</id></response>");
					}
					if(dotval::read('data/_system.db/db_return.val')=='raw'){
						// Return object id of this object in xml
						console_log($randvalue);
					}
				}
			}else{
				console_log('Database does not exists');
			}
		}
		if($param_2=='read'){
			if(isset($param_3)){
				if(isset($param_4)){
					// return a specific field value in object
					if(file_exists('data/_system.db/db_return.val')){
						if(dotval::read('data/_system.db/db_return.val')=='json'){
							// This is how you spit out a JSON formatted response of all field values
							$objectFieldNames = glob('data/'.$param_1.'.db/'.$param_3.'.do/*');
							$json_construct = '{"id":"'.$param_3.'","'.$param_4.'":"'.dotval::read('data/'.$param_1.'.db/'.$param_3.'.do/'.$param_4.'.val').'"}';
							console_log_no_arrow($json_construct);

						}
						if(dotval::read('data/_system.db/db_return.val')=='xml'){
							// This is how you spit out a JSON formatted response of all field values
							$objectFieldNames = glob('data/'.$param_1.'.db/'.$param_3.'.do/*');
							$json_construct = "<?xml version='1.0' encoding='utf-8'?><response><id>".$param_3."</id><".$param_4.">".dotval::read('data/'.$param_1.'.db/'.$param_3.'.do/'.$param_4.'.val')."</".$param_4."></response>";
							console_log_no_arrow($json_construct);
						}
						if(dotval::read('data/_system.db/db_return.val')=='raw'){
							console_log(dotval::read('data/'.$param_1.'.db/'.$param_3.'.do/'.$param_4.'.val'));
						}
					}else{
						console_log('db_return.val could not be located or "_system" database');
					}
				}else{
					// return all field values in object
					if(file_exists('data/_system.db/db_return.val')){
						if(dotval::read('data/_system.db/db_return.val')=='json'){
							// This is how you spit out a JSON formatted response of all field values
							$objectFieldNames = glob('data/'.$param_1.'.db/'.$param_3.'.do/*');
							$json_construct = '{"id":"'.$param_3.'",';
							foreach ($objectFieldNames as $objectFieldName) {
								$objectFieldValue = dotval::read($objectFieldName);
								$objectFieldName = basename($objectFieldName,'.val');

								$json_construct = $json_construct . '"'.$objectFieldName.'":"'.$objectFieldValue.'",';
							}
							$json_construct = substr($json_construct, 0, -1);
							$json_construct = $json_construct . "}";
							console_log_no_arrow($json_construct);

						}
						if(dotval::read('data/_system.db/db_return.val')=='xml'){
							// This is how you spit out a JSON formatted response of all field values
							$objectFieldNames = glob('data/'.$param_1.'.db/'.$param_3.'.do/*');
							$xml_construct = "<?xml version='1.0' encoding='utf-8'?><response><id>".$param_3."</id>";
							foreach ($objectFieldNames as $objectFieldName) {
								$objectFieldValue = dotval::read($objectFieldName);
								$objectFieldName = basename($objectFieldName,'.val');

								$xml_construct = $xml_construct . '<'.$objectFieldName.'>'.$objectFieldValue.'</'.$objectFieldName.'>';
							}
							$xml_construct = $xml_construct . "</response>";
							console_log_no_arrow($xml_construct);
						}
						if(dotval::read('data/_system.db/db_return.val')=='raw'){
							console_log('english shall be returned');
						}
					}else{
						console_log('db_return.val could not be located or "_system" database');
					}
				}
			}
		}
		if($param_2=='+i'){
			if(isset($param_3)){
				if(isset($param_4)){
					$objects = glob('data/'.$param_1.'.db/*.do');
					foreach($objects as $object){
						$IDToIndexAsValue = basename($object,'.do');
						$valueToIndex = dotval::read($object.'/'.$param_3.'.val');
						dotval::write('data/_indexes.db/'.$param_4.'.do/'.$valueToIndex.'.val',$IDToIndexAsValue);
						console_log('Indexed all objects in "'.$param_1.'" database into "'.$param_4.'" index');
					}
				}
			}
		}
		if($param_2=='update'){
			if(isset($param_3)){
				if(isset($param_4)){
					if(isset($param_5)){
						$oldValue = dotval::read('data/'.$param_1.'.db/'.$param_3.'.do/'.$param_4.'.val');
						dotval::write('data/'.$param_1.'.db/'.$param_3.'.do/'.$param_4.'.val',$param_5);
						console_log('Successfully wrote to '.$param_1.'.db/'.$param_3.'.do/'.$param_4.'.val');
						// indexing option
						if(isset($param_6)){
							if($param_6=='+i'){
								if(isset($param_7)){
									if(is_dir('data/_indexes.db/'.$param_7.'.do/')){
										dotval::write('data/_indexes.db/'.$param_7.'.do/'.strtolower($param_5).'.val',$param_3);
										if($oldValue!==strtolower($param_5)){
											if(file_exists('data/_indexes.db/'.$param_7.'.do/'.$oldValue.'.val')){
												dotval::destroy('data/_indexes.db/'.$param_7.'.do/'.$oldValue.'.val');
												console_log('Removed old '.$param_7.' previously indexed');
											}
										}
										console_log('"'.strtolower($param_5).'" was indexed under the '.$param_7.' object');
									}else{
										console_log('Failed: _indexes.db->'.$param_7.'.do is not a valid object.');
									}
								}
							}
						}else{
							// Check if auto-indexing is enabled, to index anyways
							if(file_exists('data/'.$param_1.'.db/'.$param_3.'.do/_index_val.val')){
								if(file_exists('data/'.$param_1.'.db/'.$param_3.'.do/_index_obj.val')){
									$fieldToIndex = dotval::read('data/'.$param_1.'.db/'.$param_3.'.do/_index_val.val');
									$valueOfField = dotval::read('data/'.$param_1.'.db/'.$param_3.'.do/'.$fieldToIndex.'.val');
									$indexObj = dotval::read('data/'.$param_1.'.db/'.$param_3.'.do/_index_obj.val');
									dotval::write('data/_indexes.db/'.$indexObj.'.do/'.$valueOfField.'.val',$param_3);
									console_log('Value of field "'.$fieldToIndex.'" was auto-indexed into the "'.$indexObj.'" index object');
								}
							}else{
								console_log('Auto-indexing not available for this field');
							}
						}
					}
				}
			}
		}
		if($param_2=='delete'){
			if(isset($param_3)){
				if(is_dir('data/'.$param_1.'.db/'.$param_3.'.do/')){
					//clean up indexes as well
					if(file_exists('data/'.$param_1.'.db/'.$param_3.'.do/_index_obj.val') && file_exists('data/'.$param_1.'.db/'.$param_3.'.do/_index_val.val')){
						$indexObject = dotval::read('data/'.$param_1.'.db/'.$param_3.'.do/_index_obj.val');
						$fieldName = dotval::read('data/'.$param_1.'.db/'.$param_3.'.do/_index_val.val');
						$fieldValue = dotval::read('data/'.$param_1.'.db/'.$param_3.'.do/'.$fieldName.'.val');
						dotval::destroy('data/_indexes.db/'.$indexObject.'.do/'.$fieldValue.'.val');
						console_log('Disabled indexing for this object and its fields.');
					}
					$valfiles = glob('data/'.$param_1.'.db/'.$param_3.'.do/*.val');
					foreach($valfiles as $vals){
						unlink($vals);
					}
					rmdir('data/'.$param_1.'.db/'.$param_3.'.do/');
					console_log('Successfully deleted object "'.$param_1.'" in database "'.$param_3.'"');
				}else{
					console_log('No such object or database');
				}
			}
		}
		if($param_2=='list'){
			$objects = glob('data/'.$param_1.'.db/*.do');
			if(dotval::read('data/_system.db/db_return.val')=='json'){
				// Return list of object ids of this object in json
				$json_construct = '{';
				foreach ($objects as $object) {
					$json_construct = $json_construct . '"id":"'.basename($object,'.do').'",';
				}
				$json_construct = substr($json_construct, 0, -1);
				$json_construct = $json_construct . "}";
				console_log($json_construct);
			}
			if(dotval::read('data/_system.db/db_return.val')=='xml'){
				// Return list of object ids of this object in xml
				$json_construct = "<?xml version='1.0' encoding='utf-8'?><response>";
				foreach ($objects as $object) {
					$json_construct = $json_construct . '<id>'.basename($object,'.do').'</id>';
				}
				$json_construct = $json_construct . "</response>";
				console_log($json_construct);
			}
			if(dotval::read('data/_system.db/db_return.val')=='raw'){
				// Return object id of this object in raw (commas)
				foreach ($objects as $object) {
					$raw_construct = $raw_construct . basename($object,'.do').',';
				}
				$raw_construct = substr($raw_construct, 0, -1);
				console_log($raw_construct);
			}
		}
		if($param_2=='find'){
			$objects = glob('data/'.$param_1.'.db/*.do');
			$resultsBoolean = 'false';
			foreach($objects as $object) {
				$object_id = basename($object,'.do');
				if($object_id==$param_3){
					// if the find term matches the id of an object
					console_log('"'.$object_id .'" matches the id of an object in this database');
					$resultsBoolean = 'true';
				}else{
					// search through values inside of each object
					$valuesInObject = glob('data/'.$param_1.'.db/'.$object_id.'.do/*.val');
					foreach($valuesInObject as $valueInObject){
						$fieldName = basename($valueInObject,'.val');
						$fieldValue = dotval::read($valueInObject);
						if(strpos(strtolower($fieldValue),strtolower($param_3)) !== false) {
							$resultsBoolean = 'true';
						    console_log($object_id.'->'.$fieldName.' contains "'.$fieldValue.'", which contains your term "'.$param_3.'"');
						}
					}
				}
			}
			if($resultsBoolean=='false'){
				console_log('No Results');
			}
		}
		if($param_2=='-a'){
			if($param_3=='start'){
				if(is_dir('data/'.$param_1.'.db/')){
					console_log('Could not create database titled "'.$param_1.'" because there is already a database with that title');
				}else{
					mkdir('data/'.$param_1.'.db/');
					console_log('New database created titled "'.$param_1.'"');
				}
			}
			if($param_3=='destroy'){
				if(is_dir('data/'.$param_1.'.db/')){
					$objects = glob('data/'.$param_1.'.db/*');
					foreach($objects as $object){
						$values = glob($object.'/*');
						foreach($values as $value){
							unlink($value);
						}
						rmdir($object);
					}
					rmdir('data/'.$param_1.'.db/');
					console_log('Successfully deleted database titled "'.$param_1.'"');
				}else{
					console_log('There is no database titled "'.$param_1.'"');
				}
			}
		}
	}
}
?>