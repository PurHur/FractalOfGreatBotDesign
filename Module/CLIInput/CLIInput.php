<?php

class CLIInput {

    /**
     * @var PingPongHandler
     */
    protected static $instance = null;

    /**
     * @var Resource
     */
    protected static $stdinHandler = null;

    public static function getInstance() {
        if (self::$instance == null) self::$instance = new CLIInput();
        return self::$instance;
    }

    public function __construct() {
        $this->stdinHandler = fopen ("php://stdin","r");
        stream_set_blocking($this->stdinHandler, FALSE);
    }

    public function run($ircClient, $data) {
        $input = @fread($this->stdinHandler, 512);
        if ($input) {
            $ircClient->send($input);
        }
    }
}
