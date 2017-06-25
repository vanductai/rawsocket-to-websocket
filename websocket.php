<?php
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");

require 'libs/class.websocket.php';
require 'libs/class.DBPDO.php';

function wsOnMessage($clientID, $message, $messageLength, $binary) {
    global $Server;
    global $DB;
    $ip = long2ip( $Server->wsClients[$clientID][6] );
    if ($messageLength == 0) {
        $Server->wsClose($clientID);
        return;
    }
	foreach ( $Server->wsClients as $id => $client ) {
        if (strpos($message, '###') !== false) {
            $Server->wsSend($id, $message);
            echo date('y-n-j G:i:s') . "-WSS: (" . $id .") " . $message . PHP_EOL; //debug
        }
	}
}

function wsOnOpen($clientID)
{
	global $Server;
	//$ip = long2ip( $Server->wsClients[$clientID][6] ); //debug
	//$Server->log( "$ip ($clientID) has connected." ); //debug

}

function wsOnClose($clientID, $status) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	echo date('y-n-j G:i:s') . "-WSS: $ip ($clientID) has disconnected." . PHP_EOL . PHP_EOL; //debug

}

$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');
$Server->wsStartServer('0.0.0.0', 9001);
?>
