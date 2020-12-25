<?php 
    ob_start();
	session_start();
    $pageTitle = 'settings';
	include 'inital.php';
    include "check_token.php";
    if (isset($_SESSION['user'])) {?>
    <div class="profilebody">
        <div class="container">
            <div class="profile row ">
                <div class="col-md-8 order-sm-2 order-md-1">
                <?php include $tpl . 'last.php'; ?> 
                <div class="alls">
                <?php
                if (isset($_SESSION['user'])) {
                    $getUser = $con->prepare("SELECT * FROM members WHERE username = ?");
                    $getUser->execute(array($sessionUser));
                    $row = $getUser->fetch();
                    $do = isset($_GET['do']) ? $_GET['do'] : 'edit';
                    // Start Manage Page
                    if ($do == 'Edit') {
                        ?>
                    <h1 class="text-center">تعديل العضو</h1>
                            <div class="container text-right">
                                <form class="form-horizontal" action="?do=Update" method="POST">
                                    <?php if (empty($row['avatar'])) {
                                                    echo '<h4 class="text-center">No Image</h4>';
                                                } else {
                                                    echo "<img class='image img-thumbnail' src='admin/uploads/avatars/" . $row['avatar'] . "' alt='' />";
                                                }?>
                                    <input type="hidden" name="userid" value="<?php echo $row['userid'] ?>"/>
                                    <!-- Start Username Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="control-label">اسم المستخدم</label>
                                        <input type="text" name="username" class="form-control text-right" value="<?php echo $row['username'] ?>" autocomplete="off" required="required" />
                                    </div>
                                    <!-- End Username Field -->
                                    <!-- Start Full Name Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="control-label">الاسم كاملا</label>
                                        <input type="text" name="full" value="<?php echo $row['fullname'] ?>" class="form-control text-right" required="required" />
                                    </div>
                                    <!-- End Full Name Field -->
                                    <!-- Start Password Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="control-label">الرقم السرى</label>
                                        <input type="password" name="password" class="form-control text-right" autocomplete="new-password" placeholder="ادخل فقط ارقام " required />
                                    </div>
                                    <!-- End Password Field -->
                                    <!-- Start Email Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="control-label">البريد الالكترونى</label>
                                        <input type="email" name="email" value="<?php echo $row['email'] ?>" class="form-control text-right" required="required" />
                                    </div>
                                    <!-- End Email Field -->
                                    <!-- Start phone  Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="control-label">رقم الهاتف</label>
                                        <input 
                                                class="form-control text-right" 
                                                type="tel" 
                                                name="phone" 
                                                placeholder="رقم الواتس للتواصل"
                                                value="<?php echo $row['phone'] ?>"
                                                required/>
                                    </div>
                                    <!-- End phone  Field -->
                                    <!-- Start Submit Field -->
                                    <div class="form-group form-group-lg margin">
                                            <input type="submit" value="احفظ" class="button" />
                                    </div>
                                    <!-- End Submit Field -->
                                </form>
                            </div>
                        <?php
                        } elseif ($do == 'Update') { ?>
                        <h1 class='text-center'>تحديث العضو</h1>
                            <?php
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $id 	= filter_var($_POST['userid'],FILTER_SANITIZE_NUMBER_INT);
                                $user 	= filter_var($_POST['username'],FILTER_SANITIZE_STRING);
                                $name 	= filter_var($_POST['full'],FILTER_SANITIZE_STRING);
                                $email 	= filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
                                $phone  = filter_var($_POST['phone'],FILTER_SANITIZE_NUMBER_INT);
                                $pass   = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
                                $hashedPass = sha1($pass);
                                $formErrors = array();
                                if (strlen($user) < 4) {
                                    $formErrors[] = 'الاسم لا يجب ان يكون اقل من 4 احرف';
                                }
                                if (strlen($user) > 20) {
                                    $formErrors[] = 'الاسم لايجب ان يكون ازيد من 20 حرف';
                                }
                                if (empty($user)) {
                                    $formErrors[] = 'الاسم لا يجب ان يكون فارغ';
                                }
                                if (empty($name)) {
                                    $formErrors[] = 'الاسم الكامل لا يجب ان يكون فارغ';
                                }
                                if (empty($email)) {
                                    $formErrors[] = 'البريد الالكترونى لا يجب ان يكون فارغ';
                                }
                                if (isset($phone)) {
                                    if (strlen($phone) < 8) {$formErrors[] = 'رقم الهاتف يجب ان يكون اكبر من 8 ارقام';}
                                }
                                foreach($formErrors as $error) {
                                    echo '<div class="alert alert-danger">' . $error . '</div>';
                                }
                                if (empty($formErrors)) {
                                    $stmt2 = $con->prepare("SELECT 
                                                                *
                                                            FROM 
                                                                members
                                                            WHERE
                                                                username = ?
                                                            AND 
                                                                userid != ?");
                                    $stmt2->execute(array($user, $id));
                                    $count = $stmt2->rowCount();
                                    if ($count == 1) {
                                        echo '<div class="alert alert-danger">هذا المستخدم موجود بالفعل</div>';
                                        echo "<div class='alert alert-info text-center'>سوف يتم اعادتك الصفحة الرئيسية بعد 3 ثوان.</div>";
                                        header("refresh:3;url='settings.php?do=Edit'");
                                    } else { 
                                        $stmt = $con->prepare("UPDATE members SET username = ?, email = ?, fullname = ?, password = ?, 	phone = ? WHERE userid = ?");
                                        $stmt->execute(array($user, $email, $name, $hashedPass, $phone, $id ));
                                        echo "<div class='alert alert-success main_alert setting_allert'>" . $stmt->rowCount() . ' Record Updated</div>';
                                        echo "<div class='alert alert-info text-center'>سوف يتم اعادتك الصفحة الرئيسية بعد 3 ثوان.</div>";
                                        header("refresh:3;url='profile.php'");
                                    }
                                }
                            } else {
                                echo '<div class="alert alert-danger main_alert setting_allert">لا يمكنك تصفح هذة الصفحة مباشرة</div>';
                                echo "<div class='alert alert-info text-center'>سوف يتم اعادتك الصفحة الرئيسية بعد 3 ثوان.</div>";
                                header("refresh:3;url='settings.php?do=Edit'");
                                //exit();
                            }
                        }
                }?>
                </div>         
            </div>
            <?php include $tpl . 'intro.php'; ?>
        </div>
    </div>
                        
<?php } else {
		header('Location: index.php');
		exit();
	}
	ob_end_flush();
    include $tpl . 'footer.php'; ?>
