<?php

namespace core;

use core\cli\Tty;

class Cli
{
    private $serverFd = null;
    private $bindAddress;
    private $minCursorOffset = 0;
    public function start($bindAddress = "")
    {
        $this->bindAddress = $bindAddress;
        $this->connectServer();
        while (true) {
            $input = Tty::getSingle()->getUserCommand();
            if (!$input) {
                continue;
            }
            fputs($this->getServerFd(), $input);
            $res = fread($this->getServerFd(), 2048);
            var_dump($res);
        }
    }

    public function connectServer()
    {
        $fp = stream_socket_client($this->bindAddress, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)\n";
            die;
        }
        $this->setServerFd($fp);
    }

    /**
     * @return resource|null
     */
    public function getServerFd()
    {
        return $this->serverFd;
    }

    /**
     * @param resource $serverFd
     */
    public function setServerFd($serverFd): void
    {
        $this->serverFd = $serverFd;
    }
}