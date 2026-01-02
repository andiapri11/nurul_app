<?php
echo "<h3>PHP Environment Check</h3>";
echo "Current PHP Binary: " . PHP_BINARY . "<br>";
echo "PDO MySQL Extension: " . (extension_loaded('pdo_mysql') ? '<span style="color:green">Loaded (OK)</span>' : '<span style="color:red">NOT LOADED (ERROR)</span>') . "<br>";
echo "InI File: " . php_ini_loaded_file() . "<br>";
