<?php

include 'Classes/IrcClient.php';

$config = parse_ini_file('config.ini',true, INI_SCANNER_RAW);
$ircClient = new IrcClient($config);

print_r($config);
foreach($config['start'] as $command => $params) {
    echo $command." ".$params;
}

$ircClient->listen();
