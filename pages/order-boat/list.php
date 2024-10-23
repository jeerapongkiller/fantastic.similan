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

$href = "./?pages=order-boat/print";
$href .= "&date_travel=" . $get_date;
$href .= "&search_boat=" . $search_boat;
$href .= "&search_status=" . $search_status;
$href .= "&search_agent=" . $search_agent;
$href .= "&search_product=" . $search_product;
$href .= "&search_voucher_no=" . $search_voucher_no;
$href .= "&refcode=" . $refcode;
$href .= "&name=" . $name;
$href .= "&action=print";
# --- show list boats booking --- #
$first_booking = array();
$first_prod = array();
$first_cus = array();
$first_program = array();
$first_ext = array();
$first_bomanage = array();
$first_bo = [];
$first_trans = [];
$bookings = $manageObj->showlistboats('list', 0, $get_date, $search_boat, 'all', $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name);
# --- Check products --- #
if (!empty($bookings)) {
    foreach ($bookings as $booking) {
        # --- get value Programe --- #
        if (in_array($booking['product_id'], $first_prod) == false) {
            $first_prod[] = $booking['product_id'];
            $programe_id[] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
            $programe_name[] = !empty($booking['product_name']) ? $booking['product_name'] : '';
            $programe_type[] = !empty($booking['pg_type_name']) ? $booking['pg_type_name'] : '';
            $programe_pier[] = !empty($booking['pier_name']) ? $booking['pier_name'] : '';
        }
        # --- get value booking --- #
        if (in_array($booking['id'], $first_booking) == false) {
            $first_booking[] = $booking['id'];
            $bo_id[] = !empty($booking['id']) ? $booking['id'] : 0;
            $status_by_name[$booking['id']] = !empty($booking['status_by']) ? $booking['stabyFname'] . ' ' . $booking['stabyLname'] : '';
            $status[$booking['id']] = '<span class="badge badge-pill ' . $booking['booksta_class'] . ' text-capitalized"> ' . $booking['booksta_name'] . ' </span>';
            $category_name[$booking['id']] = !empty($booking['category_name']) ? $booking['category_name'] : '';
            $adult[$booking['id']] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
            $child[$booking['id']] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
            $infant[$booking['id']] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
            $foc[$booking['id']] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
            $cate_transfer[$booking['id']] = !empty($booking['category_transfer']) ? $booking['category_transfer'] : 0;
            $cate_transfer[$booking['id']] = !empty($booking['category_transfer']) ? $booking['category_transfer'] : 0;
            $hotel_name[$booking['id']] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : '';
            $zone_pickup[$booking['id']] = !empty($booking['zonep_name']) ? ' (' . $booking['zonep_name'] . ')' : '';
            $dropoff_name[$booking['id']] = !empty($booking['dropoff_name']) ? $booking['dropoff_name'] : '';
            $zone_dropoff[$booking['id']] = !empty($booking['zoned_name']) ? ' (' . $booking['zoned_name'] . ')' : '';
            $room_no[$booking['id']] = !empty($booking['room_no']) ? $booking['room_no'] : '';
            $start_pickup[$booking['id']] = !empty($booking['start_pickup']) && $booking['start_pickup'] != '00:00' ? $booking['start_pickup'] : '00:00';
            $outside[$booking['id']] = !empty($booking['outside']) ? $booking['outside'] : '';
            $outside_dropoff[$booking['id']] = !empty($booking['outside_dropoff']) ? $booking['outside_dropoff'] : '';
            $pickup_type[$booking['id']] = !empty($booking['pickup_type']) ? $booking['pickup_type'] : 0;
            $sender[$booking['id']] = !empty($booking['sender']) ? $booking['sender'] : '';
            $note[$booking['id']] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
            $bp_id[$booking['id']] = !empty($booking['bp_id']) ? $booking['bp_id'] : 0;
            $cot[$booking['id']] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
            $book_full[$booking['id']] = !empty($booking['book_full']) ? $booking['book_full'] : '';
            $voucher_no[$booking['id']] = !empty(!empty($booking['voucher_no_agent'])) ? $booking['voucher_no_agent'] : '';
            $travel_date[$booking['id']] = !empty(!empty($booking['travel_date'])) ? $booking['travel_date'] : '0000-00-00';
            $product_name[$booking['id']] = !empty(!empty($booking['product_name'])) ? $booking['product_name'] : '';
            $agent_name[$booking['id']] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
            $mange_id[$booking['id']] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
            $bo_mange_id[$booking['id']] = !empty($booking['boman_id']) ? $booking['boman_id'] : 0;
            $boat_id[$booking['id']] = !empty($booking['boat_id']) ? $booking['boat_id'] : '';
            $boat_name[$booking['id']] = !empty($booking['boat_name']) ? $booking['boat_name'] : '';
            $color_id[$booking['id']] = !empty($booking['color_id']) ? $booking['color_id'] : '';
            # --- array programe --- #
            $check_mange[$booking['product_id']][] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
            $prod_adult[$booking['product_id']][] = !empty($booking['bp_adult']) && $booking['mange_id'] == 0 ? $booking['bp_adult'] : 0;
            $prod_child[$booking['product_id']][] = !empty($booking['bp_child']) && $booking['mange_id'] == 0 ? $booking['bp_child'] : 0;
            $prod_infant[$booking['product_id']][] = !empty($booking['bp_infant']) && $booking['mange_id'] == 0 ? $booking['bp_infant'] : 0;
            $prod_foc[$booking['product_id']][] = !empty($booking['bp_foc']) && $booking['mange_id'] == 0 ? $booking['bp_foc'] : 0;
        }
        # --- get value customer --- #
        if (in_array($booking['cus_id'], $first_cus) == false) {
            $first_cus[] = $booking['cus_id'];
            $cus_id[$booking['id']][] = !empty($booking['cus_id']) ? $booking['cus_id'] : 0;
            $cus_name[$booking['id']][] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
            $passport[$booking['id']][] = !empty($booking['id_card']) ? $booking['id_card'] : '';
            $birth_date[$booking['id']][] = !empty($booking['birth_date']) && $booking['birth_date'] != '0000-00-00' ? date('j F Y', strtotime($booking['birth_date'])) : '';
            $nation_name[$booking['id']][] = !empty($booking['nation_name']) ? $booking['nation_name'] : '';
        }

        if (in_array($booking['id'], $first_bo) == false) {
            $first_bo[] = $booking['id'];
            $book['id'][$booking['mange_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
            $book['voucher'][$booking['mange_id']][] = !empty($booking['voucher_no_agent']) ? $booking['voucher_no_agent'] : '';
            $book['book_full'][$booking['mange_id']][] = !empty($booking['book_full']) ? $booking['book_full'] : '';
            $book['sender'][$booking['mange_id']][] = !empty($booking['sender']) ? $booking['sender'] : '';
            $book['start_pickup'][$booking['mange_id']][] = !empty($booking['start_pickup']) ? date('H:i', strtotime($booking['start_pickup'])) : '';
            $book['end_pickup'][$booking['mange_id']][] = !empty($booking['end_pickup']) ? date('H:i', strtotime($booking['end_pickup'])) : '';
            $book['hotel'][$booking['mange_id']][] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : '';
            $book['room_no'][$booking['mange_id']][] = !empty($booking['room_no']) ? $booking['room_no'] : '';
            $book['cus_name'][$booking['mange_id']][] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
            $book['telephone'][$booking['mange_id']][] = !empty($booking['telephone']) ? $booking['telephone'] : '';
            $book['comp_name'][$booking['mange_id']][] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
            $book['adult'][$booking['mange_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
            $book['child'][$booking['mange_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
            $book['infant'][$booking['mange_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
            $book['foc'][$booking['mange_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
            $book['rate_adult'][$booking['mange_id']][] = !empty($booking['rate_adult']) ? $booking['rate_adult'] : 0;
            $book['rate_child'][$booking['mange_id']][] = !empty($booking['rate_child']) ? $booking['rate_child'] : 0;
            $book['rate_infant'][$booking['mange_id']][] = !empty($booking['rate_infant']) ? $booking['rate_infant'] : 0;
            $book['rate_private'][$booking['mange_id']][] = !empty($booking['rate_private']) ? $booking['rate_private'] : 0;
            $book['discount'][$booking['mange_id']][] = !empty(!empty($booking['bp_discount'])) ? $booking['bp_discount'] : 0;
            $book['note'][$booking['mange_id']][] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
            $book['cot'][$booking['mange_id']][] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
            $book['total'][$booking['mange_id']][] = $booking['booktye_id'] == 1 ? ($booking['bp_adult'] * $booking['rate_adult']) + ($booking['bp_child'] * $booking['rate_child']) + ($booking['rate_infant'] * $booking['rate_infant']) : $booking['rate_private'];
            $book['bo_mange_id'][$booking['mange_id']][] = !empty($booking['boman_id']) ? $booking['boman_id'] : 0;
        }
        # --- get value booking extra chang --- #
        if ((in_array($booking['bec_id'], $first_ext) == false) && !empty($booking['bec_id'])) {
            $first_ext[] = $booking['bec_id'];
            $bec_id[$booking['id']][] = !empty($booking['bec_id']) ? $booking['bec_id'] : 0;
            $bec_name[$booking['id']][] = !empty($booking['bec_name']) ? $booking['bec_name'] : $booking['extra_name'];
            $bec_type[$booking['id']][] = !empty($booking['bec_type']) ? $booking['bec_type'] : 0;
            $bec_adult[$booking['id']][] = !empty($booking['bec_adult']) ? $booking['bec_adult'] : 0;
            $bec_child[$booking['id']][] = !empty($booking['bec_child']) ? $booking['bec_child'] : 0;
            $bec_infant[$booking['id']][] = !empty($booking['bec_infant']) ? $booking['bec_infant'] : 0;
            $bec_privates[$booking['id']][] = !empty($booking['bec_privates']) ? $booking['bec_privates'] : 0;
            $bec_rate_adult[$booking['id']][] = !empty($booking['bec_rate_adult']) ? $booking['bec_rate_adult'] : 0;
            $bec_rate_child[$booking['id']][] = !empty($booking['bec_rate_child']) ? $booking['bec_rate_child'] : 0;
            $bec_rate_infant[$booking['id']][] = !empty($booking['bec_rate_infant']) ? $booking['bec_rate_infant'] : 0;
            $bec_rate_private[$booking['id']][] = !empty($booking['bec_rate_private']) ? $booking['bec_rate_private'] : 0;
            $bec_rate_total[$booking['id']][] = $booking['bec_type'] > 0 ? $booking['bec_type'] == 1 ? (($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) + ($booking['bec_infant'] * $booking['bec_rate_infant'])) : ($booking['bec_privates'] * $booking['bec_rate_private']) : 0;
        }

        if (in_array($booking['bomanage_id'], $first_bomanage) == false) {
            $first_managet[] = $booking['bomanage_id'];
            $retrun_t = !empty($booking['pickup']) ? 1 : 2;
            $managet['bomanage_id'][$booking['id']][$retrun_t] = !empty($booking['bomanage_id']) ? $booking['bomanage_id'] : 0;
            $managet['id'][$booking['id']][$retrun_t] = !empty($booking['manget_id']) ? $booking['manget_id'] : 0;
            $managet['car'][$booking['id']][$retrun_t] = !empty($booking['car_name']) ? $booking['car_name'] : '';
            $managet['pickup'][$booking['id']][] = !empty($booking['pickup']) ? $booking['pickup'] : 0;
            $managet['dropoff'][$booking['id']][] = !empty($booking['dropoff']) ? $booking['dropoff'] : 0;
        }
    }
}
# --- show list boats manage --- #
$first_manage = array();
$manages = $manageObj->show_manage_boat($get_date, $search_boat);
if (!empty($manages)) {
    foreach ($manages as $manage) {
        if (in_array($manage['id'], $first_manage) == false) {
            $first_manage[] = $manage['id'];
            $mange['id'][] = !empty($manage['id']) ? $manage['id'] : 0;
            $mange['color_id'][] = !empty($manage['color_id']) ? $manage['color_id'] : 0;
            $mange['color_name'][] = !empty($manage['color_name_th']) ? $manage['color_name_th'] : '';
            $mange['color_hex'][] = !empty($manage['color_hex']) ? $manage['color_hex'] : '';
            $mange['time'][] = !empty($manage['time']) ? date('H:i', strtotime($manage['time'])) : '00:00';
            $mange['boat_id'][] = !empty($manage['boat_id']) ? $manage['boat_id'] : 0;
            $mange['boat_name'][] = !empty($manage['boat_id']) ? !empty($manage['boat_name']) ? $manage['boat_name'] : '' : $manage['outside_boat'];
            // $mange['guide_name'][] = !empty($manage['guide']) ? $manage['guide'] : '';
            $mange['guide_id'][] = !empty($manage['guide_id']) ? $manage['guide_id'] : 0;
            $mange['guide_name'][] = !empty($manage['guide_name']) ? $manage['guide_name'] : '';
            $mange['captain_id'][] = !empty($manage['captain_id']) ? $manage['captain_id'] : 0;
            $mange['captain_name'][] = !empty($manage['captain_id']) ?  $manage['captain_name'] : '';
            $mange['crewf_id'][] = !empty($manage['crewf_id']) ? $manage['crewf_id'] : 0;
            $mange['crews_id'][] = !empty($manage['crews_id']) ? $manage['crews_id'] : 0;
            $mange['crewf_name'][] = !empty($manage['crewf_id']) ? $manage['crewf_name'] : '';
            $mange['crews_name'][] = !empty($manage['crews_id']) ? $manage['crews_name'] : '';
            $mange['product_id'][] = !empty($manage['product_id']) ? $manage['product_id'] : 0;
            $mange['product_name'][] = !empty($manage['product_name']) ? $manage['product_name'] : '';
            $mange['booktye_name'][] = !empty($manage['booktye_name']) ? $manage['booktye_name'] : '';
            $mange['pier_name'][] = !empty($manage['pier_name']) ? $manage['pier_name'] : '';
            $mange['note'][] = !empty($manage['note']) ? $manage['note'] : '';
            $mange['outside_boat'][] = !empty($manage['outside_boat']) ? $manage['outside_boat'] : '';

            $arr_boat['mange_id'][] = !empty($manage['id']) ? $manage['id'] : 0;
            $arr_boat['id'][] = !empty($manage['boat_id']) ? $manage['boat_id'] : 0;
            $arr_boat['boat_id'][] = !empty($manage['boat_id']) ? $manage['boat_id'] : 0;
            $arr_boat['name'][] = !empty($manage['boat_id']) ? !empty($manage['boat_name']) ? $manage['boat_name'] : '' : $manage['outside_boat'];
            $arr_boat['refcode'][] = !empty($manage['boat_refcode']) ? $manage['boat_refcode'] : '';
        }
    }
}
# --- show list programe --- #
$programed = $manageObj->show_manage_programe($get_date);
if (!empty($programed)) {
    foreach ($programed as $program) {
        if (in_array($program['id'], $first_program) == false) {
            $first_program[] = $program['id'];
            $programed_id[] = !empty($program['id']) ? $program['id'] : 0;
        }
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
                    <button type="button" class="btn btn-success waves-effect waves-float waves-light btn-page-block-spinner" data-toggle="modal" data-target="#modal-boat" onclick="modal_boat('<?php echo date('j F Y', strtotime($get_date)); ?>', 0, 0);"><i data-feather='plus'></i> เปิดเรือ</button>
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
                        if (!empty($mange['id'])) {
                            for ($i = 0; $i < count($mange['id']); $i++) {

                        ?>
                                <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
                                    <div class="col-4 text-left text-bold h4"></div>
                                    <div class="col-4 text-center text-bold h4"><?php echo $mange['boat_name'][$i]; ?></div>
                                    <div class="col-4 text-right mb-50"></div>
                                </div>

                                <table class="table table-striped text-uppercase table-vouchure-t2">
                                    <thead class="bg-light">
                                        <tr>
                                            <th colspan="11">ไกด์ : <?php echo $mange['guide_name'][$i]; ?></th>
                                            <th colspan="3" style="background-color: <?php echo $mange['color_hex'][$i]; ?>;">
                                                สี : <?php echo $mange['color_name'][$i]; ?>
                                            </th>
                                        </tr>
                                        <tr>
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
                                            <!-- <th class="text-center">รวม</th> -->
                                            <th width="5%">COT</th>
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
                                        if (!empty($book['id'][$mange['id'][$i]])) {
                                            for ($a = 0; $a < count($book['id'][$mange['id'][$i]]); $a++) {
                                                $total_tourist = $total_tourist + $book['adult'][$mange['id'][$i]][$a] + $book['child'][$mange['id'][$i]][$a] + $book['infant'][$mange['id'][$i]][$a] + $book['foc'][$mange['id'][$i]][$a];
                                                $total_adult = $total_adult + $book['adult'][$mange['id'][$i]][$a];
                                                $total_child = $total_child + $book['child'][$mange['id'][$i]][$a];
                                                $total_infant = $total_infant + $book['infant'][$mange['id'][$i]][$a];
                                                $total_foc = $total_foc + $book['foc'][$mange['id'][$i]][$a];
                                                $id = $book['id'][$mange['id'][$i]][$a];
                                        ?>
                                                <tr>
                                                    <td><?php echo $book['start_pickup'][$mange['id'][$i]][$a] != '00:00' ? $book['start_pickup'][$mange['id'][$i]][$a] . ' - ' . $book['end_pickup'][$mange['id'][$i]][$a] : ''; ?></td>
                                                    <td style="padding: 5px;"><?php echo (!empty($managet['car'][$id][1])) ? $managet['car'][$id][1] : ''; ?></td>
                                                    <td><?php echo $book['comp_name'][$mange['id'][$i]][$a]; ?></td>
                                                    <td><?php echo !empty($book['telephone'][$mange['id'][$i]][$a]) ? $book['cus_name'][$mange['id'][$i]][$a] . ' <br>(' . $book['telephone'][$mange['id'][$i]][$a] . ')' : $book['cus_name'][$mange['id'][$i]][$a]; ?></td>
                                                    <td><?php echo !empty($book['voucher'][$mange['id'][$i]][$a]) ? $book['voucher'][$mange['id'][$i]][$a] : $book['book_full'][$mange['id'][$i]][$a]; ?></td>
                                                    <td style="padding: 5px;">
                                                        <?php if ($pickup_type[$id] == 1) {
                                                            echo (!empty($hotel_name[$id])) ? '<b>Pickup : </b>' . $hotel_name[$id] . $zone_pickup[$id] . '</br>' : '<b>Pickup : </b>' . $outside[$id] . $zone_pickup[$id] . '</br>';
                                                            echo (!empty($dropoff_name[$id])) ? '<b>Dropoff : </b>' . $dropoff_name[$id] . $zone_dropoff[$id] : '<b>Dropoff : </b>' . $outside_dropoff[$id]  . $zone_dropoff[$id];
                                                        } else {
                                                            echo 'เดินทางมาเอง';
                                                        } ?>
                                                    </td>
                                                    <td><?php echo $book['room_no'][$mange['id'][$i]][$a]; ?></td>
                                                    <td class="text-center"><?php echo $book['adult'][$mange['id'][$i]][$a]; ?></td>
                                                    <td class="text-center"><?php echo $book['child'][$mange['id'][$i]][$a]; ?></td>
                                                    <td class="text-center"><?php echo $book['infant'][$mange['id'][$i]][$a]; ?></td>
                                                    <td class="text-center"><?php echo $book['foc'][$mange['id'][$i]][$a]; ?></td>
                                                    <!-- <td class="text-center"><?php echo !empty($bec_rate_total[$id]) ? number_format($book['total'][$mange['id'][$i]][$a] + array_sum($bec_rate_total[$id])) : number_format($book['total'][$mange['id'][$i]][$a]); ?></td> -->
                                                    <td class="text-nowrap"><b class="text-danger"><?php echo $book['cot'][$mange['id'][$i]][$a] > 0 ? number_format($book['cot'][$mange['id'][$i]][$a]) : ''; ?></b></td>
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
                                                            echo !empty($book['note'][$mange['id'][$i]][$a]) ? ' / ' . $book['note'][$mange['id'][$i]][$a] : ''; ?>
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
                        <input type="hidden" id="name_img" name="name_img" value="<?php echo 'ใบจัดเรือ - ' . date('j F Y', strtotime($get_date)); ?>">
                        <!-- Body ends -->
                    </div>
                </div>
                <!-- bookings filter end -->
            </section>

        </div>
    </div>