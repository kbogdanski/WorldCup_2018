<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2018-06-12
 * Time: 21:35
 */

session_start();
require_once __DIR__.'/User.php';

$db_host = 'localhost';
$db_user = 'root';
$db_password = 'coderslab';
$db_name = 'WorldCup2018';

$conn = new mysqli($db_host,$db_user,$db_password,$db_name);

if ($conn->error != 0) {
    die ('Blad polaczenia do bazy danych: {$conn->error}');
}
?>