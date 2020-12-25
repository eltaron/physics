<?php
ob_start(); 
session_start();
$pageTitle = 'posts';
if (isset($_SESSION['username'])) {
		include 'inital.php';
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
		if ($do == 'Manage') {
           $stmt = $con->prepare("SELECT 
										post.*, members.username AS Member
									FROM 
										post
									INNER JOIN 
										members 
									ON 
										members.userid = post.users
									ORDER BY 
								       post_id DESC");
			$stmt->execute();
			$posts = $stmt->fetchAll();
			if (! empty($posts)) {
			?>
            <div class="manage">
			<h1 class="text-center">صفحة المنشورات</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>لوحة التحكم</td>
                            <td>تاريخ الاضافة</td>
                            <td>اسم العضو</td>
                            <td>اسم البوست</td>
							<td>الرقم التعريفي</td>
						</tr>
						<?php
							foreach($posts as $post) {
								echo "<tr>";
                                   echo "<td>
										<a href='post.php?do=Edit&postid=" . $post['post_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> تعديل</a>
										<a href='post.php?do=Delete&postid=" . $post['post_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> حزف </a>";
										if ($post['allow_comment'] == 0) {
											echo "<a href='post.php?do=Approve&postid="
													 . $post['post_id'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> مميز</a>";
										}
									echo "</td>";
                                    echo "<td>" . $post['post_data'] ."</td>";
                                    echo "<td>" . $post['Member'] . "</td>";
                                    echo "<td>" . $post['post_name'] . "</td>";
									echo "<td>" . $post['post_id'] . "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
                    <a href="post.php?do=Add" class="btn btn-lg btn-primary">
					   <i class="fa fa-plus">اضافة منشور</i> 
				    </a>
				</div>
			</div>
            </div>
			<?php } else {
				echo '<div class="container">';
					echo '<div class="message text-right">لا يوجد منشور لعرضة</div>';
                    echo '<a href="post.php?do=Add" class="btn btn-lg btn-primary">
					   <i class="fa fa-plus">اضافة منشور</i> 
				    </a>';
				echo '</div>';
			    }
		} elseif ($do == 'Add') { ?>
			<div class="container add text-right">
			 <h1 class="text-center">اضافة منشور جديد</h1>                
				<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">عنوان المنشور</label>
							<input 
								type="text" 
								name="name" 
								class="form-control" 
								required="required"  
								placeholder="عنوان المنشور" />
					</div>
					<!-- End Name Field -->
                    <!-- Start Avatar Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">اضافة صورة</label>
							<input type="file" name="img" class="form-control" />
					</div>
					<!-- End Avatar Field -->
					<!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الوصف</label>
							<input 
								type="text" 
								name="description" 
								class="form-control" 
								required="required"  
								placeholder="وصف المنشور" />
					</div>
					<!-- End Description Field -->
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
							<input type="submit" value="اضافة المنشور" class="btn btn-primary btn-lg" />
					</div>
					<!-- End Submit Field -->
				</form>
			</div>
			<?php
		}  elseif ($do == 'Insert') {
			// Insert Member Page
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<h1 class='text-center'>اضافة عضو</h1>";
				echo "<div class='container'>";
				// Get Variables From The Form
				$name 	      = $_POST['name'];
				$description  = $_POST['description'];
				$member 	  = $_POST['member'];
				$tags 	      = $_POST['tags'];
                // Upload Variables
				$imgName = $_FILES['img']['name'];
				$imgSize = $_FILES['img']['size'];
				$imgTmp	= $_FILES['img']['tmp_name'];
				$imgType = $_FILES['img']['type'];
				// List Of Allowed File Typed To Upload
				$imgAllowedExtension = array("jpg", "png", "gif");
				// Get Avatar Extension
                $imgs = explode('.', $imgName);
				$imgExtension = strtolower(end($imgs));
				$formErrors = array();
				if (strlen($name) < 4) {
					$formErrors[] = 'الاسم المنشور يجب ان يكون اقل من 4 احرف';
				}
				if (empty($name)) {
					$formErrors[] = 'الاسم لا يجب ان يكون فارغ';
				}
				if (empty($description)) {
					$formErrors[] = 'الوصف لا يجب ان يكون فارغ';
				}
				if (empty($member)) {
					$formErrors[] = 'اسم الشخص لا يجب ان يكون فارغ';
				}
				if (! empty($imgName) && ! in_array($imgExtension, $imgAllowedExtension)) {
					$formErrors[] = 'امتداد الصورة هذا غير متوفر';
				}
				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}
				if (empty($formErrors)) {
					$img = rand(0, 10000000) . '_' . $imgName;
					move_uploaded_file($imgTmp, "uploads\avatars\\" . $img);
					$check = checkItem("username", "members", $user);
					if ($check == 1) {
						$theMsg = '<div class="alert alert-danger">هذا المستخدم موجود</div>';
						redirectHome($theMsg, 'back');
					} else {
						// Insert Userinfo In Database
						$stmt = $con->prepare("INSERT INTO 
													post(post_name, post_description, post_image, post_data, users, tags)
												VALUES(:zname, :zdescription, :zimage, now(), :zusers, :ztags) ");
						$stmt->execute(array(
							'zname' 	    => $name,
							'zdescription' 	=> $description,
							'zimage' 	    => $img,
							'zusers' 	    => $member,
							'ztags'	        => $tags
						));
						// Echo Success Message
						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
						redirectHome($theMsg, 'back');
					}
				}
			} else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">لا يمكنك تصفح هذة الصفحة مباشرة</div>';
				redirectHome($theMsg);
				echo "</div>";
			}
			echo "</div>";
            
		} elseif ($do == 'Edit') {
			// Check If Get Request userid Is Numeric & Get Its Integer Value
			$postid = isset($_GET['postid']) && is_numeric($_GET['postid']) ? intval($_GET['postid']) : 0;
			// Select All Data Depend On This ID
			$stmt = $con->prepare("SELECT * FROM post WHERE post_id = ? LIMIT 1");
			$stmt->execute(array($postid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
			if ($count > 0) { ?>                                
				<h1 class="text-center">تعديل المنشور</h1>          
				<div class="container text-right">
					<form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                        <?php if (empty($row['post_image'])) {
										echo '<h4 class="text-center">No Image</h4>';
									} else {
										echo "<img class='imgs img-thumbnail' src='uploads/avatars/" . $row['post_image'] . "' alt='' />";
									}?>
					<input type="hidden" name="postid" value="<?php echo $postid ?>" />
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">عنوان المنشور</label>
							<input 
								type="text" 
								name="name" 
								class="form-control" 
								required="required" 
                                value="<?php echo $row['post_name'] ?>"   
								placeholder="عنوان المنشور" />
					</div>
					<!-- End Name Field -->
                    <!-- Start Avatar Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">اضافة صورة</label>
							<input type="file" name="img" class="form-control"  value="<?php echo $row['post_image']; ?>"/>
					</div>
					<!-- End Avatar Field -->
					<!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الوصف</label>
							<input 
								type="text" 
								name="description" 
								class="form-control" 
								required="required"
                                value="<?php echo $row['post_description'] ?>"   
								placeholder="وصف المنشور" />
					</div>
					<!-- End Description Field -->
					<!-- Start Members Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الشخص المضيف</label>
							<select name="member">
								<?php
										$allMembers = getAllFrom("*", "members", "", "", "userid");
										foreach ($allMembers as $user) {
											echo "<option value='" . $user['userid'] . "'"; 
											if ($row['users'] == $user['userid']) { echo 'selected'; } 
											echo ">" . $user['username'] . "</option>";
										}
									?>
							</select>
					</div>
					<!-- End Members Field -->
					<!-- Start Tags Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">العنواين</label>
							<input 
								type="text" 
								name="tags" 
								class="form-control"
                                value="<?php echo $row['tags'] ?>"   
								placeholder=" (,)افصل بين العنواين ب " />
					</div>
					<!-- End Tags Field -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
							<input type="submit" value="اضافة المنشور" class="btn btn-primary btn-lg" />
					</div>
					<!-- End Submit Field -->
				</form>
				</div>
			<?php
			// If There's No Such ID Show Error Message
			} else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">لا يوجد رقم تعريفي</div>';
				redirectHome($theMsg);
				echo "</div>";
			}
		}  elseif ($do == 'Update') {
			echo "<div class='add container'>";
            echo "<h1 class='text-center'>تحديث المنشور</h1>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Get Variables From The Form
                $id 		  = $_POST['postid'];
				$name 	      = $_POST['name'];
				$description  = $_POST['description'];
				$member 	  = $_POST['member'];
				$tags 	      = $_POST['tags'];
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
				// Validate The Form
				$formErrors = array();
				if (strlen($name) < 4) {
					$formErrors[] = 'الاسم المنشور يجب ان يكون اقل من 4 احرف';
				}
				if (empty($name)) {
					$formErrors[] = 'الاسم لا يجب ان يكون فارغ';
				}
				if (empty($description)) {
					$formErrors[] = 'الوصف لا يجب ان يكون فارغ';
				}
				if (empty($member)) {
					$formErrors[] = 'اسم الشخص لا يجب ان يكون فارغ';
				}
				if (! empty($imgName) && ! in_array($imgExtension, $imgAllowedExtension)) {
					$formErrors[] = 'امتداد الصورة هذا غير متوفر';
				}
				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}
				if (empty($formErrors)) {
                    $img = rand(0, 10000000) . '_' . $imgName;
					move_uploaded_file($imgTmp, "uploads\avatars\\" . $img);
					$stmt = $con->prepare("UPDATE 
												post 
											SET  
												post_name = ?, 
												post_description = ?, 
												post_image = ?, 
												users = ?,
												tags = ?
											WHERE 
												post_id = ?");
					$stmt->execute(array($name, $description, $img, $member, $tags, $id));
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
            echo "<h1 class='text-center'>حزف المنشور</h1>";
            $postid = isset($_GET['postid']) && is_numeric($_GET['postid']) ? intval($_GET['postid']) : 0;
            $check = checkItem('post_id', 'post', $postid);
            if ($check > 0) {
                $stmt = $con->prepare("DELETE FROM post WHERE post_id = :zid");
                $stmt->bindParam(":zid", $postid);
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
            echo "<h1 class='text-center'>منشور مميز</h1>";
            $postid = isset($_GET['postid']) && is_numeric($_GET['postid']) ? intval($_GET['postid']) : 0;
            $check = checkItem('post_id', 'post', $postid);
            if ($check > 0) {
                $stmt = $con->prepare("UPDATE post SET allow_comment = 1 WHERE post_id = ?");
                $stmt->execute(array($postid));
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