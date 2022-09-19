<?php

namespace core;

class Cli
{
    private $serverFd = null;
    private $bindAddress;

    public function start($bindAddress = "")
    {
        $this->bindAddress = $bindAddress;
        $this->connectServer();
        system("reset");
        while (true) {
            $input = $this->inputCommand();
            if (!$input) {
                continue;
            }
            fputs($this->getServerFd(), $input);
            $res = fread($this->getServerFd(), 2048);
            var_dump($res);
        }
    }

    public function inputCommand()
    {
        printf("command > ");
        while (1) {
            echo fread(STDIN, 1024).PHP_EOL;
            sleep(1);
        }
        $command = trim(fgets(STDIN));
        if ($command == 'cls') {
            system("reset");
            return;
        }else if ($command == 'exit'){
            exit();
        }else if ($command == 'start'){

        }
        return $command;
    }

    public function connectServer()
    {
        $fp = stream_socket_client($this->bindAddress, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
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