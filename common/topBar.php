<?php
$currentUri = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

function renderBreadcrumbsWithLinks($items = [])
{
    $count = count($items);
    echo '<nav aria-label="breadcrumb"><ol class="breadcrumb list-unstyled d-flex flex-wrap align-items-center p-0 m-0">';

    foreach ($items as $index => $item) {
        $title = htmlspecialchars($item['title']);
        $url = $item['url'];
        $icon = isset($item['icon']) ? $item['icon'] : null;

        if ($index < $count - 1 && $url) {
            echo '<li class="breadcrumb-item me-1"><a class="text-decoration-none text-muted" href="' . $url . '">' . $icon . $title . '</a></li>';
            echo '<li class="breadcrumb-separator me-1">&gt;</li>';
        } else {
            echo '<li class="breadcrumb-item active fw-bold" aria-current="page">' . $icon . $title . '</li>';
        }
    }

    echo '</ol></nav>';
}



$sql = "SELECT 
        ntf.notification_status
        FROM pr_notifications as ntf 
        LEFT JOIN pr_users as us ON ntf.user_id = us.id
        WHERE ntf.notification_status = 'unread'
        ";

if ($isAdmin) {
    $sql .= " AND us.role_id = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
} else {
    $sql .= " AND ntf.user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $authUserId]);
}

$notificationDataCount = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold"><?= $topbarTitle ?></h4>
        <?php renderBreadcrumbsWithLinks($breadcrumbs); ?>
    </div>
    <div class="d-flex align-items-center align-items-center gap-3">
        <!-- <i class="bi bi-search fs-5"></i> -->
        <ul class="d-flex gap-2 list-inline m-0 ">
            <li class="nav-link <?= $currentUri == 'about-us.php' ? "active" : "" ?>"><a
                    class="text-decoration-none text-muted" href="about-us.php">About Us</a></li>
            <li class="nav-link  <?= $currentUri == 'contact-us.php' ? "active" : "" ?>"><a
                    class="text-decoration-none text-muted" href="contact-us.php">Contact Us</a></li>
            <li class="nav-link  <?= $currentUri == 'faq.php' ? "active" : "" ?>"><a
                    class="text-decoration-none text-muted" href="faq.php">FAQ</a></li>
        </ul>

        <div class="position-relative d-inline-block">
            <a href="notification.php">
                <i class="fa-solid fa-bell fs-4 text-muted"></i>
                <?php if (count($notificationDataCount) > 0): ?>
                    <span class="position-absolute top-25 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= count($notificationDataCount) ?>
                        <!-- <span class="visually-hidden">unread messages</span> -->
                    </span>
                <?php endif ?>
            </a>
        </div>
        <div class="dropdown text-end p-3">

            <img src="https://i.pravatar.cc/100?img=6" id="profileDropdown" class="profile-img" alt="Profile">

            <ul class="dropdown-menu dropdown-menu-end text-small mt-2" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="adminRegisterPage.php">Register</a></li>
                <li><a class="dropdown-item" href="#">Reset Password</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Logout</a></li>
            </ul>
        </div>
        <!-- <img  class="user-img" alt="User"> -->
        <div>
            <!-- <div class="fw-semibold"><?php echo $authData['id'] ?></div> -->
            <small class="text-muted ">Role : <?php echo $authData['role_name'] ?></small>
        </div>
    </div>
</div>