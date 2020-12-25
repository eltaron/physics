<?php 
	session_start();
    $pageTitle = 'Profile';
    $noNavbar = '';
	include 'inital.php';
    include "check_token.php";
    if (isset($_SESSION['user'])) {?>
		<div class="profilebody">
            <div class="container">
                <div class="profile row">
                    <div class="col-md-8 order-sm-2 order-md-1">
                        <?php include $tpl . 'last.php'; ?>
                        <div class="result alls">
                            <h1 class="text-center">نتائج الامتحانات</h1>
                            <div class="table-responsive">
                                <table class="text-center table table-bordered">
                                    <tr class="table-primary">
                                        <td> نتيجة الامتحان</td>
                                        <td> تاريخ اداء الامتحان</td>
                                        <td >اسم الامتحان</td>
                                    </tr>
                                    <?php
                                    $formErrors = array(); 
                                    $stmt = $con->prepare("SELECT 
                                                                answer.*, exams.exam_name AS exam_name  
                                                            FROM 
                                                                answer
                                                            INNER JOIN 
                                                                exams 
                                                            ON 
                                                                exams.exam_id = answer.exam_id 
                                                            WHERE 
                                                                user_id = ? 
                                                            ORDER BY exam_id asc");
                                    $stmt->execute(array($_SESSION['uid']));
                                    $exams = $stmt->fetchAll();
                                    foreach ($exams as $exam) {  ?>
                                        <tr class="table-light">
                                            <td ><?php echo $exam['mark'] ?></td>
                                            <td><?php echo $exam['date'] ?></td>
                                            <td><?php echo $exam['exam_name'] ?></td>
                                        </tr>
                                    <?php }?>
                                </table>
                            </div>
                        </div>    
                    </div>
                    <?php include $tpl . 'intro.php'; ?>                     
                </div>
            </div>     
        </div>               
    <?php } else {
		header('Location: index.php');
		exit();
	}
    include $tpl . 'footer.php'; ?>