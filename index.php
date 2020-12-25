<?php
    ob_start();
    session_start();
    $noNavbar = '';
    $pageTitle = 'Homepage';
    if (isset($_SESSION['user'])) {
		header('Location: profile.php');
	}
    include 'inital.php';
    // Check If User Coming From HTTP Post Request
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['login-up'])) {
			$user = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
			$pass = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
			$hashedPass = sha1($pass);
			// Check If The User Exist In Database
			$stmt = $con->prepare("SELECT 
								      userid, username, password, only 
									FROM 
										 members 
									WHERE 
										username = ? 
									AND 
										password = ?");
			$stmt->execute(array($user, $hashedPass));
			$get = $stmt->fetch();
			$count = $stmt->rowCount();
            // If Count > 0 This Mean The Database Contain Record About This Username
            if ($count > 0) {

                $token = getToken(10);
                $_SESSION['user'] = $user; // Register Session Name
                $_SESSION['uid'] = $get['userid']; // Register User ID in Session
                $_SESSION['token'] = $token;
                // Update user token 
                $result_token = $con->prepare("select count(*) as allcount from user_token where username = ? ");
                $result_token->execute(array($user));
                $row_token = $result_token->rowCount();
                if ($row_token > 0) {
                  $mod = $con->prepare("update user_token set token = ? where username = ?");
                 $mod->execute(array($token, $user)); 
                } else {
                   $ins = $con->prepare("insert into user_token(username,token) VALUES(:zuser, :ztoken)");
                    $ins->execute(array(
                        'zuser' 	=> $user,
                        'ztoken'		=> $token
                    ));
                }
                header('Location: profile.php'); // Redirect To Dashboard Page
                exit();
            } else {
                $formErrors = array();
                $formErrors[] = 'خطا بالتسجيل';
            }
		} else {
			$formErrors = array();
            $token = getToken(10);
			$username 	= filter_var($_POST['username'],FILTER_SANITIZE_STRING);
			$password 	= filter_var($_POST['password'],FILTER_SANITIZE_STRING);
            $password2 	= filter_var($_POST['password2'],FILTER_SANITIZE_STRING);
            $phone      = filter_var($_POST['phone'],FILTER_SANITIZE_NUMBER_INT);
            $class      = $_POST['parent'];
			if (isset($username)) {
				if (strlen($username) < 4) {$formErrors[] = 'اسم المستخدم يجب ان يكون اكبر من 4 احرف';}
			}
            if (isset($password)) {
				if (strlen($password) < 4) {$formErrors[] = 'كلمة المرور يجب ان تكون اكبر من 4 ارقام';}
			}
			if (isset($password) && isset($password2)) {
				if (empty($password)) {$formErrors[] = ' كلمة المرور لا يجب ان تكون فارغة ويجب ان تكون ارقام';}
				if (sha1($password) !== sha1($password2)) {$formErrors[] = 'كلمة المرور غير متطابقة';}
			}
            if (isset($phone)) {
				if (strlen($phone) < 8) {$formErrors[] = 'رقم الهاتف يجب ان يكون اكبر من 8 ارقام';}
			}
			// Check If There's No Error Proceed The User Add
			if (empty($formErrors)) {
				// Check If User Exist in Database
				$check = checkItem("username", "members", $username);
				if ($check == 1) {
					$formErrors[] = 'هذا المستخدم موجود عليك استخدام اسم مستخدم اخر';
				} else {
					// Insert Userinfo In Database
					$stmt = $con->prepare("INSERT INTO 
								members(username, password, phone, groupid, regstatus, date)
										VALUES(:zuser, :zpass, :zphone, :zclass, 0, now())");
					$stmt->execute(array(
						'zuser' => $username,
						'zpass' => sha1($password),
						'zphone' => $phone,
						'zclass' => $class
					));
                    // Update user token 
                   $ins = $con->prepare("insert into user_token(username,token) VALUES(:zuser, :ztoken)");
                    $ins->execute(array(
                        'zuser' 	=> $username,
                        'ztoken'		=> $token
                    ));
					// Echo Success Message
					$succesMsg = 'لقد تم تسجيل البيانات قم بتسجيل الدخول';
				}
			}
		}
	}


// Generate token
function getToken($length){
     $token = "";
     $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
     $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
     $codeAlphabet.= "0123456789";
     $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[rand(0, $max-1)];
    }

    return $token;
}

?>
         <section class="header">
                <div class="row">
                    <div class="col-md-12 col-lg-7">
                        <img src="layouts/images/header.gif" alt="">
                    </div>
                    <div class="col-md-12 col-lg-5 text-center">
                        <div class="firstrow text-right">
                            <h2>الاستاذ محسن شعبان</h2>
                            <div class="social">
                                <a href="#"><i class="fa fa-facebook fa-2x"></i></a>
                                <a href="#"><i class="fa fa-whatsapp fa-2x"></i></a>
                                <a href="#"><i class="fa fa-youtube fa-2x"></i></a>
                            </div>
                        </div>
                        <div class="second">
                            <div class="mainhead">
                                <h1>أنضم الينا</h1>
                                <p>وكن من  المتفوقين في مادة <strong>الفيزياء</strong></p>
                                <p>للثانوية العامة والثانوية الازهرية</p>
                                <div class="row buttons">
                                    <div class="col-md-6">
                                        <div class="button-big">
                                            <input type="checkbox" id="checked" class="click">
                                            <label for="checked">
                                                <div class="button-small">اشترك الان</div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="button-big">
                                            <input type="checkbox" id="checked_2" class="click">
                                            <label for="checked_2">
                                                <div class="button-small">تسجيل الدخول</div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <span class="arrow"><i class="fa fa-chevron-down" ></i></span>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <section class="features">
            <div class="container text-center">
                <h2>اهم المميزات</h2>
                <div class="row">
                    <div class="col-md-6 col-lg-3 bottom">
                        <i class="fa fa-line-chart fa-4x rounded-circle"></i>
                        <h3>أحصل علي مهارات جديدة</h3>
                        <p>أحصل على مهارات جديده من خلال التعلم بشكل صحيح ومحاوله التطبيق الجيد على المعلومات الجديدة</p>    
                    </div>
                    
                    <div class="col-md-6 col-lg-3">
                        <i class="fa fa-comments-o fa-4x rounded-circle"></i>
                        <h3>تواصل مع معلمك</h3>
                        <p>سوف يتم تحديد اوقات في الاسبوع للرد على الاسئلة والاستفسارات من خلال تطبيقات الفصول الأفتراضية </p>    
                    </div>
                    
                    <div class="col-md-6 col-lg-3">
                        <i class="fa fa-group fa-4x rounded-circle"></i>
                        <h3>الأطلاع علي أهم الأخبار </h3>
                        <p>لا تنسى الدخول الى قسم المنشورات والاطلاع على اهم المنشورات والاخبار الدراسية وأجددها</p>    
                    </div>
                    
                    <div class="col-md-6 col-lg-3 bottom">
                        <i class="fa fa-pencil-square-o fa-4x rounded-circle"></i>
                        <h3>تدرب علي جميع الدروس </h3>
                        <p>  سوف تجد بعد كل درس جزء تطبيقي عليه وسيتم تسجيل النتيجه في ملفك الشخصى للاطلااع عليها لاحقا </p>    
                    </div>
                </div>
            </div>
            <hr>
        </section>
        <section class="price-table text-center">
            <div class="container">
                <h2>تكلفة اشتراك المنصة للمرحلة الثانوية</h2>
                <div class="row">            
                    <div class="col-md-4 order-md-3">
                    <div class="price-box">
                        <h3>الصف الاول   الثانوى</h3>
                        <p class="center-block">50 جنية </p>
                        <ul class="list-unstyled right-bordrer">
                            <li>فيديوهات الشرح</li>
                            <li>ورق الشرح</li>
                            <li>امتحانات المتابعة</li>
                            <li>zoom حصص تطبيق</li>
                        </ul>
                        <a href="#" class=" btn btn-info">الاشتراك الان</a>
                    </div>
                    </div>              
                    <div class="col-md-4 order-md-2">
                    <div class="price-box">
                        <h3>الصف الثانى الثانوى</h3>
                        <p class="center-block">70 جنية </p>
                        <ul class="list-unstyled right-bordrer">
                            <li>فيديوهات الشرح</li>
                            <li>ورق الشرح</li>
                            <li>امتحانات المتابعة</li>
                            <li>zoom حصص تطبيق</li>
                        </ul>
                        <a href="#" class=" btn btn-info">الاشتراك الان</a>
                    </div>
                    </div>    
                    <div class="col-md-4 order-md-1">
                    <div class="price-box">
                        <h3>الصف الثالث الثانوى</h3>
                        <p class="center-block">80 جنية </p>
                        <ul class="list-unstyled right-bordrer">
                            <li>فيديوهات الشرح</li>
                            <li>ورق الشرح</li>
                            <li>امتحانات المتابعة</li>
                            <li>zoom حصص تطبيق</li>
                        </ul>
                        <a href="#" class=" btn btn-info">الاشتراك الان</a>
                    </div>
                    </div>
                </div>
            </div>
            <hr>
        </section>
        <section class="information">
            <div class="alert alert-info alert-dismissible fade show text-right" role="alert">
                <button type="button" class="close pull-left" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3>تعليمات تسجيل الدخول</h3>
                <ul class="list-unstyled">
                    <li>عند <strong>انشاء حساب </strong> يجب عليك مراجعة البيانات المرسلة جيدا لانها سيترتب عليها ظهور صفحات خاص بك</li>
                    <li>يجب عليك بعد انشاء حساب <strong>تسجيل الدخول</strong></li>
                    <li>لا يمكن الدخول من اكثر من متصفح في نفس الوقت</li>
                </ul>
                <h3>تعليمات التصفح داخل المنصة</h3>
                <ul class="list-unstyled">
                    <li>عند الدخول للقسم الخاص بك يوجد قسم المحاضرات وتنقسم الي قسمين</li>
                    <li>
                        <ol class="list-unstyled">
                            <li>دروس شرح غير مرتبطة بامتحان تسطيع تصفحها مباشرة --- </li>
                            <li>دروس شرح مرتبطة بامتحان يجب عليك اداء الامتحان اولا لتسطيع تصفحها --- </li>
                        </ol>
                    </li>
                    <li>عند اضاة اى تعليق داخل المنصة سيتم مراجعتة قبل نشرة</li>
                    <li>عند وجود اى اعلانات من ادارة المنصة سيتم ادراجها بقسم المنشورات لاذلك احرص على متابعته دائما</li>
                </ul>
                <p>المنصة قيد التطوير لذلك عند وجود اى مشاكل او استفسار سواء فى المنصة بشكل عام او التسجيل بشكل خاص يرجى التواصل معنا على الرقم 01066343874</p>
            </div>
        </section>
        <section class="login-page">
            <div class="contact">
                <div class="start">
                    <i class="i fa fa-user-circle fa-4x"></i>
                    <p>قم بتسجيل الدخول الان او انشاء حساب لاطلاع على اهم الدروس والمنشورات</p>
                </div>    
                    <!-- Start Login Form -->
        <div class="login-form">
        <form class="login-up" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="login text-center">    
            <div class="row log">
                <div class="form sign-in">
                    <div class="input-container input">
                        <label class="label"><i class="fa fa-user"></i> اسم المستخدم </label> 
                        <input 
                            class="username2 form-control text-center contain" 
                            type="text" 
                            name="username" 
                            autocomplete="off"
                            placeholder="يجب ان يكون مميز "
                               required/>
                        <div class="alert text-center alert-danger custom_alert">
					               يجب اضافة اسم ويزيد عن 4 حروف              
                        </div>
                     </div>    
                     <div class="input-container input">
                      <label class="label"><i class="fa fa-key"></i> كلمة المرور </label> 
                        <i class="show-pass fa fa-eye fa-lg"></i> 
                      <input 
                            class="password3 showpass form-control text-center contain" 
                            type="password" 
                            name="password" 
                            autocomplete="password"
                            placeholder=" مزيج من ارقام وحروف "
                             required/>
                        
                     <div class="alert text-center alert-danger custom_alert">
					               يجب اضافة كلمة مرور وتزيد عن 4 حروف              
                        </div>     
                    </div>    
                  <input type="submit" class="button" name="login-up" value="تسجيل الدخول">
                  <div class="foot-lnk">
                    <a for="tab-1">ليس لديك حساب؟</a>
                  </div>    
                  <div class="social-media">
                    <ul class=" text-center" >
                      <li><a href="https://www.facebook.com/%D8%B1%D9%8A%D8%A7%D8%B6%D9%8A%D8%A7%D8%AA-%D8%A8%D8%B4%D9%83%D9%84-%D8%AC%D8%AF%D9%8A%D8%AF-%D9%85%D8%AD%D9%85%D8%AF-%D8%B9%D8%A8%D8%AF%D8%A7%D9%84%D8%B9%D8%B2%D9%8A%D8%B2-109604920760675/"><i class="fa fa-facebook fa-2x"></i></a></li>
                      <li><a href="https://chat.whatsapp.com/C3tIc40lOLrIv6X0qq6aAK"><i class="fa fa-whatsapp fa-2x"></i></a></li>
                      <li><a href="https://www.youtube.com/channel/UCbTiEjv8G0eEahLklDBejaQ"><i class="fa fa-youtube fa-2x"></i></a></li>
                    </ul>
                  </div>
                </div>  
               </div>
              </div>
            </form>
        <!-- End Login Form -->
                    <!-- Start Signup Form -->    
                    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                    <div class="login text-center">    
                 <div class="form sign-in">   
                  <div class="row log"> 
                      <div class="col-md-6 order-sm-1 order-md-2">
                          <div class="input-container">
                            <label class="label"><i class="fa fa-user"></i> الاسم كاملا</label>   
                            <input 
                                class="username form-control text-center" 
                                type="text" 
                                name="username" 
                                autocomplete="off"
                                placeholder="الاسم كامل "
                                value="<?php if (isset($username)) {echo $username;}?>"   
                                   required/>
                              <div class="alert text-center alert-danger custom_alert">
					               يجب اضافة اسم ويزيد عن 4 حروف              
                                </div>
                         </div>    
                         <div class="input-container">
                          <label class="label"><i class="fa fa-key"></i> كلمة المرور </label> 
                           <i class="show-pass fa fa-eye top"></i>      
                          <input 
                                class="password showpass form-control text-center" 
                                type="password" 
                                name="password" 
                                autocomplete="password"
                                placeholder=" مزيج من ارقام وحروف "
                                 required/>
                        </div>
                        <div class="alert text-center alert-danger custom_alert">
					               يجب اضافة كلمة مرور وتزيد عن 4 حروف              
                        </div>  
                        <div class="input-container">
                          <label class="label"><i class="fa fa-clone"></i> اعد كتابة كلمة المرور</label>
                          <i class="show-pass fa fa-eye top"></i>     
                          <input 
                                class="password2 showpass form-control text-center" 
                                type="password" 
                                name="password2" 
                                autocomplete="password"
                                placeholder="يجب ان تتطابق مع كلمة المرور"
                                 required/>
                            <div class="alert text-center alert-danger custom_alert">
					               يجب اضافة كلمة مرور وتزيد عن 4 حروف              
                            </div>
                        </div>    
                    </div>      
                
                      <div class="col-md-6 order-sm-2 order-md-1">   
                         <div class="input-container">
                          <label class="label"><i class="fa fa-phone-square"></i> رقم الهاتف </label>      
                          <input 
                                class="phone form-control text-center" 
                                type="tel" 
                                name="phone" 
                                placeholder="رقم الواتس للتواصل"
                                 value="<?php if (isset($phone)) {echo $phone;}?>"
                                 required/>
                             <div class="alert text-center alert-danger custom_alert">
					               يجب اضافة رقم هاتف وتزيد عن 8 حروف              
                            </div>
                          </div>
                        <div class="input-container">
                          <label class="label"><i class="fa fa-users"></i> اختر الصف الدراسى </label>  
                          <select name="parent" class="sel">
								<option value="1">الاول الثانوي</option>
								<option value="2">الثاني الثانوي </option>
								<option value="3">الثالث الثانوى</option>
                            </select>      
                        </div>    
                    </div>      
                </div>       
                                <div class="row">     
                                    <input type="submit" class="button confirm" name="signup" value="انشاء حساب الان">
                                </div>
                                <div class="foot-lnk">
                                    <a for="tab-1">لديك حساب؟</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="the-errors text-center">
                <?php 
                    if (!empty($formErrors)) {
                        foreach ($formErrors as $error) {
                            echo '<div class="msg error">' . $error . '</div>';
                        }
                    }
                    if (isset($succesMsg)) {
                        echo '<div class="msg success">' . $succesMsg . '</div>';
                    }
                ?>
	</div>
                </div>        
            </div>
        </section>
        <section class="swiper">
            <div class="our-team text-center">
                <h2>تعرف على الكثير من <span class="bar">المميزات</span> عند الاشتراك</h2>
                <!-- Swiper -->
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide style"><img src="layouts/images/Inked1_LI.jpg" alt=""> <p class="para">فى الصفحة الرئيسية يمكنك متابعته احدث الرسائل الموجه اليك واضافه مهام لتنفيذها وكذلك الاطلاع على اخر النتائج الخاصة بك وتعديل البينات الشخصية </p></div>
                        <div class="swiper-slide style"><img src="layouts/images/2.png" alt=""> <p class="para"> عند تسجيل الدخول احرص على ادخال الصف الخاص بك لكي يتم الدخول اليه ومتابعتة دروسك</p></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>
        <section class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-4 order-3 order-md-1 order-lg-1" >
                        <h2>يمكنك متابعتنا </h2>
                        <p>من خلال تلك الروابط</p>
                        <div class="social">
                            <a href="#"><i class="fa fa-facebook-f"></i></a> 
                            <a href="#"><i class="fa fa-whatsapp"></i></a> 
                            <a href="#"><i class="fa fa-youtube"></i></a> 
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 about order-2 order-lg-2 order-md-3 text-right" >
                        <h2 class="text-center">ما تقدمة المنصة</h2>
                        <div class="row">
                            <div class="col-7">فيديوهات شرح مبسطة لكل الدروس</div>
                            <div class="col-5"><img src="layouts/images/1.jpg" alt="" /></div>
                        </div>
                        <div class="row">
                            <div class="col-7">فيديوهات لحل المسائل ومسائل القدرات العلية للتفكير </div>
                            <div class="col-5"><img src="layouts/images/2.jpg" alt="" /></div>
                        </div>
                        <div class="row">
                            <div class="col-7">ملف pdf به ورق الشرح لكل فيديو يمكن تحميلة وطباعته</div>
                            <div class="col-5"><img src="layouts/images/3.png" alt="" /></div>
                        </div>
                        <div class="row">
                            <div class="col-7">اختبارات تفاعلية من بنك الاسئله الخاص بنا</div>
                            <div class="col-5"><img src="layouts/images/4.png" alt="" /></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 order-1 order-md-2 order-lg-3 text-center" >
                        <h2>الاستاذ/ محسن شعبان</h2>
                        <p>معلم الفيزياء بمعهد كفر الشيخ الثانوى بنين</p>
                        <span class="spa">01061293757</span>
                    </div>
                </div>
            </div>
        </section>
        <section class="rights text-center">
            <hr>
            <h4>
                Copyright &copy 2020 All rights reserved | made by
                <span class="foot">Ahmed Eltaroon</span>    
            </h4>
        </section>
        <div id="scroll-top">
            <i class="fa fa-chevron-up fa-2x"></i> 
        </div>
<?php
include $tpl . 'footer.php';
ob_end_flush(); 
?>