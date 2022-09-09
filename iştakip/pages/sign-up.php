<?php
session_start();
require_once '../includes/db.php';

if(isset($_SESSION["user_id"]))
{
    header("Location: ../pages/dashboard.php");
    exit();
}


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // die(print_r($_POST));
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstName = $_POST['name'];
    $lastName = $_POST['lastname'];
    $user_role = $_POST['user_role'];
    if($user_role==2){
        $company_name=$_POST['firma_ad'];
        $company_adress=$_POST['firma_adres'];
        $company_phone = $_POST['firma_telefon'];
        $company_email=$_POST['firma_eposta'];
    }

    $emailControl = mysqli_query($connection, "SELECT * FROM users WHERE user_email='{$email}'")->fetch_assoc();
    $usernameControl = mysqli_query($connection, "SELECT * FROM users WHERE username='{$username}'")->fetch_assoc();
    if (!empty($email) && !empty($password) && !empty($firstName) && !empty($lastName) && !empty($username)) {
        if ($emailControl) {
            $_SESSION["errors"][] = "Bu email adresi başka bir kullanıcı tarafından kullanılmaktadır.";
        } else if ($usernameControl) {
            $_SESSION["errors"][] = "Bu kullanıcı adı başka bir kullanıcı tarafından kullanılmaktadır.";
        } else {
            $query2 = "INSERT INTO users (username,user_email,user_password,user_firstname,user_lastname,user_role,added_date)
        VALUES ('{$username}','{$email}','{$password}','{$firstName}','{$lastName}','{$user_role}',now())";
            $add_user = mysqli_query($connection, $query2);
            if(!empty($company_name) && !empty($company_email) && !empty($company_phone) && !empty($company_adress)) {
                $query = "INSERT INTO companies (company_name,company_adress,company_phone,company_email,company_added_date) VALUES ('{$company_name}','{$company_adress}','{$company_phone}','{$company_email}',now())";
                $add_company = mysqli_query($connection, $query);

                if ($add_company) {
                    #son eklenen kullanıcı ve şirketi çektik
                    $get_company = mysqli_query($connection, "SELECT * FROM companies ORDER BY company_id DESC LIMIT 1")->fetch_assoc();
                    $get_user = mysqli_query($connection, "SELECT * FROM users ORDER BY user_id DESC LIMIT 1 ")->fetch_assoc();
                    $add_customer = mysqli_query($connection, "INSERT INTO customers (user_id,company_id) VALUES ('{$get_user['user_id']}','{$get_company['company_id']}') ");
                    $_SESSION["success"][] = "Kaydnız Oluşturuldu Giriş Yapabilirsiniz...";
                }
            }
            header("Location: ../pages/sign-in.php");
        }
    }
    else{
        $_SESSION["errors"][] ="Lütfen gerekli alanları doldurunuz! ";
    }
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
        Kayrasoft | Kayıt Ol
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

<body class="">
<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <!-- Navbar -->
            <nav class="navbar navbar-dark bg-dark  border-radius-lg">
                <div class="container-fluid ps-2 pe-0">
                    <a class="navbar-brand font-weight-bolder" style="margin-left:450px ;" href="">
                        <img src="../assets/img/logo.png"  style="width:150px;" >
                    </a>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-100">
            <div class="container">
                <div class="row">
                    <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
                        <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('../assets/img/illustrations/illustration-signup.jpg'); background-size: cover;">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
                        <div class="card card-plain">
                            <?php
                            if(isset($_SESSION["errors"])) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php
                                    echo implode("<br />", $_SESSION["errors"]);
                                    unset($_SESSION["errors"]);
                                    ?>
                                </div>
                            <?php } ?>


                            <div class="card-header">
                                <h4 class="font-weight-bolder">Kayıt Ol</h4>
                                <p class="mb-0">Kayıt için gerekli bilgileri giriniz</p>
                            </div>
                            <div class="card-body">
                                <form role="form" action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Kullanıcı Ad</label>
                                        <input type="text" name="username" class="form-control">
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Ad</label>
                                        <input type="text" name="name" class="form-control">
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Soyad</label>
                                        <input type="text" name="lastname" class="form-control">
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Eposta</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Şifre</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="project_status" style="font-size: 20px;">Kullanıcı Tipi</label>
                                        <select name="user_role" id="user_role" style="border:1px solid gray;border-radius: 5px;" class="form-control">
                                            <option value="0">Personel</option>
                                            <option value="2"> Müşteri</option>
                                        </select>
                                    </div>

                                    <div id="firma_form">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Firma Adı</label>
                                            <input type="text" name="firma_ad" id="firma_ad" class="form-control">
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Firma Adress</label>
                                            <input type="text" name="firma_adres" id="firma_adres" class="form-control">
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Firma Telefon</label>
                                            <input type="text" name="firma_telefon" id="firma_telefon" class="form-control">
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Firma Eposta</label>
                                            <input type="text" name="firma_eposta" id="firma_eposta" class="form-control">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit"  name="save_user" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Kayıt Ol</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-2 text-sm mx-auto">
                                    Zaten hesabın var mı?
                                    <a href="../pages/sign-in.php" class="text-primary text-gradient font-weight-bold">Giriş Yap</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
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

    document.addEventListener("DOMContentLoaded", function(event) {
        document.getElementById("firma_form").style.display = "none";
    });

    var userRole = document.getElementById("user_role");
    userRole.addEventListener("change", function () {
        if(userRole.value == 2)
        {
            document.getElementById("firma_form").style.display = "block";
        }
        else
        {
            document.getElementById("firma_form").style.display = "none";
        }
    });

</script>
<script src="../assets/js/material-dashboard.min.js?v=3.0.4"></script>

</body>

</html>