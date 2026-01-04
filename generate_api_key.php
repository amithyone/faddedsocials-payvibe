<?php
// Simple API Key Generator
// Just upload this file to your server and open it in a browser

$apiKey = bin2hex(random_bytes(32));

echo "<h2>Your API Key:</h2>";
echo "<h1 style='color: green; font-family: monospace; padding: 20px; background: #f0f0f0;'>" . $apiKey . "</h1>";
echo "<p><strong>Copy this key and add it to your .env file:</strong></p>";
echo "<p style='font-family: monospace; background: #f0f0f0; padding: 10px;'>SEO_API_KEY=" . $apiKey . "</p>";
echo "<hr>";
echo "<p><small>After adding to .env, delete this file for security!</small></p>";

