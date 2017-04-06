<?php

include 'Classes/IrcClient.php';
include 'Module/PingPong/PingPongHandler.php';
include 'Module/CLIInput/CLIInput.php';
include 'Module/ChatCommand/ChatCommandDispatcher.php';

$config = parse_ini_file('config.ini',true, INI_SCANNER_RAW);
$ircClient = new IrcClient($config);


$ircClient->addListener(PingPongHandler::getInstance(),'pongOnPing');
$ircClient->addListener(ChatCommandDispatcher::getInstance(),'dispatch');
$ircClient->addListener(CLIInput::getInstance(),'run');
//exit;
$ircClient->listen();
