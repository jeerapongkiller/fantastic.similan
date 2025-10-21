<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Quotation.php';

$quotObj = new Quotation();

if (isset($_POST['action']) && $_POST['action'] == "search") {
    // get value from ajax
    $title = $_POST['title'] != "" ? $_POST['title'] : 'all';
    $name = $_POST['name'] != "" ? $_POST['name'] : '';
    $date_quo = $_POST['date_quo'] != "" ? $_POST['date_quo'] : '0000-00-00';
    $cus_name = $_POST['cus_name'] != "" ? $_POST['cus_name'] : '';

    $quotations = $quotObj->search($title, $name, $date_quo, $cus_name);
?>
    <table class="quotation-list-table table table-responsive">
        <thead class="thead-light">
            <tr>
                <th></th>
                <th>เลขที่</th>
                <th>วันที่</th>
                <th>ผู้ขาย</th>
                <th>ชื่องาน</th>
                <th>ลูกค้า</th>
            </tr>
        </thead>
        <?php if ($quotations) { ?>
            <tbody>
                <?php
                foreach ($quotations as $quotation) {
                    $href = 'href="./?pages=quotation/edit&id=' . $quotation['id'] . '" style="color:#6E6B7B"';
                ?>
                    <tr>
                        <td><a <?php echo $href; ?>><?php echo $quotation['title'] == 1 ? 'ใบเสนอราคา' : 'ใบแจ้งหนี้'; ?></a></td>
                        <td class="cell-fit"><a <?php echo $href; ?>><?php echo $quotation['quo_full'] ?></a></td>
                        <td><a <?php echo $href; ?>><?php echo date('j F Y', strtotime($quotation['date_quo'])); ?></a></td>
                        <td><a <?php echo $href; ?>><?php echo $quotation['seller']; ?></a></td>
                        <td><a <?php echo $href; ?>><?php echo $quotation['name']; ?></a></td>
                        <td><a <?php echo $href; ?>><?php echo $quotation['cus_name']; ?></a></td>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } ?>
    </table>
<?php
} else {
    echo $quotations = false;
}
