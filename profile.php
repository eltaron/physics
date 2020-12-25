<?php
	ob_start();
	session_start();
	$pageTitle = 'Profile';
	include 'inital.php';
    include "check_token.php";
	if (isset($_SESSION['user'])) {
		$getUser = $con->prepare("SELECT * FROM members WHERE username = ?");
		$getUser->execute(array($sessionUser));
		$info = $getUser->fetch();
		$userid = $info['userid'];
        $avatar = $info['avatar'];
        $data = $info['date'];
        $last_exam = $info['exam answer'];
        $stmt2 = $con->prepare("SELECT 
                                    members.*, category.category_name AS categoryname  
                                FROM 
                                    members
                                INNER JOIN 
                                    category 
                                ON 
                                    members.groupid = category.ordering
                                WHERE 
                                    username  = ?");
        $stmt2->execute(array($sessionUser));
        $name = $stmt2->fetch();
?>
<div class="profilebody">
    <div class="container profile">
        <div class="row">
        <?php include $tpl . 'intro.php'; ?>
            <div class="col-md-8 order-md-1">
            <?php include $tpl . 'last.php'; ?>
                <div class="left text-center">
                        <div class="card">
                            <div class="card-header">البيانات الشخصية</div>
                            <img class="card-img-top img-thumbnail" src="admin/uploads/avatars/<?php echo $avatar; ?>" alt="" />
                            <div class="card-body">
                                <h4 class="card-title h2"><?php echo $_SESSION['user']; ?></h4>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $data; ?></h6>
                                <ul class="list-group">
                                    <li class="list-group-item ">  تاريخ الانضمام : <?php echo $data; ?></li>
                                    <li class="list-group-item"><?php echo $name['categoryname'] ?> </li>
                                    <li class="list-group-item"><a href="exam_result.php">نتائج الامتحانات</a></li>
                                </ul>
                                <a href="settings.php?do=Edit&userid=<?php echo $userid; ?>" class="card-link">تعديل البيانات</a>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>    
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
        $stmt = $con->prepare("UPDATE members SET lil = ?, lil_data = now() WHERE userid = ?");
        $stmt->execute(array($comment, $userid));
    }

	} else {
		header('Location: index.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>