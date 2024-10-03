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
                                            <option value="custom">Custom</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12" id="div-form" hidden>
                                    <div class="form-group">
                                        <label class="form-label" for="search_rec_form">receipt Date (Form)</label></br>
                                        <input type="text" class="form-control" id="search_rec_form" name="search_rec_form" value="<?php echo $get_date; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-12" id="div-to" hidden>
                                    <div class="form-group">
                                        <label class="form-label" for="search_rec_to">receipt Date (To)</label></br>
                                        <input type="text" class="form-control" id="search_rec_to" name="search_rec_to" value="<?php echo $get_date; ?>" />
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
                                                <option value="<?php echo $agent['id']; ?>"><?php echo $agent['name']; ?></option>
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
                    <div class="table-responsive" id="receipt-search-table">
                        <?php
                            $first_rec = array();
                            $first_inv = array();
                            $first_ext = array();
                            $total = 0;
                            $receipts = $recObj->showlistreceipt('all', '0000-00-00', '0000-00-00', 'all', 'list', 0);
                            # --- Check Invoice --- #
                            if (!empty($receipts)) {
                                foreach ($receipts as $receipt) {
                                    if (in_array($receipt['id'], $first_rec) == false) {
                                        $first_rec[] = $receipt['id'];
                                        # --- get value invoice --- #
                                        $is_approved[] = !empty($receipt['is_approved']) ? $receipt['is_approved'] : 0;
                                        $rec_id[] = !empty($receipt['id']) ? $receipt['id'] : 0;
                                        $rec_date[] = !empty($receipt['rec_date']) ? $receipt['rec_date'] : 0;
                                        $rec_full[] = !empty($receipt['rec_full']) ? $receipt['rec_full'] : '';
                                        $inv_full[] = !empty($receipt['inv_full']) ? $receipt['inv_full'] : '';
                                        $book_full[] = !empty($receipt['book_full']) ? $receipt['book_full'] : '';
                                        $brch_name[] = !empty($receipt['brch_name']) ? $receipt['brch_name'] : '';
                                        $payment[] = !empty($receipt['payt_id']) ? $receipt['payt_id'] : '';
                                        $banacc[] = !empty($receipt['banacc_id']) ? $receipt['banacc_id'] : '';
                                        $bank[] = !empty($receipt['bank_id']) ? $receipt['bank_id'] : '';
                                        $cheque_no[] = !empty($receipt['cheque_no']) ? $receipt['cheque_no'] : '';
                                        $cheque_date[] = !empty($receipt['cheque_date']) ? $receipt['cheque_date'] : '';
                                        $note[] = !empty($receipt['note']) ? $receipt['note'] : '';
                                        $vat[] = !empty($receipt['vat_id']) ? $receipt['vat_id'] : 0;
                                        $vat_name[] = !empty($receipt['vat_name']) ? $receipt['vat_name'] : 0;
                                        $withholding[] = !empty($receipt['withholding']) ? $receipt['withholding'] : 0;
                                        # --- get value company agent --- #
                                        $agent_id[] = !empty($receipt['comp_id']) ? $receipt['comp_id'] : 0;
                                        $agent_name[] = !empty($receipt['comp_name']) ? $receipt['comp_name'] : '';
                                        $agent_address[] = !empty($receipt['comp_address']) ? $receipt['comp_address'] : '';
                                        $agent_telephone[] = !empty($receipt['comp_telephone']) ? $receipt['comp_telephone'] : '';
                                        $agent_tat[] = !empty($receipt['comp_tat']) ? $receipt['comp_tat'] : '';
                                    }

                                    if (in_array($receipt['inv_id'], $first_inv) == false) {
                                        $first_inv[] = $receipt['inv_id'];
                                        $inv_id[$receipt['id']][] = !empty($receipt['inv_id']) ? $receipt['inv_id'] : 0;
                                        $inv_no[$receipt['id']][] = !empty($receipt['inv_no']) ? $receipt['inv_no'] : 0;
                                        $bo_id[$receipt['id']][] = !empty($receipt['bo_id']) ? $receipt['bo_id'] : 0;
                                        $bo_full[$receipt['id']][] = !empty($receipt['book_full']) ? $receipt['book_full'] : '';
                                        $voucher_no_agent[$receipt['id']][] = !empty($receipt['voucher_no_agent']) ? $receipt['voucher_no_agent'] : '';
                                        $travel_date[$receipt['id']][] = !empty($receipt['travel_date']) ? date("j F Y", strtotime($receipt['travel_date'])) : 0;
                                        $discount[$receipt['id']][] = !empty($receipt['discount']) ? $receipt['discount'] : 0;
                                        # --- get value booking products --- #
                                        $product_name[$receipt['id']][] = !empty($receipt['product_name']) ? $receipt['product_name'] : '';
                                        # --- get value payment --- #
                                        $bopay_id[$receipt['id']][] = !empty($receipt['bopay_id']) ? $receipt['bopay_id'] : 0;
                                        $bopay_id[$receipt['id']][] = !empty($receipt['bopay_id']) ? $receipt['bopay_id'] : 0;
                                        $bopay_name[$receipt['id']][] = !empty($receipt['bopay_name']) ? $receipt['bopay_name'] : '';
                                        # --- calculator --- #
                                        $transfer_type[$receipt['id']][] = !empty($receipt['transfer_type']) ? $receipt['transfer_type'] : 0;
                                        $rate_total[$receipt['id']][] = !empty($receipt['rate_total']) ? $receipt['rate_total'] : 0;
                                        $bt_id[$receipt['id']][] = !empty($receipt['bt_id']) ? $receipt['bt_id'] : 0;
                                        $bt_adult[$receipt['id']][] = !empty($receipt['bt_adult']) ? $receipt['bt_adult'] : 0;
                                        $bt_child[$receipt['id']][] = !empty($receipt['bt_child']) ? $receipt['bt_child'] : 0;
                                        $bt_infant[$receipt['id']][] = !empty($receipt['bt_infant']) ? $receipt['bt_infant'] : 0;
                                        $btr_rate_adult[$receipt['id']][] = !empty($receipt['btr_rate_adult']) && $receipt['transfer_type'] == 1 ? $receipt['btr_rate_adult'] : 0;
                                        $btr_rate_child[$receipt['id']][] = !empty($receipt['btr_rate_child']) && $receipt['transfer_type'] == 1 ? $receipt['btr_rate_child'] : 0;
                                        $btr_rate_infant[$receipt['id']][] = !empty($receipt['btr_rate_infant']) && $receipt['transfer_type'] == 1 ? $receipt['btr_rate_infant'] : 0;
                                    }
                                    # --- get value booking extra chang --- #
                                    if ((in_array($receipt['bec_id'], $first_ext) == false) && !empty($receipt['bec_id'])) {
                                        $first_ext[] = $receipt['bec_id'];
                                        $bec_id[$receipt['bo_id']][] = !empty($receipt['bec_id']) ? $receipt['bec_id'] : 0;
                                        $extra_name[$receipt['bo_id']][] = !empty($receipt['extra_name']) ? $receipt['extra_name'] : '';
                                        $bec_name[$receipt['bo_id']][] = !empty($receipt['bec_name']) ? $receipt['bec_name'] : '';
                                        $bec_type[$receipt['bo_id']][] = !empty($receipt['bec_type']) ? $receipt['bec_type'] : 0;
                                        $bec_adult[$receipt['bo_id']][] = !empty($receipt['bec_adult']) ? $receipt['bec_adult'] : 0;
                                        $bec_child[$receipt['bo_id']][] = !empty($receipt['bec_child']) ? $receipt['bec_child'] : 0;
                                        $bec_infant[$receipt['bo_id']][] = !empty($receipt['bec_infant']) ? $receipt['bec_infant'] : 0;
                                        $bec_privates[$receipt['bo_id']][] = !empty($receipt['bec_privates']) ? $receipt['bec_privates'] : 0;
                                        $bec_rate_adult[$receipt['bo_id']][] = !empty($receipt['bec_rate_adult']) ? $receipt['bec_rate_adult'] : 0;
                                        $bec_rate_child[$receipt['bo_id']][] = !empty($receipt['bec_rate_child']) ? $receipt['bec_rate_child'] : 0;
                                        $bec_rate_infant[$receipt['bo_id']][] = !empty($receipt['bec_rate_infant']) ? $receipt['bec_rate_infant'] : 0;
                                        $bec_rate_private[$receipt['bo_id']][] = !empty($receipt['bec_rate_private']) ? $receipt['bec_rate_private'] : 0;
                                    }
                                }
                            }
                        ?>
                        <table class="table table-bordered">
                            <thead class="bg-primary bg-darken-2 text-white">
                                <tr>
                                    <th>REC DATE</th>
                                    <th>RECEIPT NO.</th>
                                    <th>INVOICE NO.</th>
                                    <th class="cell-fit">AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($rec_id)) {
                                    for ($i = 0; $i < count($rec_id); $i++) {
                                        $href = 'href="javascript:void(0)" style="color:#6E6B7B" data-toggle="modal" data-target="#modal-show-receipt" onclick="modal_show_receipt(\''. $rec_id[$i] .'\');"';
                                        for ($b=0; $b < count($rate_total[$rec_id[$i]]); $b++) { 
                                            $total = 0;
                                            $total = $total + $rate_total[$rec_id[$i]][$b];
                                            $total = $transfer_type[$rec_id[$i]][$b] == 1 ? $total + ($bt_adult[$rec_id[$i]][$b] * $btr_rate_adult[$rec_id[$i]][$b]) : $total;
                                            $total = $transfer_type[$rec_id[$i]][$b] == 1 ? $total + ($bt_child[$rec_id[$i]][$b] * $btr_rate_child[$rec_id[$i]][$b]) : $total;
                                            $total = $transfer_type[$rec_id[$i]][$b] == 1 ? $total + ($bt_infant[$rec_id[$i]][$b] * $btr_rate_infant[$rec_id[$i]][$b]) : $total;
                                            $total = $transfer_type[$rec_id[$i]][$b] == 2 ? $recObj->sumbtrprivate($bt_id[$rec_id[$i]][$b])['sum_rate_private'] + $total : $total;
                                            if (!empty($bec_id[$bo_id[$rec_id[$i]][$b]])) {
                                                for ($a = 0; $a < count($bec_id[$bo_id[$rec_id[$i]][$b]]); $a++) {
                                                    $total = $bec_type[$bo_id[$rec_id[$i]][$b]][$a] == 1 ? $total + ($bec_adult[$bo_id[$rec_id[$i]][$b]][$a] * $bec_rate_adult[$bo_id[$rec_id[$i]][$b]][$a]) + ($bec_child[$bo_id[$rec_id[$i]][$b]][$a] * $bec_rate_child[$bo_id[$rec_id[$i]][$b]][$a]) + ($bec_infant[$bo_id[$rec_id[$i]][$b]][$a] * $bec_rate_infant[$bo_id[$rec_id[$i]][$b]][$a]) : $total;
                                                    $total = $bec_type[$bo_id[$rec_id[$i]][$b]][$a] == 2 ? $total + ($bec_privates[$bo_id[$rec_id[$i]][$b]][$a] * $bec_rate_private[$bo_id[$rec_id[$i]][$b]][$a]) : $total;
                                                }
                                            }
                                            $array_bo_total[$rec_id[$i]][] = $total;
                                            $total = $total - $discount[$rec_id[$i]][$b];
                                            // $total = ($bopay_id[$rec_id[$i]][$b] == 4 || $bopay_id[$rec_id[$i]][$b] == 5) ? $total - $bo_total_paid[$rec_id[$i]][$b] : $total;
                                            $array_total[$rec_id[$i]][] = $total;
                                        }
                                ?>
                                        <tr>
                                            <td>
                                                <a <?php echo $href; ?>><?php echo date("j F Y", strtotime($rec_date[$i])); ?></a>
                                                <input type="hidden" id="<?php echo 'rec' . $rec_id[$i]; ?>" name="<?php echo 'rec' . $rec_id[$i]; ?>"
                                                value="<?php echo $rec_id[$i]; ?>" 
                                                data-bo_id='<?php echo json_encode($bo_id[$rec_id[$i]]); ?>'
                                                data-inv_id='<?php echo json_encode($inv_id[$rec_id[$i]]); ?>'
                                                data-inv_full="<?php echo $inv_full[$i]; ?>"
                                                data-inv_no='<?php echo json_encode($inv_no[$rec_id[$i]]); ?>'
                                                data-voucher='<?php echo json_encode($voucher_no_agent[$rec_id[$i]]); ?>'
                                                data-product_name='<?php echo json_encode($product_name[$rec_id[$i]]); ?>'
                                                data-bo_full='<?php echo json_encode($bo_full[$rec_id[$i]]); ?>' 
                                                data-is_approved="<?php echo $is_approved[$i]; ?>"
                                                data-rec_date="<?php echo $rec_date[$i]; ?>"
                                                data-travel_date='<?php echo json_encode($travel_date[$rec_id[$i]]); ?>'
                                                data-branche="<?php echo $brch_name[$i]; ?>"
                                                data-payment="<?php echo $payment[$i]; ?>"
                                                data-bank="<?php echo $bank[$i]; ?>"
                                                data-banacc="<?php echo $banacc[$i]; ?>"
                                                data-cheque_no="<?php echo $cheque_no[$i]; ?>"
                                                data-cheque_date="<?php echo $cheque_date[$i]; ?>"
                                                data-note="<?php echo $note[$i]; ?>"
                                                data-vat="<?php echo $vat[$i]; ?>"
                                                data-vat_name="<?php echo $vat_name[$i]; ?>"
                                                data-withholding="<?php echo $withholding[$i]; ?>"
                                                data-agent_name="<?php echo $agent_name[$i]; ?>"
                                                data-agent_address="<?php echo $agent_address[$i]; ?>" 
                                                data-agent_telephone="<?php echo $agent_telephone[$i]; ?>" 
                                                data-agent_tat="<?php echo $agent_tat[$i]; ?>" 
                                                data-payment_id='<?php echo json_encode($bopay_id[$rec_id[$i]]); ?>'
                                                data-payment_paid='<?php echo json_encode($total_paid[$rec_id[$i]]); ?>'
                                                data-discount='<?php echo json_encode($discount[$rec_id[$i]]); ?>'
                                                data-total='<?php echo json_encode($array_total[$rec_id[$i]]); ?>'
                                                />
                                            </td>
                                            <td><a <?php echo $href; ?>><?php echo $rec_full[$i]; ?></a></td>
                                            <td><a <?php echo $href; ?>><?php echo $inv_full[$i]; ?></a></td>
                                            <td class="text-right"><a <?php echo $href; ?>><?php echo number_format(array_sum($array_total[$rec_id[$i]])); ?></a></td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        <!-- Start Form Modal -->
        <!------------------------------------------------------------------>
        <div class="modal-size-xl d-inline-block">
            <div class="modal fade text-left" id="modal-show-receipt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel16">Receipt</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="div-show-receipt">

                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------>
            <!-- End Form Modal -->

        </div>

        <div class="modal-size-xl d-inline-block">
            <div class="modal fade text-left" id="modal-add-receipt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
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
                                <input type="hidden" id="today" name="today" value="<?php echo $today; ?>">
                                <input type="hidden" id="rec_id" name="rec_id" value="">
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
                                            <tr>
                                                <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 15px 0px 0px 0px; padding: 5px 0;" width="7%"><b>เลขที่</b><br>
                                                    <small>No.</small>
                                                </td>
                                                <td class="text-center" bgcolor="#333" style="color: #fff;" width="12%"><b>INVOICE NO.</b>
                                                </td>
                                                <td class="text-center" bgcolor="#333" style="color: #fff;" width="12%"><b>VOUCHER NO.</b>
                                                </td>
                                                <td class="text-center" bgcolor="#333" style="color: #fff;" width="12%"><b>BOOKING NO.</b>
                                                </td>
                                                <td class="text-center" bgcolor="#333" style="color: #fff;" width="25%"><b>โปรแกรม</b><br>
                                                    <small>Programe</small>
                                                </td>
                                                <td class="text-center" bgcolor="#333" style="color: #fff;" width="12%"><b>วันที่เที่ยว</b><br>
                                                    <small>Travel Date.</small>
                                                </td>
                                                <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 0px 15px 0px 0px;" width="20%"><b>จํานวนเงิน</b><br>
                                                    <small>Amount</small>
                                                </td>
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
                                    <button type="button" class="btn btn-danger" onclick="deleteReceipt();">Delete</button>
                                    <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------>
            <!-- End Form Modal -->

        </div>
    </div>
</div>