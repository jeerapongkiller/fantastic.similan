<?php
require_once 'controllers/Order.php';

$orderObj = new Order();

$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
$get_date = !empty($_GET['date']) ? $_GET['date'] : $today;

function check_in($var)
{
    return ($var > 0);
}
?>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
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
            <!-- order job list start -->
            <section class="app-user-list">
                <!-- list section start -->
                <div class="card">
                    <!-- order job filter end -->
                    <div class="content-header">
                        <h5 class="pt-1 pl-2 pb-0">Search Filter</h5>
                        <form id="order-job-search-form" name="order-job-search-form" method="post" enctype="multipart/form-data">
                            <div class="d-flex align-items-center mx-50 row pt-0 pb-2">
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label for="search_status">Status</label>
                                        <select class="form-control select2" id="search_status" name="search_status">
                                            <option value="all">All</option>
                                            <?php
                                            $bookstype = $orderObj->showliststatus();
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
                                            $agents = $orderObj->showlistagent();
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
                                            $products = $orderObj->showlistproduct();
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
                                            $boats = $orderObj->show_boats();
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
                                        <label class="form-label" for="date_travel_form">วันที่เที่ยว (Travel Date)</label></br>
                                        <input type="text" class="form-control date-picker" id="date_travel_form" name="date_travel_form" value="<?php echo $today; ?>" />
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
                                    <div class="form-group">
                                        <label class="form-label" for="hotel">Hotel</label>
                                        <input type="text" class="form-control" id="hotel" name="hotel" value="<?php echo $hotel; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <button type="submit" class="btn btn-primary" name="submit" value="Submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr class="pb-0 pt-0">
                    <div id="order-jobs-search-table">
                        <?php
                        # --- get data --- #
                        $first_order = array();
                        $first_bo = array();
                        $first_cus = array();
                        $first_ext = array();
                        $first_pay = array();
                        $first_bomanage = array();
                        $sum_programe = 0;
                        $sum_ad = 0;
                        $sum_chd = 0;
                        $sum_inf = 0;
                        # --- get data --- #
                        $orders = $orderObj->showlistboats('job', 0, $get_date, 'all', 'all', 'all', 'all', 'all', 'all', '', '', '', '', '');
                        if (!empty($orders)) {
                            foreach ($orders as $order) {
                                if ((in_array($order['mange_id'], $first_order) == false) && !empty($order['mange_id'])) {
                                    $first_order[] = $order['mange_id'];
                                    $mange_id[] = !empty($order['mange_id']) ? $order['mange_id'] : 0;
                                    $order_boat_id[] = !empty($order['boat_id']) ? $order['boat_id'] : '';
                                    $order_boat_name[] = empty($order['boat_id']) ? !empty($order['orboat_boat_name']) ? $order['orboat_boat_name'] : '' : $order['boat_name'];
                                    $order_boat_refcode[] = !empty($order['boat_refcode']) ? $order['boat_refcode'] : '';
                                    $order_capt_id[] = !empty($order['capt_id']) ? $order['capt_id'] : 0;
                                    $order_counter[] = !empty($order['manage_counter']) ? $order['manage_counter'] : '';
                                    $order_guide_id[] = !empty($order['guide_id']) ? $order['guide_id'] : 0;
                                    $order_guide_name[] = !empty($order['guide_id']) ? $order['guide_name'] : '';
                                    $order_note[] = !empty($order['orboat_note']) ? $order['orboat_note'] : '';
                                    $order_crew_name[] = !empty($order['crew_id']) ? $order['crew_name'] : '';
                                    $order_price[] = !empty($order['orboat_price']) ? $order['orboat_price'] : '';
                                    $color_hex[] = !empty($order['color_hex']) ? $order['color_hex'] : '';
                                    $color_name[] = !empty($order['color_name_th']) ? $order['color_name_th'] : '';
                                    $text_color[] = !empty($order['text_color']) ? $order['text_color'] : '';
                                }

                                if ((in_array($order['id'], $first_bo) == false)  && !empty($order['mange_id'])) {
                                    $first_bo[] = $order['id'];
                                    $bo_id[$order['mange_id']][] = !empty($order['id']) ? $order['id'] : 0;
                                    $check_id[$order['mange_id']][] = !empty($order['check_id']) ? $order['check_id'] : 0;
                                    $book_full[$order['mange_id']][] = !empty($order['book_full']) ? $order['book_full'] : '';
                                    $agent[$order['mange_id']][] = !empty($order['comp_name']) ? $order['comp_name'] : '';
                                    $voucher_no[$order['mange_id']][] = !empty($order['voucher_no_agent']) ? $order['voucher_no_agent'] : '';
                                    $pickup_time[$order['mange_id']][] = $order['start_pickup'] != '00:00:00' ? $order['end_pickup'] != '00:00:00' ? date('H:i', strtotime($order['start_pickup'])) . '-' . date('H:i', strtotime($order['end_pickup'])) : date('H:i', strtotime($order['start_pickup'])) : '-';
                                    $room_no[$order['mange_id']][] = !empty($order['room_no']) ? $order['room_no'] : '-';
                                    $hotel_pickup[$order['mange_id']][] = !empty($order['pickup_name']) ? $order['pickup_name'] : $order['outside'];
                                    $zone_pickup[$order['mange_id']][] = !empty($order['zonep_name']) ? ' (' . $order['zonep_name'] . ')' : '';
                                    $hotel_dropoff[$order['mange_id']][] = !empty($order['dropoff_name']) ? $order['dropoff_name'] : $order['outside_dropoff'];
                                    $zone_dropoff[$order['mange_id']][] = !empty($order['zoned_name']) ? ' (' . $order['zoned_name'] . ')' : '';
                                    $bp_note[$order['mange_id']][] = !empty($order['bp_note']) ? $order['bp_note'] : '';
                                    $product_name[$order['mange_id']][] = !empty($order['product_name']) ? $order['product_name'] : '';
                                    $booking_type[$order['mange_id']][] = !empty($order['bp_private_type']) && $order['bp_private_type'] == 2 ? 'Private' : 'Join';
                                    $adult[$order['mange_id']][] = !empty($order['bp_adult']) ? $order['bp_adult'] : 0;
                                    $child[$order['mange_id']][] = !empty($order['bp_child']) ? $order['bp_child'] : 0;
                                    $infant[$order['mange_id']][] = !empty($order['bp_infant']) ? $order['bp_infant'] : 0;
                                    $foc[$order['mange_id']][] = !empty($order['bp_foc']) ? $order['bp_foc'] : 0;
                                    $rate_adult[$order['mange_id']][] = !empty($order['rate_adult']) ? $order['rate_adult'] : 0;
                                    $rate_child[$order['mange_id']][] = !empty($order['rate_child']) ? $order['rate_child'] : 0;
                                    $car_name[$order['mange_id']][] = !empty($order['car_id']) ? $order['car_name'] : '';
                                    $start_pickup[$order['mange_id']][] = !empty($order['start_pickup']) ? date('H:i', strtotime($order['start_pickup'])) : '00:00:00';
                                    $pickup_type[$order['mange_id']][] = !empty($order['pickup_type']) ? $order['pickup_type'] : 0;
                                    $total[$order['mange_id']][] = $order['booktye_id'] == 1 ? ($order['bp_adult'] * $order['rate_adult']) + ($order['bp_child'] * $order['rate_child']) + ($order['rate_infant'] * $order['rate_infant']) : $order['rate_private'];
                                }

                                $bopay_name[$order['id']] = !empty($order['bopay_name']) ? $order['bopay_name'] : '';

                                if (in_array($order['cus_id'], $first_cus) == false) {
                                    $first_cus[] = $order['cus_id'];
                                    $cus_id[$order['id']][] = !empty($order['cus_id']) ? $order['cus_id'] : 0;
                                    $cus_name[$order['id']][] = !empty($order['cus_name']) ? $order['cus_name'] : '';
                                    $telephone[$order['id']][] = !empty($order['telephone']) ? $order['telephone'] : '';
                                    $cus_id_card[$order['id']][] = !empty($order['id_card']) ? $order['id_card'] : '';
                                }

                                # --- get value booking extra chang --- #
                                if ((in_array($order['bec_id'], $first_ext) == false) && !empty($order['bec_id'])) {
                                    $first_ext[] = $order['bec_id'];
                                    $bec_id[$order['id']][] = !empty($order['bec_id']) ? $order['bec_id'] : 0;
                                    $extra_id[$order['id']][] = !empty($order['extra_id']) ? $order['extra_id'] : 0;
                                    $extra_name[$order['id']][] = !empty($order['extra_name']) ? $order['extra_name'] : '';
                                    $bec_type[$order['id']][] = !empty($order['bec_type']) ? $order['bec_type'] : 0;
                                    $bec_adult[$order['id']][] = !empty($order['bec_adult']) ? $order['bec_adult'] : 0;
                                    $bec_child[$order['id']][] = !empty($order['bec_child']) ? $order['bec_child'] : 0;
                                    $bec_infant[$order['id']][] = !empty($order['bec_infant']) ? $order['bec_infant'] : 0;
                                    $bec_privates[$order['id']][] = !empty($order['bec_privates']) ? $order['bec_privates'] : 0;
                                    $bec_rate_adult[$order['id']][] = !empty($order['bec_rate_adult']) ? $order['bec_rate_adult'] : 0;
                                    $bec_rate_child[$order['id']][] = !empty($order['bec_rate_child']) ? $order['bec_rate_child'] : 0;
                                    $bec_rate_infant[$order['id']][] = !empty($order['bec_rate_infant']) ? $order['bec_rate_infant'] : 0;
                                    $bec_rate_private[$order['id']][] = !empty($order['bec_rate_private']) ? $order['bec_rate_private'] : 0;
                                    $bec_rate_total[$order['id']][] = $order['bec_type'] > 0 ? $order['bec_type'] == 1 ? (($order['bec_adult'] * $order['bec_rate_adult']) + ($order['bec_child'] * $order['bec_rate_child']) + ($order['bec_infant'] * $order['bec_rate_infant'])) : ($order['bec_privates'] * $order['bec_rate_private']) : 0;
                                    $bec_extar_unit[$order['id']][] = $order['bec_type'] > 0 ? $order['bec_type'] == 1 ? ($order['bec_adult'] + $order['bec_child'] + $order['bec_infant']) . ' คน' : $order['bec_privates'] . ' ' . $order['extra_unit'] : '';
                                    $bec_name[$order['id']][] = !empty($order['extra_id']) ? $order['extra_name'] : $order['bec_name'];
                                }

                                # --- in array get value booking payment --- #
                                if ((in_array($order['bopa_id'], $first_pay) == false) && !empty($order['bopa_id'])) {
                                    $first_pay[] = $order['bopa_id'];
                                    if ($order['bopay_id'] == 4) {
                                        $cot_id[$order['id']][] = !empty($order['bopa_id']) ? $order['bopa_id'] : 0;
                                        $cot_name[$order['id']] = !empty($order['bopay_name']) ? $order['bopay_name'] . ' (' . number_format($order['total_paid']) . ')' : '';
                                        $cot_class[$order['id']] = !empty($order['bopay_name_class']) ? $order['bopay_name_class'] : '';
                                        $cot[$order['id']][] = !empty($order['total_paid']) ? $order['total_paid'] : 0;
                                    }
                                }

                                if (in_array($order['bomanage_id'], $first_bomanage) == false) {
                                    $first_managet[] = $order['bomanage_id'];
                                    $retrun_t = !empty($order['pickup']) ? 1 : 2;
                                    $managet['bomanage_id'][$order['id']][$retrun_t] = !empty($order['bomanage_id']) ? $order['bomanage_id'] : 0;
                                    $managet['id'][$order['id']][$retrun_t] = !empty($order['manget_id']) ? $order['manget_id'] : 0;
                                    $managet['car'][$order['id']][$retrun_t] = !empty($order['car_name']) ? $order['car_name'] : '';
                                    $managet['pickup'][$order['id']][] = !empty($order['pickup']) ? $order['pickup'] : 0;
                                    $managet['dropoff'][$order['id']][] = !empty($order['dropoff']) ? $order['dropoff'] : 0;
                                }
                            }
                            $name_img = 'Job Guide [' . date('j F Y', strtotime($get_date)) . ']';
                        }
                        ?>
                        <div class="content-header">
                            <div class="pl-1 pt-0 pb-0">
                                <a href="./?pages=order-guide/print&action=print" target="_blank" class="btn btn-info">Print</a>
                                <a href="javascript:void(0)"><button type="button" class="btn btn-info" value="image" onclick="download_image();">Image</button></a>
                                <a href="javascript:void(0);" class="btn btn-info disabled" hidden>Download as PDF</a>
                            </div>
                        </div>
                        <hr class="pb-0 pt-0">
                        <div id="order-guide-image-table" style="background-color: #FFF;">
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
                                    <h4 class="font-weight-bolder">ใบงาน</h4>
                                    <div class="badge badge-pill badge-light-danger">
                                        <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($get_date)); ?></h5>
                                    </div>
                                </div>
                            </div>
                            </br>
                            <!-- Header ends -->
                            <!-- Body starts -->
                            <div id="div-guide-list">
                                <?php
                                if (!empty($mange_id)) {
                                    for ($i = 0; $i < count($mange_id); $i++) {
                                        $total_no = 0;
                                        if (!empty($bo_id[$mange_id[$i]]) && !empty($check_id[$mange_id[$i]])) {
                                            $checkall = count($bo_id[$mange_id[$i]]) == count(array_filter($check_id[$mange_id[$i]], "check_in")) ? 'checked' : '';
                                        }
                                ?>
                                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
                                            <div class="col-4 text-left text-bold h4"></div>
                                            <div class="col-4 text-center text-bold h4"><?php echo $order_boat_name[$i]; ?></div>
                                            <div class="col-4 text-right mb-50"></div>
                                        </div>

                                        <table class="table table-striped text-uppercase table-vouchure-t2">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th colspan="6">ไกด์ : <?php echo $order_guide_name[$i]; ?></th>
                                                    <th colspan="5">เคาน์เตอร์ : <?php echo $order_counter[$i]; ?></th>
                                                    <th colspan="4" style="background-color: <?php echo $color_hex[$i]; ?>; <?php echo $text_color[$i] != '' ? 'color: ' . $text_color[$i] . ';' : ''; ?>">
                                                        สี : <?php echo $color_name[$i]; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center" width="1%">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkall<?php echo $mange_id[$i]; ?>" onclick="checkbox(<?php echo $mange_id[$i]; ?>);" <?php echo !empty($checkall) ? $checkall : ''; ?> />
                                                            <label class="custom-control-label" for="checkall<?php echo $mange_id[$i]; ?>"></label>
                                                        </div>
                                                    </th>
                                                    <th width="5%">เวลารับ</th>
                                                    <th width="5%">Driver</th>
                                                    <th width="15%">เอเยนต์</th>
                                                    <th width="15%">ชื่อลูกค้า</th>
                                                    <th width="5%">V/C</th>
                                                    <th width="20%">โรงแรม</th>
                                                    <th width="9%">ห้อง</th>
                                                    <th class="text-center" width="1%">A</th>
                                                    <th class="text-center" width="1%">C</th>
                                                    <th class="text-center" width="1%">Inf</th>
                                                    <th class="text-center" width="1%">FOC</th>
                                                    <!-- <th class="text-center" width="1%">รวม</th> -->
                                                    <th width="5%">COT</th>
                                                    <th width="5%">Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody id="<?php echo "tbody-" . $mange_id[$i] ?>">
                                                <?php
                                                $total_tourist = 0;
                                                $total_adult = 0;
                                                $total_child = 0;
                                                $total_infant = 0;
                                                $total_foc = 0;
                                                if (!empty($bo_id[$mange_id[$i]])) {
                                                    for ($a = 0; $a < count($bo_id[$mange_id[$i]]); $a++) {
                                                        $total_tourist = $total_tourist + $adult[$mange_id[$i]][$a] + $child[$mange_id[$i]][$a] + $infant[$mange_id[$i]][$a] + $foc[$mange_id[$i]][$a];
                                                        $total_adult = $total_adult + $adult[$mange_id[$i]][$a];
                                                        $total_child = $total_child + $child[$mange_id[$i]][$a];
                                                        $total_infant = $total_infant + $infant[$mange_id[$i]][$a];
                                                        $total_foc = $total_foc + $foc[$mange_id[$i]][$a];
                                                        $id = $bo_id[$mange_id[$i]][$a];
                                                ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input dt-checkboxes checkbox-<?php echo $mange_id[$i]; ?>" type="checkbox" data-check="<?php echo $check_id[$mange_id[$i]][$a]; ?>" data-mange="<?php echo $mange_id[$i]; ?>" id="checkbox<?php echo $id; ?>" value="<?php echo $id; ?>" onclick="submit_check_in('only', this);" <?php echo $check_id[$mange_id[$i]][$a] > 0 ? 'checked' : ''; ?> />
                                                                    <label class="custom-control-label" for="checkbox<?php echo $id; ?>"></label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center"><?php echo $pickup_time[$mange_id[$i]][$a]; ?></td>
                                                            <td style="padding: 5px;"><?php echo (!empty($managet['car'][$id][1])) ? $managet['car'][$id][1] : ''; ?></td>
                                                            <td><?php echo $agent[$mange_id[$i]][$a]; ?></td>
                                                            <td><?php echo !empty($telephone[$bo_id[$mange_id[$i]][$a]][0]) ? $cus_name[$bo_id[$mange_id[$i]][$a]][0] . ' <br>(' . $telephone[$bo_id[$mange_id[$i]][$a]][0] . ')' : $cus_name[$bo_id[$mange_id[$i]][$a]][0]; ?></td>
                                                            <td><?php echo !empty($voucher_no[$mange_id[$i]][$a]) ? $voucher_no[$mange_id[$i]][$a] : $book_full[$mange_id[$i]][$a]; ?></td>
                                                            <td style="padding: 5px;">
                                                                <?php if ($pickup_type[$mange_id[$i]][$a] == 1) {
                                                                    echo (!empty($hotel_pickup[$mange_id[$i]][$a])) ? '<b>Pickup : </b>' . $hotel_pickup[$mange_id[$i]][$a] . $zone_pickup[$mange_id[$i]][$a] : '';
                                                                    echo (!empty($hotel_dropoff[$mange_id[$i]][$a])) ? '</br><b>Dropoff : </b>' . $hotel_dropoff[$mange_id[$i]][$a] . $zone_dropoff[$mange_id[$i]][$a] : '';
                                                                } else {
                                                                    echo 'เดินทางมาเอง';
                                                                } ?>
                                                            </td>
                                                            <td><?php echo $room_no[$mange_id[$i]][$a]; ?></td>
                                                            <td class="text-center"><?php echo $adult[$mange_id[$i]][$a]; ?></td>
                                                            <td class="text-center"><?php echo $child[$mange_id[$i]][$a]; ?></td>
                                                            <td class="text-center"><?php echo $infant[$mange_id[$i]][$a]; ?></td>
                                                            <td class="text-center"><?php echo $foc[$mange_id[$i]][$a]; ?></td>
                                                            <!-- <td class="text-center"><?php echo !empty($bec_rate_total[$id]) ? number_format($total[$mange_id[$i]][$a] + array_sum($bec_rate_total[$id])) : number_format($total[$mange_id[$i]][$a]); ?></td> -->
                                                            <td class="text-nowrap"><b class="text-danger"><?php echo !empty($cot[$id]) ? array_sum($cot[$id]) : ''; ?></b></td>
                                                            <td><b class="text-info">
                                                                    <?php if (!empty($bec_id[$id])) {
                                                                        for ($e = 0; $e < count($bec_name[$id]); $e++) {
                                                                            echo $e == 0 ? $bec_name[$id][$e] : ' : ' . $bec_name[$id][$e];
                                                                            // if ($bec_type[$id][$e] == 1) {
                                                                            //     echo 'A ' . $bec_adult[$id][$e] . ' X ' . $bec_rate_adult[$id][$e];
                                                                            //     echo !empty($bec_child[$id][$e]) ? ' C ' . $bec_child[$id][$e] . ' X ' . $bec_rate_child[$id][$e] : '';
                                                                            // } elseif ($bec_type[$id][$e] == 2) {
                                                                            //     echo $bec_privates[$id][$e] . ' X ' . $bec_rate_total[$id][$e] . ' ';
                                                                            // }
                                                                        }
                                                                    }
                                                                    echo !empty($bp_note[$mange_id[$i]][$a]) ? ' / ' . $bp_note[$mange_id[$i]][$a] : ''; ?>
                                                                </b>
                                                            </td>
                                                        </tr>
                                                <?php }
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
                            </div>
                            <!-- Body ends -->
                            <input type="hidden" id="name_img" name="name_img" value="<?php echo $name_img; ?>">
                        </div>
                    </div>
                </div>
                <!-- list section end -->
            </section>
            <!-- order job list ends -->

            <!-- Start Form Modal -->
            <!------------------------------------------------------------------>
            <div class="modal-size-lg d-inline-block">
                <div class="modal fade text-left" id="modal-park" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel17">ค่าอุทยาน</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="show-div-park"></div>
                                <form id="order-park-form" name="order-park-form" method="post" enctype="multipart/form-data">
                                    <input type="hidden" id="orboat_park_id" name="orboat_park_id" value="">
                                    <input type="hidden" id="orboat_id" name="orboat_id" value="">
                                    <input type="hidden" id="action_park" name="action_park" value="">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group" hidden>
                                                <label for="parks">อุทยาน</label>
                                                <select class="form-control select2" id="parks" name="parks" onchange="check_parks();">
                                                    <option value="0">กรุญาเลือกอุทยาน...</option>
                                                    <?php
                                                    $parks = $orderObj->show_park();
                                                    foreach ($parks as $park) {
                                                    ?>
                                                        <option value="<?php echo $park['id']; ?>" data-name="<?php echo $park['name']; ?>" data-adult-eng="<?php echo $park['rate_adult_eng']; ?>" data-child-eng="<?php echo $park['rate_child_eng']; ?>" data-adult-th="<?php echo $park['rate_adult_th']; ?>" data-child-th="<?php echo $park['rate_child_th']; ?>"><?php echo $park['name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">อุทยาน</label></br>
                                                <h6 id="parks_text"></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="rate_adult_eng">ผู้ใหญ่ (ราคาชาวต่างชาติ)</label></br>
                                                <input type="hidden" class="form-control numeral-mask" id="rate_adult_eng" name="rate_adult_eng" value="0" oninput="calculator_parks();" />
                                                <h6 id="adult_eng"></h6>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-1">
                                            <h5 class="mt-3"> X <span id="adult_eng"></span></h5>
                                        </div> -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="rate_child_eng">เด็ก (ราคาชาวต่างชาติ)</label></br>
                                                <input type="hidden" class="form-control numeral-mask" id="rate_child_eng" name="rate_child_eng" value="0" oninput="calculator_parks();" />
                                                <h6 id="child_eng"></h6>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-1">
                                            <h5 class="mt-3"> X <span id="child_eng"></span></h5>
                                        </div> -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="rate_adult_th">เด็ก (ราคาชาวไทย)</label></br>
                                                <input type="hidden" class="form-control numeral-mask" id="rate_adult_th" name="rate_adult_th" value="0" oninput="calculator_parks();" />
                                                <h6 id="adult_th"></h6>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-1">
                                            <h5 class="mt-3"> X <span id="adult_th"></span></h5>
                                        </div> -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="rate_child_th">เด็ก (ราคาชาวไทย)</label></br>
                                                <input type="hidden" class="form-control numeral-mask" id="rate_child_th" name="rate_child_th" value="0" oninput="calculator_parks();" />
                                                <h6 id="child_th"></h6>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-1">
                                            <h5 class="mt-3"> X <span id="child_th"></span></h5>
                                        </div> -->
                                        <!-- <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="note">Note</label></br>
                                                <textarea name="note" id="note" class="form-control" cols="30" rows="3"></textarea>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="total_park">รวมทั้งหมด</label></br>
                                                <input type="hidden" class="form-control numeral-mask" id="total_park" name="total_park" value="0" readonly />
                                                <h5 class="text-primary" id="park_total"></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <hr />
                                    <div class="d-flex justify-content-between">
                                        <div></div>
                                        <div>
                                            <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
                                        </div>
                                    </div> -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-size-img d-inline-block">
                <div class="modal fade text-left" id="modal-customer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-img" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel17">รายชื่อ</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-info bg-darken-2 text-white">
                                            <tr>
                                                <th class="text-center"></th>
                                                <th class="text-center">หมายเลขพาสปอร์ต</th>
                                                <th class="text-center">ชื่อ</th>
                                                <th class="text-center">ว/ด/ป เกิด</th>
                                                <th class="text-center">สัญชาติ</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-customer">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>