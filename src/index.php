<?php
set_time_limit(0);
require __DIR__ . '/../vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Dotenv\Dotenv;

class PrintServer implements MessageComponentInterface
{
    public function onOpen(ConnectionInterface $conn)
    {
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->safeLoad();

        try {
            $connector = new WindowsPrintConnector($_ENV['PRINTER_NAME']);
            $printer = new Printer($connector);

            $data = json_decode($msg, true);
            if (json_last_error() !== JSON_ERROR_NONE)
                throw new Exception("Invalid JSON or missing 'type' field");

            $printer->text($msg);

            $printer->feed(3);
            $printer->cut();
            $printer->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$port = isset($_ENV['PORT']) ? intval($_ENV['PORT']) : 8080;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new PrintServer()
        )
    ),
    $port
);

echo "Print server running on ws://localhost:" . $port . "\n";
$server->run();
