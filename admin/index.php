<?php
	session_start();
	$noNavbar = '';
	$pageTitle = 'Login';
	if (isset($_SESSION['username'])) {
		header('Location: dashboard.php'); // Redirect To Dashboard Page
	}
	include 'inital.php';
	// Check If User Coming From HTTP Post Request
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashedPass = sha1($password);
		// Check If The User Exist In Database
		$stmt = $con->prepare("SELECT 
									userid, username, password 
								FROM 
									members 
								WHERE 
									username = ? 
								AND 
									password = ? 
								AND 
								    regstatus = 1
								LIMIT 1");
		$stmt->execute(array($username, $hashedPass));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();
		// If Count > 0 This Mean The Database Contain Record About This Username
		if ($count > 0) {
			$_SESSION['username'] = $username; // Register Session Name
			$_SESSION['ID'] = $row['userid']; // Register Session ID
			header('Location: dashboard.php'); // Redirect To Dashboard Page
			exit();
		}
	}
?>
    <div class="login-page">
        <form class="sign container" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="login text-center">
                <div class="form sign-in">
                    <i class="i fa fa-user-circle fa-4x"></i>
                    <h3 class="blue">تسجيل الدخول</h3>
                    <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
                    <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
                    <input class="btn btn-primary btn-block" type="submit" value="Login" />
                </div>
           </div>
        </form>
    </div>    
<?php include $tpl . 'footer.php'; ?>