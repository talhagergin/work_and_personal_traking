<?php
session_start();
include "../includes/db.php";
require_once "../helps.php";

$breadcrumb = "Projelerim";


if(!isset($_SESSION["user_id"]))
{
    header("Location: ../pages/sign-in.php");
    exit();
}
if ($_SESSION['user_role'] != 2){
    header("Location: ../pages/dashboard.php");
    exit();
}

$loginUser=mysqli_query($connection,"SELECT * FROM users WHERE user_id = " . $_SESSION['user_id'])->fetch_assoc();
$customer= mysqli_query($connection,"SELECT * FROM customers WHERE user_id=". $_SESSION['user_id'])->fetch_assoc();
//Projeleri Veritabanından Çekme
if($customer){
    $query="SELECT * FROM projects WHERE company_id = {$customer['company_id']}";
    $projects=mysqli_query($connection,$query)->fetch_all(MYSQLI_ASSOC);
    $query2="SELECT company_name FROM companies WHERE company_id= '{$customer['company_id']}'";
    $company=mysqli_query($connection,$query2)->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        KayraSoft | Projeler
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.0.4" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-200">
<?php include "../includes/sidebar.php"; ?>
</aside>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <?php include "../includes/navbar.php"; ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="row">
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">

                            <h6 class="text-white text-capitalize ps-3">
                                Projeler <br>Toplam Proje Sayısı: <?= count($projects); ?>
                            </h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center justify-content-center mb-0">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-7">Proje Ad</th>
                                    <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-7 ps-1">Firma Ad</th>
                                    <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-7 ps-1">Başlangıç Tarih</th>
                                    <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-7 ps-1">Bitiş Tarih</th>
                                    <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-7 ps-1">Durumu</th>
                                    <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-7 ps-1">Açıklama</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($projects as $project){?>
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-sm"><?=$project["project_name"] ;?></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            if(!empty($company))
                                            {?>
                                                <p class="text-sm font-weight-bold mb-0"><?=$company["company_name"];?></p>
                                            <?php }
                                            else{?>
                                                <p class="text-sm font-weight-bold mb-0"><?=$project["company_name"];?></p>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0"><?=date('d.m.Y',strtotime($project["project_start_date"]));?></p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0"><?=date('d.m.Y',strtotime($project["project_finish_date"]));?></p>
                                        </td>
                                        <td>
                                            <span class="text-xs font-weight-bold"><?= $project["project_status"];?></span>
                                        </td>
                                        <td>
                                            <span class="text-xs font-weight-bold"><?= $project["project_description"];?></span>
                                        </td>

                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Navbar Ayarları -->
<?php include "../includes/navbar_settings.php"; ?>
<!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/material-dashboard.min.js?v=3.0.4"></script>
<script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
<script>
    $('#menu-projeler').addClass('active bg-gradient-primary');
</script>
</body>

</html>
