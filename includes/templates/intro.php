<div class="col-md-4 order-md-2 text-center right">
	<img src="layouts/images/logo8_17_23455.png" alt="" class="firstimg">
	<h3>اهلا</h3>
	<p><?php echo $_SESSION['user'] ;?></p>
	<?php 
		$getUser = $con->prepare("SELECT avatar FROM members WHERE username = ?");
		$getUser->execute(array($_SESSION['user']));
		$info = $getUser->fetch();
		$avatar = $info['avatar'];
	?>
	<img src="admin/uploads/avatars/<?php echo $avatar; ?>" alt="" class="secondimg">
	<div class="mainBox">
		<a href="index.php"><div class="box <?php if ($pageTitle == 'Profile' ) { echo 'active'; }?>">الصفحه الرئيسية<i class="fa fa-home fa-2x"></i></div></a>
		<a href="categories.php"><div class="box <?php if ($pageTitle == 'categories' ) { echo 'active'; }?>">الاقسام<i class="fa fa-book fa-2x"></i></div></a>
		<a href="post.php"><div class="box <?php if ($pageTitle == 'post' ) { echo 'active'; }?>">المنشورات<i class="fa fa-edit fa-2x"></i></div></a>
		<a href="settings.php?do=Edit"><div class="box <?php if ($pageTitle == 'settings' ) { echo 'active'; }?>">الاعدادات<i class="fa fa-cogs fa-2x"></i></div></a>
		<a href="logout.php"><div class="box <?php if ($pageTitle == 'logout' ) { echo 'active'; }?>">تسجيل الخروج<i class="fa fa-sign-out fa-2x"></i></div></a>
	</div>
</div>