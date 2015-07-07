<?


// API EXAMPLE FOR XML
$xml = simplexml_load_string(exec('./coredb users read 575829479 email'));
echo $xml;


// API EXAMPLE FOR JSON
$json = json_decode(exec('./coredb users read 575829479'));
print $json->{'name'}; // 12345
?>