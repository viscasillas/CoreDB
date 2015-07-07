<?php
class dotval{
	function read($location){
		$value = file_get_contents($location);
		return $value;
	}
	function write($location,$data){
		file_put_contents($location, $data);
	}
	function destroy($file){
		unlink($file);
	}
}
?>