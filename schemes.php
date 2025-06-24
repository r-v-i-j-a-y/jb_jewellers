<?php

require './functions/middleware.php';
require './config/db.php';

auth_protect();


$sql = "
            SELECT 
                schemes.id,
                schemes.scheme_name,
                schemes.scheme_tenure,
                schemes.scheme_status,
                schemes.scheme_created_by,
                schemes.created_at,
                schemes.updated_at,
                users.user_name
            FROM schemes
            LEFT JOIN users ON users.id = schemes.scheme_created_by
        ";

$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute();
$schemeData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'Dashboard';
include './common/head.php'; ?>

<body ng-app="myApp" ng-controller="MyController as jb" class="bg-light">
    <div
        ng-init="jb.commonInit();jb.schemesInit(<?= htmlspecialchars(json_encode($schemeData), ENT_QUOTES, 'UTF-8') ?>)">
        <!-- Sidebar -->
        <?php include './common/sideBar.php'; ?>

        <!-- Main Content Area -->
        <div class="main-container">
            <!-- Fixed Topbar -->
            <?php include './common/topBar.php'; ?>

            <!-- Scrollable Content -->
            <div class="content-scrollable card border-0">

                <div class="container py-5">
                    <div class="row g-4">
                        <!-- Card 1 -->
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-primary rounded-pill" href="scheme-create.php">Add Scheme</a>
                        </div>
                        <div class="col-md-6 col-lg-4" ng-repeat="scheme in jb.schemesData">
                            <div class="card shadow-sm border-0" style="background-color: #e9f0ff;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">

                                        <i class="bi bi-bookmark"></i>
                                    </div>
                                    <h5 class="card-title fw-bold text-center">{{scheme.scheme_name}}</h5>

                                    <div class="d-flex gap-2 mb-3">
                                        <span class="badge bg-light text-dark border">{{scheme.scheme_tenure}}
                                            Months</span>
                                        <span class="badge bg-light border"
                                            ng-class="{'text-success': scheme.scheme_status === 'active',
                                            'text-danger': scheme.scheme_status === 'inactive'}">{{scheme.scheme_status}}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check form-switch d-flex align-items-center mt-2">
                                            <input class="form-check-input me-2" type="checkbox"
                                                id="schemeStatusSwitch{{scheme.id}}"
                                                ng-checked="scheme.scheme_status === 'active'"
                                                ng-click="jb.schemeStatusChangeModal(scheme)" role="switch"
                                                data-bs-toggle="modal" data-bs-target="#schemeStatusModal">
                                            <label class="form-check-label fw-bold" for="schemeStatusSwitch" ng-class="{
                                                            'text-success': scheme.scheme_status === 'active',
                                                            'text-danger': scheme.scheme_status === 'inactive'
                                                        }">
                                                {{ scheme.scheme_status === 'active' ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                        <div>
                                            <a class="btn btn-primary rounded-pill"
                                                href="chits.php?scheme_id={{scheme.id}}">Chit List</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="schemeStatusModal" tabindex="-1" aria-labelledby="schemeStatusModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="schemeStatusModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are You Sure to change status
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                    ng-click="jb.schemeStatusChangeModalClose()">Close</button>
                                <button type="button" class="btn btn-primary" ng-click="jb.confirmStatusChange()">Yes,
                                    Change</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>