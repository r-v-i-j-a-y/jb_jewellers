<?php
$adminNavList = [
    [
        'title' => 'members details',
        'list' => [
            ['link' => 'index.php', 'linkName' => 'Dashboard', 'icon' => '<i class="fa-solid fa-house me-2"></i>'],
            ['link' => 'purchase-scheme.php', 'linkName' => 'purchase scheme', 'icon' => '<i class="fa-solid fa-cart-shopping me-2"></i>'],
            ['link' => 'pay-scheme.php', 'linkName' => 'pay scheme', 'icon' => '<i class="fa-solid fa-credit-card me-2"></i>'],
            ['link' => 'view-users.php', 'linkName' => 'view users', 'icon' => '<i class="fa-solid fa-users me-2"></i>'],
            ['link' => 'schemes.php', 'linkName' => 'schemes', 'icon' => '<i class="fa-solid fa-clipboard-list me-2"></i>'],
            ['link' => 'notification.php', 'linkName' => 'Notifications', 'icon' => '<i class="fa-solid fa-bell me-2"></i>'],
            // ['link' => 'chit-details.php', 'linkName' => 'chit details'],
        ]
    ],
    [
        'title' => 'transaction details.php',
        'list' => [
            ['link' => 'month-wise-payment.php', 'linkName' => 'month wise payment', 'icon' => '<i class="fa-solid fa-calendar-days me-2"></i>'],
            ['link' => 'change-status.php', 'linkName' => 'change status', 'icon' => '<i class="fa-solid fa-diagram-project me-2"></i>'],
            // ['link' => 'close-scheme.php', 'linkName' => 'close scheme'],
        ]
    ]
];

$userNavList = [
    [
        'title' => 'members details',
        'list' => [
            ['link' => 'index.php', 'linkName' => 'Dashboard', 'icon' => '<i class="fa-solid fa-house me-2"></i>'],
            ['link' => "user-details.php", 'linkName' => 'Personal Details', 'icon' => '<i class="fa-solid fa-user me-2"></i>'],
            ['link' => 'purchase-scheme.php', 'linkName' => 'Purchase schemes', 'icon' => '<i class="fa-solid fa-clipboard-list me-2"></i>'],
            ['link' => 'pay-scheme.php', 'linkName' => 'pay scheme', 'icon' => '<i class="fa-solid fa-credit-card me-2"></i>'],
            ['link' => 'payment-history.php', 'linkName' => 'Payment History', 'icon' => '<i class="fa-solid fa-rectangle-list me-2"></i>'],
            ['link' => 'notification.php', 'linkName' => 'Notifications', 'icon' => '<i class="fa-solid fa-bell me-2"></i>'],
            // ['link' => 'chit-details.php', 'linkName' => 'chit details'],
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
                                        class="text-decoration-none text-muted "><?= $item['icon'] ?><?= htmlspecialchars($item['linkName']) ?></a>
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