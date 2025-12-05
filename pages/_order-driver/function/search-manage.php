<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
$times = date("H:i:s");


if (isset($_POST['action']) && $_POST['action'] == "search") {
    // get value from ajax
    $search_status = $_POST['search_status'] != "" ? $_POST['search_status'] : 'all';
    $search_agent = $_POST['search_agent'] != "" ? $_POST['search_agent'] : 'all';
    $search_product = $_POST['search_product'] != "" ? $_POST['search_product'] : 'all';
    $search_car = !empty($_POST['search_car']) ? $_POST['search_car'] : 'all';
    $search_driver = !empty($_POST['search_driver']) ? $_POST['search_driver'] : 'all';
    $search_travel_date = !empty($_POST['search_travel_date']) ? $_POST['search_travel_date'] : $tomorrow;
    $refcode = $_POST['refcode'] != "" ? $_POST['refcode'] : '';
    $search_voucher_no = $_POST['voucher_no'] != "" ? $_POST['voucher_no'] : '';
    $name = $_POST['name'] != "" ? $_POST['name'] : '';
    $hotel = $_POST['hotel'] != "" ? $_POST['hotel'] : '';

    $href = "./?pages=order-driver/print";
    $href .= "&search_travel_date=" . $search_travel_date;
    $href .= "&search_car=" . $search_car;
    $href .= "&search_status=" . $search_status;
    $href .= "&search_agent=" . $search_agent;
    $href .= "&search_product=" . $search_product;
    $href .= "&search_voucher_no=" . $search_voucher_no;
    $href .= "&refcode=" . $refcode;
    $href .= "&name=" . $name;
    $href .= "&hotel=" . $hotel;
    $href .= "&action=print";

    // $all_manages = $manageObj->fetch_all_manage($search_car, $search_driver, $search_travel_date, 0);

    $bo_arr = array();
    $bomange_arr = array();
    $categorys_array = array();
    $cars_arr = array();
    $extra_arr = array();
    $bpr_arr = array();
    $manages_arr = array();
    $programe_arr = array();
    $all_programed = $manageObj->fetch_all_booking('booking', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, 0);
    foreach ($all_programed as $categorys) {
        if (in_array($categorys['manage_id'], $manages_arr) == false && !empty($categorys['manage_id'])) {
            $manages_arr[] = $categorys['manage_id'];
            $manage_id[] = $categorys['manage_id'];
            $car_name[] = $categorys['car_name'];
            $driver_name[] = $categorys['driver_name'];
            $manage_telephone[] = $categorys['manage_telephone'];
            $number_plate[] = $categorys['number_plate'];
            $seat[] = $categorys['seat'];
            $license[] = $categorys['license'];
        }

        if (in_array($categorys['product_id'], $programe_arr) == false && !empty($categorys['manage_id'])) {
            $programe_arr[] = $categorys['product_id'];
            $product_id[] = $categorys['product_id'];
            $product_name[] = $categorys['product_name'];
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

        if (in_array($categorys['bomange_id'], $bomange_arr) == false && !empty($categorys['manage_id'])) {
            $bomange_arr[] = $categorys['bomange_id'];
            $booking_id[$categorys['manage_id']][] = $categorys['id'];
        }

        if (in_array($categorys['id'], $bo_arr) == false) {
            $bo_arr[] = $categorys['id'];
            $bo_id[] = $categorys['id'];
            $bt_id[$categorys['id']] = $categorys['bt_id'];
            $booking_manage[$categorys['id']] = $categorys['manage_id'];
            $product_book[$categorys['id']] = $categorys['product_name'];
            $booking_prod[$categorys['product_id']][] = $categorys['id'];
            $hotelp_name[$categorys['id']] = $categorys['hotelp_name'];
            $outside_pickup[$categorys['id']] = $categorys['outside_pickup'];
            $zonep_name[$categorys['id']] = $categorys['zonep_name'];
            $hoteld_name[$categorys['id']] = $categorys['hoteld_name'];
            $zoned_name[$categorys['id']] = $categorys['zoned_name'];
            $outside_dropoff[$categorys['id']] = $categorys['outside_dropoff'];
            $start_pickup[$categorys['id']] = $categorys['start_pickup'];
            $end_pickup[$categorys['id']] = $categorys['end_pickup'];
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
            $pickup_type[$categorys['id']] = $categorys['pickup_type'];
        }

        if (in_array($categorys['bec_id'], $extra_arr) == false) {
            $extra_arr[] = $categorys['bec_id'];
            $extra_name[$categorys['id']][] = $categorys['extra_name'];
        }
    }
?>
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
                    <a href='<?php echo $href; ?>&type=pickup' target="_blank"><button class="btn btn-info" id="print-btn">Print</button></a>
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
                                <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($search_travel_date)); ?></h5>
                            </div>
                        </div>
                    </div>
                    </br>
                    <!-- Header ends -->
                    <!-- Body starts -->
                    <?php
                    if (!empty($manage_id)) {
                        for ($i = 0; $i < count($manage_id); $i++) {
                    ?>
                            <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                <div class="col-4 text-left text-bold h4"></div>
                                <div class="col-4 text-center"><span class="h4 badge-light-purple"><?php echo $car_name[$i]; ?></span></div>
                                <div class="col-4 text-right mb-50"></div>
                            </div>

                            <table class="table table-striped text-uppercase table-vouchure-t2">
                                <thead class="bg-light">
                                    <tr>
                                        <th colspan="3">คนขับ : <?php echo $driver_name[$i]; ?></th>
                                        <th colspan="4">ป้ายทะเบียน : <?php echo $license[$i]; ?></th>
                                        <th colspan="5">โทรศัพท์ : <?php echo $manage_telephone[$i]; ?></th>
                                    </tr>
                                    <tr>
                                        <th width="5%">เวลารับ</th>
                                        <th width="15%">โปรแกรม</th>
                                        <th width="10%">เอเยนต์</th>
                                        <th width="10%" class="text-center">V/C</th>
                                        <th width="15%">โรงแรม</th>
                                        <th width="6%">ห้อง</th>
                                        <th width="15%">ชื่อลูกค้า</th>
                                        <!-- <th class="cell-fit text-center">Tourist</th> -->
                                        <th class="text-center" width="1%">A</th>
                                        <th class="text-center" width="1%">C</th>
                                        <th class="text-center" width="1%">Inf</th>
                                        <th class="text-center" width="1%">FOC</th>
                                        <th width="15%">Remark</th>
                                    </tr>
                                </thead>
                                <?php
                                $total_tourist = 0;
                                $total_adult = 0;
                                $total_child = 0;
                                $total_infant = 0;
                                $total_foc = 0;
                                // $bomange_arr = array();
                                if (!empty($booking_id[$manage_id[$i]])) { ?>
                                    <tbody>
                                        <?php
                                        for ($b = 0; $b < count($booking_id[$manage_id[$i]]); $b++) {
                                            $id = $booking_id[$manage_id[$i]][$b];
                                            // if (in_array($booking_manage[$id], $bomange_arr) == false) {
                                            //     $bomange_arr[] = $booking_manage[$id];

                                            $text_hotel = '';
                                            $text_hotel = (!empty($hotelp_name[$id])) ? $hotelp_name[$id] : $outside_pickup[$id];
                                            $text_hotel .= (!empty($zonep_name[$id])) ? ' (' . $zonep_name[$id] . ')</br>' : '</br>';

                                            $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                                            $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                                            $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                                            $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                                            $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                                            $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;

                                            // $text_hotel = '';
                                            // $text_zone = '';
                                            // if ($bookings['category_transfer'] == 1) {
                                            //     if (!empty($bookings['zonep_name'])) {
                                            //         $text_zone = $bookings['zonep_name'] != $bookings['zoned_name'] ? $bookings['zonep_name'] . '<br>(D: ' . $bookings['zoned_name'] . ')' : $bookings['zonep_name'];
                                            //     }
                                            //     if (!empty($bookings['hotelp_name'])) {
                                            //         $text_hotel = $bookings['hotelp_name'] != $bookings['hoteld_name'] ? $bookings['hotelp_name'] . '<br>(D: ' . $bookings['hoteld_name'] . ')' : $bookings['hotelp_name'];
                                            //     } else {
                                            //         $text_hotel = $bookings['outside_pickup'] != $bookings['outside_dropoff'] ? $bookings['outside_pickup'] . '<br>(D: ' . $bookings['outside_dropoff'] . ')' : $bookings['outside_pickup'];
                                            //     }
                                            // } else {
                                            //     $text_hotel = 'เดินทางมาเอง';
                                            //     $text_zone = 'เดินทางมาเอง';
                                            // }

                                            // $tourist_all = $manageObj->get_values('tourist', 'booking_order_transfer', 'booking_transfer_id = ' . $bookings['bt_id'] . ' AND order_id != ' . $manages['id'], 1);
                                            // $tourist_all = !empty($tourist_all) ? array_column($tourist_all, 'tourist') : []; // Extracts all 'price' values
                                            // $total_tourist += $bookings['tourist'];
                                        ?>
                                            <tr>
                                                <td><?php echo date('H:i', strtotime($start_pickup[$id])) . ' - ' . date('H:i', strtotime($end_pickup[$id])); ?></td>
                                                <td><?php echo $product_book[$id]; ?></td>
                                                <td><?php echo !empty($agent_name[$id]) ? $agent_name[$id] : '-'; ?></td>
                                                <td class="text-center"><?php echo !empty($voucher_no_agent[$id]) ? $voucher_no_agent[$id] : $book_full[$id]; ?></td>
                                                <td><?php echo $text_hotel; ?></td>
                                                <td><?php echo $room_no[$id]; ?></td>
                                                <td><?php echo !empty($telephone[$id]) ? $cus_name[$id] . ' <br>(' . $telephone[$id] . ')' : $cus_name[$id]; ?></td>
                                                <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                                                <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                                                <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                                                <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
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
                                        <?php // }
                                        } ?>
                                    </tbody>
                                <?php } ?>
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

                            <!-- <div class="text-center mt-2 pb-5">
                                                <h4>
                                                    <div class="badge badge-pill badge-light-warning">
                                                        <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b>
                                                    </div>
                                                </h4>
                                            </div> -->

                    <?php }
                    } ?>
                    <input type="hidden" id="name_img_pickup" name="name_img_pickup" value="<?php echo 'ใบจัดรถ pickup - ' . date('j F Y', strtotime($tomorrow)); ?>">
                    <!-- Body ends -->
                </div>
            </div>

            <!-- Preview (Dropoff) Job Driver -->
            <div id="preview-dropoff" class="content">
                <div class="card-body pt-0 p-50">
                    <a href='<?php echo $href; ?>&type=dropoff' target="_blank"><button class="btn btn-info" id="print-btn">Print</button></a>
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
                        $bookings_arr = array();
                        foreach ($all_programed as $programed) {
                            if (in_array($programed['id'], $bookings_arr) == false && ($programed['outside_pickup'] != $programed['outside_dropoff'])) {
                                $bookings_arr[] = $programed['id'];

                                $dropoff_product[] = $programed['product_id'];
                                $dropoff_product_name[] = $programed['product_name'];
                                $dropoff_boid[$programed['product_id']][] = $programed['id'];
                            }
                        }

                        $programe_array = array();
                        // foreach ($all_programed as $programed) {
                        if (!empty($dropoff_product)) {
                            for ($p = 0; $p < count($dropoff_product); $p++) {
                                if (in_array($dropoff_product[$p], $programe_array) == false) {
                                    $programe_array[] = $dropoff_product[$p]; ?>
                                    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                        <div class="col-4 text-left text-bold h4"></div>
                                        <div class="col-4 text-center text-bold h4"><?php echo $dropoff_product_name[$p]; ?></div>
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
                                            // $all_bookings = $manageObj->fetch_all_booking('dropoff', $search_status, $search_agent, $programed['product_id'], $refcode, $search_voucher_no, $name, $hotel, $tomorrow, 0);
                                            // foreach ($all_bookings as $bookings) {
                                            for ($i = 0; $i < count($dropoff_boid[$dropoff_product[$p]]); $i++) {
                                                $id = $dropoff_boid[$dropoff_product[$p]][$i];
                                                if (in_array($id, $bookings_arr) == false) {
                                                    $bookings_arr[] = $id;

                                                    $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                                                    $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                                                    $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                                                    $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                                                    $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                                                    $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;

                                                    $cars = $manageObj->get_values(
                                                        'cars.name as name',
                                                        'booking_order_transfer 
                                                                    LEFT JOIN order_transfer ON order_transfer.id = booking_order_transfer.order_id 
                                                                    LEFT JOIN cars ON order_transfer.car_id = cars.id',
                                                        'booking_order_transfer.booking_transfer_id = ' . $bt_id[$id],
                                                        1
                                                    );
                                            ?>
                                                    <tr>
                                                        <td><?php echo date('H:i', strtotime($start_pickup[$id])) . ' - ' . date('H:i', strtotime($end_pickup[$id])); ?></td>
                                                        <td><?php if (!empty($cars)) {
                                                                foreach ($cars as $car) {
                                                                    echo '<div class="badge badge-light-success">' . $car['name'] . '</div>';
                                                                }
                                                            } ?></td>
                                                        <td><?php echo $agent_name[$id]; ?></td>
                                                        <td class="text-center"><?php echo !empty($voucher_no_agent[$id]) ? $voucher_no_agent[$id] : $book_full[$id]; ?></td>
                                                        <td>
                                                            <?php
                                                            echo $outside_dropoff[$id] . ' (' . $zoned_name[$id] . ')';
                                                            echo $pickup_type[$id] == 3 ? '</br><small class="text-warning">เอารถขากลับ</small>' : '';
                                                            ?>
                                                        </td>
                                                        <td><?php echo $room_no[$id]; ?></td>
                                                        <td><?php echo !empty($telephone[$id]) ? $cus_name[$id] . ' <br>(' . $telephone[$id] . ')' : $cus_name[$id]; ?></td>
                                                        <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                                                        <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                                                        <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                                                        <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
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
                        }
                    } ?>
                    <input type="hidden" id="name_img_dropoff" name="name_img_dropoff" value="<?php echo 'ใบจัดรถ dropoff - ' . date('j F Y', strtotime($tomorrow)); ?>">

                    <!-- Body ends -->
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    return false;
}
