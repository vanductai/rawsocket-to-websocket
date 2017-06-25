#!/usr/bin/env php
<?php
date_default_timezone_set("Asia/Bangkok");
error_reporting(0);
include 'libs/client.websocket.php';

const message_delimiter = "\r\n";

/* Allow the script to hang around waiting for connections. */
set_time_limit(0);

/* Turn on implicit output flushing so we see what we're getting
 * as it comes in. */
ob_implicit_flush();

$address = '0.0.0.0';
$port = 9000;

if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n"; //debug
}

if (!socket_set_nonblock($sock)) {
	die('Unable to set nonblocking mode for socket'); //debug
	socket_close($socket);
}

if (!socket_set_option($sock,SOL_SOCKET, SO_REUSEADDR, 1)) {
	die('Unable to set REUSEADDR OPTION.'); //debug
	socket_close($socket);
}

if (socket_bind($sock, $address, $port) === false) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n"; //debug
    socket_close($socket);
}

if (socket_listen($sock, 5) === false) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n"; //debug
    socket_close($socket);
}

//clients array
$clients = array();

while (isset($sock)) {
    $read = array();
    $read[] = $sock;
    //echo "In while loop!".PHP_EOL;
    $read = array_merge($read,$clients);
    
    // Set up a blocking call to socket_select
    if(socket_select($read,$write = NULL, $except = NULL, 10) < 1)
    {
        //    SocketServer::debug("Problem blocking socket_select?");
        continue;
    }
    
    // Handle new Connections
    if (in_array($sock, $read)) {        
        
        if (($msgsock = socket_accept($sock)) === false) {
            echo "Socket unable to accept, reason: " . socket_strerror(socket_last_error($sock)) . "\n";
            break;
        } else echo $_SERVER['REMOTE_ADDR'] . " (". $msgsock .") has just connected." . PHP_EOL; //debug
        $clients[] = $msgsock;
        $key = array_keys($clients, $msgsock);
        echo count($clients) . " connected to server" . PHP_EOL; //debug
    }
    
    // Handle Input
    foreach ($clients as $key => $client) { // for each client        
        if (in_array($client, $read)) {
            if (false === ($buf = socket_read($client, 4096, PHP_BINARY_READ))) {
                echo "($key) disconnected: reason: " . socket_strerror(socket_last_error($client)) . "\n"; //debug
                socket_close($client); 
				continue;
            }
            if ($buf !== trim($buf)) {
            	echo "($key) has been disconnected.\n"; //debug
            	socket_close($client);
                continue;
            }
            if ($buf == 'quit') {
                socket_close($client);
                break;
            }
            if ($buf == 'shutdown') {
                socket_close($client);
                break 2;
            }
            if (false === strpos($buf, "###")) {
            	echo "($key) has been kicked, reason: invalid client.\n"; //debug
                socket_close($client); 
				continue;
            }
	    	$ws = new ws(array
    			(
        		'host' => '127.0.0.1',
	        	'port' => 9001,
   		     	'path' => ''
    			));
    		ob_flush();
    		$messages = explode('!!!', $buf);
		    foreach ($messages as $key => $mes) {
		        $newmess = trim($mes);
		        if (!empty($newmess)) {
		            echo date('y-n-j G:i:s') . "-RSS: ".$result. PHP_EOL; //debug
		            $result = $ws->send($newmess);
		        }
		    }
		    $ws->close();
        }
        
    }        
} 
echo "RS server stopped!".PHP_EOL; // debug

socket_close($sock);
?>
