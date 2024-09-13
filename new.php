<pre>

    <?
$json_config = file_get_contents("../env.json");
$config= json_decode($json_config,true);

print_r($config)
?>
</pre>