<?php 
ob_start();
session_start();
	$pageTitle = 'Comments';
	if (isset($_SESSION['username'])) {
		include 'inital.php';
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
		// Start Manage Page
		if ($do == 'Manage') { 
            // Manage Members Page
			$stmt = $con->prepare("SELECT 
										comments.*, members.username AS Member, post.post_name AS post_name
									FROM 
										comments
									INNER JOIN 
										members 
									ON 
										members.userid = comments.member_id
                                    INNER JOIN 
										post 
									ON 
										post.post_id = comments.post_id    
									ORDER BY 
										comment_id DESC");
			$stmt->execute();
			$comments = $stmt->fetchAll();
			if (! empty($comments)) {
			?>
            <div class="manage">
			<h1 class="text-center">صفحة التعليقات</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>لوحة التحكم</td>
                            <td>تاريخ الاضافة</td>
                            <td>اسم العضو</td>
                            <td>اسم الدرس</td>
                            <td>التعليقات</td>
							<td>الرقم التعريفي</td>
						</tr>
						<?php
							foreach($comments as $comment) {
								echo "<tr>";
                                   echo "<td>
										<a href='comments.php?do=Edit&comid=" . $comment['comment_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> تعديل</a>
										<a href='comments.php?do=Delete&comid=" . $comment['comment_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> حزف </a>";
										if ($comment['status'] == 0) {
											echo "<a href='comments.php?do=Approve&comid="
													 . $comment['comment_id'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> توثيق</a>";
										}
									echo "</td>";
                                    echo "<td>" . $comment['comment_data'] ."</td>";
                                    echo "<td>" . $comment['Member'] . "</td>";
                                    echo "<td>" . $comment['post_name'] . "</td>";
                                    echo "<td>" . $comment['comment'] . "</td>";
									echo "<td>" . $comment['comment_id'] . "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
			</div>
            </div>
			<?php } else {
				echo '<div class="container">';
					echo '<div class="message text-right">لا يوجد تعليق لعرضة</div>';
				echo '</div>';
			    }
		} elseif ($do == 'Edit') {
			// Check If Get Request comid Is Numeric & Get Its Integer Value
			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
			// Select All Data Depend On This ID
			$stmt = $con->prepare("SELECT * FROM comments WHERE comment_id = ?");
			$stmt->execute(array($comid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
			if ($count > 0) { ?>
				<h1 class="text-center">تعديل التعليق</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="comid" value="<?php echo $comid ?>" />
						<!-- Start Comment Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">التعليق</label>
								<textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
						</div>
						<!-- End Comment Field -->
						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
							   <input type="submit" value="احفظ" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End Submit Field -->
					</form>
				</div>
			<?php
			} else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">لا يوجد رقم تعريفى</div>';
				redirectHome($theMsg);
				echo "</div>";
			}
		} elseif ($do == 'Update') {
            // Update Page
			echo "<h1 class='text-center'>تحديث التعليق</h1>";
			echo "<div class='container'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Get Variables From The Form
				$comid 		= $_POST['comid'];
				$comment 	= $_POST['comment'];
				// Update The Database With This Info
				$stmt = $con->prepare("UPDATE comments SET comment = ? WHERE comment_id = ?");
				$stmt->execute(array($comment, $comid));
				$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
				redirectHome($theMsg, 'back');
			} else {
				$theMsg = '<div class="alert alert-danger">لا يمكنك تصفح تلك الصفحة مباشرة</div>';
				redirectHome($theMsg);
			}
			echo "</div>";
		} elseif ($do == 'Delete') {
            // Delete Page
			echo "<h1 class='text-center'>حذف الصفحة</h1>";
			echo "<div class='container'>";
				// Check If Get Request comid Is Numeric & Get The Integer Value Of It
				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
				// Select All Data Depend On This ID
				$check = checkItem('comment_id', 'comments', $comid);
				if ($check > 0) {
					$stmt = $con->prepare("DELETE FROM comments WHERE comment_id = :zid");
					$stmt->bindParam(":zid", $comid);
					$stmt->execute();
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
					redirectHome($theMsg, 'back');
				} else {
					$theMsg = '<div class="alert alert-danger">الرقم التعريفي غير موجود</div>';
					redirectHome($theMsg);
				}
			echo '</div>';

		} elseif ($do == 'Approve') {
			echo "<h1 class='text-center'>تعليق موثوق</h1>";
			echo "<div class='container'>";
				// Check If Get Request comid Is Numeric & Get The Integer Value Of It
				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
				$check = checkItem('comment_id', 'comments', $comid);
				if ($check > 0) {
					$stmt = $con->prepare("UPDATE comments SET status = 1 WHERE comment_id = ?");
					$stmt->execute(array($comid));
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved</div>';
					redirectHome($theMsg, 'back');
				} else {
					$theMsg = '<div class="alert alert-danger">الرقم التعريفي غير موجو</div>';
					redirectHome($theMsg);
				}
			echo '</div>';
		} ?>
		<?php 
        include $tpl . 'footer.php';    
    } else {
		header('Location: index.php');
		exit();
	}
    ob_end_flush(); 
?>