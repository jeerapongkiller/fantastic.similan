<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();

if (isset($_POST['action']) && $_POST['action'] == "search" && isset($_POST['travel_date'])) {
    // get value from ajax
    $travel = !empty($_POST['travel_date']) ? $_POST['travel_date'] : '0000-00-00';
    $manage_id = !empty($_POST['manage_id']) ? $_POST['manage_id'] : 0;
    $type = !empty($_POST['type']) ? $_POST['type'] : 'not';

    $first_bpr = array();
    $first_bo = array();
    $first_manage_bo = array();
    $bookings = $manageObj->showlistboats('manage', 0, $travel, 'all', 'all', 'all', 'all', 'all', '', '', '', '');
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            # --- get value booking --- #
            if (in_array($booking['id'], $first_bo) == false) {
                $first_bo[] = $booking['id'];
                $bo_id[] = !empty($booking['id']) ? $booking['id'] : 0;
                $travel_date[] = !empty(!empty($booking['travel_date'])) ? $booking['travel_date'] : '0000-00-00';
                // $adult[] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                // $child[] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                // $infant[] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                // $foc[] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $book_full[] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                $voucher_no[] = !empty(!empty($booking['voucher_no_agent'])) ? $booking['voucher_no_agent'] : '';
                $agent_name[] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
                $cus_name[] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
                $mange_id[] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
                $product_name[] = !empty($booking['product_name']) ? $booking['product_name'] : '';
                // $category_name[] = !empty($booking['category_name']) ? $booking['category_name'] : '';
                $booktye_name[] = !empty($booking['booktye_name']) ? $booking['booktye_name'] : '';
                $hotel_name[] = !empty($booking['pickup_id']) ? $booking['pickup_name'] : '';
                $outside[] = !empty($booking['outside']) ? $booking['outside'] : '';
                $note[] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
            }
            # --- get value manage booking --- #
            if (($booking['mange_id'] == $manage_id && $booking['mange_id'] > 0) && in_array($booking['id'], $first_manage_bo) == false) {
                $first_manage_bo[] = $booking['id'];
                $manage_bo[] = !empty($booking['id']) ? $booking['id'] : 0;
                // $manage_adult[] = !empty($booking['adult']) ? $booking['adult'] : 0;
                // $manage_child[] = !empty($booking['child']) ? $booking['child'] : 0;
                // $manage_infant[] = !empty($booking['infant']) ? $booking['infant'] : 0;
                // $manage_foc[] = !empty($booking['foc']) ? $booking['foc'] : 0;
                $manage_book_full[] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                $manage_voucher_no[] = !empty(!empty($booking['voucher_no_agent'])) ? $booking['voucher_no_agent'] : '';
                $manage_agent_name[] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
                $manage_cus_name[] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
                $manage_product_name[] = !empty($booking['product_name']) ? $booking['product_name'] : '';
                // $manage_category_name[] = !empty($booking['category_name']) ? $booking['category_name'] : '';
                $manage_booktye_name[] = !empty($booking['booktye_name']) ? $booking['booktye_name'] : '';
                $manage_hotel_name[] = !empty($booking['pickup_id']) ? $booking['pickup_name'] : '';
                $manage_outside[] = !empty($booking['outside']) ? $booking['outside'] : '';
                $manage_note[] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
            }
            if (in_array($booking['bpr_id'], $first_bpr) == false) {
                $first_bpr[] = $booking['bpr_id'];
                $category_name[$booking['id']][] = !empty($booking['category_name']) ? $booking['category_name'] : '';
                $adult[$booking['id']][] = !empty($booking['adult']) ? $booking['adult'] : 0;
                $child[$booking['id']][] = !empty($booking['child']) ? $booking['child'] : 0;
                $infant[$booking['id']][] = !empty($booking['infant']) ? $booking['infant'] : 0;
                $foc[$booking['id']][] = !empty($booking['foc']) ? $booking['foc'] : 0;
                // $rate_adult[$booking['id']][] = !empty($booking['rate_adult']) ? $booking['rate_adult'] : 0;
                // $rate_child[$booking['id']][] = !empty($booking['rate_child']) ? $booking['rate_child'] : 0;
                // $cate_transfer[$booking['id']][] = !empty($booking['category_transfer']) ? $booking['category_transfer'] : 0;
                $tourist_array[$booking['id']][] = $booking['adult'] + $booking['child'] + $booking['infant'] + $booking['foc'];
            }
        }
?>
        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
            <div class="col-lg-12 col-xl-12 text-center text-bold h4">
                <?php echo date('j F Y', strtotime($travel)) ?>
            </div>
        </div>
        <div class="row border-top text-center mx-0">
            <div class="col-3 border-right py-1">
                <p class="card-text text-muted mb-0">Booking ที่ไม่ได้เลือก</p>
                <h3 class="font-weight-bolder mb-0" id="booking-false"></h3>
            </div>
            <div class="col-3 border-right py-1">
                <p class="card-text text-muted mb-0">Total ที่ไม่ได้เลือก</p>
                <h3 class="font-weight-bolder mb-0" id="toc-false"></h3>
            </div>
            <div class="col-3 border-right py-1">
                <p class="card-text text-muted mb-0">Booking ที่เลือก</p>
                <h3 class="font-weight-bolder mb-0" id="booking-true"></h3>
            </div>
            <div class="col-3 py-1">
                <p class="card-text text-muted mb-0">Total ที่เลือก</p>
                <h3 class="font-weight-bolder mb-0" id="toc-true"></h3>
            </div>
        </div>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkbo_all" name="checkbo_all" onclick="checkbox('booking');">
                            <label class="custom-control-label" for="checkbo_all"></label>
                        </div>
                    </th>
                    <th>Category</th>
                    <th>Hotel</th>
                    <th>Name</th>
                    <th class="cell-fit text-center">Total</th>
                    <th class="cell-fit text-center">A</th>
                    <th class="cell-fit text-center">C</th>
                    <th class="cell-fit text-center">INF</th>
                    <th class="cell-fit text-center">FOC</th>
                    <th class="text-nowrap">Agent</th>
                    <th class="text-nowrap">V/C</th>
                    <th>REMARKE</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($b = 0; $b < count($bo_id); $b++) {
                    if ($mange_id[$b] == 0) {
                        $id = $bo_id[$b];
                ?>
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input dt-checkboxes checkbox-bookings" type="checkbox" id="checkbox<?php echo $bo_id[$b]; ?>" name="bo_id[]" value="<?php echo $bo_id[$b]; ?>" onclick="sum_checkbox();">
                                    <label class="custom-control-label" for="checkbox<?php echo $bo_id[$b]; ?>"></label>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">
                                    <?php if (!empty($category_name[$id])) {
                                        for ($c = 0; $c < count($category_name[$id]); $c++) {
                                            echo $c == 0 ? $category_name[$id][$c] : ', ' . $category_name[$id][$c];
                                        }
                                    } ?>
                                </span>
                            </td>
                            <td><?php echo !empty($hotel_name[$b]) ? $hotel_name[$b] : $outside[$b]; ?></td>
                            <td><span class="fw-bold"><?php echo $cus_name[$b]; ?></span></td>
                            <td class="text-center" id="toc-bookings<?php echo $bo_id[$b]; ?>"><?php echo !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                            <td><?php echo $agent_name[$b]; ?></td>
                            <td><span class="fw-bold"><?php echo !empty($voucher_no[$b]) ? $voucher_no[$b] : $book_full[$b]; ?></span></td>
                            <td><?php echo $note[$b]; ?></td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
        <?php if (!empty($manage_bo)) { ?>
            <div class="divider divider-dark">
                <div class="divider-text">
                    <h3 class="text-bold mb-0">จัดเรือ</h3>
                </div>
            </div>
            <input type="hidden" id="before_managebo" name="before_managebo" value="<?php echo json_encode($manage_bo, true); ?>">
            <table class="table" id="list-group">
                <thead class="table-dark">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkmanage_all" name="checkmanage_all" onclick="checkbox('manage');" checked>
                                <label class="custom-control-label" for="checkmanage_all"></label>
                            </div>
                        </th>
                        <th>Category</th>
                        <th>Hotel</th>
                        <th>Name</th>
                        <th class="cell-fit text-center">Total</th>
                        <th class="cell-fit text-center">A</th>
                        <th class="cell-fit text-center">C</th>
                        <th class="cell-fit text-center">INF</th>
                        <th class="cell-fit text-center">FOC</th>
                        <th class="text-nowrap">Agent</th>
                        <th class="text-nowrap">V/C</th>
                        <th>REMARKE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($c = 0; $c < count($manage_bo); $c++) {
                        $id = $manage_bo[$c]; ?>
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input dt-checkboxes checkbox-manage" type="checkbox" id="checkbox<?php echo $manage_bo[$c]; ?>" name="manage_bo[]" value="<?php echo $manage_bo[$c]; ?>" onclick="sum_checkbox();" checked>
                                    <label class="custom-control-label" for="checkbox<?php echo $manage_bo[$c]; ?>"></label>
                                </div>
                            </td>
                            <td><span class="fw-bold"><?php echo $manage_category_name[$c]; ?></span></td>
                            <td><?php echo !empty($manage_hotel_name[$c]) ? $manage_hotel_name[$c] : $manage_outside[$c]; ?></td>
                            <td><span class="fw-bold"><?php echo $manage_cus_name[$c]; ?></span></td>
                            <td class="text-center" id="toc-manage<?php echo $manage_bo[$c]; ?>"><?php echo !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                            <td><?php echo $manage_agent_name[$c]; ?></td>
                            <td><span class="fw-bold"><?php echo !empty($manage_voucher_no[$c]) ? $manage_voucher_no[$c] : $manage_book_full[$c]; ?></span></td>
                            <td><?php echo $manage_note[$c]; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
        <hr>
        <div class="d-flex justify-content-between">
            <span></span>
            <button type="submit" class="btn btn-primary" onclick="submit_booking_manage('<?php echo $type; ?>', <?php echo $manage_id; ?>);">Submit</button>
        </div>
<?php
    }
} else {
    echo false;
}
?>