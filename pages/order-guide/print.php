<?php
require_once 'controllers/Order.php';

$manageObj = new Order();

if (isset($_GET['action']) && $_GET['action'] == "print") {
    // get value from ajax
    $get_date = !empty($_GET['date_travel_form']) ? $_GET['date_travel_form'] : $tomorrow; // $tomorrow->format("Y-m-d")
    $search_boat = !empty($_GET['search_boat']) ? $_GET['search_boat'] : 'all';
    $search_status = $_GET['search_status'] != "" ? $_GET['search_status'] : 'all';
    $search_agent = $_GET['search_agent'] != "" ? $_GET['search_agent'] : 'all';
    $search_product = $_GET['search_product'] != "" ? $_GET['search_product'] : 'all';
    $search_guide = $_GET['search_guide'] != "" ? $_GET['search_guide'] : 'all';
    $search_voucher_no = $_GET['voucher_no'] != "" ? $_GET['voucher_no'] : '';
    $refcode = $_GET['refcode'] != "" ? $_GET['refcode'] : '';
    $name = $_GET['name'] != "" ? $_GET['name'] : '';
    $hotel = $_GET['hotel'] != "" ? $_GET['hotel'] : '';
    $manage_id = !empty($_GET['manage_id']) ? $_GET['manage_id'] : 0;

    # --- get data --- #
    $all_manages = $manageObj->fetch_all_manageboat($get_date, $search_boat, $search_guide, $manage_id);

    $categorys_array = array();
    $all_bookings = $manageObj->fetch_all_bookingboat('all', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $hotel, 0);
    foreach ($all_bookings as $categorys) {
        $categorys_array[] = $categorys['id'];
        $category_name[$categorys['id']][] = $categorys['category_name'];
    }

    $name_img = 'ใบงาน [' . date('j F Y', strtotime($get_date)) . ']';
?>
    <!-- Header ends -->
    <div class="card-body pb-0 pt-0">
        <div class="row">
            <span class="col-6 brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
            <span class="col-6 text-right" style="color: #000;">
                โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                Email : Fantasticsimilantravel11@gmail.com
            </span>
        </div>
        <div class="text-center card-text">
            <h4 class="font-weight-bolder">ใบไกด์ - Daily Guide Report</h4>
            <h5 class="font-weight-bolder"><?php echo date('j F Y', strtotime($get_date)); ?></h5>
        </div>
    </div>
    <?php
    if ($all_manages) {
        foreach ($all_manages as $key => $manages) {
    ?>
            <div class="d-flex justify-content-between align-items-center header-actions mx-1 row pt-1">
                <div class="col-4 text-left text-bold h4"></div>
                <div class="col-4 text-center"><span class="h4 badge-light-purple"><?php echo $manages['boat_name']; ?></span></div>
                <div class="col-4 text-right mb-50"></div>
            </div>

            <div class="table-responsive" id="order-guide-search-table">
                <table>
                    <thead>
                        <tr>
                            <td colspan="5">ไกด์ : <?php echo $manages['guide_name']; ?></td>
                            <td colspan="6">เคาน์เตอร์ : <?php echo $manages['counter']; ?></td>
                            <td colspan="5" style="background-color: <?php echo $manages['color_hex']; ?>; <?php echo $manages['text_color'] != '' ? 'color: ' . $manages['text_color'] . ';' : ''; ?>">
                                สี : <?php echo $manages['color_name_th']; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center" width="1%"></th>
                            <th class="text-center" width="1%"></th>
                            <th width="5%">เวลารับ</th>
                            <th width="5%">Driver</th>
                            <th width="12%">เอเยนต์</th>
                            <th width="12%">ชื่อลูกค้า</th>
                            <th width="5%">V/C</th>
                            <th width="26%">โรงแรม</th>
                            <th width="5%">ห้อง</th>
                            <th class="text-center" width="1%">รวม</th>
                            <th class="text-center" width="1%">A</th>
                            <th class="text-center" width="1%">C</th>
                            <th class="text-center" width="1%">Inf</th>
                            <th class="text-center" width="1%">FOC</th>
                            <!-- <th class="text-center">รวม</th> -->
                            <th width="5%">COT</th>
                            <th width="8%">Remark</th>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $total_tourist = 0;
                        $total_adult = 0;
                        $total_child = 0;
                        $total_infant = 0;
                        $total_foc = 0;
                        $bomange_arr = array();
                        $all_bookings = $manageObj->fetch_all_bookingboat('manage', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $hotel, $manages['id']);
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

                                $check_in = $manageObj->get_values('id', 'check_in', 'booking_id = ' . $bookings['id'] . ' AND type = 2', 0);
                        ?>
                                <tr>
                                    <td class="text-center"><?php echo (!empty($check_in['id']) && $check_in['id'] > 0) ? '<i data-feather="check"></i>' : ''; ?></td>
                                    <td><?php echo $no++; ?></td>
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
                </br>
            </div>
            <div class="pagebreak"></div>
        <?php } ?>
        <input type="hidden" id="name_img" name="name_img" value="<?php echo $name_img; ?>">
<?php
    }
} else {
    echo FALSE;
}
