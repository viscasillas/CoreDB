<?php
//enable support for DOTVAL
require_once('bin/dotval/cla.php');
//grab client ip
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
// check if this ip is regstered
$clientIP = get_client_ip();
if(file_exists('data/_system.db/auth_keys.do/'.$clientIP.'.val')){
  $IPisRegistered = 'yes';
  $response =  '{"identity":"trusted"';
  $response = $response.',"operatingIP":"'.$clientIP .'"';
    // echo $response;
}else{
  $IPisRegistered = 'no';
  $response =  '{"identity":"not-trusted"';
    // echo $response;
}
// check if the key sent from the client's api matches the key registerd to that ip address
if($IPisRegistered=='yes'){
  if(isset($_GET['q'])){
    if($_GET['q']=='GetValue'){
      if(isset($_GET['database']) && isset($_GET['object']) && isset($_GET['field'])){
          $value = file_get_contents('data/'.$_GET['database'].'.db/'.$_GET['object'].'.do/'.$_GET['field'].'.val');
          $response = $response.',"query":"GetValue"';
          $response = $response.',"database":"'.$_GET['database'].'"';
          $response = $response.',"object":"'.$_GET['object'].'"';
          $response = $response.',"field":"'.$_GET['field'].'"';
          $response = $response.',"value":"'.$value.'"';
      }
    }
    if($_GET['q']=='PushValue'){
      if(isset($_GET['database']) && isset($_GET['object']) && isset($_GET['field']) && isset($_GET['newValue'])){
        dotval::write('data/'.$_GET['database'].'.db/'.$_GET['object'].'.do/'.$_GET['field'].'.val',$_GET['newValue']);
        $response = $response.',"query":"PushValue"';
        $response = $response.',"database":"'.$_GET['database'].'"';
        $response = $response.',"object":"'.$_GET['object'].'"';
        $response = $response.',"field":"'.$_GET['field'].'"';
        $response = $response.',"value":"'.$value.'"';
      }
    }
  }
}


$response = $response .'}';
echo $response;

?>
