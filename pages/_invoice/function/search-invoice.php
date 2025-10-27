<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Invoice.php';

$invObj = new Invoice();
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
// $today = '2024-09-29';
// $tomorrow = '2024-09-30';

function diff_date($today, $diff_date)
{
    $diff_inv = array();
    $date1 = date_create($today);
    $date2 = date_create($diff_date);
    $diff = date_diff($date1, $date2);
    $diff_inv['day'] =  $diff->format("%R%a");
    $diff_inv['num'] =  $diff->format("%a");

    return $diff_inv;
}

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['agent_id']) && !empty($_POST['travel_date'])) {
    // get value from ajax
    $agent_id = $_POST['agent_id'] != "" ? $_POST['agent_id'] : 0;
    $travel_date = $_POST['travel_date'] != "" ? $_POST['travel_date'] : '0000-00-00';

    $first_cover = array();
    $first_booking = array();
    $first_extar = array();
    $invoices = $invObj->showlist('invoices', $travel_date, $agent_id, 0);
    if (!empty($invoices)) {
        foreach ($invoices as $invoice) {
            # --- get value booking --- #
            if (in_array($invoice['cover_id'], $first_cover) == false) {
                $first_cover[] = $invoice['cover_id'];
                $cover_id[] = !empty($invoice['cover_id']) ? $invoice['cover_id'] : 0;
                $inv_full[] = !empty($invoice['inv_full']) ? $invoice['inv_full'] : '';
                $inv_date[] = !empty($invoice['inv_date']) ? $invoice['inv_date'] : '0000-00-00';
                $rec_date[] = !empty($invoice['rec_date']) ? $invoice['rec_date'] : '0000-00-00';
                $vat[] = !empty($invoice['vat']) ? $invoice['vat'] : '-';
                $withholding[] = !empty($invoice['withholding']) ? $invoice['withholding'] : '-';
                $due_date[] = (diff_date($today, $invoice['rec_date'])['day'] > 0) ? '<span class="badge badge-pill badge-light-success text-capitalized">วันที่ครบกำหนดชำระ : ' . date("j F Y", strtotime($invoice['rec_date'])) . ' (ครบกำหนดชำระในอีก ' . diff_date($today, $invoice['rec_date'])['num'] . ' วัน)</span>' : '<span class="badge badge-pill badge-light-danger text-capitalized">วันที่ครบกำหนดชำระ : ' . date("j F Y", strtotime($invoice['rec_date'])) . ' (เกินกำหนดชำระมาแล้ว ' . diff_date($today, $invoice['rec_date'])['num'] . ' วัน)</span>';
            }
            # --- get value booking --- #
            if (in_array($invoice['id'], $first_booking) == false) {
                $first_booking[] = $invoice['id'];
                $inv_id[] = !empty($invoice['inv_id']) ? $invoice['inv_id'] : 0;
                $bo_id[$invoice['cover_id']][] = !empty($invoice['id']) ? $invoice['id'] : 0;
                $comp_id[] = !empty($invoice['comp_id']) ? $invoice['comp_id'] : 0;
                $agent_name[] = !empty($invoice['comp_name']) ? $invoice['comp_name'] : '';
                $agent_license[] = !empty($invoice['tat_license']) ? $invoice['tat_license'] : '';
                $agent_telephone[] = !empty($invoice['comp_telephone']) ? $invoice['comp_telephone'] : '';
                $agent_address[] = !empty($invoice['comp_address']) ? $invoice['comp_address'] : '';
                $adult[] = !empty($invoice['bp_adult']) ? $invoice['bp_adult'] : 0;
                $child[] = !empty($invoice['bp_child']) ? $invoice['bp_child'] : 0;
                $infant[] = !empty($invoice['bp_infant']) ? $invoice['bp_infant'] : 0;
                $foc[] = !empty($invoice['bp_foc']) ? $invoice['bp_foc'] : 0;
                $cot[] = !empty($invoice['total_paid']) ? $invoice['total_paid'] : 0;
                $start_pickup[] = !empty($invoice['start_pickup']) ? date('H:i', strtotime($invoice['start_pickup'])) : '00:00:00';
                $car_name[] = !empty($invoice['car_name']) ? $invoice['car_name'] : '';
                $cus_name[] = !empty($invoice['cus_name']) ? $invoice['cus_name'] : '';
                $book_full[] = !empty($invoice['book_full']) ? $invoice['book_full'] : '';
                $voucher_no[] = !empty($invoice['voucher_no_agent']) ? $invoice['voucher_no_agent'] : '';
                $pickup_type[] = !empty($invoice['pickup_type']) ? $invoice['pickup_type'] : 0;
                $room_no[] = !empty($invoice['room_no']) ? $invoice['room_no'] : '-';
                $hotel_pickup[] = !empty($invoice['pickup_name']) ? $invoice['pickup_name'] : $invoice['outside'];
                $zone_pickup[] = !empty($invoice['zonep_name']) ? ' (' . $invoice['zonep_name'] . ')' : '';
                $hotel_dropoff[] = !empty($invoice['dropoff_name']) ? $invoice['dropoff_name'] : $invoice['outside_dropoff'];
                $zone_dropoff[] = !empty($invoice['zoned_name']) ? ' (' . $invoice['zoned_name'] . ')' : '';
                $bp_note[] = !empty($invoice['bp_note']) ? $invoice['bp_note'] : '';
                $product_name[] = !empty($invoice['product_name']) ? $invoice['product_name'] : '';

                $arr_bo[$invoice['id']]['id'] = !empty($invoice['id']) ? $invoice['id'] : 0;
                $arr_bo[$invoice['id']]['travel_date'] = !empty($invoice['travel_date']) ? $invoice['travel_date'] : '';
                $arr_bo[$invoice['id']]['text_date'] = !empty($invoice['travel_date']) ? date("d/m/Y", strtotime($invoice['travel_date'])) : '';
                $arr_bo[$invoice['id']]['cus_name'] = !empty($invoice['cus_name']) ? $invoice['cus_name'] : '';
                $arr_bo[$invoice['id']]['product_name'] = !empty($invoice['product_name']) ? $invoice['product_name'] : '';
                $arr_bo[$invoice['id']]['voucher_no'] = !empty($invoice['voucher_no']) ? $invoice['voucher_no'] : $invoice['book_full'];
                $arr_bo[$invoice['id']]['adult'] = !empty($invoice['bp_adult']) ? $invoice['bp_adult'] : '-';
                $arr_bo[$invoice['id']]['child'] = !empty($invoice['bp_child']) ? $invoice['bp_child'] : '-';
                $arr_bo[$invoice['id']]['rate_adult'] = !empty($invoice['rate_adult']) && $invoice['bp_adult'] > 0 ? $invoice['rate_adult'] : '-';
                $arr_bo[$invoice['id']]['rate_child'] = !empty($invoice['rate_child']) && $invoice['bp_child'] > 0 ? $invoice['rate_child'] : '-';
                $arr_bo[$invoice['id']]['foc'] = !empty($invoice['bp_foc']) ? $invoice['bp_foc'] : '-';
                $arr_bo[$invoice['id']]['discount'] = !empty($invoice['discount']) ? $invoice['discount'] : '-';
                $arr_bo[$invoice['id']]['cot'] = !empty($invoice['total_paid']) ? $invoice['total_paid'] : '-';
                $arr_bo[$invoice['id']]['total'] = $invoice['bp_private_type'] == 1 ? ($invoice['bp_adult'] * $invoice['rate_adult']) + ($invoice['bp_child'] * $invoice['rate_child']) : $invoice['rate_total'];
            }
            # --- get value booking --- #
            if (in_array($invoice['bec_id'], $first_extar) == false && (!empty($invoice['extra_id']) || !empty($invoice['bec_name']))) {
                $first_extar[] = $invoice['bec_id'];
                $arr_extar[$invoice['id']]['id'][] = !empty($invoice['bec_id']) ? $invoice['bec_id'] : '-';
                $arr_extar[$invoice['id']]['name'][] = !empty($invoice['extra_id']) ? $invoice['extra_name'] : $invoice['bec_name'];
                $arr_extar[$invoice['id']]['adult'][] = !empty($invoice['bec_adult']) ? $invoice['bec_adult'] : $invoice['bec_privates'];
                $arr_extar[$invoice['id']]['child'][] = !empty($invoice['bec_child']) ? $invoice['bec_child'] : '-';
                $arr_extar[$invoice['id']]['rate_adult'][] = !empty($invoice['bec_rate_adult']) && $invoice['bec_adult'] > 0 ? $invoice['bec_rate_adult'] : '-';
                $arr_extar[$invoice['id']]['rate_child'][] = !empty($invoice['bec_rate_child']) && $invoice['bec_child'] > 0 ? $invoice['bec_rate_child'] : '-';
                $arr_extar[$invoice['id']]['privates'][] = !empty($invoice['bec_privates']) ? $invoice['bec_privates'] : '-';
                $arr_extar[$invoice['id']]['rate_private'][] = !empty($invoice['bec_rate_private']) && $invoice['bec_privates'] > 0 ? $invoice['bec_rate_private'] : '-';
                $arr_extar[$invoice['id']]['total'][] = $invoice['bec_type'] == 1 ? ($invoice['bec_adult'] * $invoice['bec_rate_adult']) + ($invoice['bec_child'] * $invoice['bec_rate_child']) : ($invoice['bec_privates'] * $invoice['bec_rate_private']);
            }
        }
    }
?>
    <div class="modal-header">
        <h4 class="modal-title"><span class="text-success"><?php echo $agent_name[0]; ?></span> (<?php echo !empty(substr($travel_date, 14, 24)) ? date('j F Y', strtotime(substr($travel_date, 0, 10))) . ' - ' . date('j F Y', strtotime(substr($travel_date, 14, 24))) : date('j F Y', strtotime($travel_date)); ?>)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" id="order-agent-image-table" style="background-color: #fff;">

        <div class="text-center mt-1 mb-1">
            <h4>
                <div class="badge badge-pill badge-light-warning">
                    <b class="text-danger"><?php echo $agent_name[0]; ?></b> <span class="text-danger">(<?php echo !empty(substr($travel_date, 14, 24)) ? date('j F Y', strtotime(substr($travel_date, 0, 10))) . ' - ' . date('j F Y', strtotime(substr($travel_date, 14, 24))) : date('j F Y', strtotime($travel_date)); ?>)</span>
                </div>
            </h4>
        </div>

        <table class="table table-striped text-uppercase table-vouchure-t2">
            <thead class="bg-light">
                <tr>
                    <th>วันที่ออก INVOICE</th>
                    <th>Invoice No.</th>
                    <th>Due Date</th>
                    <th width="2%">Booking</th>
                    <th class="text-center" width="8%">ภาษีมูลค่าเพิ่ม</th>
                    <th class="text-center" width="8%">หัก ณ ที่จ่าย (%)</th>
                    <th class="text-center" width="8%">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($cover_id)) {
                    for ($i = 0; $i < count($cover_id); $i++) {
                ?>
                        <tr data-dismiss="modal" data-toggle="modal" data-target="#modal-show" onclick="modal_show_invoice(<?php echo $cover_id[$i]; ?>);">
                            <td><?php echo date('j F Y', strtotime($inv_date[$i])); ?></td>
                            <td><?php echo $inv_full[$i]; ?></td>
                            <td><?php echo $due_date[$i]; ?></td>
                            <td class="text-center"><?php echo !empty($bo_id[$cover_id[$i]]) ? count($bo_id[$cover_id[$i]]) : '-'; ?></td>
                            <td class="text-center"><?php echo $vat[$i] != '-' ? $vat[$i] == 1 ? 'รวมภาษี 7%' : 'แยกภาษี 7%' : '-'; ?></td>
                            <td class="text-center"><?php echo $withholding[$i] != '-' ? $withholding[$i] . '%' : '-'; ?></td>
                            <td class="text-center"></td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect waves-float waves-light" data-dismiss="modal" data-toggle="modal" data-target="#modal-invoice" onclick="modal_invoice();">Submit</button>
    </div>
<?php
} else {
    echo false;
}
