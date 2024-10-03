<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$orderObj = new Order();
$today = date("Y-m-d");

if (isset($_POST['action']) && $_POST['action'] == "search") {
    // get value from ajax
    $search_period = $_POST['search_period'] != "" ? $_POST['search_period'] : 'all';
    $search_product = $_POST['search_product'] != "" ? $_POST['search_product'] : 'all';
    $date_travel_form = $_POST['date_travel_form'] != "" ? $_POST['date_travel_form'] : '0000-00-00';

    $search_product_name = $search_product != 'all' ? $orderObj->get_data('name', 'products', $search_product)['name'] : '';

    # --- get data --- #
    $first_bo = array();
    $first_ext = array();
    $sum_adult = array();
    $sum_child = array();
    $sum_infant = array();
    $sum_foc = array();
    $first_pay = array();
    $name_img = 'Job Order';
    $name_img .= $search_product != 'all' ? ' [' . $search_product_name . '] ' : '';
    $name_img .= $date_travel_form != '0000-00-00' ? ' [' . date('j F Y', strtotime($date_travel_form)) . '] ' : '';
    # --- get data --- #
    $bookings = $orderObj->showlist($search_period, 'all', $search_product, 'all', $date_travel_form);
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            if ((in_array($booking['id'], $first_bo) == false)) {
                $first_bo[] = $booking['id'];
                # --- get data products --- #
                $product_id[] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
                $product_name[$booking['product_id']] = !empty($booking['product_name']) ? $booking['product_name'] : '';
                # --- get data bookings --- #
                $bo_id[$booking['product_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
                $voucher_no[$booking['id']] = !empty($booking['voucher_no_agent']) ? $booking['voucher_no_agent'] : 0;
                $company_name[$booking['id']] = !empty($booking['comp_name']) ? $booking['comp_name'] : '-';
                $sender[$booking['id']] = !empty($booking['sender']) ? $booking['sender'] : '-';
                $cus_name[$booking['id']][] = !empty($booking['cus_name']) ? $booking['cus_name'] : '-';
                $boker_name[$booking['id']] = !empty($booking['booker_fname']) ? $booking['booker_fname'] . ' ' . $booking['booker_lname'] : '';
                $book_date[$booking['id']] = !empty($booking['created_at']) ? date('j F Y', strtotime($booking['created_at'])) : '';
                $car[$booking['id']] = !empty($booking['car_id']) ? $booking['car_name'] : '-';
                $guide[$booking['id']] = !empty($booking['guide_name']) ? $booking['guide_name'] : '-';
                $boat[$booking['id']] = !empty($booking['boat_name']) ? $booking['boat_name'] : '-';
                # --- get data booking products --- #
                $bp_id[] = !empty($booking['bp_id']) ? $booking['bp_id'] : 0;
                $adult[$booking['id']] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $child[$booking['id']] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $infant[$booking['id']] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $foc[$booking['id']] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $sum_adult[$booking['product_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $sum_child[$booking['product_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $sum_infant[$booking['product_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $sum_foc[$booking['product_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $bp_note[$booking['id']] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
                # --- get data booking transfer --- #
                $bt_id[] = !empty($booking['bt_id']) ? $booking['bt_id'] : 0;
                $hotel_name[$booking['id']] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : '-';
                $room_no[$booking['id']] = !empty($booking['room_no']) ? $booking['room_no'] : '-';
                $pickup_time[$booking['id']] = $booking['start_pickup'] != '00:00:00' ? $booking['end_pickup'] != '00:00:00' ? date('H:i', strtotime($booking['start_pickup'])) . ' - ' . date('H:i', strtotime($booking['end_pickup'])) : date('H:i', strtotime($booking['start_pickup'])) : '-';
                $car_name[$booking['id']] = !empty($booking['car_name']) ? $booking['car_name'] : '';
                $driver_name[$booking['id']] = !empty($booking['driver_name']) ? $booking['driver_name'] : '';
                # --- get data booking payment --- #
                $bopay_id[$booking['id']] = !empty($booking['bopay_id']) ? $booking['bopay_id'] : 0;
                $bopay_name[$booking['id']] = !empty($booking['bopay_name']) ? $booking['bopay_name'] : '';
                $total_paid[$booking['id']] = !empty($booking['total_paid']) ? $booking['total_paid'] : '';
                $total = $booking['rate_total'];
                $total = $booking['transfer_type'] == 1 ? $total + ($booking['bt_adult'] * $booking['btr_rate_adult']) : $total;
                $total = $booking['transfer_type'] == 1 ? $total + ($booking['bp_child'] * $booking['btr_rate_child']) : $total;
                $total = $booking['transfer_type'] == 1 ? $total + ($booking['bp_infant'] * $booking['btr_rate_infant']) : $total;
                $total = $booking['transfer_type'] == 2 ? $orderObj->sumbtrprivate($booking['bt_id'])['sum_rate_private'] + $total : $total;
                // $total = $orderObj->sumbectotal($booking['id'])['sum_rate_total'] + $total;
                $total = !empty($booking['discount']) ? $total - $booking['discount'] : $total;
                $array_total[$booking['id']] = $total;
            }

            $payment_name[$booking['id']] = $booking['bopay_id'] == 4 || $booking['bopay_id'] == 5 ? $booking['bopay_name'] . '</br>(' . number_format($booking['total_paid']) . ')' : $booking['bopay_name'];

            # --- get value booking extra chang --- #
            if ((in_array($booking['bec_id'], $first_ext) == false) && !empty($booking['bec_id'])) {
                $first_ext[] = $booking['bec_id'];
                $bec_id[$booking['id']][] = !empty($booking['bec_id']) ? $booking['bec_id'] : 0;
                $bec_name[$booking['id']][] = !empty($booking['bec_name']) ? $booking['bec_name'] : '';
                $bec_type[$booking['id']][] = !empty($booking['bec_type']) ? $booking['bec_type'] : 0;
                $bec_adult[$booking['id']][] = !empty($booking['bec_adult']) ? $booking['bec_adult'] : 0;
                $bec_child[$booking['id']][] = !empty($booking['bec_child']) ? $booking['bec_child'] : 0;
                $bec_infant[$booking['id']][] = !empty($booking['bec_infant']) ? $booking['bec_infant'] : 0;
                $bec_privates[$booking['id']][] = !empty($booking['bec_privates']) ? $booking['bec_privates'] : 0;
                $bec_rate_adult[$booking['id']][] = !empty($booking['bec_rate_adult']) ? $booking['bec_rate_adult'] : 0;
                $bec_rate_child[$booking['id']][] = !empty($booking['bec_rate_child']) ? $booking['bec_rate_child'] : 0;
                $bec_rate_infant[$booking['id']][] = !empty($booking['bec_rate_infant']) ? $booking['bec_rate_infant'] : 0;
                $bec_rate_private[$booking['id']][] = !empty($booking['bec_rate_private']) ? $booking['bec_rate_private'] : 0;
                $bec_total[$booking['id']][] = $booking['bec_type'] > 0 ? $booking['bec_type'] == 1 ? (($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) + ($booking['bec_infant'] * $booking['bec_rate_infant'])) : ($booking['bec_privates'] * $booking['bec_rate_private']) : 0;
            }
            # --- in array get value booking payment --- #
            if ((in_array($booking['bopa_id'], $first_pay) == false) && !empty($booking['bopa_id'])) {
                $first_pay[] = $booking['bopa_id'];
                $payments['bopa_id'][$booking['id']][] = !empty($booking['bopa_id']) ? $booking['bopa_id'] : 0;
                $payments['bopay_id'][$booking['id']][] = !empty($booking['bopay_id']) ? $booking['bopay_id'] : 0;
                $payments['bopay_name'][$booking['id']][] = !empty($booking['bopay_name']) ? $booking['bopay_name'] : '';
                $payments['bopay_class'][$booking['id']][] = !empty($booking['bopay_name_class']) ? $booking['bopay_name_class'] : '';
                $payments['total_paid'][$booking['id']][] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
                if ($booking['bopay_id'] == 4) {
                    $cot_id[$booking['id']][] = !empty($booking['bopa_id']) ? $booking['bopa_id'] : 0;
                    $cot_name[$booking['id']] = !empty($booking['bopay_name']) ? $booking['bopay_name'] . ' (' . number_format($booking['total_paid']) . ')' : '';
                    $cot_class[$booking['id']] = !empty($booking['bopay_name_class']) ? $booking['bopay_name_class'] : '';
                    $cot[$booking['id']][] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
                }
            }
        }
        $array_product = !empty($product_id) ? array_unique($product_id) : 0;
        $name_img = 'Job Order [' . date('j F Y', strtotime($date_travel_form)) . ']';
?>
        <div class="content-header">
            <div class="pl-1 pt-0 pb-0">
                <a href="./?pages=order-job/print&action=print&<?php echo 'search_period=' . $search_period; ?>&<?php echo 'search_product=' . $search_product; ?>&<?php echo 'date_travel_form=' . $date_travel_form; ?>" target="_blank" class="btn btn-info">Print</a>
                <a href="javascript:void(0)"><button type="button" class="btn btn-info" value="image" onclick="download_image();">Image</button></a>
                <a href="javascript:void(0);" class="btn btn-info disabled" hidden>Download as PDF</a>
            </div>
        </div>
        <hr class="pb-0 pt-0">
        <div id="order-job-image-table" style="background-color: #FFF;">
            <!-- Header starts -->
            <div class="card-body pb-0">
                <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0 mb-1">
                    <div>
                        <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="150"></span>
                    </div>
                    <div>
                        <span style="color: #000;">
                            <?php echo $main_document; ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body pb-0">
                <div class="text-center card-text">
                    <h4 class="font-weight-bolder">ใบงาน</h4>
                    <div class="badge badge-pill badge-light-danger">
                        <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($date_travel_form)); ?></h5>
                    </div>
                </div>
            </div>
            </br>
            <!-- Header ends -->
            <!-- Body starts -->
            <?php
            if (!empty($array_product)) {
                foreach ($array_product as $key => $value) {
                    switch (rand(1, 8)) {
                        case 1:
                            $bg_color = 'rgba(0, 0, 255, 0.12)';
                            $color = '#0000FF';
                            break;
                        case 2:
                            $bg_color = 'rgba(255, 0, 0, 0.12)';
                            $color = '#FF0000';
                            break;
                        case 3:
                            $bg_color = 'rgba(139, 69, 19, 0.12)';
                            $color = '#8B4513';
                            break;
                        case 4:
                            $bg_color = 'rgba(128, 0, 128, 0.12)';
                            $color = '#800080';
                            break;
                        case 5:
                            $bg_color = 'rgba(255, 105, 180, 0.12)';
                            $color = '#FF69B4';
                            break;
                        case 6:
                            $bg_color = 'rgba(255, 165, 0, 0.12)';
                            $color = '#FFA500';
                            break;
                        case 7:
                            $bg_color = 'rgba(0, 128, 0, 0.12)';
                            $color = '#008000';
                            break;
                        case 8:
                            $bg_color = 'rgba(30, 144, 255, 0.12)';
                            $color = '#1E90FF';
                            break;
                    }
            ?>
                    <div class="card-body pb-0 pt-0">
                        <div class="row card-text align-items-center">
                            <div class="col-md-9 text-left">
                                <div class="avatar" style="background: <?php echo $bg_color; ?>;">
                                    <div class="avatar-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="<?php echo $color; ?>" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                                        </svg>
                                    </div>
                                </div>
                                <?php echo $product_name[$value]; ?>
                            </div>

                            <div class="col-md-3 text-right">

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" style="font-size: 14px;">
                            <!-- style="background: <?php echo $color; ?>;" -->
                            <thead class="bg-info bg-darken-2 text-white">
                                <tr>
                                    <th class="text-center" width="10%">VOUCHER</th>
                                    <th class="text-center" width="10%">AGENT</th>
                                    <th class="text-center" width="10%">SENDER</th>
                                    <th class="text-center" width="10%">CUSTOMER'S NAME</th>
                                    <th class="text-center" width="1%">AD</th>
                                    <th class="text-center" width="1%">CHD</th>
                                    <th class="text-center" width="1%">INF</th>
                                    <th class="text-center" width="1%">FOC</th>
                                    <th class="text-center" width="8%">HOTEL</th>
                                    <th class="text-center" width="4%">ROOM</th>
                                    <th class="text-center" width="5%">TIME</th>
                                    <th class="text-center" width="5%">CAR</th>
                                    <th class="text-center" width="5%">Boat</th>
                                    <th class="text-center" width="5%">guide</th>
                                    <th class="text-center" width="8%">REMARK</th>
                                    <th class="text-center" width="10%">PAYMENT</th>
                                    <th class="text-center" width="5%">TOTAL</th>
                                    <th class="text-center" width="5%">Confirmed</th>
                                    <th class="text-center" width="5%">Booking Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($bo_id[$value])) {
                                    for ($i = 0; $i < count($bo_id[$value]); $i++) {
                                        $class_tr = ($i % 2 == 1) ? 'table-active' : '';
                                        $href = 'href="./?pages=booking/edit&id=' . $bo_id[$value][$i] . '" style="color:#6E6B7B"';
                                        $total_book = !empty($bec_total[$bo_id[$value][$i]]) ? array_sum($bec_total[$bo_id[$value][$i]]) + $array_total[$bo_id[$value][$i]] : $array_total[$bo_id[$value][$i]];
                                        $total_book = !empty($cot[$bo_id[$value][$i]]) ? $total_book - array_sum($cot[$bo_id[$value][$i]]) : $total_book;
                                ?>
                                        <tr class="<?php echo $class_tr; ?>">
                                            <td class="text-nowrap text-center p-25 m-25" width="10%">
                                                <a <?php echo $href; ?>><?php echo $voucher_no[$bo_id[$value][$i]]; ?></a>
                                            </td>
                                            <td class="p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $company_name[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="text-center p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $sender[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="text-nowrap p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $cus_name[$bo_id[$value][$i]][0]; ?></a></td>
                                            <td class="text-center p-25 m-25" width="5%"><a <?php echo $href; ?>><?php echo $adult[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="text-center p-25 m-25" width="5%"><a <?php echo $href; ?>><?php echo $child[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="text-center p-25 m-25" width="5%"><a <?php echo $href; ?>><?php echo $infant[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="text-center p-25 m-25" width="5%"><a <?php echo $href; ?>><?php echo $foc[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $hotel_name[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="text-center p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $room_no[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="text-nowrap p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $pickup_time[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $car[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $boat[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $guide[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $bp_note[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="text-center p-25 m-25" width="10%"><a <?php echo $href; ?>> <?php echo $payment_name[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="text-center p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo number_format($total_book); ?></a></td>
                                            <td class="text-center p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $boker_name[$bo_id[$value][$i]]; ?></a></td>
                                            <td class="text-center p-25 m-25" width="10%"><a <?php echo $href; ?>><?php echo $book_date[$bo_id[$value][$i]]; ?></a></td>
                                        </tr>
                                <?php }
                                } ?>
                                <tr>
                                    <td class="text-right" colspan="4">TOTAL <?php echo (array_sum($sum_adult[$value]) + array_sum($sum_child[$value]) + array_sum($sum_infant[$value]) + array_sum($sum_foc[$value])); ?> PAXS</td>
                                    <td><?php echo !empty($sum_adult[$value]) ? array_sum($sum_adult[$value]) : 0; ?></td>
                                    <td><?php echo !empty($sum_child[$value]) ? array_sum($sum_child[$value]) : 0; ?></td>
                                    <td><?php echo !empty($sum_infant[$value]) ? array_sum($sum_infant[$value]) : 0; ?></td>
                                    <td><?php echo !empty($sum_infant[$value]) ? array_sum($sum_foc[$value]) : 0; ?></td>
                                    <td colspan="11"></td>
                                </tr>
                            </tbody>
                        </table>
                        </br>
                    </div>
                <?php } ?>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr class="bg-danger bg-lighten-5 text-danger">
                            <td class="text-center text-bold h3 text-danger" rowspan="2" width="20%">รวมทั้งหมด</td>
                            <td class="text-center text-bold">ADULT</td>
                            <td class="text-center text-bold">CHILD</td>
                            <td class="text-center text-bold">INFANT</td>
                            <td class="text-center text-bold">FOC</td>
                        </tr>
                        <tr>
                            <td class="text-center text-primary text-bold h4"><?php echo !empty($adult) ? array_sum($adult) : 0; ?></td>
                            <td class="text-center text-primary text-bold h4"><?php echo !empty($child) ? array_sum($child) : 0; ?></td>
                            <td class="text-center text-primary text-bold h4"><?php echo !empty($infant) ? array_sum($infant) : 0; ?></td>
                            <td class="text-center text-primary text-bold h4"><?php echo !empty($foc) ? array_sum($foc) : 0; ?></td>
                        </tr>
                    </table>
                </div>
            <?php } ?>
            <!-- Body ends -->
            <input type="hidden" id="name_img" name="name_img" value="<?php echo $name_img; ?>">
        </div>
    <?php } ?>
<?php
} else {
    echo FALSE;
}
