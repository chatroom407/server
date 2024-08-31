<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
ini_set('max_execution_time', 0);

require __DIR__ . '/vendor/autoload.php';
require_once(__DIR__ . "/class/WebSocketServer.php");

use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;

echo 'Użytkownik wykonujący skrypt PHP: ' . exec('whoami');


$port = 8081;
$pidFile = __DIR__ . '/server.pid';

function isProcessRunning($pid) {
    return posix_kill($pid, 0);
}

function startServer($port, $pidFile) {
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new WebSocketServer()
            )
        ),
        $port
    );

    file_put_contents($pidFile, getmypid());

    echo "Server running on port $port\n";
    $server->run();
}

if (file_exists($pidFile)) {
    $pid = (int)file_get_contents($pidFile);

    if($pid != 0){
        if (isProcessRunning($pid)) {
            echo "Stopping existing process with PID $pid...\n";
            posix_kill($pid, 15); 
            sleep(1); 
        }

        unlink($pidFile); 
    }
}

startServer($port, $pidFile);
?>