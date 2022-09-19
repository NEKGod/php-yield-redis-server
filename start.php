<?php
require_once "vendor/autoload.php";

use core\command\CommandException;
use core\coroutines\CoSocket;
use core\coroutines\Scheduler;
use core\command\CommandFactory;


function server($port): Generator
{
    echo "Starting server at port $port...\n";
    $socket = @stream_socket_server("tcp://0.0.0.0:$port", $errNo, $errStr);
    if (!$socket) throw new Exception($errStr, $errNo);
    stream_set_blocking($socket, 0);
    $socket = new CoSocket($socket);
    while (true) {
        yield newTask(
            handleClient(yield $socket->accept())
        );
    }
}

/**
 * @param CoSocket $socket
 * @return \Generator
 */
function handleClient(CoSocket $socket): Generator
{
    while (true) {
        $buf = (yield $socket->read(8192));
        $buf = trim($buf);
        try{
            $command = CommandFactory::execCommand($buf);
        }catch (CommandException $e){
            yield $socket->writePut($e->getMessage());
            continue;
        }
        if (!$buf || $buf == 'exit') {
            yield $socket->close();
            break;
        }
        yield $socket->writePut($command);
    }
}

$scheduler = new Scheduler;
$scheduler->newTask(server(8000));
/** @noinspection PhpUnreachableStatementInspection */
$scheduler->run();
