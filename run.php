<?php

include 'Classes/IrcClient.php';
include 'Module/PingPong/PingPongHandler.php';
include 'Module/CLIInput/CLIInput.php';

$config = parse_ini_file('config.ini',true, INI_SCANNER_RAW);
$ircClient = new IrcClient($config);


$ircClient->addListener(PingPongHandler::getInstance(),'pongOnPing');
$ircClient->addListener(CLIInput::getInstance(),'run');
//exit;
$ircClient->listen();
