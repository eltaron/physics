<?php
ob_start(); 
session_start();
$pageTitle = 'lessons';
if (isset($_SESSION['username'])) {
		include 'inital.php';
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
		if ($do == 'Manage') {
			$stmt = $con->prepare("SELECT 
										lessons.*, 
										category.category_name AS category_name, 
										members.username 
									FROM 
										lessons
									INNER JOIN 
										category 
									ON 
										category.category_id = lessons.cat_id 
									INNER JOIN 
										members 
									ON 
										members.userid = lessons.member_id
									ORDER BY 
										lesson_id DESC");
			$stmt->execute();
			$lessons = $stmt->fetchAll();
			if (! empty($lessons)) {
			?>
			<div class="manages container">
                <h1 class="text-center">الدروس</h1>
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
                            <td>لوحة التحكم</td>
                            <td>اسم المضيف</td>
                            <td>القسم</td>
                            <td>تاريخ الاضافة</td>
                            <td>الوصف</td>
                            <td>اسم الدرس</td>
							<td>الرقم التعريفي</td>
						</tr>
						<?php
							foreach($lessons as $lesson) {
								echo "<tr>";
									echo "<td>
										<a href='lessons.php?do=Edit&itemid=" . $lesson['lesson_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> تعديل</a>
										<a href='lessons.php?do=Delete&itemid=" . $lesson['lesson_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> حذف </a>";
										if ($lesson['Approve'] == 0) {
											echo "<a 
													href='lessons.php?do=Approve&itemid=" . $lesson['lesson_id'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> مميز</a>";
										}
									echo "</td>";
                                echo "<td>" . $lesson['username'] ."</td>";
                                echo "<td>" . $lesson['category_name'] ."</td>";
                                echo "<td>" . $lesson['lesson_data'] . "</td>";
                                echo "<td>" . $lesson['lesson_description'] . "</td>";
                                echo "<td>" . $lesson['lesson_name'] . "</td>";
                                echo "<td>" . $lesson['lesson_id'] . "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<a href="lessons.php?do=Add" class="btn btn-lg btn-primary">
					<i class="fa fa-plus">اضافة درس</i> 
				</a>
			</div>
			<?php } else {
				echo '<div class="container">';
					echo '<div class="message">لا يوجد دروس لعرضها</div>';
					echo '<a href="lessons.php?do=Add" class="btn btn-sm btn-primary">
							<i class="fa fa-plus"></i> اضافة درس
						</a>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($do == 'Add') { ?>
			<div class="container add text-right">
			 <h1 class="text-center">اضافة درس جديد</h1>                
				<form class="form-horizontal" action="?do=Insert" method="POST">
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الاسم</label>
							<input 
								type="text" 
								name="name" 
								class="form-control" 
								required="required"  
								placeholder="اسم الدرس" />
					</div>
					<!-- End Name Field -->
					<!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الوصف</label>
							<input 
								type="text" 
								name="description" 
								class="form-control" 
								required="required"  
								placeholder="وصف الدرس" />
					</div>
					<!-- End Description Field -->
					<!-- Start video Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">لنك الفيديو</label>
                            <textarea name="video" required class="text-right form-control" placeholder="ادخل لنك الفيديو كاملا" ></textarea>
					</div>
					<!-- End video Field -->
                    <!-- Start pdf Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">لنك ورق الشرح</label>
							<input 
								type="text" 
								name="pdf" 
								class="form-control" 
								placeholder="ادخل لنك الورق كاملا" />
					</div>
					<!-- End pdf Field -->
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
					<!-- Start Tags Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">العنواين</label>
							<input 
								type="text" 
								name="tags" 
								class="form-control" 
								placeholder=" (,)افصل بين العنواين ب " />
					</div>
					<!-- End Tags Field -->
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
				$desc 		= $_POST['description'];
				$video 		= $_POST['video'];
				$pdf 		= $_POST['pdf'];
				$member 	= $_POST['member'];
				$cat 		= $_POST['category'];
				$tags 		= $_POST['tags'];
				// Validate The Form
				$formErrors = array();
				if (empty($name)) {
					$formErrors[] = 'يجب اضافة اسم للدرس';
				}
				if (empty($desc)) {
					$formErrors[] = 'يجب اضافة وصف للدرس';
				}
				if (empty($video)) {
					$formErrors[] = 'يجب وضع لنك الفيديو';
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
						lessons(lesson_name, lesson_description, video, lesson_data, cat_id, member_id, tags, pdf)
						VALUES(:zname, :zdesc, :zvideo, now(), :zcat, :zmember, :ztags, :zpdf)");
					$stmt->execute(array(
						'zname' 	=> $name,
						'zdesc' 	=> $desc,
						'zvideo' 	=> $video,
						'zcat'		=> $cat,
						'zmember'	=> $member,
						'ztags'		=> $tags,
						'zpdf'		=> $pdf
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
		} elseif ($do == 'Edit') {
			$lessonid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
			$stmt = $con->prepare("SELECT * FROM lessons WHERE lesson_id = ?");
			$stmt->execute(array($lessonid));
			$lesson = $stmt->fetch();
			$count = $stmt->rowCount();
			if ($count > 0) { ?>
				<div class="add container text-right">
                    <h1 class="text-center">تعديل الدرس</h1>
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="itemid" value="<?php echo $lessonid ?>" />
						<!-- Start Name Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">الاسم</label>
								<input 
									type="text" 
									name="name" 
									class="form-control" 
									required="required"  
									placeholder="اسم الدرس"
									value="<?php echo $lesson['lesson_name'] ?>" />
						</div>
						<!-- End Name Field -->
						<!-- Start Description Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">وصف الدرس</label>
								<input 
									type="text" 
									name="description" 
									class="form-control" 
									required="required"  
									placeholder="وصف الدرس"
									value="<?php echo $lesson['lesson_description'] ?>" />
						</div>
						<!-- End Description Field -->
						<!-- Start video Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">لنك الفيديو</label>
                                <textarea name="video" required class="text-right form-control" placeholder="ادخل لنك الفيديو كاملا" ><?php echo $lesson['video'] ?></textarea>
						</div>
						<!-- End video Field -->
                        <!-- Start pdf Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">لنك ورق الشرح</label>
                                <textarea name="pdf" required class="text-right form-control" placeholder="لنك ورق الشرح" ><?php echo $lesson['pdf'] ?></textarea>
						</div>
						<!-- End pdf Field -->
						<!-- Start Members Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">الشخص المضيف</label>
								<select name="member">
									<?php
										$allMembers = getAllFrom("*", "members", "", "", "userid");
										foreach ($allMembers as $user) {
											echo "<option value='" . $user['userid'] . "'"; 
											if ($lesson['member_id'] == $user['userid']) { echo 'selected'; } 
											echo ">" . $user['username'] . "</option>";
										}
									?>
								</select>
						</div>
						<!-- End Members Field -->
						<!-- Start Categories Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">القسم</label>
								<select name="category">
									<?php
										$allCats = getAllFrom("*", "category", "where parent = 0", "", "category_id");
										foreach ($allCats as $cat) {
											echo "<option value='" . $cat['category_id'] . "'";
											if ($lesson['cat_id'] == $cat['category_id']) { echo ' selected'; }
											echo ">" . $cat['category_name'] . "</option>";
											$childCats = getAllFrom("*", "category", "where parent = {$cat['category_id']}", "", "category_id");
											foreach ($childCats as $child) {
												echo "<option value='" . $child['category_id'] . "'";
												if ($lesson['cat_id'] == $child['category_id']) { echo ' selected'; }
												echo ">--- " . $child['category_name'] . "</option>";
											}
										}
									?>
								</select>
						</div>
						<!-- End Categories Field -->
						<!-- Start Tags Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">Tags</label>
								<input 
									type="text" 
									name="tags" 
									class="form-control" 
									placeholder=" (,) افصل بين اللنكات عن طريق" 
									value="<?php echo $lesson['tags'] ?>" />
						</div>
						<!-- End Tags Field -->
						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
								<input type="submit" value="احفظ الدرس" class="btn btn-primary btn-lg" />
						</div>
						<!-- End Submit Field -->
					</form>
					<?php
					// Select All Users Except Admin 
					$stmt = $con->prepare("SELECT 
												comments.*, members.username AS Member  
											FROM 
												comments
											INNER JOIN 
												members 
											ON 
												members.userid = comments.member_id
											WHERE lesson_id = ?");
					$stmt->execute(array($lessonid));
					$rows = $stmt->fetchAll();
					if (! empty($rows)) {		
					?>
					<div class="add table-responsive">
                        <h2 class="text-center"> [ <?php echo $lesson['lesson_name'] ?> ] التعليقات علي درس</h2>
						<table class="main-table text-center table table-bordered">
							<tr>
                                <td>التحكم</td>
                                <td>تاريخ الاضافة</td>
                                <td>اسم الشخص</td>
                                <td>التعليقات</td>
							</tr>
							<?php
								foreach($rows as $row) {
									echo "<tr>";
										echo "<td>
											<a href='comments.php?do=Edit&comid=" . $row['comment_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> تعديل</a>
											<a href='comments.php?do=Delete&comid=" . $row['comment_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> حذف </a>";
											if ($row['status'] == 0) {
												echo "<a href='comments.php?do=Approve&comid="
														 . $row['comment_id'] . "' 
														class='btn btn-info activate'>
														<i class='fa fa-check'></i> مميز</a>";
											}
										echo "</td>";
                                        echo "<td>" . $row['comment_data'] ."</td>";
                                        echo "<td>" . $row['Member'] . "</td>";
                                        echo "<td>" . $row['comment'] . "</td>";
									echo "</tr>";
								}
							?>
							<tr>
						</table>
					</div>
					<?php } ?>
				</div>
			<?php
			} else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">لا يوجد رقم تعريفي</div>';
				redirectHome($theMsg);
				echo "</div>";
			}		
		}  elseif ($do == 'Update') {
			echo "<div class='add container'>";
            echo "<h1 class='text-center'>تحديث القسم</h1>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Get Variables From The Form
				$id 		= $_POST['itemid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$video 		= $_POST['video'];
				$cat 		= $_POST['category'];
				$member 	= $_POST['member'];
				$tags 		= $_POST['tags'];
				$pdf 		= $_POST['pdf'];
				// Validate The Form
				$formErrors = array();
				if (empty($name)) {
					$formErrors[] = 'يجب اضافة اسم للدرس';
				}
				if (empty($desc)) {
					$formErrors[] = 'يجب اضافة وصف للدرس';
				}
				if (empty($video)) {
					$formErrors[] = 'يجب وضع لنك الفيديو';
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
					$stmt = $con->prepare("UPDATE 
												lessons 
											SET 
												lesson_name = ?, 
												lesson_description = ?, 
												video = ?, 
												cat_id = ?,
												member_id = ?,
												tags = ?,
												pdf = ?
											WHERE 
												lesson_id = ?");

					$stmt->execute(array($name, $desc, $video, $cat, $member, $tags, $pdf, $id));
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
					redirectHome($theMsg, 'back');
				}
			} else {
				$theMsg = '<div class="alert alert-danger">لا يمكنك تصفح تلك الصفحة مباشرة</div>';
				redirectHome($theMsg);
			}
			echo "</div>";
		} elseif ($do == 'Delete') {
			echo "<div class='add container'>";
            echo "<h1 class='text-center'>حزف الدرس</h1>";
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            $check = checkItem('lesson_id', 'lessons', $itemid);
            if ($check > 0) {
                $stmt = $con->prepare("DELETE FROM lessons WHERE lesson_id = :zid");
                $stmt->bindParam(":zid", $itemid);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
                redirectHome($theMsg, 'back');
            } else {
                $theMsg = '<div class="alert alert-danger">هذا الرقم التعريفى غير موجود</div>';
                redirectHome($theMsg);
            }
			echo '</div>';
		} elseif ($do == 'Approve') {
			echo "<div class='add container'>";
            echo "<h1 class='text-center'>درس مميز</h1>";
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            $check = checkItem('lesson_id', 'lessons', $itemid);
            if ($check > 0) {
                $stmt = $con->prepare("UPDATE lessons SET Approve = 1 WHERE lesson_id = ?");
                $stmt->execute(array($itemid));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                redirectHome($theMsg, 'back');
            } else {
                $theMsg = '<div class="alert alert-danger">هذا الرقم التعريفي غير موجود</div>';
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