<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Booking.php';

$prodObj = new Booking();

if (isset($_POST['action']) && $_POST['action'] == "search") {
    // get value from ajax
    $search_status = $_POST['search_status'] != "" ? $_POST['search_status'] : 'all';
    $search_payment = $_POST['search_payment'] != "" ? $_POST['search_payment'] : 'all';
    $search_agent = $_POST['search_agent'] != "" ? $_POST['search_agent'] : 'all';
    $search_product = $_POST['search_product'] != "" ? $_POST['search_product'] : 'all';
    $search_travel = $_POST['search_travel'] != "" ? $_POST['search_travel'] : '0000-00-00';
    $search_voucher_no = $_POST['search_voucher_no'] != "" ? $_POST['search_voucher_no'] : '';
    $refcode = $_POST['refcode'] != "" ? $_POST['refcode'] : '';
    $name = $_POST['name'] != "" ? $_POST['name'] : '';

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
    $bookings = $prodObj->showlist($_SESSION["supplier"]["id"], $search_status, $search_payment, $search_agent, $search_product, $search_travel, $search_voucher_no, $refcode, $name);
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
                $bec_id[$booking['id']][] = $booking['bec_id'];
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

<?php
} else {
    echo $products = false;
}
