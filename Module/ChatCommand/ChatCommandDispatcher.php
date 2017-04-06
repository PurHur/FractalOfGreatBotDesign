<?php

class ChatCommandDispatcher {

    /**
     * @var ChatCommandDispatcher
     */
    protected static $instance = null;

    /**
     * @var string
     */
    protected $commandChar = 'Heil';

    public static function getInstance() {
        if (self::$instance == null) self::$instance = new ChatCommandDispatcher();
        return self::$instance;
    }

    public function __construct() {
    }

    /**
     * @param $commandChar
     */
    public function setCommandChar($commandChar) {
        $this->commandChar = $commandChar;
    }

    /**
     * @return string
     */
    public function getCommandChar() {
        return $this->commandChar;
    }

    public function dispatch($ircClient, $data) {
        $tokens = explode(' ',$data);
        if (count($tokens) > 3) {
            if ($tokens[0][0] == ':') {
                if ($tokens[1] == 'PRIVMSG') {
                    if ($tokens[3][0] == ':') {
                        if (strpos($tokens[3],$this->commandChar) === 1) {
                            $ircClient->send('PRIVMSG '.$tokens[2].' PHP');
                        }
                    }
                }
                if ($tokens[1] == 'INVITE') {
                    if ($tokens[3][0] == ':') {
                        $ircClient->send('JOIN '.substr($tokens[3],1,strlen($tokens[3])-1));
                    }
                }
            }
        }
    }
}
