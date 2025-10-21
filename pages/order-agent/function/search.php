<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$orderObj = new Order();
$today = date("Y-m-d");

function check_in($var)
{
    return ($var > 0);
}

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['travel_date'])) {
    // get value from ajax
    $travel_date = $_POST['travel_date'] != "0000-00-00" ? $_POST['travel_date'] : $today;

    $all_bookings = $orderObj->fetch_all_bookingboat('all', $travel_date, 'all', 'all', 'all', '', '', '', 0);
?>

    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
        <div class="col-4 text-left text-bold h4"></div>
        <div class="col-4 text-center text-bold h4"><?php echo date('j F Y', strtotime($travel_date)); ?></div>
        <div class="col-4 text-right mb-50"></div>
    </div>


    <table class="table table-striped text-uppercase table-vouchure-t2">
        <thead class="bg-light">
            <tr>
                <th class="text-center" width="1%">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkall<?php echo strtotime($travel_date); ?>" onclick="checkbox(<?php echo strtotime($travel_date); ?>);" <?php echo !empty($checkall) ? $checkall : ''; ?> />
                        <label class="custom-control-label" for="checkall<?php echo strtotime($travel_date); ?>"></label>
                    </div>
                </th>
                <th>ชื่อเอเยนต์</th>
                <th class="text-center">Booking</th>
                <th class="text-center">Total</th>
                <th class="text-center">Audlt</th>
                <th class="text-center">Children</th>
                <th class="text-center">Infant</th>
                <th class="text-center">FOC</th>
                <th class="text-center">COT</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($all_bookings)) {
                $agent_arr = array();
                foreach ($all_bookings as $bookings) {
                    if (in_array($bookings['agent_id'], $agent_arr) == false && $bookings['agent_id'] > 0) {
                        $agent_arr[] = $bookings['agent_id'];
                        $confirm = $orderObj->get_values('id', 'confirm_agent', 'agent_id = ' . $bookings['agent_id'] . ' AND travel_date = "' . $travel_date . '"', 0);
                        $tr = 'onclick="modal_detail(' . $bookings['agent_id'] . ', "' . addslashes($bookings['agent_name']) . '", "' . $travel_date . '");" data-toggle="modal" data-target="#modal-detail"';

                        $agents = $orderObj->get_values(
                            'bookings.id, BPRS.adult, BPRS.child, BPRS.infant, BPRS.foc, BPRS.max_tourist',
                            'bookings
                            LEFT JOIN companies
                                ON bookings.company_id = companies.id
                            LEFT JOIN booking_products BP
                                ON bookings.id = BP.booking_id
                            LEFT JOIN (
                                SELECT BP.booking_id,
                                    SUM(BPR.adult) AS adult,
                                    SUM(BPR.child) AS child,
                                    SUM(BPR.infant) AS infant,
                                    SUM(BPR.foc) AS foc,
                                    SUM(BPR.adult) + SUM(BPR.child) + SUM(BPR.infant) + SUM(BPR.foc) AS max_tourist
                                FROM booking_products BP
                                JOIN booking_product_rates BPR 
                                    ON BP.id = BPR.booking_products_id
                                GROUP BY BP.booking_id, BPR.category_id
                            ) BPRS 
                                ON BPRS.booking_id = bookings.id',
                            'companies.id = ' . $bookings['agent_id'] . ' AND BP.travel_date = "' . $travel_date . '"',
                            0
                        );
                        print_r($agents['adult']);
            ?>
                        <tr <?php echo $tr; ?>>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input dt-checkboxes checkbox-<?php echo strtotime($travel_date); ?>" type="checkbox" id="checkbox<?php echo strtotime($travel_date) . '-' . $bookings['agent_id']; ?>"
                                        data-travel="<?php echo $travel_date; ?>"
                                        data-check="<?php echo !empty($confirm['id']) ? $confirm['id'] : 0; ?>"
                                        data-confirm="<?php echo !empty($confirm['id']) ? $confirm['id'] : 0; ?>"
                                        value="<?php echo $bookings['agent_id']; ?>"
                                        onclick="submit_check_in('only', this);" <?php echo !empty($confirm['id']) ? 'checked' : ''; ?> />
                                    <label class="custom-control-label" for="checkbox<?php echo strtotime($travel_date) . '-' . $bookings['agent_id']; ?>"></label>
                                </div>
                            </td>
                            <td><?php echo $bookings['agent_name']; ?></td>
                            <td class="text-center"></td>
                            <td class="text-center"><?php echo $bookings['max_tourist']; ?></td>
                            <td class="text-center"><?php echo $bookings['adult']; ?></td>
                            <td class="text-center"><?php echo $bookings['child']; ?></td>
                            <td class="text-center"><?php echo $bookings['infant']; ?></td>
                            <td class="text-center"><?php echo $bookings['foc']; ?></td>
                            <td class="text-center"></td>
                        </tr>
            <?php }
                }
            } ?>
        </tbody>
    </table>

    <?php if (!empty($agent_id)) {
        if (!empty($agent_id) && !empty($confirm_id)) {
            $checkall = count($agent_id) == count(array_filter($confirm_id, "check_in")) ? 'checked' : '';
        }
    ?>
        <table class="table table-striped text-uppercase table-vouchure-t2">
            <thead class="bg-light">
                <tr>
                    <th class="text-center" width="1%">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkall<?php echo strtotime($travel_date); ?>" onclick="checkbox(<?php echo strtotime($travel_date); ?>);" <?php echo !empty($checkall) ? $checkall : ''; ?> />
                            <label class="custom-control-label" for="checkall<?php echo strtotime($travel_date); ?>"></label>
                        </div>
                    </th>
                    <th>ชื่อเอเยนต์</th>
                    <th class="text-center">Booking</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Audlt</th>
                    <th class="text-center">Children</th>
                    <th class="text-center">Infant</th>
                    <th class="text-center">FOC</th>
                    <th class="text-center">COT</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < count($agent_id); $i++) { ?>
                    <tr <?php echo $confirm_id[$i] > 0 ? 'class="table-success"' : ''; ?>>
                        <td class="text-center">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input dt-checkboxes checkbox-<?php echo strtotime($travel_date); ?>" type="checkbox" id="checkbox<?php echo strtotime($travel_date) . '-' . $agent_id[$i]; ?>" data-travel="<?php echo $travel_date; ?>" data-check="<?php echo $confirm_id[$i]; ?>" data-confirm="<?php echo $confirm_id[$i]; ?>" value="<?php echo $agent_id[$i]; ?>" onclick="submit_check_in('only', this);" <?php echo $confirm_id[$i] > 0 ? 'checked' : ''; ?> />
                                <label class="custom-control-label" for="checkbox<?php echo strtotime($travel_date) . '-' . $agent_id[$i]; ?>"></label>
                            </div>
                        </td>
                        <td onclick="modal_detail(<?php echo $agent_id[$i]; ?>, '<?php echo addslashes($agent_name[$i]); ?>', '<?php echo $travel_date; ?>');" data-toggle="modal" data-target="#modal-detail"><?php echo $agent_name[$i]; ?></td>
                        <td class="text-center" onclick="modal_detail(<?php echo $agent_id[$i]; ?>, '<?php echo addslashes($agent_name[$i]); ?>', '<?php echo $travel_date; ?>');" data-toggle="modal" data-target="#modal-detail"><?php echo !empty($bo_id[$agent_id[$i]]) ? count($bo_id[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center" onclick="modal_detail(<?php echo $agent_id[$i]; ?>, '<?php echo addslashes($agent_name[$i]); ?>', '<?php echo $travel_date; ?>');" data-toggle="modal" data-target="#modal-detail"><?php echo !empty($total_tourist[$agent_id[$i]]) ? array_sum($total_tourist[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center" onclick="modal_detail(<?php echo $agent_id[$i]; ?>, '<?php echo addslashes($agent_name[$i]); ?>', '<?php echo $travel_date; ?>');" data-toggle="modal" data-target="#modal-detail"><?php echo !empty($adult[$agent_id[$i]]) ? array_sum($adult[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center" onclick="modal_detail(<?php echo $agent_id[$i]; ?>, '<?php echo addslashes($agent_name[$i]); ?>', '<?php echo $travel_date; ?>');" data-toggle="modal" data-target="#modal-detail"><?php echo !empty($child[$agent_id[$i]]) ? array_sum($child[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center" onclick="modal_detail(<?php echo $agent_id[$i]; ?>, '<?php echo addslashes($agent_name[$i]); ?>', '<?php echo $travel_date; ?>');" data-toggle="modal" data-target="#modal-detail"><?php echo !empty($infant[$agent_id[$i]]) ? array_sum($infant[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center" onclick="modal_detail(<?php echo $agent_id[$i]; ?>, '<?php echo addslashes($agent_name[$i]); ?>', '<?php echo $travel_date; ?>');" data-toggle="modal" data-target="#modal-detail"><?php echo !empty($foc[$agent_id[$i]]) ? array_sum($foc[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center" onclick="modal_detail(<?php echo $agent_id[$i]; ?>, '<?php echo addslashes($agent_name[$i]); ?>', '<?php echo $travel_date; ?>');" data-toggle="modal" data-target="#modal-detail"><?php echo !empty($cot[$agent_id[$i]]) ? number_format(array_sum($cot[$agent_id[$i]])) : 0; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

<?php
} else {
    echo false;
}
