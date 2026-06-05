<?php
try {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    echo "Redis connected successfully.\n";
} catch (Exception $e) {
    echo "Redis failed: " . $e->getMessage() . "\n";
}
