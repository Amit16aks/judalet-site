<?php
require 'vendor/autoload.php';
use Square\SquareClient;
use Square\Environments;
use Square\Exceptions\SquareException;
use Dotenv\Dotenv;

try {
    // Load environment variables
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // Initialize Square client
    $square = new SquareClient(
        token: $_ENV['SQUARE_ACCESS_TOKEN'],
        options: ['baseUrl' => Environments::Sandbox->value]
    );

    // List locations
    $response = $square->locations->list();
    $locations = $response->getLocations();
    if (!empty($locations)) {
        foreach ($locations as $location) {
            echo "Location ID: {$location->getId()}, Name: {$location->getName()}, Address: {$location->getAddress()?->getAddressLine1()}, {$location->getAddress()?->getLocality()}\n";
        }
    } else {
        echo "No locations found.\n";
    }
} catch (SquareException $e) {
    echo "Error: {$e->getMessage()} (Code: {$e->getCode()})\n";
}
?>