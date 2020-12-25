<?php 
	session_start();
    $pageTitle = 'categories';
    $noNavbar = '';
    if (isset($_SESSION['user'])) {
	include 'inital.php';
    include "check_token.php";
    // Check If Get Request item Is Numeric & Get Its Integer Value
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    $examid = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;
	// Select All Data Depend On This ID
    if ($do == 'Manage') { 
        $stmt = $con->prepare("SELECT *  FROM exams WHERE exam_id = ? ");
        $stmt->execute(array($examid));
        $count = $stmt->rowCount();
        if ($count > 0) {
        // Fetch The Data
        $item = $stmt->fetch();
        ?>
        <div class="profilebody">
            <div class="text-right container">
                <div class="profile row">
                    <div class="col-md-8 order-sm-2 order-md-1 exam">
                        <?php include $tpl . 'last.php'; ?>
                        <h1 class="text-center">الامتحانات</h1>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" class="text-right">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        يجب عليك التركيز جيدا عند اداء الاختبار وخصوصا انه لا يسمح باداء الامتحان اكثر من مرة واحدة وبعد ذلك ستتمكن من الاطلاع على حلول الامتحان و الاطلاع على النتيجة الخاصة بجميع امتحاناتك في صفحتك الشخصية ثم الانتقال الى المرحلة التالية
                        </div>
                        <div class="all">
                            <?php
                                    $formErrors = array(); 
                                    $stmt = $con->prepare("SELECT 
                                                                question.*, exams.exam_name AS exam_name  
                                                            FROM 
                                                                question
                                                            INNER JOIN 
                                                                exams 
                                                            ON 
                                                                exams.exam_id = question.question_id 
                                                            WHERE 
                                                                question_id = ? 
                                                            ORDER BY RAND() 
                                                            LIMIT 10");
                                    $stmt->execute(array($item['exam_id']));
                                    $exams = $stmt->fetchAll();
                                    $i =0;
                                    foreach ($exams as $exam ) { 
                                    $i++;
                                ?>
                            <div id="demoB"></div>    
                            <form class="form-horizontal fafafa">
                            <table class="table-secondary text-center table table-bordered  table-sm-responsive">
                            <tr>
                                <td>رقم السؤال</td>
                                <td>:</td>
                                <td><?php echo $i; ?></td>
                            </tr>    
                            <tr>
                                <td><?php echo $exam['ques']; ?></td>
                                <td>:</td>
                                <td>السؤال</td>
                            </tr>
                            <tr>
                                <td class="tabled"><?php if (empty($exam['photo'])) {
                                            echo '<h6 class="text-center">لا يوجد وصف</h6>';
                                        } else {
                                            ?><img class="imgsd" src="admin/uploads/avatars/<?php echo $exam['photo']; ?>" alt="" /><?php
                                        }?></td>
                                <td>:</td>
                                <td>وصف السؤال</td>
                            </tr>    
                            <tr>
                                <td>
                                    <div class="<?php if ($exam['answer_1'] == $exam['right_answer']) { echo 'spec'; } else { echo 'speci'; }?>">
                                        <label><?php echo $exam['answer_1']; ?> <input id="com-no" type="radio" name="exam" value="<?php if ($exam['answer_1'] == $exam['right_answer']) { echo 1; } else { echo 0; }?>" /></label>
                                    </div>
                                </td>
                                <td>:</td>
                                <td>الاختيار الاول</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="<?php if ($exam['answer_2'] == $exam['right_answer']) { echo 'spec'; } else { echo 'speci'; }?>">
                                        <label><?php echo $exam['answer_2']; ?> <input id="com-no" type="radio" name="exam" value="<?php if ($exam['answer_2'] == $exam['right_answer']) { echo 1; } else { echo 0; }?>" /></label>
                                    </div>    
                                </td>
                                <td>:</td>
                                <td>الاختيار الثانى</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="<?php if ($exam['answer_3'] == $exam['right_answer']) { echo 'spec'; } else { echo 'speci'; }?>">
                                        <label><?php echo $exam['answer_3']; ?> <input id="com-no" type="radio" name="exam" value="<?php if ($exam['answer_3'] == $exam['right_answer']) { echo 1; } else { echo 0; }?>" /></label>
                                    </div>     
                                </td>
                                <td>:</td>
                                <td>الاختيار الثالث</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="<?php if ($exam['answer_4'] == $exam['right_answer']) { echo 'spec'; } else { echo 'speci'; }?>">
                                        <label><?php echo $exam['answer_4']; ?> <input id="com-no" type="radio" name="exam" value="<?php if ($exam['answer_4'] == $exam['right_answer']) { echo 1; } else { echo 0; }?>" /></label>
                                    </div>    
                                </td>
                                <td>:</td>
                                <td>الاختيار الرابع</td>
                            </tr>
                                </table> </form> <?php  }    ?>
                            <form class="form-horizontal fafafa" action="?do=Add" method="POST">
                            <select name="userid" class="hidden">
                                <option value="<?php echo $_SESSION['uid']?>"></option></select>
                            <select name="mark" class="hidden">
                                <option class="bam" value='0'></option></select>  
                            <select name="examid" class="hidden">
                                <option value="<?php echo $examid?>"></option></select>
                            <select name="lessonid" class="hidden">
                                <option value="<?php echo $item['lesson_id']?>"></option></select>     
                                <input type="submit" value="ارسال النتيجة" class="button btnnns block" />
                            </form>
                            <?php 
                            $stmt = $con->prepare("SELECT *  FROM answer where exam_id = ? AND user_id = ? AND mark != 0");
                            $count = $stmt->execute(array($examid, $_SESSION['uid']));
                            $count = $stmt->rowCount();
                            if ($count > 0) {
                            ?> <div class="button btnnns hidden">اظهار نموزج الاجابة</div> <?php   
                            }
                            ?>
                            <div class="alert alert-success hidden">لقد اجبت عدد <span class="result"></span> اجابات صحيحة</div> 
                        </div>
                        </div>
                        <?php include $tpl . 'intro.php'; ?>                  
                </div> 
            </div>
        </div>                
    <?php } else {
            echo '<div class="ads container text-right">';
                echo '<div class="alert alert-danger">لا يوجد امتحانات بهذا العنوان بعد</div>';
            echo '</div>';
        }} elseif ($do == 'Add') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $user = $_POST['userid'];
                $exam = $_POST['examid'];
                $lesson = $_POST['lessonid'];
                $stmt = $con->prepare("SELECT *  FROM answer where exam_id = ? AND user_id = ? AND lesson_id = ?");
                $count = $stmt->execute(array($exam, $user, $lesson));
                $item = $stmt->fetch();
                if ($item['exam_id'] == $exam && $item['user_id'] == $user) {
                    ?> <div class="repeat text-center"><?php
                    $theMsg = '<div class="alert alert-danger">لقد تم اداء ذلك الامتحان من قبل</div>';
                redirectHome($theMsg);
                    echo "</div>";
                        
                } else {
                    $mark = isset($_POST['mark']) ? $_POST['mark'] : $item['mark'];
                    $stmt = $con->prepare("INSERT INTO 
                            answer(exam_id, mark, user_id, date, lesson_id)
                        VALUES(:zexame, :zmark, :zuser, now(), :zlesson)");
                        $stmt->execute(array(
                            'zexame' 	=> $exam,
                            'zmark' 	=> $mark,
                            'zuser' 	=> $user,
                            'zlesson' 	=> $lesson
                        ));
                    ?> <div class="repeat text-center">
                        <?php $theMsg = "<div class='alert alert-success'>لقد اجبت عدد  $mark اجابة صحيحة </div>";
                        redirectHome($theMsg, 'back');
                    echo "</div>";
            }

        }
    }
        include $tpl . 'footer.php'; ?>
<script>
    window.addEventListener("load", function () {
        counter.init("demoB", 600, function(idx){
            window.location.replace("end.php");
        });
    });
    </script>
<?php
    } else {
            header('Location: index.php');
            exit();
    } 
?>