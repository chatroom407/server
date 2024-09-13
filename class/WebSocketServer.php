<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require_once("EnlRsa.php");
require_once("ValidateMsg.php");

class WebSocketServer implements MessageComponentInterface {
    protected $clients;
    protected $connArr;

    public function __construct(){
        $this->clients = new \SplObjectStorage;
        $this->connArr = array();
    }

    public function getClientsIds() {
        $response = "";
        $clientsIds = array();
        $response = "<tb>";
        $response .= "<instance>clients</instance>";
        foreach ($this->clients as $client) {
            $response .= "<id>" . $client->clientId . "</id>";
        }
        $response .= "</tb>";
        return $response;
    }

    private function getConnById($clientId) {
        foreach ($this->connArr as $conn) {
            if($conn[1] == $clientId){
                return $conn[0];
            }
        }
        return null;
    }

    public function sendMessageToClient($clientId, $data){
        $conn = $this->getConnById($clientId);
        if ($conn !== null) {
            $conn->send($data);
        } else {
            echo "Conn not found: $clientId\n";
            //die();
        }
    }

    function search_file($directory, $filename) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === $filename) {
                return $file->getPathname();
            }
        }
        return false;
    }

    public function onOpen(ConnectionInterface $conn){ 
        //file_put_contents( __DIR__ ."/debug.log", "Hello");       
        
        $password = 'backend219';
        
        $uri = $conn->httpRequest->getUri();
        parse_str($uri->getQuery(), $params);
        $clientPassword = $params['room'];
        $session = $params['session'];
        $clientLogin = $params['login'];        

        $session = __DIR__ . "/../sessions/" . $session;        
        $aesKey = file_get_contents($session);

        if( !file_exists($session)){
            $path = $this->search_file( __DIR__ . "/../" , $session);
            die("Session File not exist !!!");
        }

        $enlAes = new EnlAes();
        try{
            $decryptedClientPassword = $enlAes->decryptAES($clientPassword, $aesKey);
            $decryptedClientLogin = $enlAes->decryptAES($clientLogin, $aesKey);
        }catch(Exception $e){
            echo $e;
            die();
        }

        if ($decryptedClientPassword !== $password) {
            $conn->close();
            unlink($session);
            return;
        }else{
            $session = __DIR__ . "/../sessions/" . $session;
            unlink($session);
        }

        if(empty($decryptedClientLogin)){
            $response = "<tb>";
            $response .= "<instance>you</instance>";
            $response .= "<id>Decrypt Err</id>";
            $response .= "</tb>";
            $conn->send($response);
            $conn->close();
            return;
        }
        echo "Decrypted result: " . $decryptedClientLogin . "</br>";

        foreach ($this->clients as $client) {
            if( strtolower((trim($client->clientId))) == strtolower(trim($decryptedClientLogin)) ){
                $response = "<tb>";
                $response .= "<instance>you</instance>";
                $response .= "<id>Client Exist</id>";
                $response .= "</tb>";
                $conn->send($response);
                $conn->close();
                return;
            }            
        }

        foreach($this->connArr as $connEl){
            if( strtolower((trim($connEl[1]))) == strtolower(trim($decryptedClientLogin)) ){
                $response = "<tb>";
                $response .= "<instance>you</instance>";
                $response .= "<id>ConnArr double exists</id>";
                $response .= "</tb>";
                $conn->send($response);
                $conn->close();
                return;
            }
        }

        if( empty($clientLogin) ){
            $clientId = uniqid('client_');
            $conn->clientId = $clientId;
        }else{
            $clientId = $decryptedClientLogin;
            $conn->clientId = $clientId;
        }

        echo "Client connect: " . $clientId . "</br>";

        $this->clients->attach($conn);

        array_push($this->connArr, array($conn, $clientId));

        $response = "<tb>";
        $response .= "<instance>you</instance>";
        $response .= "<id>" . $clientId . "</id>";
        $response .= "</tb>";
        $conn->send($response);
    }

    public function onClose(ConnectionInterface $conn) {
        if ($this->clients->contains($conn)) {
            $this->clients->detach($conn);
        }

        echo "Connection {$conn->resourceId} has disconnected.\n</br>";

        $i = 0;
        foreach($this->connArr as $c){
            if( $c[0] == $conn ){
                //Not optimal !!!
                foreach($this->connArr as $ca){
                    if($ca[1] == $this->connArr[$i][1]){
                        unset( $this->connArr[$i][0]);
                        unset( $this->connArr[$i][1]);
                        unset( $this->connArr[$i]);
                    }
                }

                unset( $this->connArr[$i][0] );
                unset( $this->connArr[$i][1] );
                unset( $this->connArr[$i]);                
                echo "Connection is unset</br>";                
            }
            $i++;
        }
        
        foreach($this->connArr as $c){
            echo "el: " . $c[1] . "</br>";
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    public function formatXmlData($xml) {
        $dom = new DOMDocument();
        $dom->formatOutput = true; 
        $dom->loadXML($xml);
        return $dom->saveXML();
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        

        //logi
        echo "<style>
            pre {
                background-color: #f4f4f4;
                border: 1px solid #ccc;
                padding: 10px;
                border-radius: 4px;
                white-space: pre-wrap;
                word-break: break-word; 
                width 100%:  
            }
            code {
                display: block;
                font-family: Consolas, 'Courier New', monospace;
                font-size: 14px;
                color: #333;
            }
        </style>";

        echo "<pre><code>";
        echo htmlspecialchars( $this->formatXmlData($msg) ); 
        echo "</code></pre>";

        $validateMsg = new ValidateMsg();
        $validator = new ValidateMsg();

        if ($validator->validateLength($msg, 1024)) {
            // Send back lenght err
        } else {
            
        }

        if ($validator->validateStartEnd($msg, '<tb>', '</tb>')) {
            // Send back struct not support
        } else {

        }
        //... more validate

        $this->simpleConn($from, $msg);
    }

    public function simpleConn($from, $msg){
        //Phrase message
        $parsed = array();
        $xml = simplexml_load_string($msg);
        foreach ($xml->children() as $element) {
            //echo $element->getName() . ": " . $element . "<br>";
            array_push($parsed, $element);
            foreach ($element->attributes() as $name => $value) {
                //echo "$name: $value<br>";
            }
        }

        $instance  = $parsed['0'];

        if(isset($parsed['1'])){
            $clientId  = $parsed['1'];
        }else{
            $clientId  = "";
        }
        
        if(isset($parsed['2'])){
            $parsedMsg = $parsed['2'];
        }else{
            $parsedMsg = "";
        }

        if(count($parsed) == 4){
            $myId = $parsed['3'];
        }

        switch ($instance) {
            case "pls_key":
                $pls  = "<tb>";
                $pls .= "<instance>pls</instance>";
                $pls .= "<id>". $clientId ."</id>";
                $pls .= "<msg>". "" ."</msg>";
                $pls .= "<mid>". $myId ."</mid>";
                $pls .= "</tb>";
                $this->sendMessageToClient($clientId, $pls);
                echo "<pre>SERVER:<code>";
                echo htmlspecialchars( $this->formatXmlData($pls) ); 
                echo "</code></pre>";
                break;

            case "key":
                try{
                    $this->sendMessageToClient($clientId, $msg);
                    #$this->sendMessageToClient($myId, $msg);
                }catch(Exception $e){
                    echo $e;
                }

                echo "<pre>SERVER:<code>";
                echo htmlspecialchars( $this->formatXmlData($msg) ); 
                echo "</code></pre>";
                break;

            case "clients":
                $clients = $this->getClientsIds();
                $from->send($clients);
                break;

            case "send":
                $msgXml = "<tb>";
                $msgXml .= "<instance>" . "msg" . "</instance>";
                $msgXml .= "<id>" . $myId . "</id>";
                $msgXml .= "<msg>" . $parsedMsg . "</msg>";
                $msgXml .= "</tb>";
                $this->sendMessageToClient($clientId, $msgXml);

                $response = "<tb>";
                $response .= "<instance>send</instance>";
                $response .= "</tb>";
                $from->send($response);
                break;

            default:
                $response = "<tb>";
                $response .= "<instance>default</instance>";
                $response .= "</tb>";
                $from->send($response);
                break;
        }

    }

    public function variantConn($from, $msg){

    }
}