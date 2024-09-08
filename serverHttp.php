<?php
include_once 'osdependent.php';
include_once 'logging.php';

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
ini_set('max_execution_time', 0);

require __DIR__ . '/vendor/autoload.php';
require_once(__DIR__ . "/class/WebSocketServer.php");

use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;

LogWrite ("Opened serverHttp");

echo 'Użytkownik wykonujący skrypt PHP: ' . osdep_GetUsername ();


$port = 8081;
$pidFile = __DIR__ . '/server.pid';

function isProcessRunning($pid) {
    return osdep_ProcessRunning ($pid, 0);
}

function startServer($port, $pidFile) {
    LogWrite ("Creating server object");
    
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new WebSocketServer()
            )
        ),
        $port
    );

    file_put_contents($pidFile, getmypid());

    LogWrite ("Running the server");
    echo "Server running on port $port\n";
    $server->run();
}

echo "AAAA";
if (file_exists($pidFile)) {
    /*
    if( function_exists('osdep_findProcess') ){
        $pid = osdep_findProcess("serverHttp.php");
        if ($pid) {
            echo "PID procesu serverHttp.php: $pid\n";
        } else {
            echo "Nie znaleziono procesu serverHttp.php\n";
        }
    }else{
        
    }*/
    
    $pid = (int)file_get_contents($pidFile);

    echo var_dump($pid);

    if($pid != 0){
        if (isProcessRunning($pid)) {
            LogWrite ("Killing old server process");
            echo "Stopping existing process with PID $pid...\n";
            osdep_KillProcess ($pid); 
            sleep(1); 
        }else{
            echo "Proccess not running<br>";
        }

        //unlink($pidFile); 
    }
}

startServer($port, $pidFile);
?>
