<?php
require_once 'controllers/Driver.php';

$drvObj = new Driver();
$drivers = $drvObj->showlist();
?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- drivers list start -->
            <section class="app-supplier-list">
                <!-- drivers filter start -->
                <div class="card">
                    <h5 class="card-header">Search Filter</h5>
                    <form id="driver-search-form" name="driver-search-form" method="post" enctype="multipart/form-data">
                        <div class="d-flex align-items-center mx-50 row pt-0 pb-2">
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="is_approved">Status</label>
                                    <select class="form-control select2" id="is_approved" name="is_approved">
                                        <option value="all">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="id_card">ID Card</label>
                                    <input type="text" class="form-control" id="id_card" name="id_card" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="nickname">Nickname</label>
                                    <input type="text" class="form-control" id="nickname" name="nickname" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="sex">Gender</label>
                                    <select class="form-control select2" id="sex" name="sex">
                                        <option value="all">All</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <button type="submit" class="btn btn-primary" name="submit" value="Submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- drivers filter end -->
                <!-- list section start -->

                <div class="card">
                    <div class="card-datatable pt-0" id="driver-search-table">
                        <table class="driver-list-table table table-responsive">
                            <thead class="thead-light">
                                <tr>
                                    <th>Status</th>
                                    <th>Full Name</th>
                                    <th>Sex</th>
                                    <th>Age</th>
                                    <th>Telephone</th>
                                    <th class="cell-fit"></th>
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
                                            <td>
                                                <a href="./?pages=driver/edit&id=<?php echo $driver['id']; ?>">
                                                    <span class="badge badge-pill <?php echo $is_approved_class; ?> text-capitalized"><?php echo $is_approved; ?></span>
                                                </a>
                                            </td>
                                            <td><a href="./?pages=driver/edit&id=<?php echo $driver['id']; ?>" style="color:#6E6B7B"><?php echo $driver['name']; ?></a></td>
                                            <td><a href="./?pages=driver/edit&id=<?php echo $driver['id']; ?>" style="color:#6E6B7B"><?php echo $sex; ?></a></td>
                                            <td><a href="./?pages=driver/edit&id=<?php echo $driver['id']; ?>" style="color:#6E6B7B"><?php echo $drvObj->get_age($driver['birth_date']); ?></a></td>
                                            <td><a href="./?pages=driver/edit&id=<?php echo $driver['id']; ?>" style="color:#6E6B7B"><?php echo $driver['telephone']; ?></a></td>
                                            <td><a href="./?pages=driver/edit&id=<?php echo $driver['id']; ?>" style="color:#6E6B7B"></a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <!-- list section end -->
            </section>
            <!-- drivers list ends -->

        </div>
    </div>
</div>