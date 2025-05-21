<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Printer;

$connector = new WindowsPrintConnector($_ENV['PRINTER_NAME']);

$printer = new Printer($connector);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text($_ENV['STORE_NAME'] . "\n");
$printer->feed(3);

// this is a test script
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("Date: " . date('Y-m-d H:i:s') . "\n");
$printer->text("--------------------------------\n");
for ($i = 1; $i <= 20; $i++) {
    $printer->text(sprintf("Item %02d    x%2d    $%5.2f\n", $i, rand(1,5), rand(100,999)/10));
}
$printer->text("--------------------------------\n");
$printer->setEmphasis(true);
$printer->text("TOTAL:           $123.45\n");
$printer->setEmphasis(false);
$printer->feed(2);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("Thank you for shopping!\n");
// this is a test script

$printer->feed(3);
$printer->cut();

$printer->close();
