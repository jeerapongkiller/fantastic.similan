<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Driver.php';

$drvObj = new Driver();

if (isset($_POST['action']) && $_POST['action'] == "search") {
    // get value from ajax
    $is_approved = $_POST['is_approved'] != "" ? $_POST['is_approved'] : '';
    $id_card = $_POST['id_card'] != "" ? $_POST['id_card'] : '';
    $first_name = $_POST['first_name'] != "" ? $_POST['first_name'] : '';
    $last_name = $_POST['last_name'] != "" ? $_POST['last_name'] : '';
    $nickname = $_POST['nickname'] != "" ? $_POST['nickname'] : '';
    $sex = $_POST['sex'] != "" ? $_POST['sex'] : '';

    $drivers = $drvObj->search($is_approved, $id_card, $first_name, $last_name, $nickname, $sex);
?>
    <table class="driver-list-table table table-responsive">
        <thead class="thead-light">
            <tr>
                <th>Status</th>
                <th>Full Name</th>
                <th>Sex</th>
                <th>Age</th>
                <th>Telephone</th>
                <th class="cell-fit">Edit</th>
                <?php if ($_SESSION["supplier"]["role_id"] == 1 || $_SESSION["supplier"]["role_id"] == 2) { ?>
                    <th class="cell-fit">Delete</th>
                <?php } ?>
            </tr>
        </thead>
        <?php if ($drivers) { ?>
            <tbody>
                <?php
                foreach ($drivers as $driver) {
                    $is_approved = $driver['is_approved'] == 1 ? 'Active' : 'Inactive';
                    $is_approved_class = $driver['is_approved'] == 1 ? 'badge-light-success' : 'badge-light-secondary';
                    $sex = $driver['sex'] == 1 ? 'Male' : 'Female';
                ?>
                    <tr>
                        <td><span class="badge badge-pill <?php echo $is_approved_class; ?> text-capitalized"><?php echo $is_approved; ?></span></td>
                        <td><?php echo $driver['name']; ?></td>
                        <td><?php echo $sex; ?></td>
                        <td><?php echo $drvObj->get_age($driver['birth_date']); ?></td>
                        <td><?php echo $driver['telephone']; ?></td>
                        <td class="text-center"><a href="./?pages=driver/edit&id=<?php echo $driver['id']; ?>"><i data-feather='edit'></i></a></td>
                        <?php if ($_SESSION["supplier"]["role_id"] == 1 || $_SESSION["supplier"]["role_id"] == 2) { ?>
                            <td class="text-center"><a href="javascript:void(0)" onclick="deleteDriver(<?php echo $driver['id']; ?>);"><i data-feather='trash-2'></i></a></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } ?>
    </table>
<?php
} else {
    echo $drivers = false;
}
