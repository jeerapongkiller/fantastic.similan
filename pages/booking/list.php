<?php
require_once 'controllers/Booking.php';

$bookObj = new Booking();
$times = date("H:i:s");
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
$day3 = date("Y-m-d", strtotime(" +2 day"));
$day4 = date("Y-m-d", strtotime(" +3 day"));
$day5 = date("Y-m-d", strtotime(" +4 day"));
$day6 = date("Y-m-d", strtotime(" +5 day"));
$day7 = date("Y-m-d", strtotime(" +6 day"));
?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Booking</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">Booking List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                <div class="form-group breadcrumb-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#modal-form-booking" onclick="search_program();"><i data-feather='plus'></i> New Booking</button>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- bookings list start -->
            <section class="app-booking-list">
                <!-- bookings filter start -->
                <div class="card">
                    <h5 class="card-header">Search Filter</h5>
                    <form id="booking-search-form" name="booking-search-form" method="post" enctype="multipart/form-data">
                        <div class="d-flex align-items-center mx-50 row pt-0 pb-2">
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="search_status">Status</label>
                                    <select class="form-control select2" id="search_status" name="search_status">
                                        <option value="all">All</option>
                                        <?php
                                        $bookstype = $bookObj->show_booking_status();
                                        foreach ($bookstype as $booktype) {
                                        ?>
                                            <option value="<?php echo $booktype['id']; ?>"><?php echo $booktype['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="search_payment">Payments</label>
                                    <select class="form-control select2" id="search_payment" name="search_payment">
                                        <option value="all">All</option>
                                        <?php
                                        $payments = $bookObj->show_booking_payment();
                                        foreach ($payments as $payment) {
                                        ?>
                                            <option value="<?php echo $payment['id']; ?>"><?php echo $payment['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="search_agent">Agent</label>
                                    <select class="form-control select2" id="search_agent" name="search_agent">
                                        <option value="all">All</option>
                                        <?php
                                        $agents = $bookObj->show_agent();
                                        foreach ($agents as $agent) {
                                        ?>
                                            <option value="<?php echo $agent['id']; ?>"><?php echo $agent['name']; ?></option>
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
                                        $products = $bookObj->show_product();
                                        foreach ($products as $product) {
                                        ?>
                                            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="search_travel">Travel Date</label>
                                    <input type="text" class="form-control" id="search_travel" name="search_travel" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="refcode">Booking No #</label>
                                    <input type="text" class="form-control" id="refcode" name="refcode" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="voucher_no">Voucher No #</label>
                                    <input type="text" class="form-control" id="voucher_no" name="voucher_no" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="name">Customer Name</label>
                                    <input type="text" class="form-control" id="name" name="name" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <button type="submit" class="btn btn-primary" name="submit" value="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- bookings filter end -->

                <!-- report booking start -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="today-tab" data-toggle="tab" href="#today" aria-controls="today" role="tab" aria-selected="true">Today</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tomorrow-tab" data-toggle="tab" href="#tomorrow" aria-controls="tomorrow" role="tab" aria-selected="false">Tomorrow</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="day3-tab" data-toggle="tab" href="#day3" aria-controls="day3" role="tab" aria-selected="false"><?php echo date('j F', strtotime($day3)); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="day4-tab" data-toggle="tab" href="#day4" aria-controls="day4" role="tab" aria-selected="false"><?php echo date('j F', strtotime($day4)); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="day5-tab" data-toggle="tab" href="#day5" aria-controls="day5" role="tab" aria-selected="false"><?php echo date('j F', strtotime($day5)); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="day6-tab" data-toggle="tab" href="#day6" aria-controls="day6" role="tab" aria-selected="false"><?php echo date('j F', strtotime($day6)); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="day7-tab" data-toggle="tab" href="#day7" aria-controls="day7" role="tab" aria-selected="false"><?php echo date('j F', strtotime($day7)); ?></a>
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
                                    <div class="tab-pane" id="day3" aria-labelledby="day3-tab" role="tabpanel">

                                    </div>
                                    <div class="tab-pane" id="day4" aria-labelledby="day4-tab" role="tabpanel">

                                    </div>
                                    <div class="tab-pane" id="day5" aria-labelledby="day5-tab" role="tabpanel">

                                    </div>
                                    <div class="tab-pane" id="day6" aria-labelledby="day6-tab" role="tabpanel">

                                    </div>
                                    <div class="tab-pane" id="day7" aria-labelledby="day7-tab" role="tabpanel">

                                    </div>
                                    <div class="tab-pane" id="custom" aria-labelledby="custom-tab" role="tabpanel">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- report booking end -->

                <!-- list section start -->
                <div id="booking-search-table">
                    <?php
                    # --- get data --- #
                    $first_book = array();
                    $first_bpr = array();
                    $first_pay = array();
                    $first_ext = array();
                    $total_sum = 0;
                    $revenue = 0;
                    $count_confirm = 0;
                    $count_noshow = 0;
                    $count_cancel = 0;
                    $bookings = $bookObj->showlist($_SESSION["supplier"]["id"], 'all', 'all', 'all', 'all', $tomorrow, '', '', '');
                    # --- Check products --- #
                    if (!empty($bookings)) {
                        foreach ($bookings as $booking) {
                            # --- get value booking --- #
                            if (in_array($booking['id'], $first_book) == false) {
                                $first_book[] = $booking['id'];
                                $bo_id[] = !empty($booking['id']) ? $booking['id'] : 0;
                                $bp_id[] = !empty($booking['bp_id']) ? $booking['bp_id'] : 0;
                                $book_full[] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                                $voucher_no[] = !empty(!empty($booking['voucher_no_agent'])) ? $booking['voucher_no_agent'] : '';
                                $discount[] = !empty(!empty($booking['discount'])) ? $booking['discount'] : 0;
                                $travel_date[] = !empty(!empty($booking['travel_date'])) ? $booking['travel_date'] : '0000-00-00';
                                $product_name[] = !empty(!empty($booking['product_name'])) ? $booking['product_name'] : '';
                                $agent_name[] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
                                $cus_name[] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
                                $hotel_pickup[] = !empty($booking['hotel_pickup']) ? $booking['hotel_pickup'] : '';
                                $hotel_dropoff[] = !empty($booking['hotel_dropoff']) ? $booking['hotel_dropoff'] : '';
                                $pickup_name[] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : '';
                                $dropoff_name[] = !empty($booking['dropoff_name']) ? $booking['dropoff_name'] : '';
                                $pickup_type[] = !empty($booking['pickup_type']) ? $booking['pickup_type'] : 0;
                                $room_no[] = !empty($booking['room_no']) ? $booking['room_no'] : '';
                                $start_pickup[] = !empty($booking['start_pickup']) ? !empty($booking['end_pickup']) ? date('H:i', strtotime($booking['start_pickup'])) . '-' . date('H:i', strtotime($booking['end_pickup'])) : date('H:i', strtotime($booking['start_pickup'])) : '';
                                $booker_name[] = !empty($booking['booker_id']) ? $booking['booker_fname'] . ' ' . $booking['booker_lname'] : '';
                                $status_by_name[] = !empty($booking['status_by']) ? $booking['stabyFname'] . ' ' . $booking['stabyLname'] : '';
                                $status[] = !empty($booking['is_deleted']) ? '<span class="badge badge-pill badge-light-danger text-capitalized">Delete</span>' : '<span class="badge badge-pill ' . $booking['booksta_class'] . ' text-capitalized"> ' . $booking['booksta_name'] . ' </span>';
                                $note[] = !empty($booking['note']) ? $booking['note'] : '';
                                $created_at[] = !empty(!empty($booking['created_at'])) ? $booking['created_at'] : '0000-00-00';
                            }
                            # --- get value booking --- #
                            if (in_array($booking['bpr_id'], $first_bpr) == false) {
                                $first_bpr[] = $booking['bpr_id'];
                                $adult[$booking['id']][] = !empty($booking['adult']) ? $booking['adult'] : 0;
                                $child[$booking['id']][] = !empty($booking['child']) ? $booking['child'] : 0;
                            }
                            # --- get value booking extra chang --- #
                            if ((in_array($booking['bec_id'], $first_ext) == false) && !empty($booking['bec_id'])) {
                                $first_ext[] = $booking['bec_id'];
                                $bec_name[$booking['id']][] = !empty($booking['bec_name']) ? $booking['bec_name'] : $booking['extra_name'];
                            }
                            # --- get value booking payment --- #
                            if ((in_array($booking['bopa_id'], $first_pay) == false) && !empty($booking['bopa_id'])) {
                                $first_pay[] = $booking['bopa_id'];
                                $bopay_id[$booking['id']] = !empty($booking['bopay_id']) ? $booking['bopay_id'] : 0;
                                $bopay_name_class[$booking['id']] = !empty($booking['bopay_name_class']) ? $booking['bopay_name_class'] : '';
                                $bopay_paid_name[$booking['id']] = $booking['bopay_id'] == 4 || $booking['bopay_id'] == 5 ? $booking['bopay_name'] . '</br>(' . number_format($booking['total_paid']) . ')' : $booking['bopay_name'];
                            }
                        }
                    }
                    ?>

                    <div class="card">
                        <div class="card-datatable pt-0">
                            <table class="booking-list-table table table-responsive">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="cell-fit">STATUS</th>
                                        <th class="cell-fit">PAYMENT</th>
                                        <th>โปรแกรม</th>
                                        <th>TRAVEL DATE / BOOKING DATE</th>
                                        <th>AGENT NAME</th>
                                        <th>ชื่อลูกค้า</th>
                                        <th>โรงแรม</th>
                                        <th>ห้อง</th>
                                        <th class="text-center">A</th>
                                        <th class="text-center">C</th>
                                        <th>เวลารับ</th>
                                        <th>VOUCHER NO.</th>
                                        <th>Remark</th>
                                        <th>BOKING NO.</th>
                                    </tr>
                                </thead>
                                <?php if ($bookings) { ?>
                                    <tbody>
                                        <?php
                                        for ($i = 0; $i < count($bo_id); $i++) {
                                            $href = 'href="./?pages=booking/edit&id=' . $bo_id[$i] . '" style="color:#6E6B7B" class="btn-page-block-spinner"';
                                        ?>
                                            <tr>
                                                <td><a <?php echo $href; ?>>
                                                        <?php echo $status[$i]; ?>
                                                    </a>
                                                </td>
                                                <td><a <?php echo $href; ?>>
                                                        <?php echo !empty($bopay_id[$bo_id[$i]]) ? '<span class="badge badge-pill ' . $bopay_name_class[$bo_id[$i]] . ' text-capitalized"> ' . $bopay_paid_name[$bo_id[$i]] . ' </span>' : '<span class="badge badge-pill badge-light-primary text-capitalized"> ไม่ได้ระบุ </span>'; ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a <?php echo $href; ?>>
                                                        <?php echo (!empty($bp_id[$i])) ? $product_name[$i] : 'ไม่มีสินค้า'; ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a <?php echo $href; ?>>
                                                        <span class="text-nowrap">
                                                            <?php echo (!empty($bp_id[$i])) ? date('j F Y', strtotime($travel_date[$i])) . ' </br><small>' . date('j F Y', strtotime($created_at[$i])) . '</small>' : 'ไม่มีสินค้า'; ?>
                                                        </span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a <?php echo $href; ?>>
                                                        <?php echo $agent_name[$i]; ?></a>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a <?php echo $href; ?>>
                                                        <?php echo $cus_name[$i]; ?></a>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a <?php echo $href; ?>>
                                                        <?php if ($pickup_type[$i] == 1) {
                                                            echo !empty($pickup_name[$i]) ? $hotel_pickup[$i] . ' (' . $pickup_name[$i] . ')' : $hotel_pickup[$i];
                                                        } elseif ($pickup_type[$i] == 2) {
                                                            echo '-';
                                                        } elseif ($pickup_type[$i] == 3) {
                                                            echo !empty($dropoff_name[$i]) ? $hotel_dropoff[$i] . ' (' . $dropoff_name[$i] . ')' : $hotel_dropoff[$i];
                                                            echo '</br><small class="text-warning">เอารถขากลับ</small>';
                                                        }
                                                        ?></a>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a <?php echo $href; ?>>
                                                        <?php echo $room_no[$i]; ?>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a <?php echo $href; ?>>
                                                        <?php echo !empty($adult[$bo_id[$i]]) ? array_sum($adult[$bo_id[$i]]) : '-'; ?></a>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a <?php echo $href; ?>>
                                                        <?php echo !empty($child[$bo_id[$i]]) ? array_sum($child[$bo_id[$i]]) : '-'; ?></a>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a <?php echo $href; ?>>
                                                        <?php echo $start_pickup[$i]; ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a <?php echo $href; ?>>
                                                        <?php echo $voucher_no[$i]; ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a <?php echo $href; ?>>
                                                        <?php if (!empty($bec_id[$bo_id[$i]])) {
                                                            for ($e = 0; $e < count($bec_name[$bo_id[$i]]); $e++) {
                                                                echo $e == 0  ? $bec_name[$bo_id[$i]][$e] : ' : ' . $bec_name[$bo_id[$i]][$e];
                                                            }
                                                        }
                                                        echo !empty($note[$i]) ? ' / ' . $note[$i] : ''; ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a <?php echo $href; ?>>
                                                        <?php echo $book_full[$i]; ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- list section end -->
            </section>
            <!-- bookings list ends -->

            <!-- modal create booking start -->
            <div class="modal-size-xl d-inline-block">
                <div class="modal fade text-left" id="modal-form-booking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel17">New Booking</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="booking-create-form" name="booking-create-form" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <input type="hidden" id="book_date" name="book_date" value="<?php echo $today; ?>" />
                                    <input type="hidden" id="book_time" name="book_time" value="<?php echo $times; ?>" />
                                    <div class="row">
                                        <div class="form-group col-xl-3 col-md-4 col-12">
                                            <label for="travel_date">Travel Date</label>
                                            <input type="text" class="form-control flatpickr-basic" id="travel_date" name="travel_date" value="<?php echo $tomorrow; ?>" />
                                        </div>
                                        <div class="col-xl-3 col-md-4 col-12">
                                            <div class="form-group" id="frm-agent">
                                                <label for="agent">Agent</label>
                                                <select class="form-control select2" id="agent" name="agent" onchange="search_program();">
                                                    <option value="0">Please Select Agent...</option>
                                                    <option value="outside">กรอกข้อมูลเพิ่มเติม</option>
                                                    <?php
                                                    $agents = $bookObj->show_agent();
                                                    foreach ($agents as $agent) {
                                                    ?>
                                                        <option value="<?php echo $agent['id']; ?>"><?php echo $agent['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group" id="frm-agent-outside" hidden>
                                                <label for="agent_outside">Agent</label>
                                                <div class="input-group input-group-merge mb-2">
                                                    <input type="text" class="form-control" id="agent_outside" name="agent_outside" value="">
                                                    <div class="input-group-append outside-text">
                                                        <span class="input-group-text cursor-pointer"><i data-feather='x'></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-xl-3 col-md-4 col-12">
                                            <label for="product_id">Programe (สินค้าหลัก)</label>
                                            <select class="form-control select2" id="product_id" name="product_id" onchange="search_program();">
                                                <?php
                                                $prods = $bookObj->show_product();
                                                foreach ($prods as $prod) {
                                                ?>
                                                    <option value="<?php echo $prod['id']; ?>"><?php echo $prod['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-xl-3 col-md-4 col-12">
                                            <label for="category_id">Categorys (สินค้ารอง)</label>
                                            <select class="form-control select2" id="category_id" name="category_id[]" onchange="check_category();" multiple>
                                            </select>
                                        </div>
                                    </div>
                                    <!------ start div rates ---->
                                    <div class="row" id="div-rates">
                                    </div>
                                    <input type="hidden" id="rate_total" name="rate_total" value="0">
                                    <!------ end div rates ------>
                                    <div class="row">
                                        <div class="form-group col-xl-2 col-md-4 col-12">
                                            <label class="form-label" for="voucher_no">Voucher</label>
                                            <input type="text" id="voucher_no" name="voucher_no" class="form-control" onchange="check_no_agent(this);" />
                                            <div class="invalid-feedback" id="invalid-voucher-no">หมายเลข Voucher ซ้ำ.</div>
                                        </div>
                                        <div class="form-group col-xl-2 col-md-4 col-12">
                                            <label class="form-label" for="sender">Sender</label>
                                            <input type="text" id="sender" name="sender" class="form-control" />
                                        </div>
                                        <div class="form-group col-xl-2 col-md-4 col-12">
                                            <label for="cus_name">Customer Name</label>
                                            <input type="text" class="form-control" id="cus_name" name="cus_name" value="" />
                                        </div>
                                        <div class="form-group col-xl-2 col-md-4 col-12">
                                            <label for="telephone">Telephone</label>
                                            <input type="text" class="form-control" id="telephone" name="telephone" value="" />
                                        </div>
                                        <div class="form-group col-xl-2 col-md-4 col-12">
                                            <label for="discount">Discount</label>
                                            <input type="text" class="form-control numeral-mask" id="discount" name="discount" value="" />
                                        </div>
                                        <div class="form-group col-xl-2 col-md-4 col-12">
                                            <label for="cot">Cash on tour</label>
                                            <input type="text" class="form-control numeral-mask" id="cot" name="cot" value="" />
                                        </div>
                                        <div class="form-group col-xl-6 col-md-4 col-12">
                                            <label class="form-label" for="bp_note">Remark</label>
                                            <textarea class="form-control" name="bp_note" id="bp_note" rows="1"></textarea>
                                        </div>
                                    </div>
                                    <div class="row" id="div-transfer">
                                        <div class="col-md-3 div-transfer-pickup">
                                            <input type="hidden" id="bt_id" name="bt_id" value="" />
                                            <div class="form-group">
                                                <label for="zone_pickup">Pickup Zone</label>
                                                <select class="form-control select2" id="zone_pickup" name="zone_pickup" onchange="check_time('zone_pickup');">
                                                    <option value="0">Please Select Zone...</option>
                                                    <?php
                                                    $zones = $bookObj->show_zone();
                                                    foreach ($zones as $zone) {
                                                    ?>
                                                        <option value="<?php echo $zone['id']; ?>" data-start-pickup="<?php echo date("H:i", strtotime($zone['start_pickup'])); ?>" data-end-pickup="<?php echo date("H:i", strtotime($zone['end_pickup'])); ?>"><?php echo $zone['name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 div-transfer-pickup">
                                            <div class="form-group" id="frm-hotel-pickup" hidden>
                                                <label for="hotel_pickup">Pickup Hotel</label>
                                                <select class="form-control select2" id="hotel_pickup" name="hotel_pickup" onchange="check_outside('hotel_pickup');">
                                                    <option value="0">Please Select Hotel...</option>
                                                    <option value="outside">กรอกข้อมูลเพิ่มเติม</option>
                                                    <?php
                                                    $hotels = $bookObj->show_hotel();
                                                    foreach ($hotels as $hotel) {
                                                    ?>
                                                        <option value="<?php echo $hotel['id']; ?>"><?php echo $hotel['name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group" id="frm-hotel-outside">
                                                <label for="hotel_outside">Pickup Hotel</label>
                                                <div class="input-group input-group-merge mb-2">
                                                    <input type="text" class="form-control" id="hotel_outside" name="hotel_outside" value="">
                                                    <div class="input-group-append" onclick="check_outside('hotel_outside');">
                                                        <span class="input-group-text cursor-pointer"><i data-feather='x' hidden></i></span> <!--  ปิดการใช้งาน -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <table>
                                                <tr>
                                                    <td width="100%" colspan="3"><label for="start_pickup">Pickup Time</label></td>
                                                </tr>
                                                <tr>
                                                    <td width="45%">
                                                        <input type="text" id="start_pickup" name="start_pickup" class="form-control time-mask text-left" placeholder="HH:MM" value="" />
                                                    </td>
                                                    <td width="10%" align="center"> - </td>
                                                    <td width="45%">
                                                        <input type="text" id="end_pickup" name="end_pickup" class="form-control time-mask text-left" placeholder="HH:MM" value="" />
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label" for="room_no">Room No.</label>
                                            <input type="text" id="room_no" name="room_no" class="form-control" value="" />
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="zone_dropoff">Dropoff Zone</label>
                                                <select class="form-control select2" id="zone_dropoff" name="zone_dropoff" onchange="check_time('zone_dropoff');">
                                                    <option value="0">Please Select Zone...</option>
                                                    <?php
                                                    $zones = $bookObj->show_zone();
                                                    foreach ($zones as $zone) {
                                                    ?>
                                                        <option value="<?php echo $zone['id']; ?>"><?php echo $zone['name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" id="frm-dropoff" hidden>
                                                <label for="hotel_dropoff">Drop Off Hotel</label>
                                                <select class="form-control select2" id="hotel_dropoff" name="hotel_dropoff" onchange="check_outside('hotel_dropoff');">
                                                    <option value="0">Please Select Hotel...</option>
                                                    <option value="outside">กรอกข้อมูลเพิ่มเติม</option>
                                                    <?php
                                                    $hotels = $bookObj->show_hotel();
                                                    foreach ($hotels as $hotel) {
                                                    ?>
                                                        <option value="<?php echo $hotel['id']; ?>"><?php echo $hotel['name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group" id="frm-dropoff-outside">
                                                <label for="dropoff_outside">Drop Off Hotel</label>
                                                <div class="input-group input-group-merge mb-2">
                                                    <input type="text" class="form-control" id="dropoff_outside" name="dropoff_outside" value="">
                                                    <div class="input-group-append" onclick="check_outside('dropoff_outside');">
                                                        <span class="input-group-text cursor-pointer"><i data-feather='x' hidden></i></span> <!--  ปิดการใช้งาน -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="before_arr_cus" name="before_arr_cus" value="">
                                    <div class="row" id="frm-customer"></div>
                                    <!-- Extar Charge -->
                                    <div class="row mt-1">
                                        <div class="col-12">
                                            <a href="javascript:void(0);" onclick="accordion_check('extar');">
                                                <h4>Extra Charge</h4>
                                            </a>
                                            <hr class="mt-0">
                                        </div>
                                    </div>
                                    <div class="row accordion-collapse collapse hidden" id="accordionTwo">
                                        <?php
                                        $extras = $bookObj->show_extra_charge();
                                        foreach ($extras as $extra) {
                                            echo '<input type="hidden" class="small" id="extar_ad' . $extra['id'] . '" value="' . $extra['rate_adult'] . '">';
                                            echo '<input type="hidden" class="small" id="extar_chd' . $extra['id'] . '" value="' . $extra['rate_child'] . '">';
                                            echo '<input type="hidden" class="small" id="extar_inf' . $extra['id'] . '" value="' . $extra['rate_infant'] . '">';
                                            echo '<input type="hidden" class="small" id="extar_total' . $extra['id'] . '" value="' . $extra['rate_total'] . '">';
                                        }
                                        ?>
                                        <div class="col-12">
                                            <div class="extra-charge-repeater">
                                                <div data-repeater-list="extra-charge">
                                                    <div data-repeater-item>
                                                        <input type="hidden" name="bec_id" value="">
                                                        <div id="div-extra-charge">
                                                            <div class="row d-flex align-items-start">
                                                                <div class="col-md-3 col-12">
                                                                    <div class="form-group">
                                                                        <label for="extra_charge">Extra Charge (ค่าใช้จ่ายเพิ่มเติม)</label>
                                                                        <select class="form-control" name="extra_charge" data-extra-repeater="select2" onchange="chang_extra_charge(this);">
                                                                            <option value="0">Please Select Extra Charge...</option>
                                                                            <?php
                                                                            foreach ($extras as $extra) {
                                                                            ?>
                                                                                <option value="<?php echo $extra['id']; ?>"><?php echo $extra['name']; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 col-12">
                                                                    <div class="form-group">
                                                                        <label for="extc_name">Custom Extra Charge (กำหนดเองค่าใช้จ่ายเพิ่มเติม)</label>
                                                                        <input type="text" class="form-control" name="extc_name" aria-describedby="extc_name" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 col-12">
                                                                    <div class="form-group">
                                                                        <label for="extra_type">Extra Charge Type (ค่าใช้จ่ายประเภท)</label>
                                                                        <select class="form-control" name="extra_type" data-extra-repeater="select2" onchange="check_extar_type(this);">
                                                                            <option value="0">Please Select Type...</option>
                                                                            <option value="1">Per Pax</option>
                                                                            <option value="2">Total</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-12" <?php echo $account; ?>>
                                                                    <div class="form-group">
                                                                        <label for="extc_total">Total (รวมทั้งหมด)</label><br>
                                                                        <span name="extc_total" class="text-danger text-bold h5"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1 col-12 mb-50 mt-2">
                                                                    <div class="form-group">
                                                                        <button class="btn btn-outline-danger text-nowrap px-1" data-repeater-delete type="button">
                                                                            <i data-feather="x"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row d-flex align-items-start">
                                                                <div class="col-md-3 col-12" name="div_extar_perpax" hidden>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="extra_adult">Adult (ผู้ใหญ่)</label>
                                                                                    <input type="number" class="form-control" name="extra_adult" oninput="checke_rate_extar();" value="0" />
                                                                                </div>
                                                                            </td>
                                                                            <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                            <td <?php echo $account; ?>>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="extra_rate_adult">Rate Adult (ราคาผู้ใหญ่)</label>
                                                                                    <input type="text" name="extra_rate_adult" class="form-control numeral-mask" value="0" oninput="checke_rate_extar();">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-3 col-12" name="div_extar_perpax" hidden>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="extra_child">Children (เด็ก)</label>
                                                                                    <input type="number" class="form-control" name="extra_child" oninput="checke_rate_extar();" value="0" />
                                                                                </div>
                                                                            </td>
                                                                            <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                            <td <?php echo $account; ?>>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="extra_rate_child">Rate Children (ราคาเด็ก)</label>
                                                                                    <input type="text" name="extra_rate_child" class="form-control numeral-mask" value="0" oninput="checke_rate_extar();">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-3 col-12" name="div_extar_perpax" hidden>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="extra_infant">Infant (ทารก)</label>
                                                                                    <input type="number" class="form-control" name="extra_infant" oninput="checke_rate_extar();" value="0" />
                                                                                </div>
                                                                            </td>
                                                                            <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                            <td <?php echo $account; ?>>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="extra_rate_infant">Rate Infant (ราคาทารก)</label>
                                                                                    <input type="text" name="extra_rate_infant" class="form-control numeral-mask" value="0" oninput="checke_rate_extar();">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-3 col-12" name="div_extar_total" hidden>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="extra_num_private">Private (จำนวน)</label>
                                                                                    <input type="number" class="form-control" name="extra_num_private" oninput="checke_rate_extar();" value="0" />
                                                                                </div>
                                                                            </td>
                                                                            <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                            <td <?php echo $account; ?>>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="extra_rate_private">Rate Private (ราคา/จำนวน)</label>
                                                                                    <input type="text" name="extra_rate_private" class="form-control numeral-mask" value="0" oninput="checke_rate_extar();">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-12">
                                                        <button class="btn btn-outline-primary mr-50" type="button" data-repeater-create>
                                                            <i data-feather="plus" class="mr-25"></i>
                                                            <span>เพิ่มข้อมูลค่าใช้จ่ายเพิ่มเติม</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Accordion หมายเหตุ -->
                                    <div class="row mt-1">
                                        <div class="col-12">
                                            <a href="javascript:void(0);" onclick="accordion_check('note');">
                                                <h4>หมายเหตุ</h4>
                                            </a>
                                            <hr class="mt-0">
                                        </div>
                                    </div>
                                    <div class="row accordion-collapse collapse hidden" id="accordionOne">
                                        <div class="form-group col-md-3">
                                            <div class="form-group">
                                                <label for="book_status">Booking Status</label>
                                                <select class="form-control select2" id="book_status" name="book_status">
                                                    <?php
                                                    $bookstype = $bookObj->show_booking_status();
                                                    foreach ($bookstype as $booktype) {
                                                    ?>
                                                        <option value="<?php echo $booktype['id']; ?>" <?php echo $booktype['id'] == 1 ? 'selected' : ''; ?>><?php echo $booktype['name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label" for="booktype">Booking Type (ประเภท)</label>
                                            <?php
                                            $types = $bookObj->show_booking_type();
                                            foreach ($types as $type) {
                                            ?>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="<?php echo 'book_type' . $type['id']; ?>" name="booking_type" class="custom-control-input customer_type" value="<?php echo $type['id']; ?>" <?php echo $type['id'] == 1 ? 'checked' : ''; ?> onchange="search_program();" />
                                                    <label class="custom-control-label" for="<?php echo 'book_type' . $type['id']; ?>"><?php echo $type['name']; ?></label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label class="form-label">Transfer Type (ประเภท)</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="transfer_join" name="transfer_type" class="custom-control-input" value="1" checked />
                                                <label class="custom-control-label" for="transfer_join">Join</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="transfer_private" name="transfer_type" class="custom-control-input" value="2" />
                                                <label class="custom-control-label" for="transfer_private">Private</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label class="form-label">Transfer</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="pickup_type_1" name="pickup_type" class="custom-control-input" value="1" <?php echo $pickup_type == 1 || $pickup_type == 0 ? 'checked' : ''; ?> onclick="check_transfer();"/>
                                                <label class="custom-control-label" for="pickup_type_1">เอารถรับส่ง</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="pickup_type_2" name="pickup_type" class="custom-control-input" value="2" <?php echo $pickup_type == 2 ? 'checked' : ''; ?> onclick="check_transfer();"/>
                                                <label class="custom-control-label" for="pickup_type_2">เดินทางมาเอง</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="pickup_type_3" name="pickup_type" class="custom-control-input" value="3" <?php echo $pickup_type == 3 ? 'checked' : ''; ?> onclick="check_transfer();"/>
                                                <label class="custom-control-label" for="pickup_type_3">เอารถขากลับ</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <span></span>
                                    <!-- <a href="./?pages=booking/create" type="button" id="btn-more" class="btn btn-flat-secondary waves-effect btn-page-block-spinner">เพิ่มเติม</a> -->
                                    <button type="submit" class="btn btn-primary waves-effect waves-float waves-light btn-page-block-spinner">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- modal create booking ends -->

        </div>
    </div>
</div>