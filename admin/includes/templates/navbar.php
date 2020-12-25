<input type="checkbox" id="check">
    <!--header area start-->
    <header> 
      <div class="right_area">
        <label for="check" >
        <i class="fa fa-bars" id="sidebar_btn"></i>
            <h3><a href="dashboard.php">الصفحة <span>الرئيسية</span></a></h3>
      </label>  
      </div>
    </header>
    <!--header area end-->
    <!--sidebar start-->
    <div class="sidebar text-right ">
    <?php echo '<a href="member.php?do=Edit&userid=' . $_SESSION['ID'] . '"><span>الصفحة الشخصية</span><i class="fa fa-home fa-lg"></i></a>';?>
      <a href="member.php"><span>الاعضاء</span><i class="fa fa-users fa-lg"></i></a>
      <a href="categories.php"><span>الاقسام</span><i class="fa fa-table fa-lg"></i></a>
      <a href="lessons.php"><span>الدروس</span><i class="fa fa-book fa-lg"></i></a>
      <a href="exam.php"><span>الامتحانات</span><i class="fa fa-question fa-2x"></i></a>
      <a href="question_list.php"><span>الاسئلة</span><i class="fa fa-question fa-2x"></i></a>    
      <a href="post.php"><span>المنشورات</span><i class="fa fa-edit fa-lg"></i></a>
      <a href="comments.php"><span>تعليقات الدروس</span><i class="fa fa-commenting-o fa-lg"></i></a>
      <a href="comment_2.php"><span>تعليقات المنشورات</span><i class="fa fa-commenting-o fa-lg"></i></a>    
      <a href="../index.php"><span>صفحة الواجهة</span><i class="fa fa-desktop fa-lg"></i></a>    
      <a href="logout.php"><span>تسجيل الخروج</span><i class="fa fa-sign-out fa-lg"></i></a>            
    </div>
    <!--sidebar end-->

