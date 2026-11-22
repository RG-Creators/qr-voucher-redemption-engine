<?php
// The password you want to hash
$password = "noirLogin@tanush_123";

// Generate password hash using the default algorithm (currently BCRYPT)
$hash = password_hash($password, PASSWORD_DEFAULT);

// Output the hash
echo "Generated Hash: " . $hash;
?>