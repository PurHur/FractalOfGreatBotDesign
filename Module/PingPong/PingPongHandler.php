<?php

class PingPongHandler {

    /**
     * @var PingPongHandler
     */
    protected static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) self::$instance = new PingPongHandler();
        return self::$instance;
    }

    public function pongOnPing($ircClient, $data) {
        $data = explode(' ',$data);
        if ($data[0] == 'PING') {
            $ircClient->send('PONG '.$data['1']);
        }
    }
}
