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
$main_author = "Fantastic Similan Travel";
$main_title = "Fantastic Similan Travel";
$hostPageUrl = $_SERVER["HTTP_HOST"] == 'localhost' ? 'storage' : 'http://' . $_SERVER["HTTP_HOST"] . "/storage";
$main_document = "Fantastic Similan Travel <br>
26/74 M.7 T.KHUK-KHAK A.TAKUAPA PHANG-NGA 82220 THAILAN <br>
TEL: 062 332 2800 | 084 744 3000 | 083 175 7444 <br>
Email: Fantasticsimilantravel11@gmail.com <br>";
$open_rates = 1;
