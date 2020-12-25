<?php
ob_start(); 
session_start();
$pageTitle = 'lessons';
if (isset($_SESSION['username'])) {
		include 'inital.php';
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
		if ($do == 'Manage') {
			$stmt = $con->prepare("SELECT 
										exams.*, 
										category.category_name AS category_name, 
										members.username 
									FROM 
										exams
									INNER JOIN 
										category 
									ON 
										category.category_id = exams.categ_id 
									INNER JOIN 
										members 
									ON 
										members.userid = exams.member_id
									ORDER BY 
										exam_id DESC");
			$stmt->execute();
			$exams = $stmt->fetchAll();
			if (! empty($exams)) {
			?>
			<div class="manages container">
                <h1 class="text-center">الامتحانات</h1>
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
                            <td>لوحة التحكم</td>
                            <td>اسم المضيف</td>
                            <td>القسم</td>
                            <td>تاريخ الاضافة</td>
                            <td>اسم الامتحان</td>
							<td>الرقم التعريفي</td>
						</tr>
						<?php
							foreach($exams as $exam) {
								echo "<tr>";
									echo "<td>
										<a href='exam.php?do=Delete&examid=" . $exam['exam_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> حذف </a>";
									echo "</td>";
                                echo "<td>" . $exam['username'] ."</td>";
                                echo "<td>" . $exam['category_name'] ."</td>";
                                echo "<td>" . $exam['exam_date'] . "</td>";
                                echo "<td>" . $exam['exam_name'] . "</td>";
                                echo "<td>" . $exam['exam_id'] . "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<a href="exam.php?do=Add" class="btn btn-lg btn-primary">
					<i class="fa fa-plus">اضافة امتحان</i> 
				</a>
                <a href="mark.php" class="btn btn-lg btn-primary">
					<i class="fa fa-check">نتائج الطلبة</i> 
				</a>
			</div>
			<?php } else {
				echo '<div class="container">';
					echo '<div class="message text-right">لا يوجد امتحانات</div>';
					echo '<a href="exam.php?do=Add" class="btn btn-sm btn-primary">
							<i class="fa fa-plus"></i> اضافة امتحان
						</a>';
				echo '</div>';

			} ?>

		<?php 
		} elseif ($do == 'Add') { ?>
			<div class="container add text-right">
			 <h1 class="text-center">اضافة امتحان جديد</h1>                
				<form class="form-horizontal" action="?do=Insert" method="POST">
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الاسم</label>
							<input 
								type="text" 
								name="name" 
								class="form-control" 
								required="required"  
								placeholder="اسم الامتحان" />
					</div>
					<!-- End Name Field -->
					<!-- Start Members Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الشخص المضيف</label>
							<select name="member">
								<option value="0">...</option>
								<?php
									$allMembers = getAllFrom("*", "members", "", "", "userid");
									foreach ($allMembers as $user) {
										echo "<option value='" . $user['userid'] . "'>" . $user['username'] . "</option>";
									}
								?>
							</select>
					</div>
					<!-- End Members Field -->
                    <!-- Start lesson Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الدرس المرتبط بة</label>
							<select name="lesson">
								<option value="0">...</option>
								<?php
									$allMembers = getAllFrom("*", "lessons", "", "", "lesson_id");
									foreach ($allMembers as $lesson) {
										echo "<option value='" . $lesson['lesson_id'] . "'>" . $lesson['lesson_name'] . "</option>";
									}
								?>
							</select>
					</div>
					<!-- End lesson Field -->
					<!-- Start Categories Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">القسم</label>
							<select name="category">
								<option value="0">...</option>
								<?php
									$allCats = getAllFrom("*", "category", "where parent = 0", "", "ordering", "asc");
									foreach ($allCats as $cat) {
										echo "<option value='" . $cat['category_id'] . "'>" . $cat['category_name'] . "</option>";
										$childCats = getAllFrom("*", "category", "where parent = {$cat['category_id']}", "", "ordering", "asc");
										foreach ($childCats as $child) {
											echo "<option value='" . $child['category_id'] . "'>--- " . $child['category_name'] . "</option>";
										}
									}
								?>
							</select>
					</div>
					<!-- End Categories Field -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
							<input type="submit" value="اضافة درس" class="btn btn-primary btn-lg" />
					</div>
					<!-- End Submit Field -->
				</form>
			</div>
			<?php
		} elseif ($do == 'Insert') {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<div class='container add'>";
                echo "<h1 class='text-center'>اضافة الدرس</h1>";
				// Get Variables From The Form
				$name		= $_POST['name'];
				$member 	= $_POST['member'];
				$cat 		= $_POST['category'];
				$lesson		= $_POST['lesson'];
				// Validate The Form
				$formErrors = array();
				if (empty($name)) {
					$formErrors[] = 'يجب اضافة اسم للدرس';
				}
				if ($member == 0) {
					$formErrors[] = 'يجب عليك اختيار الشخص ';
				}
				if ($cat == 0) {
					$formErrors[] = 'يجب اختيار القسم';
				}
				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}
				if (empty($formErrors)) {
					$stmt = $con->prepare("INSERT INTO 
						exams(exam_name, exam_date, categ_id, member_id, lesson_id)
						VALUES(:zname, now(), :zcat, :zmember, :zlesson)");
					$stmt->execute(array(
						'zname' 	=> $name,
						'zcat'		=> $cat,
						'zmember'	=> $member,
						'zlesson'	=> $lesson
					));
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
					redirectHome($theMsg, 'back');
				}
			} else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">لا يمكنك تصفح تلك الصفحة مباشرة</div>';
				redirectHome($theMsg);
				echo "</div>";
			}
			echo "</div>";
		} elseif ($do == 'Delete') {
			echo "<div class='add container'>";
            echo "<h1 class='text-center'>حزف الامتحان</h1>";
            $examid = isset($_GET['examid']) && is_numeric($_GET['examid']) ? intval($_GET['examid']) : 0;
            $check = checkItem('exam_id', 'exams', $examid);
            if ($check > 0) {
                $stmt = $con->prepare("DELETE FROM exams WHERE exam_id = :zid");
                $stmt->bindParam(":zid", $examid);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
                redirectHome($theMsg, 'back');
            } else {
                $theMsg = '<div class="alert alert-danger">هذا الرقم التعريفى غير موجود</div>';
                redirectHome($theMsg);
            }
			echo '</div>';
		}               
    include $tpl . 'footer.php';
	} else {
		header('Location: index.php');
		exit();
	}
	ob_end_flush();
?>        