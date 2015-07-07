<?php
class db{
	function id_specify($database,$id){
		if(is_dir('data/'.$database.'.db/'.$id.'.do/')){
			console_log('The object name you defined already exists');
		}else{
			mkdir('data/'.$database.'.db/'.$id.'.do');
		}
		return $id;
	}
	function id_generator($database){
		$value = rand(1,999999999);
		if(is_dir('data/'.$database.'.db/'.$value.'.do/')){
			$value = rand(1,999999999);
			if(is_dir('data/'.$database.'.db/'.$value.'.do/')){
				$value = rand(1,999999999);
				if(is_dir('data/'.$database.'.db/'.$value.'.do/')){
					$value = rand(1,999999999);
					if(is_dir('data/'.$database.'.db/'.$value.'.do/')){
						$value = rand(1,999999999);
						if(is_dir('data/'.$database.'.db/'.$value.'.do/')){
							$return = 'false';
						}else{
							mkdir('data/'.$database.'.db/'.$value.'.do');
							chmod('data/'.$database.'.db/'.$value.'.do/', 0777);
							$return = $value;
						}
					}else{
						mkdir('data/'.$database.'.db/'.$value.'.do');
						chmod('data/'.$database.'.db/'.$value.'.do/', 0777);
						$return = $value;
					}
				}else{
					mkdir('data/'.$database.'.db/'.$value.'.do');
					chmod('data/'.$database.'.db/'.$value.'.do/', 0777);
					$return = $value;
				}
			}else{
				mkdir('data/'.$database.'.db/'.$value.'.do');
				chmod('data/'.$database.'.db/'.$value.'.do/', 0777);
				$return = $value;
			}
		}else{
			mkdir('data/'.$database.'.db/'.$value.'.do');
			chmod('data/'.$database.'.db/'.$value.'.do/', 0777);
			$return = $value;
		}
		return $return;
	}
}
?>
