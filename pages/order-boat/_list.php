<?php
require_once 'controllers/Order.php';

$manageObj = new Order();
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
// $today = '2024-09-29';
// $tomorrow = '2024-09-30';
$get_date = !empty($_GET['date_travel_booking']) ? $_GET['date_travel_booking'] : $tomorrow; // $tomorrow->format("Y-m-d")
$search_boat = !empty($_GET['search_boat']) ? $_GET['search_boat'] : 'all';
$search_status = $_GET['search_status'] != "" ? $_GET['search_status'] : 'all';
$search_agent = $_GET['search_agent'] != "" ? $_GET['search_agent'] : 'all';
$search_product = $_GET['search_product'] != "" ? $_GET['search_product'] : 'all';
$search_voucher_no = $_GET['voucher_no'] != "" ? $_GET['voucher_no'] : '';
$refcode = $_GET['refcode'] != "" ? $_GET['refcode'] : '';
$name = $_GET['name'] != "" ? $_GET['name'] : '';
$manage = !empty($_GET['manage_id']) ? $_GET['manage_id'] : 0;

$href = "./?pages=order-boat/print";
$href .= "&date_travel=" . $get_date;
$href .= "&search_boat=" . $search_boat;
$href .= "&search_status=" . $search_status;
$href .= "&search_agent=" . $search_agent;
$href .= "&search_product=" . $search_product;
$href .= "&search_voucher_no=" . $search_voucher_no;
$href .= "&refcode=" . $refcode;
$href .= "&name=" . $name;
$href .= "&manage_id=" . $manage;
$href .= "&action=print";

// $all_manages = $manageObj->fetch_all_manageboat($get_date, $search_boat, 'all', 0);

$bo_arr = array();
$bomange_arr = array();
$categorys_array = array();
$cars_arr = array();
$extra_arr = array();
$bpr_arr = array();
$manages_arr = array();
$all_bookings = $manageObj->fetch_all_bookingboat('all', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $hotel = '', $search_boat, $search_guide = 'all', $manage);
foreach ($all_bookings as $categorys) {
    if (in_array($categorys['manage_id'], $manages_arr) == false && !empty($categorys['manage_id'])) {
        $manages_arr[] = $categorys['manage_id'];
        $manage_id[] = $categorys['manage_id'];
        $manage_time[] = $categorys['manage_time'];
        $boat_id[] = $categorys['boat_id'];
        $boat_name[] = $categorys['boat_name'];
        $guide_id[] = $categorys['guide_id'];
        $guide_name[] = $categorys['guide_name'];
        $counter[] = $categorys['manage_counter'];
        $color_id[] = $categorys['color_id'];
        $color_hex[] = $categorys['color_hex'];
        $text_color[] = $categorys['text_color'];
        $color_name_th[] = $categorys['color_name_th'];
        $manage_note[] = $categorys['manage_note'];
    }

    if (in_array($categorys['bpr_id'], $bpr_arr) == false) {
        $bpr_arr[] = $categorys['bpr_id'];
        $categorys_array[] = $categorys['id'];
        $category_name[$categorys['id']][] = $categorys['category_name'];
        $adult[$categorys['id']][] = $categorys['adult'];
        $child[$categorys['id']][] = $categorys['child'];
        $infant[$categorys['id']][] = $categorys['infant'];
        $foc[$categorys['id']][] = $categorys['foc'];
        $tourist_array[$categorys['id']][] = $categorys['adult'] + $categorys['child'] + $categorys['infant'] + $categorys['foc'];
    }

    if (in_array($categorys['bomange_id'], $bomange_arr) == false) {
        $bomange_arr[] = $categorys['bomange_id'];
        $booking_id[$categorys['manage_id']][] = $categorys['id'];
    }

    if (in_array($categorys['id'], $bo_arr) == false) {
        $bo_arr[] = $categorys['id'];
        $bo_id[] = $categorys['id'];
        $hotelp_name[$categorys['id']] = $categorys['hotelp_name'];
        $outside_pickup[$categorys['id']] = $categorys['outside_pickup'];
        $zonep_name[$categorys['id']] = $categorys['zonep_name'];
        $hoteld_name[$categorys['id']] = $categorys['hoteld_name'];
        $zoned_name[$categorys['id']] = $categorys['zoned_name'];
        $outside_dropoff[$categorys['id']] = $categorys['outside_dropoff'];
        $start_pickup[$categorys['id']] = $categorys['start_pickup'];
        $end_pickup[$categorys['id']] = $categorys['end_pickup'];
        $product_name[$categorys['id']] = $categorys['product_name'];
        $telephone[$categorys['id']] = $categorys['telephone'];
        $cus_name[$categorys['id']] = $categorys['cus_name'];
        $voucher_no_agent[$categorys['id']] = $categorys['voucher_no_agent'];
        $book_full[$categorys['id']] = $categorys['book_full'];
        $room_no[$categorys['id']] = $categorys['room_no'];
        $bp_note[$categorys['id']] = $categorys['bp_note'];
        $agent_name[$categorys['id']] = $categorys['agent_name'];
        $status_name[$categorys['id']] = $categorys['status_name'];
        $booksta_class[$categorys['id']] = $categorys['booksta_class'];
        $sender[$categorys['id']] = $categorys['sender'];
        $cot[$categorys['id']] = $categorys['cot'];
    }

    if (in_array($categorys['bot_id'], $cars_arr) == false) {
        $cars_arr[] = $categorys['bot_id'];
        $car_name[$categorys['id']][] = $categorys['car_name'];
    }

    if (in_array($categorys['bec_id'], $extra_arr) == false) {
        $extra_arr[] = $categorys['bec_id'];
        $extra_name[$categorys['id']][] = $categorys['extra_name'];
    }
}

?>

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Manage Boat</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                <div class="form-group breadcrumb-right">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="today-tab" data-toggle="tab" href="#today" aria-controls="today" role="tab" aria-selected="true">Today</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tomorrow-tab" data-toggle="tab" href="#tomorrow" aria-controls="tomorrow" role="tab" aria-selected="false">Tomorrow</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="customh-tab" data-toggle="tab" href="#custom" aria-controls="custom" role="tab" aria-selected="true">Custom</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="today" aria-labelledby="today-tab" role="tabpanel">

                            </div>
                            <div class="tab-pane" id="tomorrow" aria-labelledby="tomorrow-tab" role="tabpanel">

                            </div>
                            <div class="tab-pane" id="custom" aria-labelledby="custom-tab" role="tabpanel">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="sortable-lists">
                <!-- bookings filter start -->
                <div class="card">
                    <h5 class="card-header">Search Filter</h5>
                    <form id="booking-search-form" name="booking-search-form" method="get" enctype="multipart/form-data">
                        <input type="hidden" name="pages" value="<?php echo $_GET['pages']; ?>">
                        <div class="d-flex align-items-center mx-50 row pt-0 pb-0">
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="search_status">Status</label>
                                    <select class="form-control select2" id="search_status" name="search_status">
                                        <option value="all">All</option>
                                        <?php
                                        $bookstype = $manageObj->showliststatus();
                                        foreach ($bookstype as $booktype) {
                                            $selected = $search_status == $booktype['id'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $booktype['id']; ?>" <?php echo $selected; ?>><?php echo $booktype['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_agent">Agent</label>
                                    <select class="form-control select2" id="search_agent" name="search_agent">
                                        <option value="all">All</option>
                                        <?php
                                        $agents = $manageObj->showlistagent();
                                        foreach ($agents as $agent) {
                                            $selected = $search_agent == $agent['id'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $agent['id']; ?>" <?php echo $selected; ?>><?php echo $agent['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_product">Programe</label>
                                    <select class="form-control select2" id="search_product" name="search_product">
                                        <option value="all">All</option>
                                        <?php
                                        $products = $manageObj->showlistproduct();
                                        foreach ($products as $product) {
                                            $selected = $search_product == $product['id'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $product['id']; ?>" <?php echo $selected; ?>><?php echo $product['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_boat">เรือ</label>
                                    <select class="form-control select2" id="search_boat" name="search_boat">
                                        <option value="all">All</option>
                                        <?php
                                        $boats = $manageObj->show_boats();
                                        foreach ($boats as $boat) {
                                            $selected = $search_boat == $boat['id'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $boat['id']; ?>" <?php echo $selected; ?>><?php echo $boat['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="date_travel_booking">วันที่เที่ยว (Travel Date)</label></br>
                                    <input type="text" class="form-control date-picker" id="date_travel_booking" name="date_travel_booking" value="<?php echo $get_date; ?>" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="refcode">Booking No #</label>
                                    <input type="text" class="form-control" id="refcode" name="refcode" value="<?php echo $refcode; ?>" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="voucher_no">Voucher No #</label>
                                    <input type="text" class="form-control" id="voucher_no" name="voucher_no" value="<?php echo $search_voucher_no; ?>" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="name">Customer Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-body pt-0 p-50">
                        <a href='<?php echo $href; ?>' target="_blank"><button class="btn btn-info" id="print-btn">Print</button></a>
                        <button type="button" class="btn btn-info waves-effect waves-float waves-light btn-page-block-spinner" onclick="download_image();">Image</button>
                    </div>
                    <div id="div-boat-job-image" style="background-color: #FFF;">
                        <!-- Header starts -->
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing">
                                <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
                                <span style="color: #000;">
                                    โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                                    Email : Fantasticsimilantravel11@gmail.com
                                </span>
                            </div>
                            <div class="text-center card-text">
                                <h4 class="font-weight-bolder">ใบจัดเรือ</h4>
                                <div class="badge badge-pill badge-light-danger">
                                    <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($get_date)); ?></h5>
                                </div>
                            </div>
                        </div>
                        <!-- Header ends -->
                        <!-- Body starts -->
                        <?php
                        if (!empty($manage_id)) {
                            for ($m = 0; $m < count($manage_id); $m++) {
                        ?>
                                <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                    <div class="col-4 text-left text-bold h4"></div>
                                    <div class="col-4 text-center"><span class="h4 badge-light-purple"><?php echo $boat_name[$m]; ?></span></div>
                                    <div class="col-4 text-right mb-50"></div>
                                </div>

                                <table class="table table-striped text-uppercase table-vouchure-t2">
                                    <thead class="bg-light">
                                        <tr>
                                            <th colspan="5">ไกด์ : <?php echo $guide_name[$m]; ?></th>
                                            <th colspan="6">เคาน์เตอร์ : <?php echo $counter[$m]; ?></th>
                                            <th colspan="3" style="background-color: <?php echo $color_hex[$m]; ?>; <?php echo $text_color[$m] != '' ? 'color: ' . $text_color[$m] . ';' : ''; ?>">
                                                สี : <?php echo $color_name_th[$m]; ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>เวลารับ</th>
                                            <th width="5%">Driver</th>
                                            <th width="15%">เอเยนต์</th>
                                            <th width="15%">ชื่อลูกค้า</th>
                                            <th width="5%">V/C</th>
                                            <th width="20%">โรงแรม</th>
                                            <th>ห้อง</th>
                                            <!-- <th class="text-center">รวม</th> -->
                                            <th class="text-center">A</th>
                                            <th class="text-center">C</th>
                                            <th class="text-center">Inf</th>
                                            <th class="text-center">FOC</th>
                                            <th>COT</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total_tourist = 0;
                                        $total_adult = 0;
                                        $total_child = 0;
                                        $total_infant = 0;
                                        $total_foc = 0;
                                        if (!empty($booking_id[$manage_id[$m]])) {
                                            $booking_id_arr = array();
                                            for ($i = 0; $i < count($booking_id[$manage_id[$m]]); $i++) {
                                                if (in_array($booking_id[$manage_id[$m]][$i], $booking_id_arr) == false) {
                                                    $booking_id_arr[] = $booking_id[$manage_id[$m]][$i];
                                                    $id = $booking_id[$manage_id[$m]][$i];

                                                    $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                                                    $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                                                    $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                                                    $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                                                    $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                                                    $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;

                                                    $text_hotel = '';
                                                    $text_hotel = (!empty($hotelp_name[$id])) ? $hotelp_name[$id] : $outside_pickup[$id];
                                                    $text_hotel .= (!empty($zonep_name[$id])) ? ' (' . $zonep_name[$id] . ')' : '';
                                                    // $text_hotel .= (!empty($hoteld_name[$id])) ? '<b>Dropoff : </b>' . $hoteld_name[$id] : '<b>Dropoff : </b>' . $outside_dropoff[$id];
                                                    // $text_hotel .= (!empty($zoned_name[$id])) ? ' (' . $zoned_name[$id] . ')' : '';
                                        ?>
                                                    <tr>
                                                        <td class="cell-fit"><?php echo date('H:i', strtotime($start_pickup[$id])) . ' - ' . date('H:i', strtotime($end_pickup[$id])); ?></td>
                                                        <td class="cell-fit">
                                                            <?php if (!empty($car_name[$id])) {
                                                                for ($c = 0; $c < count($car_name[$id]); $c++) {
                                                                    echo $c > 0 ? '<br>' : '';
                                                                    echo '<div class="badge badge-light-success">' . $car_name[$id][$c] . '</div>';
                                                                }
                                                            } ?>
                                                        </td>
                                                        <td><?php echo $agent_name[$id]; ?></td>
                                                        <td><?php echo $cus_name[$id]; ?></td>
                                                        <td><?php echo !empty($voucher_no_agent[$id]) ? $voucher_no_agent[$id] : $book_full[$id]; ?></td>
                                                        <td style="padding: 5px;"><?php echo $text_hotel; ?></td>
                                                        <td class="cell-fit"><?php echo $room_no[$id]; ?></td>
                                                        <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                                                        <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                                                        <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                                                        <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                                                        <td class="cell-fit text-nowrap"><b class="text-danger"><?php echo !empty($cot[$id]) ? number_format($cot[$id]) : ''; ?></b></td>
                                                        <td>
                                                            <b class="text-info">
                                                                <?php
                                                                if (!empty($extra_name[$id])) {
                                                                    for ($e = 0; $e < count($extra_name[$id]); $e++) {
                                                                        echo $e == 0 ? $extra_name[$id][$e] : ' : ' . $extra_name[$id][$e];
                                                                    }
                                                                }
                                                                echo $bp_note[$id]; ?>
                                                            </b>
                                                        </td>
                                                    </tr>
                                        <?php }
                                            }
                                        } ?>
                                    </tbody>
                                </table>

                                <div class="text-center mt-1 pb-2">
                                    <h4>
                                        <div class="badge badge-pill badge-light-warning">
                                            <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b> |
                                            Adult : <?php echo $total_adult; ?>
                                            Child : <?php echo $total_child; ?>
                                            Infant : <?php echo $total_infant; ?>
                                            FOC : <?php echo $total_foc; ?>
                                        </div>
                                    </h4>
                                </div>
                        <?php }
                        } ?>
                        <input type="hidden" id="name_img" name="name_img" value="<?php echo 'ใบจัดเรือ - ' . date('j F Y', strtotime($get_date)); ?>">
                        <!-- Body ends -->
                    </div>
                </div>
                <!-- bookings filter end -->
            </section>

        </div>
    </div>