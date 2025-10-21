<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Booking.php');

$bookObj = new Booking();

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['categorys'])) {
    # --- get value --- #
    $agent_id = !empty($_POST['agent_id']) && $_POST['agent_id'] != 'outside' ? $_POST['agent_id'] : 0;
    $product_id = !empty($_POST['product_id']) ? $_POST['product_id'] : 0;
    $travel_date = !empty($_POST['travel_date']) ? $_POST['travel_date'] : '0000-00-00';
    $categorys = !empty($_POST['categorys']) ? json_decode($_POST['categorys'], true) : [];
    $json_bpr = !empty($_POST['json_bpr']) ? json_decode($_POST['json_bpr'], true) : [];
    $book_type = !empty($_POST['book_type']) ? $_POST['book_type'] : 0;
    $transfer = 1;
    $category_id = [];
    foreach ($categorys as $category) {
        $category_id[] = $category['id'];
    }

    # --- show category rate --- #
    if (!empty($json_bpr)) {
        $sanitized_bpr = array_map('intval', $json_bpr);
        $bpr_values = implode(',', $sanitized_bpr);
        $before = (!empty($json_bpr)) ? $bookObj->get_values('*', 'booking_product_rates', 'id IN (' . $bpr_values . ')', 1) : [];
        foreach ($before as $bf) {
            echo '<input type="text" id="before_bpr" name="before_bpr[]" value="'.$bf['id'].'">';
        }
    }

    $rates = (!empty($category_id) && !empty($agent_id)) ? $bookObj->show_category_rate($agent_id, $travel_date, $category_id) : [];
    
    if (!empty($rates)) {
        $transfer = $rates[0]['transfer'];
?>
        <div class="col-12 mb-1">
            <div class="table-responsive border rounded">
                <table class="table table-striped table-borderless">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th>Adult</th>
                            <th>Children</th>
                            <th>Infant</th>
                            <th>FOC</th>
                            <?php if ($book_type == 2) {
                                echo '<th>Private</th>';
                            } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rates as $key => $value) { ?>
                            <tr>
                                <td>
                                    <input type="hidden" id="periodid" name="periodid[]" value="<?php echo $value['periodid']; ?>">
                                    <input type="hidden" id="prodrid" name="prodrid[]" value="<?php echo $value['prodrid']; ?>">
                                    <input type="hidden" id="bpr_id" name="bpr_id[]" value="<?php echo !empty($before[$key]['id']) ? $before[$key]['id'] : 0; ?>">
                                    <?php echo $value['name']; ?>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr style="background-color: transparent;">
                                            <!-- type Join or Private -->
                                            <?php if ($book_type == 1) { ?>
                                                <td width="30%" class="p-0">
                                                    <input type="text" class="form-control numeral-mask" id="adult" name="adult[]" value="<?php echo !empty($before[$key]['adult']) ? $before[$key]['adult'] : 0; ?>" oninput="check_rate();" />
                                                </td>
                                                <td width="1%" class="p-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x m-1 font-medium-4">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </td>
                                                <td width="69%" class="p-0">
                                                    <input type="text" id="rates_adult" name="rates_adult[]" class="form-control numeral-mask" value="<?php echo (!empty($before[$key]['rates_adult']) && $before[$key]['category_id'] == $value['id']) ? number_format($before[$key]['rates_adult']) : number_format($value['rate_adult']); ?>" oninput="check_rate();">
                                                </td>
                                            <?php } else if ($book_type == 2) { ?>
                                                <input type="text" class="form-control numeral-mask" id="adult" name="adult[]" value="<?php echo !empty($before[$key]['adult']) ? $before[$key]['adult'] : 0; ?>" oninput="check_rate();"/>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr style="background-color: transparent;">
                                            <?php if ($book_type == 1) { ?>
                                                <td width="30%" class="p-0">
                                                    <input type="text" class="form-control numeral-mask" id="child" name="child[]" value="<?php echo !empty($before[$key]['child']) ? $before[$key]['child'] : 0;; ?>" oninput="check_rate();" />
                                                </td>
                                                <td width="1%" class="p-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x m-1 font-medium-4">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </td>
                                                <td width="69%" class="p-0">
                                                    <input type="text" id="rates_child" name="rates_child[]" class="form-control numeral-mask" value="<?php echo (!empty($before[$key]['rates_child']) && $before[$key]['category_id'] == $value['id']) ? number_format($before[$key]['rates_child']) : number_format($value['rate_child']); ?>" oninput="check_rate();">
                                                </td>
                                            <?php } else if ($book_type == 2) { ?>
                                                <input type="text" class="form-control numeral-mask" id="child" name="child[]" value="<?php echo !empty($before[$key]['child']) ? $before[$key]['child'] : 0;; ?>" oninput="check_rate();"/>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr style="background-color: transparent;">
                                            <?php if ($book_type == 1) { ?>
                                                <td width="30%" class="p-0">
                                                    <input type="text" class="form-control numeral-mask" id="infant" name="infant[]" value="<?php echo !empty($before[$key]['infant']) ? $before[$key]['infant'] : 0;; ?>" oninput="check_rate();" />
                                                </td>
                                                <td width="1%" class="p-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x m-1 font-medium-4">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </td>
                                                <td width="69%" class="p-0">
                                                    <input type="text" id="rates_infant" name="rates_infant[]" class="form-control numeral-mask" value="<?php echo (!empty($before[$key]['rates_infant']) && $before[$key]['category_id'] == $value['id']) ? number_format($before[$key]['rates_infant']) : number_format($value['rate_infant']); ?>" oninput="check_rate();">
                                                </td>
                                            <?php } else if ($book_type == 2) { ?>
                                                <input type="text" class="form-control numeral-mask" id="infant" name="infant[]" value="<?php echo !empty($before[$key]['infant']) ? $before[$key]['infant'] : 0;; ?>" oninput="check_rate();"/>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <input type="text" id="foc" name="foc[]" class="form-control numeral-mask" value="<?php echo !empty($before[$key]['foc']) ? $before[$key]['foc'] : 0; ?>" oninput="check_rate();">
                                </td>
                                <?php if ($book_type == 2) { ?>
                                    <td>
                                        <input type="text" id="rates_private" name="rates_private[]" class="form-control numeral-mask" value="<?php echo (!empty($before[$key]['rates_private']) && $before[$key]['category_id'] == $value['id']) ? number_format($before[$key]['rates_private']) : number_format($value['rate_private']); ?>" oninput="check_rate();">
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    } else if (!empty($categorys)) {
        $transfer = $categorys[0]['transfer'];
    ?>
        <div class="col-12 mb-1">
            <div class="table-responsive border rounded">
                <table class="table table-striped table-borderless">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th>Adult</th>
                            <th>Children</th>
                            <th>Infant</th>
                            <th>FOC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorys as $key => $category) { ?>
                            <tr>
                                <td>
                                    <input type="hidden" id="periodid" name="periodid[]" value="0">
                                    <input type="hidden" id="prodrid" name="prodrid[]" value="0">
                                    <input type="hidden" id="bpr_id" name="bpr_id[]" value="<?php echo !empty($before[$key]['id']) ? $before[$key]['id'] : 0; ?>">
                                    <?php echo $category['name']; ?>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr style="background-color: transparent;">
                                            <!-- type Join or Private -->
                                            <?php if ($book_type == 1) { ?>
                                                <td width="30%" class="p-0">
                                                    <input type="text" class="form-control numeral-mask" id="adult" name="adult[]" value="<?php echo !empty($before[$key]['adult']) ? $before[$key]['adult'] : 0; ?>" oninput="check_rate();" />
                                                </td>
                                                <td width="1%" class="p-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x m-1 font-medium-4">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </td>
                                                <td width="69%" class="p-0">
                                                    <input type="text" id="rates_adult" name="rates_adult[]" class="form-control numeral-mask" value="<?php echo !empty($before[$key]['rates_adult']) ? $before[$key]['rates_adult'] : 0; ?>" oninput="check_rate();">
                                                </td>
                                            <?php } else if ($book_type == 2) { ?>
                                                <input type="text" class="form-control numeral-mask" id="adult" name="adult[]" value="<?php echo !empty($before[$key]['adult']) ? $before[$key]['adult'] : 0; ?>" oninput="check_rate();"/>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr style="background-color: transparent;">
                                            <?php if ($book_type == 1) { ?>
                                                <td width="30%" class="p-0">
                                                    <input type="text" class="form-control numeral-mask" id="child" name="child[]" value="<?php echo !empty($before[$key]['child']) ? $before[$key]['child'] : 0; ?>" oninput="check_rate();" />
                                                </td>
                                                <td width="1%" class="p-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x m-1 font-medium-4">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </td>
                                                <td width="69%" class="p-0">
                                                    <input type="text" id="rates_child" name="rates_child[]" class="form-control numeral-mask" value="<?php echo !empty($before[$key]['rates_child']) ? $before[$key]['rates_child'] : 0; ?>" oninput="check_rate();">
                                                </td>
                                            <?php } else if ($book_type == 2) { ?>
                                                <input type="text" class="form-control numeral-mask" id="child" name="child[]" value="<?php echo !empty($before[$key]['child']) ? $before[$key]['child'] : 0; ?>" oninput="check_rate();"/>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr style="background-color: transparent;">
                                            <?php if ($book_type == 1) { ?>
                                                <td width="30%" class="p-0">
                                                    <input type="text" class="form-control numeral-mask" id="infant" name="infant[]" value="<?php echo !empty($before[$key]['infant']) ? $before[$key]['infant'] : 0; ?>" oninput="check_rate();" />
                                                </td>
                                                <td width="1%" class="p-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x m-1 font-medium-4">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </td>
                                                <td width="69%" class="p-0">
                                                    <input type="text" id="rates_infant" name="rates_infant[]" class="form-control numeral-mask" value="<?php echo !empty($before[$key]['rates_infant']) ? $before[$key]['rates_infant'] : 0; ?>" oninput="check_rate();">
                                                </td>
                                            <?php } else if ($book_type == 2) { ?>
                                                <input type="text" class="form-control numeral-mask" id="infant" name="infant[]" value="<?php echo !empty($before[$key]['infant']) ? $before[$key]['infant'] : 0; ?>" oninput="check_rate();"/>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <input type="text" id="foc" name="foc[]" class="form-control numeral-mask" value="<?php echo !empty($before[$key]['foc']) ? $before[$key]['foc'] : 0; ?>" oninput="check_rate();">
                                </td>
                                <?php if ($book_type == 2) { ?>
                                    <td>
                                        <input type="text" id="rates_private" name="rates_private[]" class="form-control numeral-mask" value="<?php echo !empty($before[$key]['rates_private']) ? $before[$key]['rates_private'] : 0; ?>" oninput="check_rate();">
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }
    ?>
    <input type="hidden" id="include" name="include" value="<?php echo $transfer == 1 ? $transfer : 2; ?>" />
<?php
} else {
    echo $response = false;
}
        //     $rate_arr['periodid'] = !empty($rates[0]['periodid']) ? $rates[0]['periodid'] : 0;
        //     $rate_arr['prodrid'] = !empty($rates[0]['prodrid']) ? $rates[0]['prodrid'] : 0;
        //     $rate_arr['transfer'] = !empty($rates[0]['transfer']) ? $rates[0]['transfer'] : 1;
        //     $rate_arr['rate_adult'] = !empty($rates[0]['rate_adult']) ? $rates[0]['rate_adult'] : 0;
        //     $rate_arr['rate_child'] = !empty($rates[0]['rate_child']) ? $rates[0]['rate_child'] : 0;
        //     $rate_arr['rate_infant'] = !empty($rates[0]['rate_infant']) ? $rates[0]['rate_infant'] : 0;
        //     $rate_arr['rate_private'] = !empty($rates[0]['rate_private']) ? $rates[0]['rate_private'] : 0;
        //     switch ($product_id) {
        //         case 12:
        //             $rate_arr['periodid'] = 0;
        //             $rate_arr['prodrid'] = 0;
        //             $rate_arr['transfer'] = 1;
        //             $rate_arr['rate_adult'] = ($category_id == 14) ? 1700 : 2100;
        //             $rate_arr['rate_child'] = ($category_id == 14) ? 1200 : 1500;
        //             $rate_arr['rate_infant'] = 0;
        //             $rate_arr['rate_private'] = 0;
        //             break;
        //         case 13:
        //             $rate_arr['periodid'] = 0;
        //             $rate_arr['prodrid'] = 0;
        //             $rate_arr['transfer'] = 1;
        //             $rate_arr['rate_adult'] = ($category_id == 14) ? 1600 : 2000;
        //             $rate_arr['rate_child'] = ($category_id == 14) ? 1100 : 1400;
        //             $rate_arr['rate_infant'] = 0;
        //             $rate_arr['rate_private'] = 0;
        //             break;
        //         case 14:
        //             $rate_arr['periodid'] = 0;
        //             $rate_arr['prodrid'] = 0;
        //             $rate_arr['transfer'] = 1;
        //             $rate_arr['rate_adult'] = ($category_id == 14) ? 2500 : 3000;
        //             $rate_arr['rate_child'] = ($category_id == 14) ? 1700 : 2700;
        //             $rate_arr['rate_infant'] = 0;
        //             $rate_arr['rate_private'] = 0;
        //             break;
        //         case 15:
        //             $rate_arr['periodid'] = 0;
        //             $rate_arr['prodrid'] = 0;
        //             $rate_arr['transfer'] = 1;
        //             $rate_arr['rate_adult'] = ($category_id == 14) ? 1500 : 1900;
        //             $rate_arr['rate_child'] = ($category_id == 14) ? 1000 : 1300;
        //             $rate_arr['rate_infant'] = 0;
        //             $rate_arr['rate_private'] = 0;
        //             break;
        //     }