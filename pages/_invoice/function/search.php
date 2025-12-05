<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Invoice.php';

$invObj = new Invoice();
$today = date("Y-m-d");
$times = date("H:i:s");

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

$date_action = (!empty($_POST['search_travel']) || !empty($_POST['search_inv_date'])) ? true : false;

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['search_period']) && $date_action == true) {
    // get value from ajax
    $search_type = !empty($_POST['search_type']) ? $_POST['search_type'] : 'all';
    $search_period = !empty($_POST['search_period']) ? $_POST['search_period'] : 'all';
    $search_agent = !empty($_POST['search_agent']) ? $_POST['search_agent'] : 'all';
    $search_invoice = !empty($_POST['search_invoice']) ? $_POST['search_invoice'] : '';
    $search_booking = !empty($_POST['search_booking']) ? $_POST['search_booking'] : '';
    $search_voucher = !empty($_POST['search_voucher']) ? $_POST['search_voucher'] : '';
    $search_travel = !empty($_POST['search_travel']) ? $_POST['search_travel'] : '';
    $search_inv_date = !empty($_POST['search_inv_date']) ? $_POST['search_inv_date'] : '';
    $search_vat = !empty($_POST['search_vat']) ? $_POST['search_vat'] : '';

    $first_cover = array();
    $first_company = array();
    $first_booking = array();
    $first_bpr = array();
    $first_extar = array();
    $invoices = $invObj->showlist($search_type, $search_agent, $search_invoice, $search_booking, $search_voucher, $search_travel, $search_inv_date, $search_vat);
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
                $rec_id[$invoice['comp_id']][] = !empty($invoice['rec_id']) ? $invoice['rec_id'] : 0;
                $inv_full[$invoice['comp_id']][] = !empty($invoice['inv_full']) ? $invoice['inv_full'] : '';
                $inv_date[$invoice['comp_id']][] = !empty($invoice['inv_date']) ? $invoice['inv_date'] : '0000-00-00';
                $rec_date[$invoice['comp_id']][] = !empty($invoice['rec_date']) ? $invoice['rec_date'] : '0000-00-00';
                $vat[$invoice['comp_id']][] = !empty($invoice['vat']) ? $invoice['vat'] : '-';
                $withholding[$invoice['comp_id']][] = !empty($invoice['withholding']) ? $invoice['withholding'] : '-';
                $due_date[$invoice['comp_id']][] = (diff_date($today, $invoice['rec_date'])['day'] > 0) ? '<span class="badge badge-pill badge-light-warning text-capitalized">' . date("j F Y", strtotime($invoice['rec_date'])) . ' (ครบกำหนดชำระในอีก ' . diff_date($today, $invoice['rec_date'])['num'] . ' วัน)</span>' : '<span class="badge badge-pill badge-light-danger text-capitalized">' . date("j F Y", strtotime($invoice['rec_date'])) . ' (เกินกำหนดชำระมาแล้ว ' . diff_date($today, $invoice['rec_date'])['num'] . ' วัน)</span>';
            }
            # --- get value booking --- #
            if (in_array($invoice['id'], $first_booking) == false) {
                $first_booking[] = $invoice['id'];
                $bo_id[$invoice['comp_id']][] = !empty($invoice['id']) ? $invoice['id'] : 0;
                $bo_inv[$invoice['cover_id']][] = !empty($invoice['id']) ? $invoice['id'] : 0;
                $cot[$invoice['comp_id']][] = !empty($invoice['cot']) ? $invoice['cot'] : 0;
                $cot_comp[$invoice['comp_id']][] = !empty($invoice['cot']) ? $invoice['cot'] : 0;
                $cot_inv[$invoice['cover_id']][] = !empty($invoice['cot']) ? $invoice['cot'] : 0;

                # --- get value rates --- #
                $all_bookings = $invObj->fetch_all_bookingdetail($invoice['id']);
                $amount = 0;
                $rates_arr = array();
                foreach ($all_bookings as $rates) {
                    if (in_array($rates['bpr_id'], $rates_arr) == false) {
                        $rates_arr[] = $rates['bpr_id'];
                        if ($rates['booking_type_id'] == 1) {
                            $amount += $rates['adult'] * $rates['rates_adult'];
                            $amount += $rates['child'] * $rates['rates_child'];
                        } elseif ($rates['booking_type_id'] == 2) {
                            $amount += $rates['rates_private'];
                        }
                    }
                }
                
                $total_comp[$invoice['comp_id']][] = $amount;
                $total_inv[$invoice['cover_id']][] = $amount;
            }
            # --- get value booking --- #
            if (in_array($invoice['bec_id'], $first_extar) == false && (!empty($invoice['extra_id']) || !empty($invoice['bec_name']))) {
                $first_extar[] = $invoice['bec_id'];
                $extar['agent'][$invoice['comp_id']][] = $invoice['bec_type'] == 1 ? ($invoice['bec_adult'] * $invoice['bec_rate_adult']) + ($invoice['bec_child'] * $invoice['bec_rate_child']) : ($invoice['bec_privates'] * $invoice['bec_rate_private']);
                $extar['inv'][$invoice['cover_id']][] = $invoice['bec_type'] == 1 ? ($invoice['bec_adult'] * $invoice['bec_rate_adult']) + ($invoice['bec_child'] * $invoice['bec_rate_child']) : ($invoice['bec_privates'] * $invoice['bec_rate_private']);
            }
        }
    }
    if (!empty($agent_id)) {
        for ($i = 0; $i < count($agent_id); $i++) {
?>
            <div class="card" id="basic-table">
                <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                    <div class="col-lg-12 col-xl-12 text-center text-bold h4"><?php echo $agent_name[$i]; ?></div>
                </div>
                <table class="table table-bordered table-striped table-vouchure-t2">
                    <thead class="thead-light">
                        <tr class="table-warning">
                            <td colspan="9">
                                <dl class="row text-center m-0">
                                    <dt class="col-sm-3 m-0">Invoice <b><?php echo !empty($cover_id[$agent_id[$i]]) ? count($cover_id[$agent_id[$i]]) : 0; ?></b></dt>
                                    <dt class="col-sm-3 m-0">Booking <b><?php echo !empty($bo_id[$agent_id[$i]]) ? count($bo_id[$agent_id[$i]]) : 0; ?></b></dt>
                                    <dt class="col-sm-2 m-0">ยอดรวม <b><?php echo !empty($total_comp[$agent_id[$i]]) ? !empty($extar['agent'][$agent_id[$i]]) ? number_format((array_sum($total_comp[$agent_id[$i]]) + array_sum($extar['agent'][$agent_id[$i]])), 2) : number_format(array_sum($total_comp[$agent_id[$i]]), 2) : '-'; ?></b></dt>
                                    <dt class="col-sm-2 m-0">COT <b><?php echo !empty($cot_comp[$agent_id[$i]]) ? number_format(array_sum($cot_comp[$agent_id[$i]]), 2) : '-'; ?></b></dt>
                                    <dt class="col-sm-2 m-0">ยอดชำระ <b><?php echo !empty($total_comp[$agent_id[$i]]) ? !empty($extar['agent'][$agent_id[$i]]) ? number_format((array_sum($total_comp[$agent_id[$i]]) + array_sum($extar['agent'][$agent_id[$i]]) - array_sum($cot[$agent_id[$i]])), 2) : number_format(array_sum($total_comp[$agent_id[$i]]) - array_sum($cot[$agent_id[$i]]), 2) : '-'; ?></b></dt>
                                </dl>
                            </td>
                        </tr>
                        <tr>
                            <!-- <th></th> -->
                            <th>วันที่ออก INVOICE</th>
                            <th>Invoice No.</th>
                            <th>กำหนดรับชำระ</th>
                            <th class="text-center cell-fit">Booking</th>
                            <th class="text-center">ยอดรวม</th>
                            <th class="text-center">COT</th>
                            <th class="text-center">ยอดชำระ</th>
                            <th class="cell-fit">รับชำระ</th>
                            <th class="cell-fit">ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($cover_id[$agent_id[$i]])) {
                            for ($a = 0; $a < count($cover_id[$agent_id[$i]]); $a++) {
                                $modal = 'data-dismiss="modal" data-toggle="modal" data-target="#modal-invoice" onclick="modal_invoice(' . $cover_id[$agent_id[$i]][$a] . ');"';
                        ?>
                                <tr>
                                    <td <?php echo $modal; ?>><?php echo date('j F Y', strtotime($inv_date[$agent_id[$i]][$a])); ?></td>
                                    <td <?php echo $modal; ?>><?php echo $inv_full[$agent_id[$i]][$a]; ?></td>
                                    <td <?php echo $modal; ?>><?php echo $rec_id[$agent_id[$i]][$a] == 0 ? $due_date[$agent_id[$i]][$a] : '<span class="badge badge-pill badge-light-success text-capitalized">ชำระเรียบร้อยแล้ว</span>'; ?></td>
                                    <td <?php echo $modal; ?> class="text-center"><?php echo !empty($bo_inv[$cover_id[$agent_id[$i]][$a]]) ? count($bo_inv[$cover_id[$agent_id[$i]][$a]]) : '-'; ?></td>
                                    <td <?php echo $modal; ?> class="text-center"><?php echo !empty($total_inv[$cover_id[$agent_id[$i]][$a]]) ? !empty($extar['inv'][$cover_id[$agent_id[$i]][$a]]) ? number_format((array_sum($total_inv[$cover_id[$agent_id[$i]][$a]]) + array_sum($extar['inv'][$cover_id[$agent_id[$i]][$a]])), 2) : number_format(array_sum($total_inv[$cover_id[$agent_id[$i]][$a]]), 2) : '-'; ?></td>
                                    <td <?php echo $modal; ?> class="text-center"><?php echo !empty($cot_inv[$cover_id[$agent_id[$i]][$a]]) ? number_format(array_sum($cot_inv[$cover_id[$agent_id[$i]][$a]]), 2) : '-'; ?></td>
                                    <td <?php echo $modal; ?> class="text-center" width="8%"><?php echo !empty($total_inv[$cover_id[$agent_id[$i]][$a]]) ? !empty($extar['inv'][$cover_id[$agent_id[$i]][$a]]) ? number_format((array_sum($total_inv[$cover_id[$agent_id[$i]][$a]]) + array_sum($extar['inv'][$cover_id[$agent_id[$i]][$a]]) - array_sum($cot_inv[$cover_id[$agent_id[$i]][$a]])), 2) : number_format(array_sum($total_inv[$cover_id[$agent_id[$i]][$a]]) - array_sum($cot_inv[$cover_id[$agent_id[$i]][$a]]), 2) : '-'; ?></td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-gradient-info" data-toggle="modal" data-target="#modal-receipt" onclick="modal_receipt(<?php echo $cover_id[$agent_id[$i]][$a]; ?>, <?php echo $agent_id[$i]; ?>)">ชำระ</button></td>
                                    <td class="text-center"><a href="javascript:void(0)" onclick="deleteInvoice(<?php echo $cover_id[$agent_id[$i]][$a]; ?>);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </a></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
<?php
        }
    }
} else {
    echo $invoices = false;
}
