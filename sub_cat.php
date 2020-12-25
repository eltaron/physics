<?php 
    ob_start();
	session_start();
    $pageTitle = 'categories';
    if (isset($_SESSION['user'])) {
        include 'inital.php';
        include "check_token.php";
        $catid = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;
        $getAll = $con->prepare("SELECT * FROM category where category_id = ?");
        $getAll->execute(array($catid));
        $all = $getAll->fetch();
        $count = $getAll->rowCount();
        if ($count > 0) {
        ?>
        <div class="profilebody sub_cat">
            <div class="row profile">
                <div class="col-md-9">
                    <div class="container text-center main_con">
                        <?php include $tpl . 'last.php'; ?>
                        <div class="leftcol">
                            <h1>ملخص لمحتوى ذلك الجزء من المنهج</h1>
                            <div class="contenent">
                                <div class="row">
                                    <div class="col-md-4">
                                        <i class="fa fa-question"></i>
                                        <span class="text">: عدد الامتحانات</span>
                                        <span class="number"><?php echo checkItem("categ_id", "exams", $catid) ?></span>
                                    </div>
                                    <div class="col-md-4 top">
                                        <i class="fa fa-tasks"></i>
                                        <span class="text">: اسم القسم</span>
                                        <span class="text"><?php echo $all['category_name']; ?></span>
                                    </div>
                                    <div class="col-md-4">
                                        <i class="fa fa-book"></i>
                                        <span class="text">: عدد الدروس</span>
                                        <span class="number"><?php echo checkItem("cat_id", "lessons", $catid) ?></span>
                                    </div>
                                </div>
                                <div class="row text-center">
                                        <div class="col-lg-6 order-lg-2 allmain">
                                            <h2>جزء الشرح</h2>
                                            <img src="layouts/images/3886411-removebg-preview.png" alt="">
                                            <h3>تفاصيل الدروس</h3>
                                            <div class="lessonlist text-right">
                                                <?php  
                                                    $allItem = getAllFrom("*", "lessons", "where cat_id = {$catid}", "", "lesson_id");
                                                    foreach ($allItem as $lesson) {?> 
                                                        <div class="full-view text-right">
                                                            <a href="lesson.php?pageid=<?php echo $lesson['lesson_id']?>">
                                                                <i class="fa fa-chevron-circle-left"></i>
                                                                <span><?php echo $lesson['lesson_name']; ?></span>
                                                            </a>
                                                            <P><?php echo $lesson['lesson_description']; ?></P></div> 
                                                <?php }?> 
                                            </div>
                                        </div>
                                        <div class="col-lg-6 order-lg-1 allmain">
                                        <h2>جزء الامتحانات</h2>
                                            <img src="layouts/images/4235572-removebg-preview.png" alt="">
                                            <h3>تفاصيل الامتحانات</h3>
                                            <div class="lessonlist text-right">
                                                <?php 
                                                $allItem = getAllFrom("*", "exams", "where categ_id = {$catid}", "", "exam_date");
                                                foreach ($allItem as $exams) {?>
                                                <div class="full-view">
                                                    <a href="exam.php?pageid=<?php echo $exams['exam_id']?>">
                                                        <i class="fa fa-chevron-circle-left"></i>
                                                        <span><?php echo $exams['exam_name']; ?></span>
                                                    </a>
                                                </div>     
                                                <?php } ?>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <section class="information">
                                <div class="alert alert-info alert-dismissible fade show text-right" role="alert">
                                    <button type="button" class="close pull-left" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h3>التعليمات</h3>
                                    <ul class="list-unstyled">
                                        <li>يجب عليك مراجعة دروسك جيدا والتاكد من فهمك للمعلومه </li>
                                        <li>بعد كل درس يوجد تدريب بسيط يجب عليك التاكد من اجتيازه</li>
                                        <li>لا يمكن الدخول للدرس التالى دون اجتياز التدريب بنتيجة جيدة</li>
                                        <li>في حاله وجود استفسار سيتم الاجابة علية في الميعاد المخصص لتلقي الاسئلة</li>
                                    </ul>
                                    <p>المنصة قيد التطوير لذلك عند وجود اى مشاكل او استفسار سواء فى المنصة بشكل عام او التسجيل بشكل خاص يرجى التواصل معنا على الرقم 01066343874</p>
                                </div>
                            </section>
                        </div>    
                    </div>    
                </div>
                <div class="col-md-3 text-right rightcol">
                    <img src="layouts/images/logo8_17_23455.png" alt="" class="firstimg">
                    <div class="maincont">
                        <a href="categories.php">الرئيسية <i class="fa fa-home"></i></a>
                        <span class="text-center"><?php echo $all['category_name']; ?></span>
                    </div>
                    <div class="contenentlist">
                    <?php
                        $stmt2 = $con->prepare("SELECT 
                                                lessons.*, exams.exam_id, exams.exam_name 
                                            FROM 
                                                lessons
                                            INNER JOIN 
                                                exams 
                                            ON 
                                                exams.lesson_id = lessons.lesson_id
                                            WHERE 
                                            cat_id  = ?
                                            ORDER BY 
                                            lesson_id");
                        $stmt2->execute(array($catid));
                        $gets = $stmt2->fetchAll();
                        foreach ($gets as $cat) {?>
                            <a href="lesson.php?pageid=<?php echo $cat['lesson_id']?>">
                                <h4><i class="fa fa-book"></i> <?php echo $cat['lesson_name']; ?> </h4>
                            </a>
                            <a href="exam.php?pageid=<?php echo $cat['exam_id']?>">
                                <h4><i class="fa fa-question-circle-o"></i> <?php echo $cat['exam_name']; ?> </h4>
                            </a>
                        <?php } ?>    
                    </div>
                </div>
            </div>
        </div>
<?php 
            } else {
                echo '<div class="ads container text-right">';
                    echo '<div class="alert alert-danger">لا يوجد قسم بهذا العنوان بعد يرجى الرجوع للقسم الرئيسى</div>';
                echo '</div>';
            }
} else {
		header('Location: index.php');
		exit();
	}
    include $tpl . 'footer.php';
    ob_end_flush(); 
?>