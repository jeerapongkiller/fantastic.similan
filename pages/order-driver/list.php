<?php
require_once 'controllers/Order.php';

$manageObj = new Order();
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
// $today = '2025-09-24';
// $tomorrow = '2025-09-25';

$all_manages = $manageObj->fetch_all_manage('all', 'all', $tomorrow, 0);
?>

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">ใบงานรถ</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
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
            <!-- Sortable lists section start -->
            <section id="sortable-lists">
                <!-- filter start -->
                <div class="card">
                    <h5 class="card-header">Search Filter</h5>
                    <form id="manages-search-form" name="manages-search-form" method="post" enctype="multipart/form-data">
                        <div class="d-flex align-items-center mx-50 row pt-0 pb-0">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_status">Status</label>
                                    <select class="form-control select2" id="search_status" name="search_status">
                                        <option value="all">All</option>
                                        <?php
                                        $bookstype = $manageObj->showliststatus();
                                        foreach ($bookstype as $booktype) {
                                        ?>
                                            <option value="<?php echo $booktype['id']; ?>"><?php echo $booktype['name']; ?></option>
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
                                        ?>
                                            <option value="<?php echo $agent['id']; ?>"><?php echo $agent['name']; ?></option>
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
                                        ?>
                                            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_car">ชื่อรถ</label>
                                    <select class="form-control select2" id="search_car" name="search_car">
                                        <option value="all">All</option>
                                        <?php
                                        $cars = $manageObj->show_cars();
                                        foreach ($cars as $car) {
                                        ?>
                                            <option value="<?php echo $car['id']; ?>" data-name="<?php echo $car['name']; ?>"><?php echo $car['name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="search_driver">ชื่อคนขับ</label>
                                    <select class="form-control select2" id="search_driver" name="search_driver">
                                        <option value="all">All</option>
                                        <?php
                                        $drivers = $manageObj->show_drivers();
                                        foreach ($drivers as $driver) {
                                        ?>
                                            <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="search_travel_date">วันที่เที่ยว (Travel Date)</label></br>
                                    <input type="text" class="form-control date-picker" id="search_travel_date" name="search_travel_date" value="<?php echo $tomorrow; ?>" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="refcode">Booking No #</label>
                                    <input type="text" class="form-control" id="refcode" name="refcode" value="" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="voucher_no">Voucher No #</label>
                                    <input type="text" class="form-control" id="voucher_no" name="voucher_no" value="" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="name">Customer Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="hotel">Hotel</label>
                                    <input type="text" class="form-control" id="hotel" name="hotel" value="" />
                                </div>
                            </div>
                            <input type="hidden" id="pickup_retrun" name="search_retrun" value="1">
                            <div class="col-md-4 col-12 mb-1">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- filter end -->

                <div id="div-manage-list">
                    <div class="bs-stepper horizontal-wizard-example">
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#preview-pickup">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">1</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">ใบจัดรถ Pickup</span>
                                        <span class="bs-stepper-subtitle">จัดการรถ</span>
                                    </span>
                                </button>
                            </div>
                            <div class="step" data-target="#preview-dropoff">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">2</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">ใบจัดรถ Drop off</span>
                                        <span class="bs-stepper-subtitle">Preview เอกสารการจัดรถ</span>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="bs-stepper-content p-0">
                            <!-- Management Car (Pickup) -->
                            <div id="preview-pickup" class="content">
                                <div class="card-body pt-0 p-50">
                                    <a href="./?pages=order-driver/print&action=print&search_travel_date=<?php echo $tomorrow; ?>&type=pickup" target="_blank"><button class="btn btn-info" id="print-btn">Print</button></a>
                                    <button type="button" class="btn btn-info waves-effect waves-float waves-light btn-page-block-spinner" onclick="download_image(1);">Image</button>
                                </div>
                                <div id="div-driver-job-pickup" style="background-color: #FFF;">
                                    <!-- Header starts -->
                                    <div class="card-body pb-0 pt-0">
                                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing">
                                            <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
                                            <span style="color: #000;">
                                                โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                                                Email : Fantasticsimilantravel11@gmail.com
                                            </span>
                                        </div>
                                        <div class="text-center card-text">
                                            <h4 class="font-weight-bolder">ใบจัดรถ (Pickup)</h4>
                                            <div class="badge badge-pill badge-light-danger">
                                                <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($tomorrow)); ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    </br>
                                    <!-- Header ends -->
                                    <!-- Body starts -->
                                    <?php
                                    if (!empty($all_manages)) {
                                        foreach ($all_manages as $manages) {
                                    ?>
                                            <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                                <div class="col-4 text-left text-bold h4"></div>
                                                <div class="col-4 text-center"><span class="h4 badge-light-purple"><?php echo $manages['car_name']; ?></span></div>
                                                <div class="col-4 text-right mb-50"></div>
                                            </div>

                                            <table class="table table-striped text-uppercase table-vouchure-t2">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th colspan="3">คนขับ : <?php echo $manages['driver_name']; ?></th>
                                                        <th colspan="4">ป้ายทะเบียน : <?php echo $manages['license']; ?></th>
                                                        <th colspan="5">โทรศัพท์ : <?php echo $manages['telephone']; ?></th>
                                                    </tr>
                                                    <tr>
                                                        <th width="5%">เวลารับ</th>
                                                        <th width="15%">โปรแกรม</th>
                                                        <th width="10%">เอเยนต์</th>
                                                        <th width="10%" class="text-center">V/C</th>
                                                        <th width="15%">โรงแรม</th>
                                                        <th width="6%">ห้อง</th>
                                                        <th width="15%">ชื่อลูกค้า</th>
                                                        <th class="cell-fit text-center">Tourist</th>
                                                        <th width="15%">Remark</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                $total_tourist = 0;
                                                $bomange_arr = array();
                                                $all_bookings = $manageObj->fetch_all_booking('manage', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $tomorrow, $manages['id']);
                                                if ($all_bookings) { ?>
                                                    <tbody>
                                                        <?php
                                                        foreach ($all_bookings as $bookings) {
                                                            if (in_array($bookings['bomange_id'], $bomange_arr) == false) {
                                                                $bomange_arr[] = $bookings['bomange_id'];
                                                                $text_hotel = '';
                                                                $text_zone = '';
                                                                if ($bookings['category_transfer'] == 1) {
                                                                    if (!empty($bookings['zonep_name'])) {
                                                                        $text_zone = $bookings['zonep_name'] != $bookings['zoned_name'] ? $bookings['zonep_name'] . '<br>(D: ' . $bookings['zoned_name'] . ')' : $bookings['zonep_name'];
                                                                    }
                                                                    if (!empty($bookings['hotelp_name'])) {
                                                                        $text_hotel = $bookings['hotelp_name'] != $bookings['hoteld_name'] ? $bookings['hotelp_name'] . '<br>(D: ' . $bookings['hoteld_name'] . ')' : $bookings['hotelp_name'];
                                                                    } else {
                                                                        $text_hotel = $bookings['outside_pickup'] != $bookings['outside_dropoff'] ? $bookings['outside_pickup'] . '<br>(D: ' . $bookings['outside_dropoff'] . ')' : $bookings['outside_pickup'];
                                                                    }
                                                                } else {
                                                                    $text_hotel = 'เดินทางมาเอง';
                                                                    $text_zone = 'เดินทางมาเอง';
                                                                }
                                                                $tourist_all = $manageObj->get_values('tourist', 'booking_order_transfer', 'booking_transfer_id = ' . $bookings['bt_id'] . ' AND order_id != ' . $manages['id'], 1);
                                                                $tourist_all = !empty($tourist_all) ? array_column($tourist_all, 'tourist') : []; // Extracts all 'price' values
                                                                $total_tourist += $bookings['tourist'];
                                                        ?>
                                                                <tr>
                                                                    <td><?php echo $bookings['start_pickup'] != '00:00' ? date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])) : ''; ?></td>
                                                                    <td><?php echo $bookings['product_name']; ?></td>
                                                                    <td><?php echo !empty($bookings['agent_name']) ? $bookings['agent_name'] : '-'; ?></td>
                                                                    <td class="text-center"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                                                                    <td><?php echo $text_hotel . ' (' . $text_zone . ')'; ?></td>
                                                                    <td><?php echo $bookings['room_no']; ?></td>
                                                                    <td><?php echo $bookings['cus_name']; ?></td>
                                                                    <td class="text-center"><?php echo $bookings['tourist']; ?></td>
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
                                                <?php } ?>
                                            </table>

                                            <div class="text-center mt-2 pb-5">
                                                <h4>
                                                    <div class="badge badge-pill badge-light-warning">
                                                        <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b>
                                                    </div>
                                                </h4>
                                            </div>

                                    <?php }
                                    } ?>
                                    <input type="hidden" id="name_img_pickup" name="name_img_pickup" value="<?php echo 'ใบจัดรถ pickup - ' . date('j F Y', strtotime($tomorrow)); ?>">
                                    <!-- Body ends -->
                                </div>
                            </div>

                            <!-- Preview (Dropoff) Job Driver -->
                            <div id="preview-dropoff" class="content">
                                <div class="card-body pt-0 p-50">
                                    <a href='./?pages=order-driver/print&action=print&search_travel_date=<?php echo $tomorrow; ?>&type=dropoff' target="_blank"><button class="btn btn-info" id="print-btn">Print</button></a>
                                    <button type="button" class="btn btn-info waves-effect waves-float waves-light btn-page-block-spinner" onclick="download_image(2);">Image</button>
                                </div>
                                <div id="div-driver-job-dropoff" style="background-color: #FFF;">
                                    <!-- Header starts -->
                                    <div class="card-body pb-0 pt-0">
                                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing">
                                            <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
                                            <span style="color: #000;">
                                                โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                                                Email : Fantasticsimilantravel11@gmail.com
                                            </span>
                                        </div>
                                        <div class="text-center card-text">
                                            <h4 class="font-weight-bolder">ใบจัดรถ (Dropoff)</h4>
                                            <div class="badge badge-pill badge-light-danger">
                                                <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($tomorrow)); ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    </br>
                                    <!-- Header ends -->
                                    <!-- Body starts -->
                                    <?php
                                    $all_programed = $manageObj->fetch_all_booking('dropoff', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $tomorrow, 0);
                                    if (!empty($all_programed)) {
                                        $programe_array = array();
                                        foreach ($all_programed as $programed) {
                                            if (in_array($programed['product_id'], $programe_array) == false) {
                                                $programe_array[] = $programed['product_id']; ?>
                                                <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                                    <div class="col-4 text-left text-bold h4"></div>
                                                    <div class="col-4 text-center text-bold h4"><?php echo $programed['product_name']; ?></div>
                                                    <div class="col-4 text-right mb-50"></div>
                                                </div>

                                                <table class="table table-striped text-uppercase table-vouchure-t2">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th width="5%">เวลารับ</th>
                                                            <!-- <th width="15%">โปรแกรม</th> -->
                                                            <th width="5%">รถ</th>
                                                            <th width="15%">เอเยนต์</th>
                                                            <th width="10%" class="text-center">V/C</th>
                                                            <th width="20%">โรงแรม</th>
                                                            <th width="6%">ห้อง</th>
                                                            <th width="20%">ชื่อลูกค้า</th>
                                                            <th width="1%" class="text-center">A</th>
                                                            <th width="1%" class="text-center">C</th>
                                                            <th width="1%" class="text-center">Inf</th>
                                                            <th width="1%" class="text-center">FOC</th>
                                                            <th width="15%">Remark</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $bookings_arr = array();
                                                        $total_tourist = 0;
                                                        $total_adult = 0;
                                                        $total_child = 0;
                                                        $total_infant = 0;
                                                        $total_foc = 0;
                                                        $all_bookings = $manageObj->fetch_all_booking('dropoff', $search_status, $search_agent, $programed['product_id'], $refcode, $search_voucher_no, $name, $hotel, $tomorrow, 0);
                                                        foreach ($all_bookings as $bookings) {
                                                            if (in_array($bookings['id'], $bookings_arr) == false && ($bookings['outside_pickup'] != $bookings['outside_dropoff'])) {
                                                                $bookings_arr[] = $bookings['id'];
                                                                $total_adult += $bookings['adult'];
                                                                $total_child += $bookings['child'];
                                                                $total_infant += $bookings['infant'];
                                                                $total_foc += $bookings['foc'];
                                                                $total_tourist += $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                                                                $cars = $manageObj->get_values(
                                                                    'cars.name as name',
                                                                    'booking_order_transfer 
                                                                    LEFT JOIN order_transfer ON order_transfer.id = booking_order_transfer.order_id 
                                                                    LEFT JOIN cars ON order_transfer.car_id = cars.id',
                                                                    'booking_order_transfer.booking_transfer_id = ' . $bookings['bt_id'],
                                                                    1
                                                                );
                                                        ?>
                                                                <tr>
                                                                    <td><?php echo $bookings['start_pickup'] != '00:00' ? date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])) : ''; ?></td>
                                                                    <td><?php if (!empty($cars)) {
                                                                            foreach ($cars as $car) {
                                                                                echo '<div class="badge badge-light-success">' . $car['name'] . '</div>';
                                                                            }
                                                                        } ?></td>
                                                                    <td><?php echo $bookings['agent_name']; ?></td>
                                                                    <td class="text-center"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        echo $bookings['outside_dropoff'] . ' (' . $bookings['zoned_name'] . ')';
                                                                        echo $bookings['pickup_type'] == 3 ? '</br><small class="text-warning">เอารถขากลับ</small>' : '';
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo $bookings['room_no']; ?></td>
                                                                    <td><?php echo $bookings['cus_name']; ?></td>
                                                                    <td class="text-center"><?php echo !empty($bookings['adult']) ? $bookings['adult'] : 0; ?></td>
                                                                    <td class="text-center"><?php echo !empty($bookings['child']) ? $bookings['child'] : 0; ?></td>
                                                                    <td class="text-center"><?php echo !empty($bookings['infant']) ? $bookings['infant'] : 0; ?></td>
                                                                    <td class="text-center"><?php echo !empty($bookings['foc']) ? $bookings['foc'] : 0; ?></td>
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

                                                <div class="text-center mt-2 pb-5">
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
                                        }
                                    } ?>
                                    <input type="hidden" id="name_img_dropoff" name="name_img_dropoff" value="<?php echo 'ใบจัดรถ dropoff - ' . date('j F Y', strtotime($tomorrow)); ?>">

                                    <!-- Body ends -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!-- Sortable lists section end -->

        </div>
    </div>