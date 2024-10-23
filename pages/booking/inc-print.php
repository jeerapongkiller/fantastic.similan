<?php
$id = $_GET['id'];
$bookings = $bookObj->get_data($id);
# --- get value booking --- #
if ($bookings[0]['id'] > 0) {
    $total_sum = 0;
    $total_product = 0;
    $data_send_email = array();
    $bo_id = !empty($bookings[0]['id']) ? $bookings[0]['id'] : 0;
    $book_full = !empty($bookings[0]['book_full']) ? $bookings[0]['book_full'] : '';
    $book_date = !empty(!empty($bookings[0]['booking_date'])) ? $bookings[0]['booking_date'] : '0000-00-00';
    $book_time = !empty(!empty($bookings[0]['booking_time'])) ? $bookings[0]['booking_time'] : '00:00:00';
    $voucher_no_agent = !empty($bookings[0]['voucher_no_agent']) ? $bookings[0]['voucher_no_agent'] : '';
    $discount = !empty($bookings[0]['discount']) ? $bookings[0]['discount'] : 0;
    $booker_id = !empty($bookings[0]['booker_id']) ? $bookings[0]['booker_id'] : 0;
    $booker_name = !empty($bookings[0]['booker_id']) ? $bookings[0]['booker_fname'] . ' ' . $bookings[0]['booker_lname'] : '';
    $sender = !empty($bookings[0]['sender']) ? $bookings[0]['sender'] : '';
    $agent_id = !empty($bookings[0]['company_id']) ? $bookings[0]['company_id'] : 0;
    $agent_name = !empty($bookings[0]['comp_name']) ? $bookings[0]['comp_name'] : '';
    $agent_license = !empty($bookings[0]['tat_license']) ? $bookings[0]['tat_license'] : '';
    $agent_telephone = !empty($bookings[0]['comp_telephone']) ? $bookings[0]['comp_telephone'] : '';
    $agent_address = !empty($bookings[0]['comp_address']) ? $bookings[0]['comp_address'] : '';
    $book_type = !empty(!empty($bookings[0]['booking_type_id'])) ? $bookings[0]['booking_type_id'] : 0;
    $book_type_name = !empty(!empty($bookings[0]['booking_type_id'])) ? $bookings[0]['booktye_name'] : '';
    $book_status = !empty(!empty($bookings[0]['booking_status_id'])) ? $bookings[0]['booking_status_id'] : 0;
    $book_status_name = !empty(!empty($bookings[0]['booksta_name'])) ? $bookings[0]['booksta_name'] : 0;
    $created_at = date('j F Y', strtotime($bookings[0]['created_at']));
    $guests_type = $agent_id == 0 ? 'Supplier' : 'Agent';
    # --- get value manage boat and transfer --- #
    $mange_transfer_id = !empty($bookings[0]['bomange_id']) ? $bookings[0]['bomange_id'] : 0;
    $mange_transfer = !empty($bookings[0]['manget_id']) ? $bookings[0]['manget_id'] : 0;
    $mange_boat_id = !empty($bookings[0]['boman_id']) ? $bookings[0]['boman_id'] : 0;
    $mange_boat = !empty($bookings[0]['mange_id']) ? $bookings[0]['mange_id'] : 0;
    # --- get value booking products --- #
    $bp_id = !empty($bookings[0]['bp_id']) ? $bookings[0]['bp_id'] : 0;
    $product_id = !empty($bookings[0]['product_id']) ? $bookings[0]['product_id'] : 0;
    $product_name = !empty($bookings[0]['product_name']) ? $bookings[0]['product_name'] : 0;
    $category_id = !empty($bookings[0]['category_id']) ? $bookings[0]['category_id'] : 0;
    $category_name = !empty($bookings[0]['category_name']) ? $bookings[0]['category_name'] : 0;
    $travel_date = !empty($bookings[0]['travel_date']) ? $bookings[0]['travel_date'] : '0000-00-00';
    $adult = !empty($bookings[0]['bp_adult']) ? $bookings[0]['bp_adult'] : 0;
    $child = !empty($bookings[0]['bp_child']) ? $bookings[0]['bp_child'] : 0;
    $infant = !empty($bookings[0]['bp_infant']) ? $bookings[0]['bp_infant'] : 0;
    $foc = !empty($bookings[0]['bp_foc']) ? $bookings[0]['bp_foc'] : 0;
    $bp_note = !empty($bookings[0]['bp_note']) ? $bookings[0]['bp_note'] : '';
    $private_type = !empty($bookings[0]['bp_private_type']) ? $bookings[0]['bp_private_type'] : 0;
    # --- get value booking product rate --- #
    $bpr_id = !empty($bookings[0]['bpr_id']) ? $bookings[0]['bpr_id'] : 0;
    $prod_rate_id = !empty($bookings[0]['product_rates_id']) ? $bookings[0]['product_rates_id'] : 0;
    $rate_adult = !empty($bookings[0]['rate_adult']) ? $bookings[0]['rate_adult'] : 0;
    $rate_child = !empty($bookings[0]['rate_child']) ? $bookings[0]['rate_child'] : 0;
    $rate_infant = !empty($bookings[0]['rate_infant']) ? $bookings[0]['rate_infant'] : 0;
    $rate_total = !empty($bookings[0]['rate_total']) ? $bookings[0]['rate_total'] : 0;
    $total_sum = $total_sum + $rate_total;
    $total_product = $total_product + $rate_total;
    # --- get value booking transfer --- #
    $bt_id = !empty($bookings[0]['bt_id']) ? $bookings[0]['bt_id'] : 0;
    $bt_adult = !empty($bookings[0]['bt_adult']) ? $bookings[0]['bt_adult'] : 0;
    $bt_child = !empty($bookings[0]['bt_child']) ? $bookings[0]['bt_child'] : 0;
    $bt_infant = !empty($bookings[0]['bt_infant']) ? $bookings[0]['bt_infant'] : 0;
    $bt_foc = !empty($bookings[0]['bt_foc']) ? $bookings[0]['bt_foc'] : 0;
    $start_pickup = !empty($bookings[0]['start_pickup']) ? $bookings[0]['start_pickup'] : '00:00:00';
    $end_pickup = !empty($bookings[0]['end_pickup']) ? $bookings[0]['end_pickup'] : '00:00:00';
    $hotel_pickup_id = !empty($bookings[0]['hotel_pickup_id']) ? $bookings[0]['hotel_pickup_id'] : 0;
    $hotel_pickup_name = !empty($bookings[0]['hotel_pickup_name']) ? $bookings[0]['hotel_pickup_name'] : '';
    $hotel_dropoff_id = !empty($bookings[0]['hotel_dropoff_id']) ? $bookings[0]['hotel_dropoff_id'] : 0;
    $hotel_dropoff_name = !empty($bookings[0]['hotel_dropoff_name']) ? $bookings[0]['hotel_dropoff_name'] : '';
    $hotel_pickup_outside = !empty($bookings[0]['hotel_pickup']) ? $bookings[0]['hotel_pickup'] : '';
    $hotel_dropoff_outside = !empty($bookings[0]['hotel_dropoff']) ? $bookings[0]['hotel_dropoff'] : '';
    $room_no = !empty($bookings[0]['room_no']) ? $bookings[0]['room_no'] : '';
    $bt_note = !empty($bookings[0]['bt_note']) ? $bookings[0]['bt_note'] : '';
    $transfer_type = !empty($bookings[0]['transfer_type']) ? $bookings[0]['transfer_type'] : 0;
    $pickup_type = !empty($bookings[0]['pickup_type']) ? $bookings[0]['pickup_type'] : 0;
    $pickup_id = !empty($bookings[0]['pickup_id']) ? $bookings[0]['pickup_id'] : 0;
    $pickup_name = !empty($bookings[0]['pickup_name']) ? $bookings[0]['pickup_name'] : '';
    $dropoff_id = !empty($bookings[0]['dropoff_id']) ? $bookings[0]['dropoff_id'] : 0;
    $dropoff_name = !empty($bookings[0]['dropoff_name']) ? $bookings[0]['dropoff_name'] : '';
    # --- get value booking transfer rate !transfer_type == 1! --- #
    $btr_id = !empty($bookings[0]['btr_id']) ? $bookings[0]['btr_id'] : 0;
    if ($transfer_type == 1) {
        $btr_rate_adult = !empty($bookings[0]['btr_rate_adult']) ? $bookings[0]['btr_rate_adult'] : 0;
        $btr_rate_child = !empty($bookings[0]['btr_rate_child']) ? $bookings[0]['btr_rate_child'] : 0;
        $btr_rate_infant = !empty($bookings[0]['btr_rate_infant']) ? $bookings[0]['btr_rate_infant'] : 0;
        $total_sum = $total_sum + ($bt_adult * $btr_rate_adult) + ($bt_child * $btr_rate_child) + ($bt_infant * $btr_rate_infant);
        $total_product = $total_product + ($bt_adult * $btr_rate_adult) + ($bt_child * $btr_rate_child) + ($bt_infant * $btr_rate_infant);
    } elseif ($transfer_type == 2) {
        $btr_rate_private = !empty($bookings[0]['btr_rate_private']) ? $bookings[0]['btr_rate_private'] : 0;
        $total_sum = $btr_rate_private + $total_sum;
        $total_product = $btr_rate_private + $total_product;
    }
    # --- get value Invoice --- #
    $inv_id = !empty($bookings[0]['inv_id']) ? $bookings[0]['inv_id'] : 0;
    $inv_date = !empty($bookings[0]['inv_date']) ? $bookings[0]['inv_date'] : '0000-00-00';
    $rec_date = !empty($bookings[0]['rec_date']) ? $bookings[0]['rec_date'] : '0000-00-00';
    $withholding = !empty($bookings[0]['withholding']) ? $bookings[0]['withholding'] : 0;
    $branche_id = !empty($bookings[0]['branche_id']) ? $bookings[0]['branche_id'] : 0;
    $vat_id = !empty($bookings[0]['vat_id']) ? $bookings[0]['vat_id'] : 0;
    $currency_id = !empty($bookings[0]['currency_id']) ? $bookings[0]['currency_id'] : 0;
    $inv_bank_account = !empty($bookings[0]['inv_bank_account']) ? $bookings[0]['inv_bank_account'] : 0;
    $inv_note = !empty($bookings[0]['inv_note']) ? $bookings[0]['inv_note'] : '';
    $inv_full = !empty($bookings[0]['inv_full']) ? $bookings[0]['inv_full'] : '';
    # --- get value in array !foreach! --- #
    $first_cus = array();
    $first_btr = array();
    $first_ext = array();
    $first_pay = array();
    foreach ($bookings as $booking) {
        # --- get value customers --- #
        if ((in_array($booking['cus_id'], $first_cus) == false) && !empty($booking['cus_id'])) {
            $first_cus[] = $booking['cus_id'];
            $customers['cus_id'][] = !empty($booking['cus_id']) ? $booking['cus_id'] : 0;
            $customers['cus_age'][] = !empty($booking['cus_age']) ? $booking['cus_age'] : 0;
            $customers['name'][] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
            $customers['birth_date'][] = !empty($booking['birth_date']) ? $booking['birth_date'] : '0000-00-00';
            $customers['id_card'][] = !empty($booking['id_card']) ? $booking['id_card'] : '';
            $customers['telephone'][] = !empty($booking['telephone']) ? $booking['telephone'] : '';
            $customers['address'][] = !empty($booking['cus_address']) ? $booking['cus_address'] : '';
            $customers['head'][] = !empty($booking['cus_head']) ? $booking['cus_head'] : 0;
            $customers['nationality'][] = !empty($booking['nationality_id']) ? $booking['nationality_id'] : 0;
            $customers['cus_type'][] = !empty($booking['cus_type']) ? $booking['cus_type'] : 0;
            $customers['nation_name'][] = !empty($booking['nation_name']) ? $booking['nation_name'] : '';
        }
        # --- get value booking Extra charge --- #
        if ((in_array($booking['bec_id'], $first_ext) == false) && !empty($booking['bec_id'])) {
            $first_ext[] = $booking['bec_id'];
            $extar['bec_id'][] = !empty($booking['bec_id']) ? $booking['bec_id'] : 0;
            $extar['bec_name'][] = !empty($booking['bec_name']) ? $booking['bec_name'] : '';
            $extar['extra_id'][] = !empty($booking['extra_id']) ? $booking['extra_id'] : 0;
            $extar['extra_name'][] = !empty($booking['extra_name']) ? $booking['extra_name'] : '';
            $extar['bec_type'][] = !empty($booking['bec_type']) ? $booking['bec_type'] : 0;
            $extar['bec_adult'][] = !empty($booking['bec_adult']) ? $booking['bec_adult'] : 0;
            $extar['bec_child'][] = !empty($booking['bec_child']) ? $booking['bec_child'] : 0;
            $extar['bec_infant'][] = !empty($booking['bec_infant']) ? $booking['bec_infant'] : 0;
            $extar['bec_privates'][] = !empty($booking['bec_privates']) ? $booking['bec_privates'] : 0;
            $extar['bec_rate_adult'][] = !empty($booking['bec_rate_adult']) ? $booking['bec_rate_adult'] : 0;
            $extar['bec_rate_child'][] = !empty($booking['bec_rate_child']) ? $booking['bec_rate_child'] : 0;
            $extar['bec_rate_infant'][] = !empty($booking['bec_rate_infant']) ? $booking['bec_rate_infant'] : 0;
            $extar['bec_rate_private'][] = !empty($booking['bec_rate_private']) ? $booking['bec_rate_private'] : 0;
            $extar['bec_rate_total'][] = $booking['bec_type'] > 0 ? $booking['bec_type'] == 1 ? (($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) + ($booking['bec_infant'] * $booking['bec_rate_infant'])) : ($booking['bec_privates'] * $booking['bec_rate_private']) : 0;
            $total_sum = $booking['bec_type'] > 0 ? $booking['bec_type'] == 1 ? $total_sum + (($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) + ($booking['bec_infant'] * $booking['bec_rate_infant'])) : $total_sum + ($booking['bec_privates'] * $booking['bec_rate_private']) : $total_sum;
        }
        # --- get value booking payment --- #
        if ((in_array($booking['bopa_id'], $first_pay) == false) && !empty($booking['bopa_id'])) {
            # --- in array get value booking payment --- #
            $first_pay[] = $booking['bopa_id'];
            $payments['bopa_id'][] = !empty($booking['bopa_id']) ? $booking['bopa_id'] : 0;
            $payments['bopay_id'][] = !empty($booking['bopay_id']) ? $booking['bopay_id'] : 0;
            $payments['bopay_name'][] = !empty($booking['bopay_name']) ? $booking['bopay_name'] : '';
            $payments['payment_type_id'][] = !empty($booking['payment_type_id']) ? $booking['payment_type_id'] : 0;
            $payments['bank_account_id'][] = !empty($booking['bank_account_id']) ? $booking['bank_account_id'] : 0;
            $payments['date_paid'][] = !empty($booking['date_paid']) ? $booking['date_paid'] : '0000-00-00';
            $payments['total_paid'][] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
            $payments['card_no'][] = !empty($booking['card_no']) ? $booking['card_no'] : '';
            $payments['bopa_photo'][] = !empty($booking['bopa_photo']) ? $booking['bopa_photo'] : '';
            $payments['bopa_note'][] = !empty($booking['bopa_note']) ? $booking['bopa_note'] : '';
            if ($booking['bopay_id'] == 4) {
                $cot_id = !empty($booking['bopa_id']) ? $booking['bopa_id'] : 0;
                $cot = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
            }
        }
        $payment_name = !empty($booking['bopay_name']) ? $booking['bopay_name'] : '';
    }
    $product_total = ($adult * $rate_adult) + ($child * $rate_child) + ($infant * $rate_infant);
    $payment_total = !empty($cot) ? $cot : 0;
    $transfer_total = ($transfer_type == 1) ? ($bt_adult * $btr_rate_adult) + ($bt_child * $btr_rate_child) + ($bt_infant * $btr_rate_infant) : $btr_rate_private;
    $extar_total = !empty($extar['bec_rate_total']) ? array_sum($extar['bec_rate_total']) : 0;
}
if (!empty($bookings[0]['bp_id'])) {
?>

    <div id="div-inc-print" style="background-color: #fff;">

        <!-- Header starts -->
        <div class="row" hidden>
            <div class="col-6">
                <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="120"></span>
            </div>
            <div class="col-6 text-right mt-md-0 mt-2">
                <span style="color: #000;">
                    <?php echo $main_document; ?>
                </span>
                <table width="100%" class="mt-50">
                    <tr>
                        <td rowspan="2" class="text-center" bgcolor="#c28f26" style="color: #fff; border-radius: 15px 0px 0px 0px;">
                            VOUCHER
                        </td>
                        <td class="text-center">
                            Voucher No.
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <?php echo $voucher_no_agent; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- Header ends -->

        <!-- Header 2 starts -->
        <table width="100%" hidden class="mt-1">
            <tr class="default-td">
                <td width="34%" align="left" colspan="4">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-right">
                            Booking No.
                        </dt>
                        <dd class="col-sm-8"><?php echo $book_full; ?></dd>
                    </dl>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-right">
                            Programe
                        </dt>
                        <dd class="col-sm-8"></dd>
                    </dl>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-right">
                            Travel Date
                        </dt>
                        <dd class="col-sm-8"><?php echo date('j F Y', strtotime($travel_date)); ?></dd>
                    </dl>
                    <?php $position_cus = !empty($customers['head']) ? array_search(1, $customers['head'], true) : ''; ?>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-right">
                            ชื่อลูกค้า
                        </dt>
                        <dd class="col-sm-8"><?php echo !empty($customers['name'][$position_cus]) ? $customers['name'][$position_cus] : ''; ?></dd>
                    </dl>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-right">
                            โทร
                        </dt>
                        <dd class="col-sm-8"><?php echo !empty($customers['telephone'][$position_cus]) ? $customers['telephone'][$position_cus] : ''; ?></dd>
                    </dl>
                </td>
                <td align="left" colspan="2">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-right">
                            Created
                        </dt>
                        <dd class="col-sm-8"><?php echo date('j F Y', strtotime($created_at)); ?></dd>
                    </dl>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-right">
                            Booker
                        </dt>
                        <dd class="col-sm-8"><?php echo $booker_name; ?></dd>
                    </dl>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-right">
                            Payment
                        </dt>
                        <dd class="col-sm-8"><?php echo !empty($payment_name) ? $payment_name : '-'; ?></dd>
                    </dl>
                </td>
                <td width="34%" align="left" colspan="2">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-right">
                            Company
                        </dt>
                        <dd class="col-sm-8"><?php echo $agent_name; ?></dd>
                    </dl>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-right">
                            Tel
                        </dt>
                        <dd class="col-sm-8"><?php echo $agent_telephone; ?></dd>
                    </dl>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-right">
                            Sender
                        </dt>
                        <dd class="col-sm-8"><?php echo $sender; ?></dd>
                    </dl>
                </td>
            </tr>
        </table>
        <!-- Header 2 ends -->

        <table width="100%" hidden class="mt-1">
            <tr>
                <td colspan="5">Detail</td>
            </tr>
            <tr>
                <td>1.</td>
                <td>Adult(s)</td>
                <td>3</td>
                <td>1,000</td>
                <td>3,000</td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Child(s)</td>
                <td>2</td>
                <td>500</td>
                <td>1,000</td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Infant(s)</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            </tr>
            <tr>
                <td>4.</td>
                <td>FOC</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            </tr>
            <tr colspan="5">
                <td>Amount 0</td>
            </tr>
        </table>

        <table width="100%" hidden class="mt-1">
            <tr>
                <td colspan="6">Extra Charge</td>
            </tr>
            <tr>
                <td>5.</td>
                <td>name</td>
                <td>Adult(s)</td>
                <td>3</td>
                <td>1,000</td>
                <td>3,000</td>
            </tr>
            <tr>
                <td>6.</td>
                <td>name</td>
                <td>Child(s)</td>
                <td>2</td>
                <td>500</td>
                <td>1,000</td>
            </tr>
            <tr>
                <td>7.</td>
                <td>name</td>
                <td>Infant(s)</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            </tr>
            <tr>
                <td>8.</td>
                <td>name</td>
                <td>FOC</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            </tr>
            <tr colspan="6">
                <td>Amount 0</td>
            </tr>
        </table>

        <table width="100%" hidden class="mt-1">
            <tr>
                <td colspan="6">Extra Charge</td>
            </tr>
            <tr>
                <td>5.</td>
                <td>name</td>
                <td>Adult(s)</td>
                <td>3</td>
                <td>1,000</td>
                <td>3,000</td>
            </tr>
            <tr>
                <td>6.</td>
                <td>name</td>
                <td>Child(s)</td>
                <td>2</td>
                <td>500</td>
                <td>1,000</td>
            </tr>
            <tr>
                <td>7.</td>
                <td>name</td>
                <td>Infant(s)</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            </tr>
            <tr>
                <td>8.</td>
                <td>name</td>
                <td>FOC</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            </tr>
            <tr colspan="6">
                <td>Amount 0</td>
            </tr>
        </table>

        <div class="card-body invoice-padding pb-0">
            <!-- Header starts -->
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
            <!-- Header ends -->
            <!-- Header starts -->
            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                <div class="col-6 mt-md-0 mt-2">
                    <h3>Voucher No. <?php echo $voucher_no_agent; ?></h3>
                    <h4>Booking No. <?php echo $book_full; ?></h4>
                    <h4><?php echo $agent_name; ?></h4>
                    <h4>Sender : <?php echo $sender; ?></h4>
                    <input type="hidden" value="<?php echo $book_full; ?>" id="booking_full">
                    <br>
                    <?php
                    $position_cus = !empty($customers['head']) ? array_search(1, $customers['head'], true) : '';
                    echo !empty($customers['name'][$position_cus]) ? '<b>ชื่อลูกค้า : </b>' . $customers['name'][$position_cus] . '</br>' : '';
                    // echo !empty($customers['whatsapp'][$position_cus]) ? $customers['whatsapp'][$position_cus] . '</br>' : '';
                    // echo !empty($customers['email'][$position_cus]) ? $customers['email'][$position_cus] . '</br>' : '';
                    echo !empty($customers['telephone'][$position_cus]) ? '<b>โทร : </b>' .  $customers['telephone'][$position_cus] . '</br>' : '';
                    echo !empty($customers['nation_name'][$position_cus]) ? '<b>สัญชาติ : </b>' .  $customers['nation_name'][$position_cus] . '</br>' : '';
                    // echo !empty($customers['address'][$position_cus]) ? $customers['address'][$position_cus] . '</br>' : '';
                    ?>
                </div>

                <div class="mt-md-0 mt-2">
                    <?php echo $_SESSION["supplier"]["fullname"]; ?>
                    <table style="border: 1px solid #ddd;" cellspacing="0" cellpadding="6" class="w-100">
                        <tr>
                            <td bgcolor="#d9d9d9">
                                Booking No:
                            </td>
                            <td align="right"><?php echo $book_full; ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#d9d9d9">
                                Created:
                            </td>
                            <td align="right"><?php echo date('j F Y', strtotime($created_at)); ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#d9d9d9">
                                Booker:
                            </td>
                            <td align="right"><?php echo $booker_name; ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#d9d9d9">
                                Payment :
                            </td>
                            <td align="right"><?php echo !empty($payment_name) ? $payment_name : 'ไม่ได้ระบุ'; ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#d9d9d9">
                                Travel Date:
                            </td>
                            <td align="right">
                                <?php echo date('j F Y', strtotime($travel_date)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#d9d9d9">
                                Total (THB):
                            </td>
                            <td align="right">฿ <?php echo number_format($total_sum); ?></td>
                        </tr>
                    </table>

                </div>
            </div>
            <!-- Header ends -->
        </div>

        <hr class="invoice-spacing" />

        <!-- Invoice Description starts -->
        <div class="table-responsive">
            <table width="100%" style="border: 1px solid #ddd;" cellspacing="0" cellpadding="10" class="table">
                <tr bgcolor="#d9d9d9">
                    <th align="left" class="py-1">Item</th>
                    <th align="left" class="py-1">Rate</th>
                    <th align="right" class="py-1">Amount</th>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #ddd;">
                        <!-- <b><?php // echo $product_name; 
                                ?></b><br> -->
                        <b><?php echo $category_name; ?></b><br>
                        ***REMARK***<br>
                        <?php echo !empty($bp_note) ? '<b>Note (Program Detail) : </b>' . $bp_note . '</br>' : ''; ?>
                        <?php echo !empty($pickup_name) ? '<b>Pickup : </b>' . $pickup_name . '</br>' : ''; ?>
                        <?php echo empty($hotel_pickup_id) ? !empty($hotel_pickup_outside) ? '<b>Hotel (Pickup) : </b>' . $hotel_pickup_outside . '</br>' : '' : '<b>Hotel (Pickup) : </b>' . $hotel_pickup_name . '</br>'; ?>
                        <?php echo !empty($room_no) ? '<b>Room : </b>' . $room_no . '</br>' : ''; ?>
                        <?php echo !empty($dropoff_name) ? '<b>Dropoff : </b>' . $dropoff_name . '</br>' : ''; ?>
                        <?php echo empty($hotel_dropoff_id) ? !empty($hotel_dropoff_outside) ? '<b>Hotel (Dropoff) : </b>' . $hotel_dropoff_outside . '</br>' : '' : '<b>Hotel (Dropoff) : </b>' . $hotel_dropoff_name . '</br>'; ?>
                        <?php echo !empty($bt_note) ? '<b>Note (Transfer) : </b>' . $bt_note : ''; ?>
                    </td>
                    <td class="inc_td_padding" style="border-bottom: 1px solid #ddd;">
                        <!-- Products rate -->
                        <table class="table table-bordered mb-1">
                            <tr bgcolor="#d9d9d9">
                                <th colspan="4" class="text-center"><b>Tour</b></th>
                            </tr>
                            <tr>
                                <td>Adult(s):</td>
                                <td align="right" class="text-nowrap"><?php echo $adult; ?> x</td>
                                <td>
                                    <?php
                                    echo number_format($rate_adult);
                                    ?>
                                </td>
                                <td class="text-nowrap text-right"><b>฿ <?php echo number_format($adult * $rate_adult); ?></b></td>
                            </tr>
                            <tr>
                                <td>Child(s):</td>
                                <td align="right" class="text-nowrap"><?php echo $child; ?> x</td>
                                <td>
                                    <?php
                                    echo number_format($rate_child);
                                    ?>
                                </td>
                                <td class="text-nowrap text-right"><b>฿ <?php echo number_format($child * $rate_child); ?></b></td>
                            </tr>
                            <tr>
                                <td>Infant(s):</td>
                                <td align="right" class="text-nowrap"><?php echo $infant; ?> x</td>
                                <td>
                                    <?php
                                    echo number_format($rate_infant);
                                    ?>
                                </td>
                                <td class="text-nowrap text-right"><b>฿ <?php echo number_format($infant * $rate_infant); ?></b></td>
                            </tr>
                            <tr>
                                <td>Foc(s):</td>
                                <td align="right" class="text-nowrap"><?php echo $foc; ?> x</td>
                                <td>0</td>
                                <td class="text-nowrap text-right"><b>฿ 0</b></td>
                            </tr>
                        </table>
                        <!-- Transfer Join transfer type !1! -->
                        <?php if (!empty($transfer_type) && $transfer_type == 1) { ?>
                            <table class="table table-bordered mb-1">
                                <tr bgcolor="#d9d9d9">
                                    <th colspan="4" class="text-center"><b>Transfer Join</b></th>
                                </tr>
                                <tr>
                                    <td>Adult(s):</td>
                                    <td align="right" class="text-nowrap"><?php echo $bt_adult; ?> x</td>
                                    <td>
                                        <?php echo number_format($btr_rate_adult); ?>
                                    </td>
                                    <td class="text-nowrap text-right"><b><?php echo ($bt_adult * $btr_rate_adult) > 0 ? '฿ ' . number_format($bt_adult * $btr_rate_adult) : 'Free'; ?></b></td>
                                </tr>
                                <tr>
                                    <td>Child(s):</td>
                                    <td align="right" class="text-nowrap"><?php echo $bt_child; ?> x</td>
                                    <td>
                                        <?php echo number_format($btr_rate_child); ?>
                                    </td>
                                    <td class="text-nowrap text-right"><b><?php echo ($bt_child * $btr_rate_child) > 0 ? '฿ ' . number_format($bt_child * $btr_rate_child) : 'Free'; ?></b></td>
                                </tr>
                                <tr>
                                    <td>Infant(s):</td>
                                    <td align="right" class="text-nowrap"><?php echo $bt_infant; ?> x</td>
                                    <td>
                                        <?php echo number_format($btr_rate_infant); ?>
                                    </td>
                                    <td class="text-nowrap text-right"><b><?php echo ($bt_infant * $btr_rate_infant) > 0 ? '฿ ' . number_format($bt_infant * $btr_rate_infant) : 'Free'; ?></b></td>
                                </tr>
                                <tr>
                                    <td>Foc(s):</td>
                                    <td align="right" class="text-nowrap"><?php echo $bt_foc; ?> x</td>
                                    <td>0</td>
                                    <td class="text-nowrap text-right"><b>Free</b></td>
                                </tr>
                            </table>
                        <?php } ?>
                        <!-- Transfer Private transfer type !2! -->
                        <?php if (!empty($transfer_type) && $transfer_type == 2) { ?>
                            <table class="table table-bordered mb-1">
                                <tr bgcolor="#d9d9d9">
                                    <th colspan="4" class="text-center"><b>Transfer Private</b></th>
                                </tr>
                                <?php
                                if (!empty($transfers['btr_id'])) {
                                    for ($i = 0; $i < count($transfers['btr_id']); $i++) {
                                ?>
                                        <tr>
                                            <td><?php echo $transfers['carc_name'][$i] . ':'; ?></td>
                                            <td><?php echo number_format($transfers['rate_private'][$i]); ?></td>
                                            <td class="text-nowrap text-right"><b>฿ <?php echo number_format($transfers['rate_private'][$i]); ?></b></td>
                                        </tr>
                                <?php }
                                } ?>
                            </table>
                        <?php } ?>
                    </td>
                    <td align="right" class="text-danger"><b>฿ <?php echo number_format($total_product); ?></b></td>
                </tr>
                <!-- Extra Chang -->
                <?php if (!empty($extar)) {
                    for ($i = 0; $i < count($extar['bec_id']); $i++) {
                ?>
                        <tr>
                            <td style="border-bottom: 1px solid #ddd;">
                                <b><?php echo !empty($extar['extra_id'][$i]) ? $extar['extra_name'][$i] : $extar['bec_name'][$i]; ?></b>
                            </td>
                            <td class="inc_td_padding" style="border-bottom: 1px solid #ddd;">
                                <?php if ($extar['bec_type'][$i] == 1) { ?>
                                    <table class="table table-bordered mb-1">
                                        <tr bgcolor="#d9d9d9">
                                            <th colspan="4" class="text-center"><b>Extra Charge Per Pax</b></th>
                                        </tr>
                                        <tr>
                                            <td>Adult(s):</td>
                                            <td align="right" class="text-nowrap"><?php echo $extar['bec_adult'][$i]; ?> x</td>
                                            <td>
                                                <?php echo number_format($extar['bec_rate_adult'][$i]); ?>
                                            </td>
                                            <td class="text-nowrap text-right"><b><?php echo ($extar['bec_adult'][$i] * $extar['bec_rate_adult'][$i]) > 0 ? '฿ ' . number_format($extar['bec_adult'][$i] * $extar['bec_rate_adult'][$i]) : 'Free'; ?></b></td>
                                        </tr>
                                        <tr>
                                            <td>Child(s):</td>
                                            <td align="right" class="text-nowrap"><?php echo $extar['bec_child'][$i]; ?> x</td>
                                            <td>
                                                <?php echo number_format($extar['bec_rate_child'][$i]); ?>
                                            </td>
                                            <td class="text-nowrap text-right"><b><?php echo ($extar['bec_child'][$i] * $extar['bec_rate_child'][$i]) > 0 ? '฿ ' . number_format($extar['bec_child'][$i] * $extar['bec_rate_child'][$i]) : 'Free'; ?></b></td>
                                        </tr>
                                        <tr>
                                            <td>Infant(s):</td>
                                            <td align="right" class="text-nowrap"><?php echo $extar['bec_infant'][$i]; ?> x</td>
                                            <td>
                                                <?php echo number_format($extar['bec_rate_infant'][$i]); ?>
                                            </td>
                                            <td class="text-nowrap text-right"><b><?php echo ($extar['bec_infant'][$i] * $extar['bec_rate_infant'][$i]) > 0 ? '฿ ' . number_format($extar['bec_infant'][$i] * $extar['bec_rate_infant'][$i]) : 'Free'; ?></b></td>
                                        </tr>
                                    </table>
                                <?php } elseif ($extar['bec_type'][$i] == 2) { ?>
                                    <table class="table table-bordered mb-1">
                                        <tr bgcolor="#d9d9d9">
                                            <th colspan="4" class="text-center"><b>Extra Charge Private</b></th>
                                        </tr>
                                        <tr>
                                            <td>Private(s):</td>
                                            <td align="right" class="text-nowrap"><?php echo $extar['bec_privates'][$i]; ?> x</td>
                                            <td>
                                                <?php echo number_format($extar['bec_rate_private'][$i]); ?>
                                            </td>
                                            <td class="text-nowrap text-right"><b><?php echo ($extar['bec_privates'][$i] * $extar['bec_rate_private'][$i]) > 0 ? '฿ ' . number_format($extar['bec_privates'][$i] * $extar['bec_rate_private'][$i]) : 'Free'; ?></b></td>
                                        </tr>
                                    </table>
                                <?php } ?>
                            </td>
                            <td align="right" class="text-danger"><b>฿ <?php echo number_format($extar['bec_rate_total'][$i]); ?></b></td>
                        </tr>
                <?php }
                } ?>
                <tr>
                    <td colspan="3" align="right"><b>Total :</b> ฿ <?php echo number_format($total_sum); ?></td>
                </tr>
                <?php
                if (!empty($payments['bopay_id'])) {
                    for ($p = 0; $p < count($payments['bopay_id']); $p++) {
                        if ($payments['bopay_id'][$p] == 4) {
                            echo '<tr><td colspan="3" align="right"><b>Cash on tour :</b> ฿ ' . number_format($payments['total_paid'][$p]) . '</td></tr>';
                        } elseif ($payments['bopay_id'][$p] == 5) {
                            echo '<tr><td colspan="3" align="right"><b>Deposit :</b> ฿ ' . number_format($payments['total_paid'][$p]) . '</td></tr>';
                        }
                        $total_sum = ($payments['bopay_id'][$p] == 4 || $payments['bopay_id'][$p] == 5) ? $total_sum - $payments['total_paid'][$p] : $total_sum;
                    }
                }
                ?>
                <tr>
                    <td colspan="3" align="right">
                        <table>
                            <tr>
                                <td style="border-top: 0; padding: 0"><b class="mr-1">Discount : </b></td>
                                <td style="border-top: 0; padding: 0">
                                    <div id="div-discound">
                                        <input type="hidden" id="inc_bp_id" name="inc_bp_id" value="<?php echo $bo_id; ?>">
                                        <div class="input-group" style="width: 200px">
                                            <input type="text" class="form-control numeral-mask" id="inc_discount" name="inc_discount" placeholder="1,000" value="<?php echo $discount > 0 ? '฿ ' . number_format($discount) : '';  ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text p-0"><button type="button" style="padding: 10px; border-radius: 0 0.357rem 0.357rem 0;" class="btn btn-primary" onclick="add_discount();">Add</button></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="right"> <b>Amount Paid : </b> ฿ <?php echo number_format($total_sum - $discount); ?></td>
                </tr>
            </table>
        </div>
    </div>
<?php  } ?>