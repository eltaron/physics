<?php
ob_start(); 
session_start();
$pageTitle = 'questions';
if (isset($_SESSION['username'])) {
		include 'inital.php';
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
		if ($do == 'Manage') {
           $stmt = $con->prepare("SELECT * FROM question ORDER BY question_id DESC");
			$stmt->execute();
			$questions = $stmt->fetchAll();
			if (! empty($questions)) {
			?>
            <div class="manage">
                <h1 class="text-center">صفحة عرض الاسئلة</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                         <tr>
                            <td>لوحة التحكم</td>
                            <td>تاريخ الاضافة</td>
                            <td>السؤال</td>
                            <td>رقم السؤال</td>
                         </tr>
                            <?php
                                foreach($questions as $question) {
                                    echo "<tr>";
                                       echo "<td>
                                            <a href='question_list.php?do=Delete&questionid=" . $question['id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> حزف </a>";
                                        echo "</td>";
                                        echo "<td>" . $question['added_date'] ."</td>";
                                        echo "<td>" . $question['ques'] . "</td>";
                                        echo "<td>" . $question['question_id'] . "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </table>
                        <a href="question_list.php?do=Add" class="btn btn-lg btn-primary">
                           <i class="fa fa-plus">اضافة سؤال</i> 
                        </a>
                    </div>
                </div>
            </div>
<?php 
       } else {
				echo '<div class="container">';
					echo '<div class="message text-right">لا يوجد اسئلة لعرضها</div>';
                    echo '<a href="question_list.php?do=Add" class="btn btn-lg btn-primary">
                           <i class="fa fa-plus">اضافة سؤال</i> 
                        </a>';
				echo '</div>';
			    }   
   } elseif ($do == 'Delete') {
			echo "<div class='add container'>";
            echo "<h1 class='text-center'>حزف السؤال</h1>";
            $questionid = isset($_GET['questionid']) && is_numeric($_GET['questionid']) ? intval($_GET['questionid']) : 0;
            $check = checkItem('id', 'question', $questionid);
            if ($check > 0) {
                $stmt = $con->prepare("DELETE FROM question WHERE id = :zid");
                $stmt->bindParam(":zid", $questionid);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
                redirectHome($theMsg, 'back');
            } else {
                $theMsg = '<div class="alert alert-danger">هذا الرقم التعريفى غير موجود</div>';
                redirectHome($theMsg);
            } 
        } elseif ($do == 'Add') { ?>
           <div class="manages container text-right">
			 <h1 class="text-center">اضافة سؤال جديد</h1>
               <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                    <table class="table-secondary text-center table table-bordered  table-sm-responsive">
                        <tr>
                            <td><input type="file" name="img" class="form-control" /></td>
                            <td>:</td>
                            <td>اضافة صورة</td>
                        </tr>
                        <tr>
                            <td><input type="text"  name="ques" class="form-control" placeholder="ادخل السؤال" required></td>
                            <td>:</td>
                            <td>السؤال</td>
                        </tr>
                        <tr>
                            <td><input type="text" name="ans1" class="form-control" placeholder="الاختيار الاول" required></td>
                            <td>:</td>
                            <td>الاختيار الاول</td>
                        </tr>
                        <tr>
                            <td><input type="text"  name="ans2" class="form-control" placeholder="الاختيار الثانى" required></td>
                            <td>:</td>
                            <td>الاختيار الثانى</td>
                        </tr>
                        <tr>
                            <td><input type="text"  name="ans3" class="form-control" placeholder="الاختيار الثالث" required></td>
                            <td>:</td>
                            <td>الاختيار الثالث</td>
                        </tr>
                        <tr>
                            <td><input type="text"  name="ans4" class="form-control" placeholder="الاختيار الرابع" required></td>
                            <td>:</td>
                            <td>الاختيار الرابع</td>
                        </tr>
                        <tr>
                            <td>
                               <select name="rightAns">
								<option value="0">...</option>
                                    <?php
                                        echo "<option value='1'>" . "الاختيار الاول" . "</option>";
                                        echo "<option value='2'>" . "الاختيار الثانى" . "</option>";
                                        echo "<option value='3'>" . "الاختيار الثالث" . "</option>";
                                        echo "<option value='4'>" . "الاختيار الرابع" . "</option>";
                                    ?>
							</select>
                            </td>
                            <td>:</td>
                            <td>الاختيار الصحيح</td>
                        </tr>
                        <tr>
                            <td>
                               <select name="exam">
								<option value="0">...</option>
                                    <?php
                                        $allexams = getAllFrom("*", "exams", "", "", "exam_id", "");
                                        foreach ($allexams as $exam) {
                                            echo "<option value='" . $exam['exam_id'] . "'>" . $exam['exam_name'] . "</option>";
										}
                                    ?>
							</select>
                            </td>
                            <td>:</td>
                            <td>اسم الامتحان</td>
                        </tr>
                    </table>
                    <div class="form-group form-group-lg">
                        <input type="submit" value="اضافة المنشور" class="btn btn-primary btn-lg" />
                    </div>
               </form>
           </div>      
<?php
		} elseif ($do == 'Insert') {
			// Insert Member Page
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<h1 class='text-center'>اضافة عضو</h1>";
				echo "<div class='container'>";
                    // Get Variables From The Form
                    $ques  = $_POST['ques'];
                    $ans1  = $_POST['ans1'];
                    $ans2  = $_POST['ans2'];
                    $ans3  = $_POST['ans3'];
                    $ans4  = $_POST['ans4'];
                    //right answer
                    if ($_POST['rightAns'] == 1){$rightAns = $_POST['ans1'];}
                    elseif ($_POST['rightAns'] == 2){$rightAns = $_POST['ans2'];}
                    elseif ($_POST['rightAns'] == 3){$rightAns = $_POST['ans3'];}
                    elseif ($_POST['rightAns'] == 4){$rightAns = $_POST['ans4'];}
                    else {echo 'تم ادخال قيمة خطا';}
                    $exam  = $_POST['exam'];
                    // Upload Variables
                    $imgName = $_FILES['img']['name'];
                    $imgSize = $_FILES['img']['size'];
                    $imgTmp	= $_FILES['img']['tmp_name'];
                    $imgType = $_FILES['img']['type'];
                    // List Of Allowed File Typed To Upload
                    $imgAllowedExtension = array("jpeg", "jpg", "png", "gif");
                    // Get Avatar Extension
                    $imgs = explode('.', $imgName);
                    $imgExtension = strtolower(end($imgs));
                    $formErrors = array();
                    if (empty($ques)) {
                        $formErrors[] = 'يجب اضافة سؤال';
                    }
                    if (empty($ans1)) {
                        $formErrors[] = 'يجب اضافة اول اجابة';
                    }
                    if (empty($ans2)) {
                        $formErrors[] = 'يجب وضع ثاني اجابة';
                    }
                    if (empty($ans3)) {
                        $formErrors[] = 'يجب اضافة ثالث اجابة';
                    }
                    if (empty($ans4)) {
                        $formErrors[] = 'يجب وضع رابع اجابة';
                    }
                    if (empty($rightAns)) {
                        $formErrors[] = 'يجب وضع الاجابة الصحيحة';
                    }
                    if (! empty($imgName) && ! in_array($imgExtension, $imgAllowedExtension)) {
					$formErrors[] = 'امتداد الصورة هذا غير متوفر';
				    }
                    foreach($formErrors as $error) {
                        $theMsg = '<div class="alert alert-danger">' . $error . '</div>';
                        redirectHome($theMsg, 'back');
                    }
                    if (empty($formErrors)) {
                        $img = $imgName;
					    move_uploaded_file($imgTmp, "uploads\avatars\\" . $img);
                        $stmt = $con->prepare("INSERT INTO 
                            question(ques, added_date, answer_1, answer_2, answer_3, answer_4, right_answer, question_id, photo)
                            VALUES(:zques, now(), :zanswer_1, :zanswer_2, :zanswer_3, :zanswer_4, :zright, :zquestion_id, :zphoto)");
                        $stmt->execute(array(
                            'zques' 	    => $ques,
                            'zanswer_1'     => $ans1,
                            'zanswer_2'     => $ans2,
                            'zanswer_3'	    => $ans3,
                            'zanswer_4'  	=> $ans4,
                            'zright'	    => $rightAns,
                            'zquestion_id'	=> $exam,
                            'zphoto'        => $img
                        ));
                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
                        redirectHome($theMsg, 'back');
                   	}
			} else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">لا يمكنك تصفح هذة الصفحة مباشرة</div>';
				redirectHome($theMsg);
				echo "</div>";
			}
			echo "</div>";
		} 
  include $tpl . 'footer.php';
} else {
		header('Location: index.php');
		exit();
}
ob_end_flush();
?>                                