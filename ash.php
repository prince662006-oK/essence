<?php 

$mot = 12345678;

$pass = password_hash($mot , PASSWORD_DEFAULT);

echo $pass ;




?>