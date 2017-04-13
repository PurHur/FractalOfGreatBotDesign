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
     * @var boolean
     */
    protected $ircState = null;

    /**
     * @var int
     */
    protected $maxMessageLength = 512;

    /**
     * @var array
     */
    protected $messageListener = array();


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
        stream_set_blocking($this->socket, FALSE);

        if (!$this->socket) {
            throw new Exception('No connection');
        }

        $this->login($this->config['auth']);

    }

    private function printLine($data) {
        echo $data;
        flush();
    }

    public function getMaxMessageLength() {
      return $this->maxMessageLength;
    }

    /**
     * @param $authData
     */
    public function login($authData) {
        $this->send('USER '.$authData['nick'].' $'.$authData['host'].' '.' '.$authData['nick'].' :'.$authData['name']);
        $this->changeNick($authData['nick']);
    }

    public function changeNick($nick) {
      $this->send('NICK '.$nick);
    }

    public function listen() {
        $error = null;
        while(!$error) {
            $data = fgets($this->socket, $this->maxMessageLength);
            if ($data) {
                $this->printLine($data);
            }
            foreach($this->messageListener as $listener) {
                $result = $listener['instance']->$listener['callable']($this, $data);
                if (is_string($result)) $this->printLine($result);
            }
        }

        throw new Exception($error);
    }

    public function addListener($instance, $callable) {
        $this->messageListener[] = array(
            'instance' => $instance,
            'callable' => $callable,
        );
    }

    public function send($data) {
        $this->printLine($data."\r\n");
        fputs($this->socket, $data."\r\n");
    }

}
