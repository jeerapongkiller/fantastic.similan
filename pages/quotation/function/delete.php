<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Quotation.php';

$quotObj = new Quotation();

if (isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['quotation_id']) && $_POST['quotation_id'] > 0) {
    // get value from ajax
    $quotation_id = $_POST['quotation_id'];
        
    $response = $quotObj->delete_data($quotation_id);

    echo $response;
} else {
    echo $response = false;
}
