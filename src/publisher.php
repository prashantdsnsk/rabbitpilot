<?php
//error_reporting(E_ALL);

require_once('../vendor/autoload.php');
//require dirname(__DIR__).'/vendor/autoload.php';
//require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


$exchange = 'pdstest';
$queue = 'test';


$host = "35.154.23.54";
$port = "9090";
$user = "guest";
$pass = "guest";
$vhost = "guest";
//echo "<br> after connection";

/* 
$host = "dinosaur.rmq.cloudamqp.com";
$port = 5672;
$user = "gdeoaaqt";
$pass = "XOSLMkQZQ7glbZONA8R1UNf5B9U0zfE-";
$vhost = "gdeoaaqt";
 */

$connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);

$channel = $connection->channel();

$channel->queue_declare($queue, false, true, false, false);

$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_bind($queue, $exchange);
//$messageBody = implode(' ', array_slice($argv, 1));

$messageBody = json_encode([
		'email'=>'prashant.sonawane@esds.com',
		'subscribed' =>true,
	]);

$messageBodyText = "Hi From Prashant Sonawane";

$message = new AMQPMessage($messageBody, ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
$channel->basic_publish($message, $exchange);
$channel->close();
$connection->close();

?>