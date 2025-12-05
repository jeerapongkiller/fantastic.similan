<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Receipt.php';

$recObj = new Receipt();
$today = date("Y-m-d");
$times = date("H:i:s");

if (isset($_POST['action']) && $_POST['action'] == "search-invoice" && !empty($_POST['travel_date'])) {
    // get value from ajax
    $travel_date = $_POST['travel_date'] != "" ? $_POST['travel_date'] : '0000-00-00';

    $first_booking = array();
    $first_cover = array();
    $first_company = array();
    $invoices = $recObj->showlist('invoices', $travel_date, 'all', 0);
    if (!empty($invoices)) {
        foreach ($invoices as $invoice) {
            # --- get value agent --- #
            if (in_array($invoice['comp_id'], $first_company) == false && !empty($invoice['comp_id'])) {
                $first_company[] = $invoice['comp_id'];
                $agent_id[] = !empty($invoice['comp_id']) ? $invoice['comp_id'] : 0;
                $agent_name[] = !empty($invoice['comp_name']) ? $invoice['comp_name'] : '';
            }
            # --- get value booking --- #
            if (in_array($invoice['cover_id'], $first_cover) == false) {
                $first_cover[] = $invoice['cover_id'];
                $cover_id[$invoice['comp_id']][] = !empty($invoice['cover_id']) ? $invoice['cover_id'] : 0;
            }
            # --- get value booking --- #
            if (in_array($invoice['id'], $first_booking) == false) {
                $first_booking[] = $invoice['id'];
                $bo_id[$invoice['comp_id']][] = !empty($invoice['id']) ? $invoice['id'] : 0;
                $adult[$invoice['comp_id']][] = !empty($invoice['bp_adult']) ? $invoice['bp_adult'] : 0;
                $child[$invoice['comp_id']][] = !empty($invoice['bp_child']) ? $invoice['bp_child'] : 0;
                $infant[$invoice['comp_id']][] = !empty($invoice['bp_infant']) ? $invoice['bp_infant'] : 0;
                $foc[$invoice['comp_id']][] = !empty($invoice['bp_foc']) ? $invoice['bp_foc'] : 0;
                $tourrist[$invoice['comp_id']][] = $invoice['bp_adult'] + $invoice['bp_child'] + $invoice['bp_infant'] + $invoice['bp_foc'];
                $cot[$invoice['comp_id']][] = !empty($invoice['total_paid']) ? $invoice['total_paid'] : 0;
            }
        }
    }
?>

    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
        <div class="col-4 text-left text-bold h4"></div>
        <div class="col-4 text-center text-bold h4"><?php echo !empty(substr($travel_date, 14, 24)) ? date('j F Y', strtotime(substr($travel_date, 0, 10))) . ' - ' . date('j F Y', strtotime(substr($travel_date, 14, 24))) : date('j F Y', strtotime($travel_date)); ?></div>
        <div class="col-4 text-right mb-50"></div>
    </div>

    <?php if (!empty($agent_id)) { ?>
        <table class="table table-striped text-uppercase table-vouchure-t2">
            <thead class="bg-light">
                <tr>
                    <th>ชื่อเอเยนต์</th>
                    <th class="text-center">Invoice</th>
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
                    <tr onclick="modal_detail(<?php echo $agent_id[$i]; ?>, '<?php echo addslashes($agent_name[$i]); ?>', '<?php echo $travel_date; ?>');" data-toggle="modal" data-target="#modal-detail">
                        <td><?php echo $agent_name[$i]; ?></td>
                        <td class="text-center"><?php echo !empty($cover_id[$agent_id[$i]]) ? count($cover_id[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($tourrist[$agent_id[$i]]) ? array_sum($tourrist[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($adult[$agent_id[$i]]) ? array_sum($adult[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($child[$agent_id[$i]]) ? array_sum($child[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($infant[$agent_id[$i]]) ? array_sum($infant[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($foc[$agent_id[$i]]) ? array_sum($foc[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($cot[$agent_id[$i]]) ? number_format(array_sum($cot[$agent_id[$i]])) : 0; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

<?php
} elseif (isset($_POST['action']) && $_POST['action'] == "search-receipt" && !empty($_POST['travel_date'])) {
    // get value from ajax
    $travel_date = $_POST['travel_date'] != "" ? $_POST['travel_date'] : '0000-00-00';

    $first_rec = array();
    $first_cover = array();
    $first_company = array();
    $first_booking = array();
    $invoices = $recObj->showlist('receipts', $travel_date, 'all', 0);
    if (!empty($invoices)) {
        foreach ($invoices as $invoice) {
            # --- get value booking --- #
            if (in_array($invoice['rec_id'], $first_rec) == false) {
                $first_rec[] = $invoice['rec_id'];
                $rec_id[$invoice['comp_id']][] = !empty($invoice['rec_id']) ? $invoice['rec_id'] : 0;
            }
            # --- get value booking --- #
            if (in_array($invoice['cover_id'], $first_cover) == false) {
                $first_cover[] = $invoice['cover_id'];
                $cover_id[$invoice['comp_id']][] = !empty($invoice['cover_id']) ? $invoice['cover_id'] : 0;
            }
            # --- get value agent --- #
            if (in_array($invoice['comp_id'], $first_company) == false && !empty($invoice['comp_id'])) {
                $first_company[] = $invoice['comp_id'];
                $agent_id[] = !empty($invoice['comp_id']) ? $invoice['comp_id'] : 0;
                $agent_name[] = !empty($invoice['comp_name']) ? $invoice['comp_name'] : '';
            }
            # --- get value booking --- #
            if (in_array($invoice['id'], $first_booking) == false) {
                $first_booking[] = $invoice['id'];
                $bo_id[$invoice['comp_id']][] = !empty($invoice['id']) ? $invoice['id'] : 0;
                $adult[$invoice['comp_id']][] = !empty($invoice['bp_adult']) ? $invoice['bp_adult'] : 0;
                $child[$invoice['comp_id']][] = !empty($invoice['bp_child']) ? $invoice['bp_child'] : 0;
                $infant[$invoice['comp_id']][] = !empty($invoice['bp_infant']) ? $invoice['bp_infant'] : 0;
                $foc[$invoice['comp_id']][] = !empty($invoice['bp_foc']) ? $invoice['bp_foc'] : 0;
                $cot[$invoice['comp_id']][] = !empty($invoice['total_paid']) ? $invoice['total_paid'] : 0;
            }
        }
    }
?>
    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
        <div class="col-4 text-left text-bold h4"></div>
        <div class="col-4 text-center text-bold h4"><?php echo !empty(substr($travel_date, 14, 24)) ? date('j F Y', strtotime(substr($travel_date, 0, 10))) . ' - ' . date('j F Y', strtotime(substr($travel_date, 14, 24))) : date('j F Y', strtotime($travel_date)); ?></div>
        <div class="col-4 text-right mb-50"></div>
    </div>

    <?php if (!empty($agent_id)) { ?>
        <table class="table table-striped text-uppercase table-vouchure-t2">
            <thead class="bg-light">
                <tr>
                    <th>ชื่อเอเยนต์</th>
                    <th class="text-center">Receipt</th>
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
                    <tr onclick="modal_detail(<?php echo $agent_id[$i]; ?>, '<?php echo addslashes($agent_name[$i]); ?>', '<?php echo $travel_date; ?>');" data-toggle="modal" data-target="#modal-detail">
                        <td><?php echo $agent_name[$i]; ?></td>
                        <td class="text-center"><?php echo !empty($rec_id[$agent_id[$i]]) ? count($rec_id[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo array_sum($adult[$agent_id[$i]]) + array_sum($child[$agent_id[$i]]) + array_sum($infant[$agent_id[$i]]) + array_sum($foc[$agent_id[$i]]); ?></td>
                        <td class="text-center"><?php echo !empty($adult[$agent_id[$i]]) ? array_sum($adult[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($child[$agent_id[$i]]) ? array_sum($child[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($infant[$agent_id[$i]]) ? array_sum($infant[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($foc[$agent_id[$i]]) ? array_sum($foc[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($cot[$agent_id[$i]]) ? number_format(array_sum($cot[$agent_id[$i]])) : 0; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
<?php
} else {
    echo false;
}
