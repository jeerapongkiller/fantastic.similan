<?php
require_once 'controllers/Order.php';

$today = date("Y-m-d");
$tomorrow = new DateTime('tomorrow');
$manageObj = new Order();

if (isset($_GET['action']) && $_GET['action'] == "print" && !empty($_GET['type'])) {
    // get value from ajax
    $type = $_GET['type'];
    $search_status = $_GET['search_status'] != "" ? $_GET['search_status'] : 'all';
    $search_agent = $_GET['search_agent'] != "" ? $_GET['search_agent'] : 'all';
    $search_product = $_GET['search_product'] != "" ? $_GET['search_product'] : 'all';
    $search_car = !empty($_GET['search_car']) ? $_GET['search_car'] : 'all';
    $search_driver = !empty($_GET['search_driver']) ? $_GET['search_driver'] : 'all';
    $search_travel_date = !empty($_GET['search_travel_date']) ? $_GET['search_travel_date'] : $tomorrow;
    $refcode = $_GET['refcode'] != "" ? $_GET['refcode'] : '';
    $search_voucher_no = $_GET['voucher_no'] != "" ? $_GET['voucher_no'] : '';
    $name = $_GET['name'] != "" ? $_GET['name'] : '';
    $hotel = $_GET['hotel'] != "" ? $_GET['hotel'] : '';

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
    <div id="div-driver-job-image" style="background-color: #FFF;">
        <!-- Start Pickup -->
        <!-- ---------------------------------------------- -->
        <?php
        if ($type == 'pickup') {
            if (!empty($manage_id)) {
                for ($i = 0; $i < count($manage_id); $i++) {
        ?>
                    <div class="card-body pb-0">
                        <div class="row">
                            <span class="col-6 brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
                            <span class="col-6 text-right" style="color: #000;">
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

                    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
                        <div class="col-4 text-left text-bold h4"></div>
                        <div class="col-4 text-center text-bold h1"><?php echo $car_name[$i]; ?></div>
                        <div class="col-4 text-right mb-50"></div>
                    </div>

                    <div class="table-responsive">
                        <table class="tableprint">
                            <thead class="">
                                <tr>
                                    <th colspan="3" style="border-bottom: 1px solid #fff;">คนขับ : <?php echo $driver_name[$i]; ?></th>
                                    <th colspan="4" style="border-bottom: 1px solid #fff;">ป้ายทะเบียน : <?php echo $license[$i]; ?></th>
                                    <th colspan="5" style="border-bottom: 1px solid #fff;">โทรศัพท์ : <?php echo $manage_telephone[$i]; ?></th>
                                </tr>
                                <tr>
                                    <th width="5%">เวลารับ</th>
                                    <th width="10%">โปรแกรม</th>
                                    <th width="10%">เอเยนต์</th>
                                    <th width="10%" class="text-center">V/C</th>
                                    <th width="20%">โรงแรม</th>
                                    <th width="6%">ห้อง</th>
                                    <th width="15%">ชื่อลูกค้า</th>
                                    <!-- <th width="4%" class="text-center">Tourist</th> -->
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

                                        $text_hotel = '';
                                        $text_hotel = (!empty($hotelp_name[$id])) ? $hotelp_name[$id] : $outside_pickup[$id];
                                        $text_hotel .= (!empty($zonep_name[$id])) ? ' (' . $zonep_name[$id] . ')</br>' : '</br>';

                                        $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                                        $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                                        $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                                        $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                                        $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                                        $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                                    ?>
                                        <tr>
                                            <td class="bg-primary bg-lighten-4"><?php echo date('H:i', strtotime($start_pickup[$id])) . ' - ' . date('H:i', strtotime($end_pickup[$id])); ?></td>
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
                                    <?php } ?>
                                    <tr>
                                        <td colspan="12" class="p-0" style="border: 0;">
                                            <div class="text-center mt-50">
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
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="12" class="p-0" style="border: 0;">
                                            <div class="card-body invoice-padding py-0 bg-danger">
                                                <p class="pt-50 mb-0 text-white">หมายเหตุ</p>
                                                <p class="pb-50 mt-0 text-white">ถ้าลูกค้าช้า 5-10 นาทีกรุณาติดต่อกลับด่วน ***รบกวนเก็บวอเชอร์ลูกค้าก่อนขึ้นรถด้วยนะคะ (สำคัญมาก)*** Tel.0613851000, 0910343805</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            <?php } ?>
                        </table>

                    </div>
        <?php }
            }
        } ?>
        <!-- ---------------------------------------------- -->
        <!-- End Pickup -->

        <!-- Start dropoff -->
        <!-- ---------------------------------------------- -->
        <?php if ($type == 'dropoff') { ?>
            <div class="card-body pb-0">
                <div class="row">
                    <span class="col-6 brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
                    <span class="col-6 text-right" style="color: #000;">
                        โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                        Email : Fantasticsimilantravel11@gmail.com
                    </span>
                </div>
                <div class="text-center card-text">
                    <h4 class="font-weight-bolder">ใบจัดรถ (Dropoff)</h4>
                    <div class="badge badge-pill badge-light-danger">
                        <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($search_travel_date)); ?></h5>
                    </div>
                </div>
            </div>

            <?php
            $all_programed = $manageObj->fetch_all_booking('dropoff', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, 0);
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
                if (!empty($dropoff_product)) {
                    for ($p = 0; $p < count($dropoff_product); $p++) {
                        if (in_array($dropoff_product[$p], $programe_array) == false) {
                            $programe_array[] = $dropoff_product[$p]; ?>
                            <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
                                <div class="col-4 text-left text-bold h4"></div>
                                <div class="col-4 text-center text-bold h4" style="color: #000;"><?php echo $dropoff_product_name[$p]; ?></div>
                                <div class="col-4 text-right mb-50"></div>
                            </div>

                            <div class="table-responsive">
                                <table class="tableprint">
                                    <thead class="">
                                        <tr>
                                            <th width="5%">เวลารับ</th>
                                            <th width="5%">รถ</th>
                                            <th width="10%">เอเยนต์</th>
                                            <th width="10%" class="text-center">V/C</th>
                                            <th width="15%">โรงแรม</th>
                                            <th width="6%">ห้อง</th>
                                            <th width="15%">ชื่อลูกค้า</th>
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
                    }
                }
            } ?>
                            </div>
                        <?php } ?>
                        <!-- ---------------------------------------------- -->
                        <!-- End dropoff -->

    </div>

    <input type="hidden" id="name_img" name="name_img" value="<?php echo 'ใบจัดรถ - ' . date('j F Y', strtotime($search_travel_date)); ?>">
<?php
}
?>