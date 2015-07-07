<?php
class switcher{
	function off(){
		if(file_exists('data/_system.db/status.val')){
			if(dotval::read('data/_system.db/status.val')=='disabled'){
				console_log('API over HTTP is already disabled');
			}
			if(dotval::read('data/_system.db/status.val')=='enabled'){
				dotval::write('data/_system.db/status.val','disabled');
				console_log('API over HTTP has been disabled');
			}
		}
	}
	function on(){
		if(file_exists('data/_system.db/status.val')){
			if(dotval::read('data/_system.db/status.val')=='enabled'){
				console_log('API over HTTP is already enabled');
			}
			if(dotval::read('data/_system.db/status.val')=='disabled'){
				dotval::write('data/_system.db/status.val','enabled');
				console_log('API over HTTP has been enabled');
			}
		}
	}
}

?>