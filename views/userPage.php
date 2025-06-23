<!-- views/home.php -->

<!DOCTYPE html>
<html>

<?php includeWithData(__DIR__ . '../common/head.php', [
    'title' => 'User Details',
]);
?>

<body ng-app="myApp" ng-controller="MyController as jb">

    <div
        ng-init="jb.commonInit(<?= htmlspecialchars(json_encode($this->auth), ENT_QUOTES, 'UTF-8') ?>);jb.usersInit(<?= htmlspecialchars(json_encode($userDetails), ENT_QUOTES, 'UTF-8') ?>)">
        <!-- Sidebar -->
        <?php include __DIR__ . '../common/sideBar.php'; ?>

        <!-- Main Content Area -->
        <div class="main-container">
            <!-- Fixed Topbar -->
            <?php include __DIR__ . '../common/topBar.php'; ?>

            <!-- Scrollable Content -->
            <div class="content-scrollable">
                <div class="container my-5 card p-4 shadow form-section">
                    <table id="userTable" datatable class="display table " style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="user in jb.allUserList track by $index">
                                <td>{{ $index +1 }}</td>
                                <td class="text-capitalize">{{ user.user_name }}</td>
                                <td>{{ user.mobile }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.created_at }}</td>
                                <td>{{ user.status }}</td>
                                <td>
                                    <a class="btn btn-primary " href="/user-details?id={{user.id}}">Update</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
<?php include __DIR__ . '../common/footer.php'; ?>


</html>