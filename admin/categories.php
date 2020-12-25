<?php
ob_start(); // Output Buffering Start
session_start();
$pageTitle = 'Categories';
if (isset($_SESSION['username'])) {
    include 'inital.php';
	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {
			$sort = 'asc';
			$sort_array = array('asc', 'desc');
			if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
				$sort = $_GET['sort'];
			}
			$stmt2 = $con->prepare("SELECT * FROM category WHERE parent = 0 ORDER BY ordering $sort");
			$stmt2->execute();
			$cats = $stmt2->fetchAll(); 
			if (! empty($cats)) {
			?>
			<div class="container categories">
                <h1 class="text-center">صفحة الاقسام</h1>
				<div class="card">
					<div class="card-heading">
						<i class="fa fa-edit pull-right">الاقسام</i> 
						<div class="option ">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <i class="fa fa-sort"></i> الترتيب: [
                                    <a class="<?php if ($sort == 'asc') { echo 'active'; } ?>" href="?sort=asc">تصاعدى</a> | 
                                    <a class="<?php if ($sort == 'desc') { echo 'active'; } ?>" href="?sort=desc">تنازلى</a> ]
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <i class="fa fa-eye"></i> العرض: [
                                    <span class="active" data-view="full">بشكل كامل </span> |
                                    <span data-view="classic">كلاسيكي</span> ]
                                </div>    
                            </div>    
						</div>
					</div>
					<div class="card-body">
						<?php
							foreach($cats as $cat) {
								echo "<div class='cat'>";
									echo "<div class='hidden-buttons'>";
										echo "<a href='categories.php?do=Edit&catid=" . $cat['category_id'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> تعديل</a>";
										echo "<a href='categories.php?do=Delete&catid=" . $cat['category_id'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> حذف</a>";
									echo "</div>";
									echo "<h3 class='text-right'>" . $cat['category_name'] . '</h3>';
									echo "<div class='full-view pull-right'>";
										echo "<p>"; if($cat['category_description'] == '') { echo 'هذا القسم ليس له وصف'; } else { echo $cat['category_description']; } echo "</p>";
										if($cat['Visibility'] == 1) { echo '<span class="visibility cat-span"><i class="fa fa-eye"></i> غير ظاهر</span>'; } 
										if($cat['Allow_Comment'] == 1) { echo '<span class="commenting cat-span"><i class="fa fa-close"></i> التعليقات غير مفعلة</span>'; }
										if($cat['Allow_Ads'] == 1) { echo '<span class="advertises cat-span"><i class="fa fa-close"></i>الاعلانات غير مفعلة</span>'; }  
									echo "</div>";
									// Get Child Categories
							      	$childCats = getAllFrom("*", "category", "where parent = {$cat['category_id']}", "", "category_id", "ASC");
							      	if (! empty($childCats)) {
								      	echo "<h4 class='child-head '>قسم فرعى</h4>";
								      	echo "<ul class='list-unstyled child-cats'>";
										foreach ($childCats as $c) {
											echo "<li class='child-link'>
												<a href='categories.php?do=Edit&catid=" . $c['category_id'] . "'>" . $c['category_name'] . "</a>
												<a href='categories.php?do=Delete&catid=" . $c['category_id'] . "' class='show-delete confirm'> Delete</a>
											</li>";
										}
										echo "</ul>";
									}
								echo "</div>";
								echo "<hr>";
							}
						?>
					</div>
				</div>
				<a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> اضافة قسم جديد</a>
			</div>
			<?php } else {
				echo '<div class="container">';
					echo '<div class="message">لا يوجد قسم لعرضة</div>';
					echo '<a href="categories.php?do=Add" class="btn btn-primary">
							<i class="fa fa-plus"></i> قسم جديد
						</a>';
				echo '</div>';

			} ?>
			<?php
		} elseif ($do == 'Add') { ?>
			<div class="container add text-right">
                <h1 class="text-center">اضافة قسم جديد</h1>
				<form class="form-horizontal" action="?do=Insert" method="POST">
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">اسم القسم</label>
							<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="اسم القسم" />
					</div>
					<!-- End Name Field -->
					<!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الوصف</label>
							<input type="text" name="description" class="form-control" placeholder="وصف القسم" />
					</div>
					<!-- End Description Field -->
					<!-- Start Ordering Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الترتيب</label>
							<input type="text" name="ordering" class="form-control" placeholder=" ترتيب القسم" />
					</div>
					<!-- End Ordering Field -->
					<!-- Start Category Type -->
					<div class="form-group form-group-lg">
						<label class="control-label">القسم الرئيسي؟</label>
							<select name="parent">
								<option value="0">فارغ</option>
								<?php 
									$allCats = getAllFrom("*", "category", "where parent = 0", "", "ordering", "ASC");
									foreach($allCats as $cat) {
										echo "<option value='" . $cat['category_id'] . "'>" . $cat['category_name'] . "</option>";
									}
								?>
							</select>
					</div>
					<!-- End Category Type -->
					<!-- Start Visibility Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">الظهور</label>
                            <div class="im">
								<input id="vis-no" type="radio" name="visibility" value="1" />
								<label for="vis-no">لا</label> 
							</div>
							<div class="im">
								<input id="vis-yes" type="radio" name="visibility" value="0" checked />
								<label for="vis-yes">نعم</label> 
							</div>
					</div>
					<!-- End Visibility Field -->
					<!-- Start Commenting Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">تمكين التعليقات</label>
                            <div class="im">
								<input id="com-no" type="radio" name="commenting" value="1" />
								<label for="com-no">لا</label> 
							</div>
							<div class="im">
								<input id="com-yes" type="radio" name="commenting" value="0" checked />
								<label for="com-yes">نعم</label> 
							</div>
					</div>
					<!-- End Commenting Field -->
					<!-- Start Ads Field -->
					<div class="form-group form-group-lg">
						<label class="control-label">تمكين الاعلانات</label>
                            <div class="im">
								<input id="ads-no" type="radio" name="ads" value="1" />
								<label for="ads-no">لا</label> 
							</div>
							<div class="im">
								<input id="ads-yes" type="radio" name="ads" value="0" checked />
								<label for="ads-yes">نعم</label> 
							</div>
					</div>
					<!-- End Ads Field -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2">
							<input type="submit" value="اضافة قسم" class="btn btn-primary btn-lg" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div>
			<?php
		} elseif ($do == 'Insert') {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<h1 class='text-center'>ادراج قسم</h1>";
				echo "<div class='container'>";
				// Get Variables From The Form
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$parent 	= $_POST['parent'];
				$order 		= $_POST['ordering'];
				$visible 	= $_POST['visibility'];
				$comment 	= $_POST['commenting'];
				$ads 		= $_POST['ads'];
					// Insert Category Info In Database
					$stmt = $con->prepare("INSERT INTO 
						category(category_name, category_description, parent, ordering, Visibility, Allow_Comment, Allow_Ads)
					VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :zads)");
					$stmt->execute(array(
						'zname' 	=> $name,
						'zdesc' 	=> $desc,
						'zparent' 	=> $parent,
						'zorder' 	=> $order,
						'zvisible' 	=> $visible,
						'zcomment' 	=> $comment,
						'zads'		=> $ads
					));
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
					redirectHome($theMsg, 'back');
				
			} else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">لا يمكنك تصفح تلك الصفحة مباشرة</div>';
				redirectHome($theMsg, 'back');
				echo "</div>";
			}
			echo "</div>";
		} elseif ($do == 'Edit') {
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
			$stmt = $con->prepare("SELECT * FROM category WHERE category_id = ?");
			$stmt->execute(array($catid));
			$cat = $stmt->fetch();
			$count = $stmt->rowCount();
			if ($count > 0) { ?>
				<div class="container add text-right">
                    <h1 class="text-center">تعديل القسم</h1>
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="catid" value="<?php echo $catid ?>" />
						<!-- Start Name Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">اسم القسم</label>
								<input type="text" name="name" class="form-control" required="required" placeholder="اسم القسم" value="<?php echo $cat['category_name'] ?>" />
						</div>
						<!-- End Name Field -->
						<!-- Start Description Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">الوصف</label>
								<input type="text" name="description" class="form-control" placeholder="وصف القسم" value="<?php echo $cat['category_description'] ?>" />
						</div>
						<!-- End Description Field -->
						<!-- Start Ordering Field -->
						<div class="form-group form-group-lg">
							<label class="control-label">الترتيب</label>
								<input type="text" name="ordering" class="form-control" placeholder="رقم ترتيب القسم" value="<?php echo $cat['ordering'] ?>" />
						</div>
						<!-- End Ordering Field -->
						<!-- Start Category Type -->
						<div class="form-group form-group-lg">
							<label class="control-label">القسم الرئيسي؟</label>
								<select name="parent">
									<option value="0">فارغ</option>
									<?php 
									$allCats = getAllFrom("*", "category", "where parent = 0", "", "category_id", "ASC");
										foreach($allCats as $c) {
											echo "<option value='" . $c['category_id'] . "'";
											if ($cat['parent'] == $c['category_id']) { echo ' selected'; }
											echo ">" . $c['category_name'] . "</option>";
										}
									?>
								</select>
						</div>
						<!-- End Category Type -->
                        <!-- Start Visibility Field -->
                        <div class="form-group form-group-lg">
                            <label class="control-label">الظهور</label>
                                <div class="im">
                                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) { echo 'checked'; } ?>/>
                                    <label for="vis-no">لا</label> 
                                </div>
                                <div class="im">
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) { echo 'checked'; } ?> />
                                    <label for="vis-yes">نعم</label> 
                                </div>
                        </div>
                        <!-- End Visibility Field -->
                        <!-- Start Commenting Field -->
                        <div class="form-group form-group-lg">
                            <label class="control-label">تمكين التعليقات</label>
                                <div class="im">
                                    <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Visibility'] == 1) { echo 'checked'; } ?>/>
                                    <label for="com-no">لا</label> 
                                </div>
                                <div class="im">
                                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Visibility'] == 0) { echo 'checked'; } ?> />
                                    <label for="com-yes">نعم</label> 
                                </div>
                        </div>
                        <!-- End Commenting Field -->
                        <!-- Start Ads Field -->
                        <div class="form-group form-group-lg">
                            <label class="control-label">تمكين الاعلانات</label>
                                <div class="im">
                                    <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Visibility'] == 1) { echo 'checked'; } ?>/>
                                    <label for="ads-no">لا</label> 
                                </div>
                                <div class="im">
                                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Visibility'] == 0) { echo 'checked'; } ?> />
                                    <label for="ads-yes">نعم</label> 
                                </div>
                        </div>
                        <!-- End Ads Field -->
						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
								<input type="submit" value="حفظ" class="btn btn-primary btn-lg" />
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
			echo "<h1 class='text-center'>تحديث القسم</h1>";
			echo "<div class='container add'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Get Variables From The Form
				$id 		= $_POST['catid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$order 		= $_POST['ordering'];
				$parent 	= $_POST['parent'];
				$visible 	= $_POST['visibility'];
				$comment 	= $_POST['commenting'];
				$ads 		= $_POST['ads'];
				// Update The Database With This Info
				$stmt = $con->prepare("UPDATE 
											category 
										SET 
											category_name = ?, 
											category_description = ?, 
											Ordering = ?, 
											parent = ?,
											Visibility = ?,
											Allow_Comment = ?,
											Allow_Ads = ? 
										WHERE 
								           category_id = ?");
				$stmt->execute(array($name, $desc, $order, $parent, $visible, $comment, $ads, $id));
				// Echo Success Message
				$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
				redirectHome($theMsg, 'back');
			} else {
				$theMsg = '<div class="alert alert-danger">لا يمكنك تصفح تلك الصفحة مباشرة</div>';
				redirectHome($theMsg);
			}
			echo "</div>";
		} elseif ($do == 'Delete') {
			echo "<div class='add container'>";
            echo "<h1 class='text-center'>حزف القسم</h1>";
			// Check If Get Request Catid Is Numeric & Get The Integer Value Of It
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
            $check = checkItem('category_id', 'category', $catid);
			if ($check > 0) {
					$stmt = $con->prepare("DELETE FROM category WHERE category_id = :zid");
					$stmt->bindParam(":zid", $catid);
					$stmt->execute();
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
					redirectHome($theMsg, 'back');
				} else {
					$theMsg = '<div class="alert alert-danger">لا يوجد رقم تعريفي</div>';
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