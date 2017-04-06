<?php


class IrcClient {

    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var resource
     */
    protected $socket = null;

    /**
     * @var int
     */
    protected $maxMessageLength = 512;


    /**
     * IrcClient constructor.
     * @param $config
     */
    public function __construct($config) {
        $this->config = $config;
        $this->connect();
    }

    protected function connect() {
        $this->socket = fsockopen($this->config['server']['host'], $this->config['server']['port']);
        if (!$this->socket) {
            throw new Exception('No connection');
        }

        $this->login($this->config['auth']);

    }

    private function printLine($data) {
        echo $data;
        flush();
    }

    /**
     * @param $authData
     */
    public function login($authData) {
        $this->send('USER '.$authData['nick'].' purh.pw '.' '.$authData['nick'].' :'.$authData['name']);
        $this->send('NICK '.$authData['nick']);
    }

    public function listen() {
        $backgroundFunction = new Background(function() {
            $error = null;
            while(!$error) {
                $data = fgets($this->socket, $this->maxMessageLength);
                if ($data) {
                    $this->printLine($data);

                    $data = explode(' ',$data);
                    if ($data[0] == 'PING') {
                        $this->send('PONG '.$data['1']);
                    }
                }
            }

            throw new Exception($error);
        });
    }

    public function send($data) {
        //$chunks = chunk_split
        //if (strlen($data) < 5210)

        $this->printLine($data."\r\n");
        fputs($this->socket, $data."\r\n");
    }

}

class Background extends Thread {

    public function __construct(callable $call, array $args = []) {
        $this->call = $call;
        $this->args = $args;
    }

    public function run() {
        call_user_func_array($this->call, $this->args);
    }

    protected $call;
    protected $args;
}