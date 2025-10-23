<?php
require_once 'controllers/Order.php';

$manageObj = new Order();

// $today = date("Y-m-d");
$today = '2025-09-25';
$tomorrow = date("Y-m-d", strtotime(" +1 day"));

# --- get data --- #
$all_manages = $manageObj->fetch_all_manageboat($today, $search_boat = 'all', 0);

$categorys_array = array();
$all_bookings = $manageObj->fetch_all_bookingboat('all', $today, $search_status = 'all', $search_agent = 'all', $search_product = 'all', $search_voucher_no = '', $refcode = '', $name = '', $hotel = '', 0);
foreach ($all_bookings as $categorys) {
    $categorys_array[] = $categorys['id'];
    $category_name[$categorys['id']][] = $categorys['category_name'];
}

$name_img = 'ใบงาน [' . date('j F Y', strtotime($today)) . ']';
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
                                <a class="nav-link active" id="today-tab" data-toggle="tab" href="#today" aria-controls="today" role="tab" aria-selected="true" data-day="<?php echo $today; ?>" onclick="trigger_search(this);">Today</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tomorrow-tab" data-toggle="tab" href="#tomorrow" aria-controls="tomorrow" role="tab" aria-selected="false" data-day="<?php echo $tomorrow; ?>" onclick="trigger_search(this);">Tomorrow</a>
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
                                <div class="col-md-4 col-12">
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
                        <div class="content-header">
                            <div class="pl-1 pt-0 pb-0">
                                <a href="./?pages=order-job/print&action=print" target="_blank" class="btn btn-info">Print</a>
                                <a href="javascript:void(0)"><button type="button" class="btn btn-info" value="image" onclick="download_image();">Image</button></a>
                            </div>
                        </div>
                        <hr class="pb-0 pt-0">
                        <div id="order-job-image-table" style="background-color: #FFF;">
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
                                        <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($today)); ?></h5>
                                    </div>
                                </div>
                            </div>
                            </br>
                            <!-- Header ends -->
                            <!-- Body starts -->
                            <div id="div-guide-list">
                                <?php
                                if ($all_manages) {
                                    foreach ($all_manages as $key => $manages) {
                                ?>
                                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                            <div class="col-4 text-left text-bold h4"></div>
                                            <div class="col-4 text-center"><span class="h4 badge-light-purple"><?php echo $manages['boat_name']; ?></span></div>
                                            <div class="col-4 text-right mb-50"></div>
                                        </div>

                                        <table class="table table-striped text-uppercase table-vouchure-t2">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th colspan="5">ไกด์ : <?php echo $manages['guide_name']; ?></th>
                                                    <th colspan="6">เคาน์เตอร์ : <?php echo $manages['counter']; ?></th>
                                                    <th colspan="4" style="background-color: <?php echo $manages['color_hex']; ?>; <?php echo $manages['text_color'] != '' ? 'color: ' . $manages['text_color'] . ';' : ''; ?>">
                                                        สี : <?php echo $manages['color_name_th']; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center" width="1%">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkall<?php echo $manages['id']; ?>" onclick="checkbox(<?php echo $manages['id']; ?>);" <?php echo !empty($checkall) ? $checkall : ''; ?> />
                                                            <label class="custom-control-label" for="checkall<?php echo $manages['id']; ?>"></label>
                                                        </div>
                                                    </th>
                                                    <th width="5%">เวลารับ</th>
                                                    <th width="5%">Driver</th>
                                                    <th width="15%">เอเยนต์</th>
                                                    <th width="15%">ชื่อลูกค้า</th>
                                                    <th width="5%">V/C</th>
                                                    <th width="20%">โรงแรม</th>
                                                    <th width="9%">ห้อง</th>
                                                    <th class="text-center" width="1%">รวม</th>
                                                    <th class="text-center" width="1%">A</th>
                                                    <th class="text-center" width="1%">C</th>
                                                    <th class="text-center" width="1%">Inf</th>
                                                    <th class="text-center" width="1%">FOC</th>
                                                    <th width="5%">COT</th>
                                                    <th width="5%">Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total_tourist = 0;
                                                $total_adult = 0;
                                                $total_child = 0;
                                                $total_infant = 0;
                                                $total_foc = 0;
                                                $bomange_arr = array();
                                                $all_bookings = $manageObj->fetch_all_bookingboat('manage', $today, $search_status = 'all', $search_agent = 'all', $search_product = 'all', $search_voucher_no = '', $refcode = '', $name = '', $hotel = '', $manages['id']);
                                                foreach ($all_bookings as $bookings) {
                                                    if (in_array($bookings['bomange_id'], $bomange_arr) == false) {
                                                        $bomange_arr[] = $bookings['bomange_id'];
                                                        $total_adult += !empty($bookings['adult']) ? $bookings['adult'] : 0;
                                                        $total_child += !empty($bookings['child']) ? $bookings['child'] : 0;
                                                        $total_infant += !empty($bookings['infant']) ? $bookings['infant'] : 0;
                                                        $total_foc += !empty($bookings['foc']) ? $bookings['foc'] : 0;
                                                        $total_tourist += $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                                                        $tourist = $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                                                        $text_hotel = '';
                                                        $text_hotel = (!empty($bookings['hotelp_name'])) ? '<b>Pickup : </b>' . $bookings['hotelp_name'] : '<b>Pickup : </b>' . $bookings['outside_pickup'];
                                                        $text_hotel .= (!empty($bookings['zonep_name'])) ? ' (' . $bookings['zonep_name'] . ')</br>' : '</br>';
                                                        $text_hotel .= (!empty($bookings['hoteld_name'])) ? '<b>Dropoff : </b>' . $bookings['hoteld_name'] : '<b>Dropoff : </b>' . $bookings['outside_dropoff'];
                                                        $text_hotel .= (!empty($bookings['zoned_name'])) ? ' (' . $bookings['zoned_name'] . ')' : '';

                                                        $cars = $manageObj->get_values(
                                                            'cars.name as name',
                                                            'booking_order_transfer 
                                                            LEFT JOIN order_transfer ON order_transfer.id = booking_order_transfer.order_id 
                                                            LEFT JOIN cars ON order_transfer.car_id = cars.id',
                                                            'booking_order_transfer.booking_transfer_id = ' . $bookings['bt_id'],
                                                            1
                                                        );

                                                        $check_in = $manageObj->get_values('id', 'check_in', 'booking_id = ' . $bookings['id'] . ' AND type = 1', 0);
                                                ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input dt-checkboxes checkbox-<?php echo $manages['id']; ?>" type="checkbox"
                                                                        data-check="<?php echo !empty($check_in['id']) ? $check_in['id'] : 0; ?>"
                                                                        data-mange="<?php echo $manages['id']; ?>"
                                                                        id="checkbox<?php echo $bookings['id']; ?>"
                                                                        value="<?php echo $bookings['id']; ?>"
                                                                        onclick="submit_check_in('only', this);"
                                                                        <?php echo (!empty($check_in['id']) && $check_in['id'] > 0) ? 'checked' : ''; ?> />
                                                                    <label class="custom-control-label" for="checkbox<?php echo $bookings['id']; ?>"></label>
                                                                </div>
                                                            </td>
                                                            <td class="cell-fit"><?php echo date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])); ?></td>
                                                            <td class="cell-fit">
                                                                <?php if (!empty($cars)) {
                                                                    foreach ($cars as $key => $car) {
                                                                        echo $key > 0 ? '<br>' : '';
                                                                        echo '<div class="badge badge-light-success">' . $car['name'] . '</div>';
                                                                    }
                                                                } ?>
                                                            </td>
                                                            <td><?php echo $bookings['agent_name']; ?></td>
                                                            <td><?php echo $bookings['cus_name']; ?></td>
                                                            <td><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                                                            <td style="padding: 5px;"><?php echo $text_hotel; ?></td>
                                                            <td class="cell-fit"><?php echo $bookings['room_no']; ?></td>
                                                            <td class="cell-fit text-center"><?php echo $tourist; ?></td>
                                                            <td class="cell-fit text-center"><?php echo !empty($bookings['adult']) ? $bookings['adult'] : 0; ?></td>
                                                            <td class="cell-fit text-center"><?php echo !empty($bookings['child']) ? $bookings['child'] : 0; ?></td>
                                                            <td class="cell-fit text-center"><?php echo !empty($bookings['infant']) ? $bookings['infant'] : 0; ?></td>
                                                            <td class="cell-fit text-center"><?php echo !empty($bookings['foc']) ? $bookings['foc'] : 0; ?></td>
                                                            <td class="cell-fit text-nowrap"><b class="text-warning"><?php echo number_format($bookings['cot']); ?></b></td>
                                                            <td>
                                                                <b class="text-info">
                                                                    <?php
                                                                    $e = 0;
                                                                    $extra_charges = $manageObj->get_extra_charge($bookings['id']);
                                                                    if (!empty($extra_charges)) {
                                                                        foreach ($extra_charges as $extra_charge) {
                                                                            echo $e == 0 ? $extra_charge['extra_name'] : ' : ' . $extra_charge['extra_name'];
                                                                            $e++;
                                                                        }
                                                                    }
                                                                    echo $bookings['bp_note']; ?>
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

        </div>
    </div>
</div>