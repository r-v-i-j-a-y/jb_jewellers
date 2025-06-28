<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];
$isAdmin = ($authData['role_id'] == 1) ? true : false;

$sql = "SELECT 
                pr_schemes.id,
                pr_schemes.scheme_name,
                pr_schemes.scheme_tenure,
                pr_schemes.scheme_status,
                pr_schemes.scheme_created_by,
                pr_schemes.created_at,
                pr_schemes.updated_at,
                pr_users.user_name
            FROM pr_schemes
            LEFT JOIN pr_users ON pr_users.id = pr_schemes.scheme_created_by
            Where pr_schemes.scheme_status = 'active'
        ";

$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute();
$schemeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'Privacy Policy';
include './common/head.php';
$topbarTitle = 'About Us';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'About us', 'url' => '']
];

$privacyPolicy = [
    "At <b> Prayaan Jewel Mart </b>, we deeply value your trust and are committed to ensuring the highest standards of privacy and security in handling your personal information. This Privacy Policy outlines how we collect, use, and safeguard the information you share with us. It also explains your rights and how you can exercise them.",
    "By accessing or using our website, <b> www.prayaanjewelmart.co.in </b>, you consent to the practices described in this policy. If you do not agree with any part of this Privacy Policy, please discontinue using our Site immediately.",
    "We reserve the right to modify this Privacy Policy at any time without prior notice. To remain informed of updates, we encourage you to review this page periodically. Continued use of the Site after changes constitutes acceptance of the updated policy."
];

$informationWeCollect = [
    [
        "title" => "Personal Information",
        "first_para" => "This includes details that can identify you, such as:",
        "list" => ["Name, email address, phone number, and postal address (collected during registration or order placement).", "Payment details (credit/debit card numbers, expiration dates, and billing information).", "Correspondence, including emails, messages, or inquiries sent to our customer care team."]
    ],
    [
        "title" => "Non-Personal Information",
        "first_para" => "This includes technical and behavioural data collected during your interaction with our Site, such as:",
        "list" => ["Browser type and version.", "Internet Protocol (IP) address and Internet Service Provider (ISP).", "Date, time, and duration of your visit.", "Referring/exit pages and clickstream data."]
    ],
    [
        "title" => " Cookies and Tracking Tools",
        "first_para" => "We use cookies and similar technologies to collect information about your browsing preferences. Cookies enhance your experience by:",
        "list" => ["Reducing the need to re-enter information during your visit.", "Enabling personalized recommendations and targeted content.", "Measuring the effectiveness of promotional campaigns."],
        "last_para" => "While most cookies are session-based and automatically deleted after your visit, you can manage cookie settings in your browser. Please note, disabling cookies may affect your ability to use certain features on our Site."
    ],
    [
        "title" => "Third-Party Cookies",
        "first_para" => "On certain pages, third parties may place cookies or similar tools. These are outside our control, and we recommend reviewing their respective privacy policies.",
    ],

];


$useYour = [
    "first_para" => "Your information is utilized to deliver a seamless and secure experience, including:",
    "list" => [
        [
            "title" => "Order Fulfilment:",
            "description" => " Processing transactions, verifying payments, and delivering products."
        ],
        [
            "title" => "Customer Support:",
            "description" => "Addressing inquiries, resolving disputes, and improving communication."
        ],
        [
            "title" => "Personalization:",
            "description" => "Recommending products or services based on your preferences."
        ],
        [
            "title" => "Marketing:",
            "description" => "Sending newsletters, promotional offers, and updates (with your consent)."
        ],
        [
            "title" => "Security:",
            "description" => "Detecting and preventing fraud, unauthorized access, and other malicious activities."
        ],
        [
            "title" => "Legal Compliance:",
            "description" => "Meeting legal obligations and responding to lawful requests from authorities.ஃ"
        ],
    ],
    "last_para" => "We also aggregate demographic data to analyse user behaviour and improve our products, services, and website performance."
];
$sharingYour = [
    "first_para" => "We value your privacy and do not sell your personal data. However, we may share your information in the following circumstances:",
    "list" => [
        [
            "title" => "With Affiliates and Partners:",
            "description" => "To improve service delivery, prevent fraudulent activities, and provide co-branded services (with your consent)."
        ],
        [
            "title" => "For Legal Reasons: ",
            "description" => "To comply with legal obligations, such as responding to subpoenas, court orders, or regulatory requests."
        ],
        [
            "title" => "In Business Transfers: ",
            "description" => "If Prayaan Jewel Mart undergoes a merger, acquisition, or sale, your information may be transferred to the new entity, subject to this Privacy Policy."
        ]
    ]
];
$dataSecurity = [
    "first_para" => "We implement advanced security protocols to protect your information from unauthorized access, alteration, or misuse. These measures include:",
    "list" => [
        "Secure Socket Layer (SSL) encryption for sensitive transactions.",
        "Regular updates to our systems and practices to stay aligned with security best practices.",
        "Restricting access to personal data to authorized personnel only."
    ],
    "last_para" => "Despite our best efforts, no data transmission over the internet can be guaranteed as entirely secure. We encourage you to take precautions, such as using strong passwords and avoiding sharing sensitive information via unsecured channels."
];

?>

<body ng-app="myApp" ng-controller="MyController as jb" class="bg-light">
    <div ng-init="jb.commonInit()">
        <!-- Sidebar -->
        <?php include './common/sideBar.php'; ?>

        <!-- Main Content Area -->
        <div class="main-container">
            <!-- Fixed Topbar -->
            <?php include './common/topBar.php'; ?>

            <!-- Scrollable Content -->
            <div class="content-scrollable card border-0  d-flex flex-column justify-content-between">

                <div class="container p-5 text-muted">
                    <div class="mb-5">
                        <h2 class="text-uppercase text-warning mb-3" style="text-shadow: 1px 1px 2px #00000047;">Privacy
                            Policy</h2>
                        <ul class="list-inline">
                            <?php foreach ($privacyPolicy as $term): ?>
                                <li class="mb-3 text-justify"><?= $term ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    <div>
                        <h5 class="text-uppercase text-center text-warning mb-3"
                            style="text-shadow: 1px 1px 2px #00000047;">Information We collect</h5>
                        <p class="text-warning text-center text-muted">We collect various types of information to
                            provide and
                            improve our services, ensure secure transactions, and enhance your user experience. The
                            information collected includes:</p>
                        <div class="row ">
                            <?php foreach ($informationWeCollect as $index => $collect): ?>
                                <div class=" col-xl-6">
                                    <h6><?= $index + 1 ?>. <?= $collect['title'] ?></h6>
                                    <p class="text-justify"><?= $collect['first_para'] ?></p>
                                    <?php if (!empty($collect['list'])): ?>
                                        <ul>
                                            <?php foreach ($collect['list'] as $index => $text): ?>
                                                <li><?= $text ?></li>
                                            <?php endforeach ?>
                                        </ul>
                                    <?php endif ?>
                                    <?php if (!empty($collect['last_para'])): ?>
                                        <p class="text-justify"> <?= $collect['last_para'] ?></p>
                                    <?php endif ?>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class=" col-xl-6">
                            <h5 class="text-uppercase text-center text-warning mb-3"
                                style="text-shadow: 1px 1px 2px #00000047;">How We Use Your Information</h5>
                            <p class="text-justify"><?= $useYour['first_para'] ?> </p>
                            <ul>
                                <?php foreach ($useYour['list'] as $index => $text): ?>
                                    <li><b><?= $text['title'] ?></b><?= $text['description'] ?></li>
                                <?php endforeach ?>
                            </ul>
                            <p class="text-justify"><?= $useYour['last_para'] ?> </p>

                        </div>
                        <div class=" col-xl-6">
                            <h5 class="text-uppercase text-center text-warning mb-3"
                                style="text-shadow: 1px 1px 2px #00000047;">Sharing Your Information</h5>
                            <p class="text-justify"><?= $sharingYour['first_para'] ?> </p>
                            <ul>
                                <?php foreach ($sharingYour['list'] as $index => $text): ?>
                                    <li><b><?= $text['title'] ?></b><?= $text['description'] ?></li>
                                <?php endforeach ?>
                            </ul>

                        </div>
                    </div>
                    <div class="row ">

                        <div class=" col-xl-6">
                            <h5 class="text-uppercase text-center text-warning mb-3"
                                style="text-shadow: 1px 1px 2px #00000047;">Data Security</h5>
                            <p class="text-justify"><?= $dataSecurity['first_para'] ?> </p>
                            <ul>
                                <?php foreach ($dataSecurity['list'] as $index => $text): ?>
                                    <li><?= $text ?></li>
                                <?php endforeach ?>
                            </ul>
                            <p class="text-justify"><?= $dataSecurity['last_para'] ?> </p>
                        </div>
                        <div class=" col-xl-6">
                            <h5 class="text-uppercase text-center text-warning mb-3"
                                style="text-shadow: 1px 1px 2px #00000047;">Your Rights and Choices</h5>
                            <p class="text-justify">You have the following rights concerning your personal data:</p>
                            <ul>
                                <li><b>1. Access and Correction:</b>You may request access to the personal information
                                    we
                                    hold about you and correct any inaccuracies.</li>
                                <li><b>2. Data Portability:</b>Request a copy of your data in a commonly used format.
                                </li>
                                <li><b>3. Opt-Out:</b>
                                    <ul>
                                        <li>Withdraw consent for non-essential communications by contacting <b>
                                                prayaanjewelmart@gmail.com</b>.</li>
                                        <li>Manage cookie preferences through your browser settings.</li>
                                    </ul>
                                </li>
                                <li><b>4. Data Deletion: Request the deletion of your personal information, subject to
                                        legal or operational obligations.</b>
                            </ul>
                        </div>
                        <div class=" col-xl-6">
                            <h5 class="text-uppercase text-center text-warning mb-3"
                                style="text-shadow: 1px 1px 2px #00000047;">Children’s Privacy</h5>
                            <p class="text-justify">We are committed to protecting the privacy of minors. Our services
                                are
                                not directed at individuals under the age of 18. We do not knowingly collect information
                                from minors. If you believe a child has provided personal information, please contact us
                                immediately at <b>prayaanjewelmart@gmail.com</b> , and we will promptly take steps to
                                delete
                                the data.</p>

                        </div>
                        <div class=" col-xl-6">
                            <h5 class="text-uppercase text-center text-warning mb-3"
                                style="text-shadow: 1px 1px 2px #00000047;">Changes to This Privacy Policy</h5>
                            <p class="text-justify">Prayaan Jewel Mart reserves the right to update this Privacy Policy.
                                Updates will be communicated on this page, and the "Last Updated" date will be revised
                                accordingly. We encourage you to check this page regularly to stay informed about how we
                                protect your data.</p>

                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <h5 class="text-uppercase text-center text-warning mb-3"
                                style="text-shadow: 1px 1px 2px #00000047;">Grievance and Contact Information</h5>
                            <p>If you have any questions, concerns, or complaints about this Privacy Policy, please
                                contact our designated Grievance Officer:</p>
                            <p><b>Hariharan R</b></p>
                            <p>Customer Service – Prayaan Jewel Mart</p>
                            <p>27/29, 3rd Main Road, Thillaiganga Nagar, Nanganallur,</p>
                            <p>Chennai, Tamil Nadu 600061</p>
                            <p>Phone: +91 96290 85220</p>
                            <p><b>Email: prayaanjewelmart@gmail.com</b></p>
                            <p>Our team is available from <b> Monday to Saturday, 10:00 AM to 7:00 PM </b>, to address
                                your concerns promptly.</p>
                        </div>
                    </div>
                </div>
                <?php include './footerTop.php'; ?>
            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>