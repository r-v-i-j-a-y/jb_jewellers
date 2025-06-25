<?php
$adminNavList = [
    [
        'title' => 'members details',
        'list' => [
            ['link' => 'index.php', 'linkName' => 'Dashboard'],
            ['link' => 'purchase-scheme.php', 'linkName' => 'purchase scheme'],
            ['link' => 'pay-scheme.php', 'linkName' => 'pay scheme'],
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

$userNavList = [
    [
        'title' => 'members details',
        'list' => [
            ['link' => 'index.php', 'linkName' => 'Dashboard'],
            ['link' => "user-details.php", 'linkName' => 'Personal Details'],
            ['link' => 'purchase-scheme.php', 'linkName' => 'Purchase schemes'],
             ['link' => 'pay-scheme.php', 'linkName' => 'pay scheme'],
            ['link' => 'payment-history.php', 'linkName' => 'View Payment History'],
            ['link' => 'chit-details.php', 'linkName' => 'chit details'],
        ]
    ],
    
];

$navList = $authData['role_id'] == 1 ? $adminNavList : $userNavList;
$currentUri = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
?>

<div class="sidebar d-flex flex-column">
    <div class="mb-4">
        <h5 class="fw-bold">JB JEWELLERS</h5>
    </div>
    <div class="d-flex flex-column justify-content-between h-100">

        <div class="sidebar_list">
            <ul class="sidebar_list list-inline">
                <?php foreach ($navList as $section): ?>
                    <li><strong
                            class="text-muted small mt-4 text-uppercase"><?= htmlspecialchars($section['title']) ?></strong>
                        <ul class="p-0">
                            <?php foreach ($section['list'] as $item): ?>
                                <li class="nav-link mb-2 text-capitalize <?= $currentUri == $item['link'] ? "active" : "" ?>">
                                    <a href="<?= $item['link'] == 'user-details.php' ? $item['link'] . "?id=$authUserId" : $item['link'] ?>"
                                        class="text-decoration-none text-muted"><?= htmlspecialchars($item['linkName']) ?></a>
                                </li>
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