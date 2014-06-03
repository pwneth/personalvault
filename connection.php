<?php

try {
$pdo = new PDO('mysql:host=[**hostname_here**];dbname=[**database_name_here**];charset=utf8', '[database_username]', '[database_password]'', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
} catch (PDOException $e) {
	exit('Database error.');
}
?>
