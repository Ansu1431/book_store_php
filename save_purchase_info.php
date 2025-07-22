<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);
if ($data) {
    $_SESSION['last_purchase_info'] = $data;
}
?> 