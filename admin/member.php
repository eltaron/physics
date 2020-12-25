<?php
	ob_start(); // Output Buffering Start
	session_start();
	$pageTitle = 'Members';
	if (isset($_SESSION['username'])) {
		include 'inital.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        // Start Manage Page
		if ($do == 'Manage') { 
			$query = '';
			if (isset($_GET['page']) && $_GET['page'] == 'vip') {
				$query = 'AND regstatus = 0';
			}
			$stmt = $con->prepare("SELECT * FROM members WHERE groupid != 1 $query ORDER BY userid DESC");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			if (! empty($rows)) {
			?>
            <div class="manage">
			<h1 class="text-center">صفحة الاعضاء</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members text-center table table-bordered">
						<tr>
                            <td>لوحة التحكم</td>
                            <td>تاريخ التسجيل</td>
                            <td>الاسم بالكامل</td>
                            <td>الايميل</td>
                            <td>اسم المستخدم</td>
                            <td>الصورة</td>
							<td>الرقم التعريفي</td>
						</tr>
						<?php
							foreach($rows as $row) {
								echo "<tr>";
                                    echo "<td>
										<a href='member.php?do=Edit&userid=" . $row['userid'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='member.php?do=Delete&userid=" . $row['userid'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
										if ($row['regstatus'] == 0) {
											echo "<a 
													href='member.php?do=Activate&userid=" . $row['userid'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Activate</a>";
										}
									echo "</td>";
                                    echo "<td>" . $row['date'] ."</td>";
                                    echo "<td>" . $row['fullname'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['username'] . "</td>";
									echo "<td>";
									if (empty($row['avatar'])) {
										echo 'No Image';
									} else {
										echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
									}
									echo "</td>";
									echo "<td>" . $row['userid'] . "</td>";		
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<a href="member.php?do=Add" class="btn btn-primary">
					<i class="fa fa-plus"></i> اضافة عضو
				</a>
			</div>
            </div>    
			<?php } else {
				echo '<div class="container">';
					echo '<div class="message">لا يوجد اعضاء</div>';
					echo '<a href="member.php?do=Add" class="btn btn-primary">
							<i class="fa fa-plus"></i> اضافة عضو
						</a>';
				echo '</div>';}
            // end Manage Page
            // start Add Page    
            } elseif ($do == 'Add') { ?>
			
			<div class="add container text-right">
                <h1 class="text-center">اضافة عضو جديد</h1>
				<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
					<!-- Start Username Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الاسم</label>
							<input type="text" name="username" class="form-control" autocomplete="off"  placeholder=" اسم المستخدم " required/>
					</div>
					<!-- End Username Field -->
					<!-- Start Password Field -->
					<div class="form-group form-group-lg">
						<label class="control-label"><i class="show-pass fa fa-eye fa-lg"></i> الرقم السرى</label>
							<input type="password" name="password" class="password form-control"  autocomplete="new-password" placeholder="الرقم السرى يجب ان يكون صعب توقعة" required />
							
					</div>
					<!-- End Password Field -->
					<!-- Start Email Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">البريد الالكترونى</label>
							<input type="email" name="email" class="form-control"  placeholder="البريد الالكترونى يجب ان يكون صحيح" required />
					</div>
					<!-- End Email Field -->
					<!-- Start Full Name Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الاسم الكامل</label>
							<input type="text" name="full" class="form-control"  placeholder="الاسم بالكامل يظهر امام الجميع" required />
					</div>
					<!-- End Full Name Field -->
					<!-- Start Avatar Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الصورة الشخصية</label>
							<input type="file" name="img" class="form-control pull-right" />
					</div>
					<!-- End Avatar Field -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
							<input type="submit" value="اضافة العضو" class="btn btn-primary btn-lg" />
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
				// Upload Variables
				$avatarName = $_FILES['img']['name'];
				$avatarSize = $_FILES['img']['size'];
				$avatarTmp	= $_FILES['img']['tmp_name'];
				$avatarType = $_FILES['img']['type'];
				// List Of Allowed File Typed To Upload
				$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
				// Get Avatar Extension
                $avatars = explode('.', $avatarName);
				$avatarExtension = strtolower(end($avatars));
				// Get Variables From The Form
				$user 	= $_POST['username'];
				$pass 	= $_POST['password'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];
				$hashPass = sha1($_POST['password']);
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
				if (empty($pass)) {
					$formErrors[] = 'الرقم السرى لا يجب ان يكون فارغ';
				}
				if (empty($name)) {
					$formErrors[] = 'الاسم الكامل لا يجب ان يكون فارغ';
				}
				if (empty($email)) {
					$formErrors[] = 'البريد الالكترونى لا يجب ان يكون فارغ';
				}
				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}
				if (empty($formErrors)) {
					$avatar = rand(0, 10000000) . '_' . $avatarName;
					move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);
					$check = checkItem("username", "members", $user);
					if ($check == 1) {
						$theMsg = '<div class="alert alert-danger">هذا المستخدم موجود</div>';
						redirectHome($theMsg, 'back');
					} else {
						// Insert Userinfo In Database
						$stmt = $con->prepare("INSERT INTO 
													members(username, password, email, fullname, regstatus, date, 	avatar)
												VALUES(:zuser, :zpass, :zmail, :zname, 0, now(), :zavatar) ");
						$stmt->execute(array(
							'zuser' 	=> $user,
							'zpass' 	=> $hashPass,
							'zmail' 	=> $email,
							'zname' 	=> $name,
							'zavatar'	=> $avatar
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
		}  elseif ($do == 'Edit') {
			// Check If Get Request userid Is Numeric & Get Its Integer Value
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
			// Select All Data Depend On This ID
			$stmt = $con->prepare("SELECT * FROM members WHERE userid = ? LIMIT 1");
			$stmt->execute(array($userid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
			if ($count > 0) { ?>
				<h1 class="text-center">تعديل العضو</h1>
				<div class="container text-right">
					<form class="form-horizontal" action="?do=Update" method="POST">
                        <?php if (empty($row['avatar'])) {
										echo '<h4 class="text-center">No Image</h4>';
									} else {
										echo "<img class='img img-thumbnail' src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
									}?>
						<input type="hidden" name="userid" value="<?php echo $userid ?>" />
						<!-- Start Username Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">اسم المستخدم</label>
								<input type="text" name="username" class="form-control" value="<?php echo $row['username'] ?>" autocomplete="off" required="required" />
						</div>
						<!-- End Username Field -->
						<!-- Start Password Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">الرقم السرى</label>
								<input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?>" />
								<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="اتركة فارغا اذا لم ترغب في تعديل الرقم السرى" />
						</div>
						<!-- End Password Field -->
						<!-- Start Email Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">البريد الالكترونى</label>
								<input type="email" name="email" value="<?php echo $row['email'] ?>" class="form-control" required="required" />
						</div>
						<!-- End Email Field -->
						<!-- Start Full Name Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">الاسم كاملا</label>
								<input type="text" name="full" value="<?php echo $row['fullname'] ?>" class="form-control" required="required" />
						</div>
						<!-- End Full Name Field -->
						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
								<input type="submit" value="احفظ" class="btn btn-primary btn-lg" />
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
		} elseif ($do == 'Update') { // Update Page
			echo "<h1 class='text-center'>تحديث العضو</h1>";
			echo "<div class='container'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Get Variables From The Form
				$id 	= $_POST['userid'];
				$user 	= $_POST['username'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];
				// Password Trick
				$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
				// Validate The Form
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
				// Loop Into Errors Array And Echo It
				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}
				// Check If There's No Error Proceed The Update Operation
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
						$theMsg = '<div class="alert alert-danger">هذا المستخدم موجود بالفعل</div>';
						redirectHome($theMsg, 'back');
					} else { 
						// Update The Database With This Info
						$stmt = $con->prepare("UPDATE members SET username = ?, email = ?, fullname = ?, password = ? WHERE userid = ?");
						$stmt->execute(array($user, $email, $name, $pass, $id ));
						// Echo Success Message
						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
						redirectHome($theMsg, 'back');
					}
				}
			} else {
				$theMsg = '<div class="alert alert-danger">لا يمكنك تصفح هذة الصفحة مباشرة</div>';
				redirectHome($theMsg);
			}
			echo "</div>";
		} elseif ($do == 'Delete') { // Delete Member Page
			echo "<h1 class='text-center'>حزف عضو</h1>";
			echo "<div class='container'>";
				// Check If Get Request userid Is Numeric & Get The Integer Value Of It
				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
				// Select All Data Depend On This ID
				$check = checkItem('userid', 'members', $userid);
				// If There's Such ID Show The Form
				if ($check > 0) {
					$stmt = $con->prepare("DELETE FROM members WHERE userid = :zuser");
					$stmt->bindParam(":zuser", $userid);
					$stmt->execute();
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
					redirectHome($theMsg, 'back');
				} else {
					$theMsg = '<div class="alert alert-danger">العنوان التعريفي غير موجود</div>';
					redirectHome($theMsg);
				}
			echo '</div>';
		} elseif ($do == 'Activate') {
			echo "<h1 class='text-center'>عضو مميز</h1>";
			echo "<div class='container'>";
				// Check If Get Request userid Is Numeric & Get The Integer Value Of It
				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
				// Select All Data Depend On This ID
				$check = checkItem('userid', 'members', $userid);
				// If There's Such ID Show The Form
				if ($check > 0) {
					$stmt = $con->prepare("UPDATE members SET regstatus = 1 WHERE userid = ?");
					$stmt->execute(array($userid));
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
					redirectHome($theMsg);
				} else {
					$theMsg = '<div class="alert alert-danger">العنوان التعريفي غير موجود</div>';
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