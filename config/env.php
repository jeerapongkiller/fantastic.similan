<?php
// enable us to use Headers
ob_start();

// set sessions
if (!isset($_SESSION)) {
    session_start();
}

// set timezone
date_default_timezone_set("Asia/Bangkok");

// set value
$main_description = "tours management system by shambhala.travel";
$main_keywords = "tours management system";
$main_author = "Shambhala TMS";
$main_title = "Shambhala TMS";
$hostPageUrl = $_SERVER["HTTP_HOST"] == 'localhost' ? 'storage' : 'http://' . $_SERVER["HTTP_HOST"] . "/storage";
$main_document = "บริษัท SHAMBHALA TRAVEL จํากัด <br>
156/72 หมู่ที 5 ตําบลรัษฎา อําเภอเมือง จังหวัดภูเก็ต 83000 <br>
เลขทีผู้เสียภาษี 0-8355-64008-01-7 | (สํานักงานใหญ่) <br>
โทร: 081-691-6501 | info@shambhala.com <br>
www.shambhala.travel <br>";
$open_rates = 1;
