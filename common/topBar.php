<?php
function renderBreadcrumbsWithLinks($items = [])
{
    $count = count($items);
    echo '<nav aria-label="breadcrumb"><ol class="breadcrumb list-unstyled d-flex flex-wrap align-items-center p-0 m-0">';

    foreach ($items as $index => $item) {
        $title = htmlspecialchars($item['title']);
        $url = $item['url'];

        if ($index < $count - 1 && $url) {
            echo '<li class="breadcrumb-item me-1"><a class="text-decoration-none text-muted" href="' . $url . '">' . $title . '</a></li>';
            echo '<li class="breadcrumb-separator me-1">&gt;</li>';
        } else {
            echo '<li class="breadcrumb-item active fw-bold" aria-current="page">' . $title . '</li>';
        }
    }

    echo '</ol></nav>';
}
?>

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold"><?= $topbarTitle ?></h4>
        <?php renderBreadcrumbsWithLinks($breadcrumbs); ?>
    </div>
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-search fs-5"></i>
        <img src="https://i.pravatar.cc/100?img=6" class="user-img" alt="User">
        <div>
            <!-- <div class="fw-semibold"><?php echo $authData['id'] ?></div> -->
            <small class="text-muted ">Role : <?php echo $authData['role_name'] ?></small>
        </div>
    </div>
</div>