<?php

try {
    $db = new mysqli('localhost', 'root', '', 'kgru');
} catch (mysqli_sql_exception $e) {
    die('Need mysql on localhost user:root, pass:, db:kgru');
}

if ($db->connect_error) {
    error_log('DB connection error: ' . $db->connect_error);
    die('DB connection error. See log for details.');
}
