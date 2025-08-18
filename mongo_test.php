<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    echo "✅ Connection to MongoDB successful!";
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "❌ MongoDB connection failed: ", $e->getMessage();
}
?>
