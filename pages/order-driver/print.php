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

    $all_manages = $manageObj->fetch_all_manage($search_car, $search_driver, $search_travel_date, 0);
?>
    <div id="div-driver-job-image" style="background-color: #FFF;">
        <!-- Start Pickup -->
        <!-- ---------------------------------------------- -->
        <?php
        if ($type == 'pickup') {
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
            <?php
            if (!empty($all_manages)) {
                foreach ($all_manages as $manages) {
            ?>
                    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
                        <div class="col-4 text-left text-bold h4"></div>
                        <div class="col-4 text-center text-bold h1"><?php echo $manages['car_name']; ?></div>
                        <div class="col-4 text-right mb-50"></div>
                    </div>

                    <div class="table-responsive">
                        <table class="tableprint">
                            <thead class="">
                                <tr>
                                    <th colspan="3" style="border-bottom: 1px solid #fff;">คนขับ : <?php echo $manages['driver_name']; ?></th>
                                    <th colspan="4" style="border-bottom: 1px solid #fff;">ป้ายทะเบียน : <?php echo $manages['license']; ?></th>
                                    <th colspan="5" style="border-bottom: 1px solid #fff;">โทรศัพท์ : <?php echo $manages['telephone']; ?></th>
                                </tr>
                                <tr>
                                    <th width="5%">เวลารับ</th>
                                    <th width="10%">โปรแกรม</th>
                                    <th width="10%">เอเยนต์</th>
                                    <th width="10%" class="text-center">V/C</th>
                                    <th width="20%">โรงแรม</th>
                                    <th width="6%">ห้อง</th>
                                    <th width="15%">ชื่อลูกค้า</th>
                                    <th width="4%" class="text-center">Tourist</th>
                                    <th width="15%">Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $total_tourist = 0;
                                $bomange_arr = array();
                                $all_bookings = $manageObj->fetch_all_booking('manage', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, $manages['id']);
                                if ($all_bookings) {
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
                                                <td class="bg-primary bg-lighten-4"><?php echo $bookings['start_pickup'] != '00:00' ? date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])) : ''; ?></td>
                                                <td><?php echo $bookings['product_name']; ?></td>
                                                <td><?php echo !empty($bookings['agent_name']) ? $bookings['agent_name'] : '-'; ?></td>
                                                <td class="text-center"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                                                <td><?php echo $text_hotel . ' (' . $text_zone . ')'; ?></td>
                                                <td><?php echo $bookings['room_no']; ?></td>
                                                <td><?php echo $bookings['cus_name']; ?></td>
                                                <td class="text-center bg-warning bg-lighten-3"><?php echo $bookings['tourist']; ?></td>
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
                                    }
                                }
                                ?>
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
                $programe_array = array();
                foreach ($all_programed as $programed) {
                    if (in_array($programed['product_id'], $programe_array) == false) {
                        $programe_array[] = $programed['product_id']; ?>
                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
                            <div class="col-4 text-left text-bold h4"></div>
                            <div class="col-4 text-center text-bold h4" style="color: #000;"><?php echo $programed['product_name']; ?></div>
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
                                    $all_bookings = $manageObj->fetch_all_booking('dropoff', $search_status, $search_agent, $programed['product_id'], $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, 0);
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