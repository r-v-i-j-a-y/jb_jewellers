<?php
$navList = [
    [
        'title' => 'members details',
        'list' => [
            ['link' => 'index.php', 'linkName' => 'Dashboard'],
            ['link' => 'purchase-scheme.php', 'linkName' => 'purchase scheme'],
            ['link' => 'pays-cheme.php', 'linkName' => 'pay scheme'],
            ['link' => 'view-users.php', 'linkName' => 'view users'],
            ['link' => 'schemes.php', 'linkName' => 'schemes'],
            ['link' => 'chit-details.php', 'linkName' => 'chit details'],
        ]
    ],
    [
        'title' => 'transaction details.php',
        'list' => [
            ['link' => 'month-wise-payment.php', 'linkName' => 'month wise payment'],
            ['link' => 'chit-wise-payment.php', 'linkName' => 'chit wise payment'],
            ['link' => 'pending-payment.php', 'linkName' => 'pending payment'],
            ['link' => 'change-status.php', 'linkName' => 'change status'],
            ['link' => 'close-scheme.php', 'linkName' => 'close scheme'],
        ]
    ]
];
?>

<div class="sidebar d-flex flex-column">
    <div class="mb-4">
        <h5 class="fw-bold">JB JEWELLERS</h5>
    </div>
    <div class="d-flex flex-column justify-content-between h-100">

        <div class="sidebar_list">
            <!-- <div ng-repeat="navlist in jb.navList">
                <h6 class="text-muted small mt-4 text-uppercase">{{navlist.title}}</h6>
                <ul class="list-inline">
                    <li class="nav-link mb-2 text-capitalize" ng-class="{'active': jb.currentPath === list.link}"
                        ng-repeat="list in navlist.list">
                        <a class="text-decoration-none text-muted" href="{{list.link}}">{{list.linkName}}</a>
                    </li>
                </ul>
            </div> -->
            <ul class="sidebar_list list-inline">
                <?php foreach ($navList as $section): ?>
                    <li ><strong class="text-muted small mt-4 text-uppercase"><?= htmlspecialchars($section['title']) ?></strong>
                        <ul class="p-0">
                            <?php foreach ($section['list'] as $item): ?>
                                <li class="nav-link mb-2 text-capitalize"><a href="<?= $item['link'] ?>" class="text-decoration-none text-muted"><?= htmlspecialchars($item['linkName']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <a href="logout.php" class="btn btn-danger w-100">Logout</a>
        </div>
    </div>
</div>