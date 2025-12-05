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
    $manage = !empty($_GET['manage_id']) ? $_GET['manage_id'] : 0;

    # --- get data --- #
    $bomange_arr = array();
    $categorys_array = array();
    $cars_arr = array();
    $extra_arr = array();
    $bpr_arr = array();
    $manages_arr = array();
    $all_bookings = $manageObj->fetch_all_bookingboat('guide', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $hotel, $search_boat, $search_guide, $manage);
    foreach ($all_bookings as $categorys) {
        if (in_array($categorys['manage_id'], $manages_arr) == false && !empty($categorys['manage_id'])) {
            $manages_arr[] = $categorys['manage_id'];
            $manage_id[] = $categorys['manage_id'];
            $boat_name[] = $categorys['boat_name'];
            $guide_name[] = $categorys['guide_name'];
            $counter[] = $categorys['manage_counter'];
            $color_hex[] = $categorys['color_hex'];
            $text_color[] = $categorys['text_color'];
            $color_name_th[] = $categorys['color_name_th'];
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
            $bo_id[$categorys['manage_id']][] = $categorys['id'];
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
            $check_in[$categorys['id']] = $categorys['check_in'];
            $agent_name[$categorys['id']] = $categorys['agent_name'];
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
    if (!empty($manage_id)) {
        for ($m = 0; $m < count($manage_id); $m++) {
    ?>
            <div class="d-flex justify-content-between align-items-center header-actions mx-1 row pt-1">
                <div class="col-4 text-left text-bold h4"></div>
                <div class="col-4 text-center"><span class="h4 badge-light-purple"><?php echo $boat_name[$m]; ?></span></div>
                <div class="col-4 text-right mb-50"></div>
            </div>

            <div class="table-responsive" id="order-guide-search-table">
                <table>
                    <thead>
                        <tr>
                            <td colspan="5">ไกด์ : <?php echo $guide_name[$m]; ?></td>
                            <td colspan="6">เคาน์เตอร์ : <?php echo $counter[$m]; ?></td>
                            <td colspan="5" style="background-color: <?php echo $color_hex[$m]; ?>; <?php echo $text_color[$m] != '' ? 'color: ' . $text_color[$m] . ';' : ''; ?>">
                                สี : <?php echo $color_name_th[$m]; ?>
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
                            <th class="text-center" width="1%">A</th>
                            <th class="text-center" width="1%">C</th>
                            <th class="text-center" width="1%">Inf</th>
                            <th class="text-center" width="1%">FOC</th>
                            <th width="5%">COT</th>
                            <th width="8%">Remark</th>
                    </thead>
                    <tbody>
                        <?php
                        $total_tourist = 0;
                        $total_adult = 0;
                        $total_child = 0;
                        $total_infant = 0;
                        $total_foc = 0;
                        $bomange_arr = array();
                        $booking_id_arr = array();
                        if (!empty($bo_id[$manage_id[$m]])) {
                            for ($i = 0; $i < count($bo_id[$manage_id[$m]]); $i++) {
                                if (in_array($bo_id[$manage_id[$m]][$i], $booking_id_arr) == false) {
                                    $booking_id_arr[] = $bo_id[$manage_id[$m]][$i];
                                    $id = $bo_id[$manage_id[$m]][$i];

                                    $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                                    $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                                    $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                                    $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                                    $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                                    $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;

                                    $text_hotel = '';
                                    $text_hotel = (!empty($hotelp_name[$id])) ? '<b>Pickup : </b>' . $hotelp_name[$id] : '<b>Pickup : </b>' . $outside_pickup[$id];
                                    $text_hotel .= (!empty($zonep_name[$id])) ? ' (' . $zonep_name[$id] . ')</br>' : '</br>';
                                    $text_hotel .= (!empty($hoteld_name[$id])) ? '<b>Dropoff : </b>' . $hoteld_name[$id] : '<b>Dropoff : </b>' . $outside_dropoff[$id];
                                    $text_hotel .= (!empty($zoned_name[$id])) ? ' (' . $zoned_name[$id] . ')' : '';
                        ?>
                                    <tr>
                                        <td class="text-center"><?php echo (!empty($check_in[$id]) && $check_in[$id] > 0) ? '<i data-feather="check"></i>' : ''; ?></td>
                                        <td><?php echo $i + 1; ?></td>
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
                                        <td><?php echo !empty($telephone[$id]) ? $cus_name[$id] . ' <br>(' . $telephone[$id] . ')' : $cus_name[$id]; ?></td>
                                        <td><?php echo !empty($voucher_no_agent[$id]) ? $voucher_no_agent[$id] : $book_full[$id]; ?></td>
                                        <td style="padding: 5px;"><?php echo $text_hotel; ?></td>
                                        <td class="cell-fit"><?php echo $room_no[$id]; ?></td>
                                        <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                                        <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                                        <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                                        <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                                        <td class="cell-fit text-nowrap"><b class="text-warning"><?php echo !empty($cot[$id]) ? number_format($cot[$id]) : ''; ?></b></td>
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
