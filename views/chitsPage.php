<!-- views/home.php -->
<?php

?>
<!DOCTYPE html>
<html>

<?php includeWithData(__DIR__ . '../common/head.php', [
    'title' => 'Chits',
]);

?>

<body ng-app="myApp" ng-controller="MyController as jb" class="bg-light">
    <div
        ng-init="jb.commonInit(<?= htmlspecialchars(json_encode($this->auth), ENT_QUOTES, 'UTF-8') ?>);jb.chitsInit(<?= htmlspecialchars(json_encode($chitData), ENT_QUOTES, 'UTF-8') ?>)">
        <!-- Sidebar -->
        <?php include __DIR__ . '../common/sideBar.php'; ?>

        <!-- Main Content Area -->
        <div class="main-container">
            <!-- Fixed Topbar -->
            <?php include __DIR__ . '../common/topBar.php'; ?>

            <!-- Scrollable Content -->
            <div class="content-scrollable card border-0">

                <div class="container py-5">
                    <div class="row g-4">
                        <!-- Card 1 -->
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-warning rounded-pill" href="/chit-create?scheme_id=<?php echo $_GET['scheme_id']?>">Add Chit</a>
                        </div>
                        <div class="col-md-6 col-lg-4" ng-repeat="chit in jb.chitData">
                            <div class="card shadow-sm border-0" style="background-color: #e9f0ff;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">

                                        <i class="bi bi-bookmark"></i>
                                    </div>
                                    <h5 class="card-title fw-bold text-center">{{chit.chit_amount}} ₹</h5>

                                    <div class="d-flex gap-2 mb-3">
                                        <span class="badge bg-light text-dark border">{{chit.chit_tenure}}
                                            Months</span>
                                        <span class="badge bg-light border"
                                            ng-class="{'text-success': chit.status === 'active',
                                            'text-danger': chit.status === 'inactive'}">{{chit.status}}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check form-switch d-flex align-items-center mt-2">
                                            <input class="form-check-input me-2" type="checkbox"
                                                id="chitStatusSwitch{{chit.id}}"
                                                ng-checked="chit.status === 'active'"
                                                ng-click="jb.chitStatusChangeModal(chit)" role="switch"
                                                data-bs-toggle="modal" data-bs-target="#chitStatusModal">
                                            <label class="form-check-label fw-bold" for="chitStatusSwitch" ng-class="{
                                                            'text-success': chit.status === 'active',
                                                            'text-danger': chit.status === 'inactive'
                                                        }">
                                                {{ chit.status === 'active' ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                        <!-- <div>
                                            <a class="btn btn-primary rounded-pill"
                                                href="chit-list?id={{chit.id}}">Chit List</a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="chitStatusModal" tabindex="-1" aria-labelledby="chitStatusModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="chitStatusModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are You Sure to change status
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                    ng-click="jb.chitStatusChangeModalClose()">Close</button>
                                <button type="button" class="btn btn-primary" ng-click="jb.confirmChitStatusChange()">Yes,
                                    Change</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

<?php include __DIR__ . '../common/footer.php'; ?>

</html>