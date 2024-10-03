<?php
require_once 'controllers/Receipt.php';

$recObj = new Receipt();
$today = date("Y-m-d");
$times = date("H:i:s");
?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- cars list start -->
            <section class="app-user-list">
                <div class="card">
                    <!-- Search Filter Start -->
                    <div class="content-header">
                        <h5 class="pt-1 pl-2 pb-0">Search Filter</h5>
                        <form id="receipt-search-form" name="receipt-search-form" method="post" enctype="multipart/form-data">
                            <div class="d-flex align-items-center mx-50 row pt-0 pb-0">
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label for="search_period">Period</label>
                                        <select class="form-control select2" id="search_period" name="search_period" onchange="fun_search_period();">
                                            <option value="all">All</option>
                                            <option value="today">Today</option>
                                            <option value="tomorrow">Tomorrow</option>
                                            <option value="custom" <?php echo !empty($_GET['date']) ? 'selected' : ''; ?>>Custom</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12" id="div-due-form" hidden>
                                    <div class="form-group">
                                        <label class="form-label" for="search_due_form">Due Date. (Form)</label></br>
                                        <input type="text" class="form-control" id="search_due_form" name="search_due_form" value="<?php echo $get_date; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-12" id="div-due-to" hidden>
                                    <div class="form-group">
                                        <label class="form-label" for="search_due_to">Due Date (Form)</label></br>
                                        <input type="text" class="form-control" id="search_due_to" name="search_due_to" value="<?php echo $get_date; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="search_agent">Agents</label>
                                        <select class="form-control select2" id="search_agent" name="search_agent">
                                            <option value="all">All</option>
                                            <?php
                                            $agents = $recObj->showlistagent();
                                            foreach ($agents as $agent) {
                                            ?>
                                                <option value="<?php echo $agent['id']; ?>" data-name="<?php echo $agent['name']; ?>" data-address="<?php echo $agent['address']; ?>" data-telephone="<?php echo $agent['telephone']; ?>" data-tat="<?php echo $agent['tat_license']; ?>"><?php echo $agent['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <button type="submit" class="btn btn-primary" name="submit" value="Submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr class="pb-0 pt-0">
                    <div class="card-datatable pt-0" id="invoice-search-table">
                        <?php
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

                        $total = 0;
                        $first_inv = array();
                        $first_cover = array();
                        $first_ext = array();
                        $invoices = $recObj->showlistinvoice('all', '0000-00-00', '0000-00-00', 'all');
                        # --- Check products --- #
                        if (!empty($invoices)) {
                            foreach ($invoices as $invoice) {
                                # --- get value invoice --- #
                                if ((in_array($invoice['cover_id'], $first_cover) == false)) {
                                    $first_cover[] = $invoice['cover_id'];
                                    $cover_id[] = !empty($invoice['cover_id']) ? $invoice['cover_id'] : 0;
                                    $inv_full[] = !empty($invoice['inv_full']) ? $invoice['inv_full'] : 0;
                                    $rec_date[] = !empty($invoice['rec_date']) ? $invoice['rec_date'] : 0;
                                    $vat_id[] = !empty($invoice['vat_id']) ? $invoice['vat_id'] : 0;
                                    $vat_name[] = !empty($invoice['vat_name']) ? $invoice['vat_name'] : '-';
                                    $withholding[] = !empty($invoice['withholding']) ? $invoice['withholding'] : 0;
                                    $status[] = (diff_date($today, $invoice['rec_date'])['day'] > 0) ? '<span class="badge badge-pill badge-light-success text-capitalized">ครบกำหนดชำระในอีก ' . diff_date($today, $invoice['rec_date'])['num'] . ' วัน</span>' : '<span class="badge badge-pill badge-light-danger text-capitalized">เกินกำหนดชำระมาแล้ว ' . diff_date($today, $invoice['rec_date'])['num'] . ' วัน</span>';
                                }

                                if ((in_array($invoice['id'], $first_inv) == false)) {
                                    $first_inv[] = $invoice['id'];
                                    $inv_id[$invoice['cover_id']][] = !empty($invoice['id']) ? $invoice['id'] : 0;
                                    $inv_no[$invoice['cover_id']][] = !empty($invoice['no']) ? $invoice['no'] : 0;
                                    $bo_id[$invoice['cover_id']][] = !empty($invoice['bo_id']) ? $invoice['bo_id'] : 0;
                                    $travel_date[$invoice['cover_id']][] = !empty($invoice['travel_date']) ? $invoice['travel_date'] : 0;
                                    $book_full[$invoice['cover_id']][] = !empty($invoice['book_full']) ? $invoice['book_full'] : 0;
                                    $voucher_no[$invoice['cover_id']][] = !empty($invoice['voucher_no_agent']) ? $invoice['voucher_no_agent'] : 0;
                                    $brch_name[$invoice['cover_id']][] = !empty($invoice['brch_name']) ? $invoice['brch_name'] : '-';
                                    $discount[$invoice['cover_id']][] = !empty($invoice['discount']) ? $invoice['discount'] : 0;
                                    # --- get value booking products --- #
                                    $product_name[$invoice['cover_id']][] = !empty($invoice['product_name']) ? $invoice['product_name'] : '';
                                    # --- get value customer --- #
                                    $cus_name[$invoice['cover_id']][] = !empty($invoice['cus_name']) ? $invoice['cus_name'] : '';
                                    # --- get value company agent --- #
                                    $agent_id[$invoice['cover_id']][] = !empty($invoice['comp_id']) ? $invoice['comp_id'] : 0;
                                    $agent_name[$invoice['cover_id']][] = !empty($invoice['comp_name']) ? $invoice['comp_name'] : '';
                                    $agent_address[$invoice['cover_id']][] = !empty($invoice['comp_address']) ? $invoice['comp_address'] : '';
                                    $agent_telephone[$invoice['cover_id']][] = !empty($invoice['comp_telephone']) ? $invoice['comp_telephone'] : '';
                                    $agent_tat[$invoice['cover_id']][] = !empty($invoice['comp_tat']) ? $invoice['comp_tat'] : '';
                                    # --- get value payment --- #
                                    $bopay_id[$invoice['cover_id']][] = !empty($invoice['bopay_id']) ? $invoice['bopay_id'] : 0;
                                    $bopay_name[$invoice['cover_id']][] = !empty($invoice['bopay_name']) ? $invoice['bopay_name'] : 0;
                                    $total_paid[$invoice['cover_id']][] = !empty($invoice['bopay_id']) ? $invoice['total_paid'] : 0;
                                    # --- calculator --- #
                                    $bo_bopay_id[$invoice['bo_id']] = !empty($invoice['bopay_id']) ? $invoice['bopay_id'] : 0;
                                    $bo_total_paid[$invoice['bo_id']] = !empty($invoice['bopay_id']) ? $invoice['total_paid'] : 0;
                                    $bt_id[$invoice['bo_id']] = !empty($invoice['bt_id']) ? $invoice['bt_id'] : 0;
                                    $transfer_type[$invoice['bo_id']] = !empty($invoice['transfer_type']) ? $invoice['transfer_type'] : 0;
                                    $rate_total[$invoice['bo_id']] = !empty($invoice['rate_total']) ? $invoice['rate_total'] : 0;
                                    $bt_adult[$invoice['bo_id']] = !empty($invoice['bt_adult']) ? $invoice['bt_adult'] : 0;
                                    $bt_child[$invoice['bo_id']] = !empty($invoice['bt_child']) ? $invoice['bt_child'] : 0;
                                    $bt_infant[$invoice['bo_id']] = !empty($invoice['bt_infant']) ? $invoice['bt_infant'] : 0;
                                    $btr_rate_adult[$invoice['bo_id']] = !empty($invoice['btr_rate_adult']) && $invoice['transfer_type'] == 1 ? $invoice['btr_rate_adult'] : 0;
                                    $btr_rate_child[$invoice['bo_id']] = !empty($invoice['btr_rate_child']) && $invoice['transfer_type'] == 1 ? $invoice['btr_rate_child'] : 0;
                                    $btr_rate_infant[$invoice['bo_id']] = !empty($invoice['btr_rate_infant']) && $invoice['transfer_type'] == 1 ? $invoice['btr_rate_infant'] : 0;
                                }
                                # --- get value booking extra chang --- #
                                if ((in_array($invoice['bec_id'], $first_ext) == false) && !empty($invoice['bec_id'])) {
                                    $first_ext[] = $invoice['bec_id'];
                                    $bec_id[$invoice['bo_id']][] = !empty($invoice['bec_id']) ? $invoice['bec_id'] : 0;
                                    $extra_name[$invoice['bo_id']][] = !empty($invoice['extra_name']) ? $invoice['extra_name'] : '';
                                    $bec_name[$invoice['bo_id']][] = !empty($invoice['bec_name']) ? $invoice['bec_name'] : '';
                                    $bec_type[$invoice['bo_id']][] = !empty($invoice['bec_type']) ? $invoice['bec_type'] : 0;
                                    $bec_adult[$invoice['bo_id']][] = !empty($invoice['bec_adult']) ? $invoice['bec_adult'] : 0;
                                    $bec_child[$invoice['bo_id']][] = !empty($invoice['bec_child']) ? $invoice['bec_child'] : 0;
                                    $bec_infant[$invoice['bo_id']][] = !empty($invoice['bec_infant']) ? $invoice['bec_infant'] : 0;
                                    $bec_privates[$invoice['bo_id']][] = !empty($invoice['bec_privates']) ? $invoice['bec_privates'] : 0;
                                    $bec_rate_adult[$invoice['bo_id']][] = !empty($invoice['bec_rate_adult']) ? $invoice['bec_rate_adult'] : 0;
                                    $bec_rate_child[$invoice['bo_id']][] = !empty($invoice['bec_rate_child']) ? $invoice['bec_rate_child'] : 0;
                                    $bec_rate_infant[$invoice['bo_id']][] = !empty($invoice['bec_rate_infant']) ? $invoice['bec_rate_infant'] : 0;
                                    $bec_rate_private[$invoice['bo_id']][] = !empty($invoice['bec_rate_private']) ? $invoice['bec_rate_private'] : 0;
                                    # --- get value array booking extra charge --- #
                                    $array_extra[$invoice['bo_id']]['bec_id'][] = !empty($invoice['bec_id']) ? $invoice['bec_id'] : '';
                                    $array_extra[$invoice['bo_id']]['extra_name'][] = !empty($invoice['extra_name']) ? $invoice['extra_name'] : '';
                                    $array_extra[$invoice['bo_id']]['bec_name'][] = !empty($invoice['bec_name']) ? $invoice['bec_name'] : '';
                                    $array_extra[$invoice['bo_id']]['bec_type'][] = !empty($invoice['bec_type']) ? $invoice['bec_type'] : 0;
                                    $array_extra[$invoice['bo_id']]['bec_adult'][] = !empty($invoice['bec_adult']) ? $invoice['bec_adult'] : 0;
                                    $array_extra[$invoice['bo_id']]['bec_child'][] = !empty($invoice['bec_child']) ? $invoice['bec_child'] : 0;
                                    $array_extra[$invoice['bo_id']]['bec_infant'][] = !empty($invoice['bec_infant']) ? $invoice['bec_infant'] : 0;
                                    $array_extra[$invoice['bo_id']]['bec_privates'][] = !empty($invoice['bec_privates']) ? $invoice['bec_privates'] : 0;
                                    $array_extra[$invoice['bo_id']]['bec_rate_adult'][] = !empty($invoice['bec_rate_adult']) ? $invoice['bec_rate_adult'] : 0;
                                    $array_extra[$invoice['bo_id']]['bec_rate_child'][] = !empty($invoice['bec_rate_child']) ? $invoice['bec_rate_child'] : 0;
                                    $array_extra[$invoice['bo_id']]['bec_rate_infant'][] = !empty($invoice['bec_rate_infant']) ? $invoice['bec_rate_infant'] : 0;
                                    $array_extra[$invoice['bo_id']]['bec_rate_private'][] = !empty($invoice['bec_rate_private']) ? $invoice['bec_rate_private'] : 0;
                                }
                            }
                        }
                        ?>
                        <table class="invoice-list-table table table-responsive">
                            <thead class="thead-light">
                                <tr>
                                    <th class="cell-fit">STATUS</th>
                                    <th>AGENT</th>
                                    <th>DUE DATE.</th>
                                    <th>INVOICE NO.</th>
                                    <th class="cell-fit">ภาษีมูลค่าเพิ่ม</th>
                                    <th class="cell-fit">หัก ณ ที่จ่าย (%)</th>
                                    <th class="cell-fit">AMOUNT</th>
                                </tr>
                            </thead>
                            <?php if (!empty($invoices)) { ?>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($cover_id); $i++) {
                                        # --- get value total calculator --- #
                                        for ($b = 0; $b < count($bo_id[$cover_id[$i]]); $b++) {
                                            $total = 0;
                                            $total = $total + $rate_total[$bo_id[$cover_id[$i]][$b]];
                                            $total = $transfer_type[$bo_id[$cover_id[$i]][$b]] == 1 ? $total + ($bt_adult[$bo_id[$cover_id[$i]][$b]] * $btr_rate_adult[$bo_id[$cover_id[$i]][$b]]) : $total;
                                            $total = $transfer_type[$bo_id[$cover_id[$i]][$b]] == 1 ? $total + ($bt_child[$bo_id[$cover_id[$i]][$b]] * $btr_rate_child[$bo_id[$cover_id[$i]][$b]]) : $total;
                                            $total = $transfer_type[$bo_id[$cover_id[$i]][$b]] == 1 ? $total + ($bt_infant[$bo_id[$cover_id[$i]][$b]] * $btr_rate_infant[$bo_id[$cover_id[$i]][$b]]) : $total;
                                            $total = $transfer_type[$bo_id[$cover_id[$i]][$b]] == 2 ? $recObj->sumbtrprivate($bt_id[$bo_id[$cover_id[$i]][$b]])['sum_rate_private'] + $total : $total;
                                            if (!empty($bec_id[$bo_id[$cover_id[$i]][$b]])) {
                                                for ($a = 0; $a < count($bec_id[$bo_id[$cover_id[$i]][$b]]); $a++) {
                                                    $total = $bec_type[$bo_id[$cover_id[$i]][$b]][$a] == 1 ? $total + ($bec_adult[$bo_id[$cover_id[$i]][$b]][$a] * $bec_rate_adult[$bo_id[$cover_id[$i]][$b]][$a]) + ($bec_child[$bo_id[$cover_id[$i]][$b]][$a] * $bec_rate_child[$bo_id[$cover_id[$i]][$b]][$a]) + ($bec_infant[$bo_id[$cover_id[$i]][$b]][$a] * $bec_rate_infant[$bo_id[$cover_id[$i]][$b]][$a]) : $total;
                                                    $total = $bec_type[$bo_id[$cover_id[$i]][$b]][$a] == 2 ? $total + ($bec_privates[$bo_id[$cover_id[$i]][$b]][$a] * $bec_rate_private[$bo_id[$cover_id[$i]][$b]][$a]) : $total;
                                                }
                                            }
                                            $array_bo_total[$cover_id[$i]][] = $total;
                                            $total = $total - array_sum($discount[$cover_id[$i]]);
                                            $total = ($bo_bopay_id[$bo_id[$cover_id[$i]][$b]] == 4 || $bo_bopay_id[$bo_id[$cover_id[$i]][$b]] == 5) ? $total - $bo_total_paid[$bo_id[$cover_id[$i]][$b]] : $total;
                                            $array_total[$cover_id[$i]][] = $total;
                                        }
                                        $href = 'href="javascript:void(0)" style="color:#6E6B7B" data-toggle="modal" data-target="#modal_add_receipt" onclick="modal_receipt(\'' . $cover_id[$i] . '\');"';
                                    ?>
                                        <tr>
                                            <td>
                                                <a <?php echo $href; ?>>
                                                    <?php echo $status[$i]; ?>
                                                    <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkbox<?php echo $cover_id[$i]; ?>" name="cover_id" value="<?php echo $cover_id[$i]; ?>" 
                                                    data-inv_id='<?php echo json_encode($inv_id[$cover_id[$i]]); ?>' 
                                                    data-bo_id='<?php echo json_encode($bo_id[$cover_id[$i]]); ?>' 
                                                    data-bo_full='<?php echo json_encode($book_full[$cover_id[$i]]); ?>' 
                                                    data-inv_full="<?php echo $inv_full[$i]; ?>" 
                                                    data-inv_no='<?php echo json_encode($inv_no[$cover_id[$i]]); ?>' 
                                                    data-voucher='<?php echo json_encode($voucher_no[$cover_id[$i]]); ?>' 
                                                    data-cus_name='<?php echo json_encode($cus_name[$cover_id[$i]]); ?>' 
                                                    data-product_name='<?php echo json_encode($product_name[$cover_id[$i]]); ?>' 
                                                    data-vat_id="<?php echo $vat_id[$i]; ?>" 
                                                    data-vat_name="<?php echo $vat_name[$i]; ?>" 
                                                    data-withholding="<?php echo $withholding[$i]; ?>" 
                                                    data-rec_date="<?php echo date("j F Y", strtotime($rec_date[$i])); ?>" 
                                                    data-brch_name="<?php echo $brch_name[$cover_id[$i]][0]; ?>" 
                                                    data-payment_id='<?php echo json_encode($bopay_id[$cover_id[$i]]); ?>' 
                                                    data-payment_name='<?php echo json_encode($bopay_name[$cover_id[$i]]); ?>' 
                                                    data-total_paid='<?php echo json_encode($total_paid[$cover_id[$i]]); ?>' 
                                                    data-discount='<?php echo json_encode($discount[$cover_id[$i]]); ?>' 
                                                    data-total='<?php echo json_encode($array_total[$cover_id[$i]]); ?>' 
                                                    data-agent_name="<?php echo $agent_name[$cover_id[$i]][0]; ?>" 
                                                    data-agent_address="<?php echo $agent_address[$cover_id[$i]][0]; ?>" 
                                                    data-agent_telephone="<?php echo $agent_telephone[$cover_id[$i]][0]; ?>" 
                                                    data-agent_tat="<?php echo $agent_tat[$cover_id[$i]][0]; ?>" />
                                                </a>
                                            </td>
                                            <td><a <?php echo $href; ?>><?php echo $agent_name[$cover_id[$i]][0]; ?></a></td>
                                            <td><a <?php echo $href; ?>><?php echo date("j F Y", strtotime($rec_date[$i])); ?></a></td>
                                            <td><a <?php echo $href; ?>><?php echo $inv_full[$i]; ?></a></td>
                                            <td class="text-nowrap"><a <?php echo $href; ?>><?php echo $vat_name[$i]; ?></a></td>
                                            <td><a <?php echo $href; ?>><?php echo $withholding[$i]; ?></a></td>
                                            <td class="text-right"><a <?php echo $href; ?>><?php echo number_format(array_sum($array_total[$cover_id[$i]])); ?></a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <!-- list section end -->

                <!-- Start Form Modal -->
                <!------------------------------------------------------------------>
                <div class="modal-size-xl d-inline-block">
                    <div class="modal fade text-left" id="modal_add_receipt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel16">สร้าง Receipt</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="receipt-form" name="receipt-form" method="post" enctype="multipart/form-data">
                                        <input type="hidden" id="cover_id" name="cover_id" value="">
                                        <input type="hidden" id="bo_id" name="bo_id" value="">
                                        <input type="hidden" id="today" name="today" value="<?php echo $today; ?>">
                                        <input type="hidden" id="amount" name="amount" value="">
                                        <div id="div-show"></div>
                                        <div class="row">
                                            <div class="form-group col-md-3 col-12">
                                                <label class="form-label" for="is_approved"></label>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_approved" name="is_approved" value="1" checked />
                                                    <label class="custom-control-label" for="is_approved">ชำระเงินแล้ว</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" hidden>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="rec_no">ใบเสร็จรับเงิน</label>
                                                <input type="hidden" id="rec_no" name="rec_no" value="">
                                                <input type="hidden" id="rec_full" name="rec_full" value="">
                                                <div class="input-group">
                                                    <span id="rec_no_text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3 col-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="rec_date">วันที่ชำระ</label></br>
                                                    <input type="text" class="form-control" id="rec_date" name="rec_date" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="content-header">
                                                    <h5>Agent</h5>
                                                </div>
                                                <hr class="mt-0">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label class="form-label" for="agent_name">Company</label>
                                                <div class="input-group">
                                                    <span id="agent_name_text"></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label class="form-label" for="agent_tax">Tax ID.</label>
                                                <div class="input-group">
                                                    <span id="agent_tax_text"></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label class="form-label" for="agent_tel">Tel</label>
                                                <div class="input-group">
                                                    <span id="agent_tel_text"></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label class="form-label" for="agent_address">Address</label>
                                                <div class="input-group">
                                                    <span id="agent_address_text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Start Data Table payment -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="content-header">
                                                    <h5 class="mt-1">การชำระเงิน</h5>
                                                </div>
                                                <hr class="mt-0">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="branch">สาขา</label>
                                                    <div class="input-group">
                                                        <span id="branch_text"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <div class="form-group">
                                                    <?php $payments = $recObj->showpayments(2); ?>
                                                    <label for="payments_type">รูปแบบการชำระเงิน</label>
                                                    <select class="form-control select2" id="payments_type" name="payments_type" onchange="check_payment();">
                                                        <option value="">Please choose payments type ... </option>
                                                        <?php
                                                        foreach ($payments as $payment) {
                                                        ?>
                                                            <option value="<?php echo $payment['id']; ?>" data-name="<?php echo $payment['name']; ?>"><?php echo $payment['name']; ?></option>
                                                        <?php
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12" id="div-bank-account">
                                                <div class="form-group">
                                                    <?php $banks_acc = $recObj->showbankaccount(); ?>
                                                    <label for="bank_account">เข้าบัญชี</label>
                                                    <select class="form-control select2" id="bank_account" name="bank_account">
                                                        <option value="">Please choose bank account ... </option>
                                                        <?php
                                                        foreach ($banks_acc as $bank_acc) {
                                                        ?>
                                                            <option value="<?php echo $bank_acc['id']; ?>" data-name="<?php echo $bank_acc['account_name']; ?>"><?php echo $bank_acc['banName'] . ' ' . $bank_acc['account_no'] . ' (' . $bank_acc['account_name'] . ')'; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12" id="div-bank">
                                                <div class="form-group">
                                                    <?php $banks = $recObj->showbank(); ?>
                                                    <label for="rec_bank">ธนาคาร</label>
                                                    <select class="form-control select2" id="rec_bank" name="rec_bank">
                                                        <option value="">Please choose bank ... </option>
                                                        <?php
                                                        foreach ($banks as $bank) {
                                                            // $selected_payment = $payments_type == $payment['id'] ? 'selected' : '';
                                                        ?>
                                                            <option value="<?php echo $bank['id']; ?>" data-name="<?php echo $bank['name']; ?>"><?php echo $bank['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12" id="div-check-no">
                                                <div class="form-group">
                                                    <label class="form-label" for="check_no">เลขที่เช็ค</label>
                                                    <input type="text" id="check_no" name="check_no" class="form-control" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12" id="div-check-date">
                                                <div class="form-group">
                                                    <label class="form-label" for="date_check">วันที่เช็ค</label></br>
                                                    <input type="text" class="form-control" id="date_check" name="date_check" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <table class="table table-bordered">
                                                <thead class="bg-warning bg-darken-2 text-white">
                                                    <!-- <tr>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 15px 0px 0px 0px; padding: 5px 0;" width="10%"><b>No.</b>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="20%"><b>Invoice No.</b>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="20%"><b>Voucher No.</b>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="15%"><b>DUE. DATE</b>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="15%"><b>Payment</b>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 0px 15px 0px 0px;" width="20%"><b>Amount</b>
                                                        </td>
                                                    </tr> -->
                                                    <tr>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 15px 0px 0px 0px; padding: 5px 0;" width="2%"><b>เลขที่</b><br>
                                                            <small>No.</small>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="6%"><b>Invoice No.</b>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="6%"><b>Booking No.</b>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="6%"><b>Voucher No.</b>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="18%"><b>ชื่อลูค้า</b><br>
                                                            <small>Customer's Name</small>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="18%"><b>โปรแกรม</b><br>
                                                            <small>Programe</small>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="15%"><b>DUE. DATE</b></td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="14%"><b>Payment</b>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 0px 15px 0px 0px;" width="15%"><b>Amount</b></td>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-invoice">
                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="hidden" id="price_total" name="price_total" value="">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="note">Note</label>
                                                    <textarea class="form-control" name="note" id="note" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span></span>
                                            <button type="submit" class="btn btn-primary" id="btn-submit-inv" name="submit" value="Submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------------------------------------>
                    <!-- End Form Modal -->

                </div>
            </section>
            <!-- cars type list ends -->
        </div>
    </div>
</div>