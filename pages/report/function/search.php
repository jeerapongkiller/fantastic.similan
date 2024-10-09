<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Report.php';

$repObj = new Report();
$today = date("Y-m-d");
$times = date("H:i:s");

if (isset($_POST['action']) && $_POST['action'] == "search") {
    
}
