<?php 
    ob_start();
	session_start();
    $pageTitle = 'categories';
    if (isset($_SESSION['user'])) {
        include 'inital.php';
        include "check_token.php";
        // Check If Get Request item Is Numeric & Get Its Integer Value
        $lessonid = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;
        // Select All Data Depend On This ID
        $stmt = $con->prepare("SELECT *  FROM lessons WHERE lesson_id = ? ");
        // Execute Query
        $stmt->execute(array($lessonid));
        $count = $stmt->rowCount();
        if ($count > 0) {
            $item = $stmt->fetch();
            if ($item['Approve'] == 1) {
                include 'lesson_main.php';
            } else {
               $stmt2 = $con->prepare("SELECT *  FROM answer WHERE lesson_id = ? And user_id = ? ");
                $stmt2->execute(array($lessonid, $_SESSION['uid']));
                $mark = $stmt2->fetch();    
                $count2 = $stmt2->rowCount();
                if ($count2 > 0 && $mark['mark'] > 5) {
                    include 'lesson_main.php';
                } else {
                    echo '<div class="ads container text-right">';
                        $theMsg= '<div class="alert alert-danger"> لم يتم اداء الامتحان المخصص لفتح ذلك الفيديو بعد او تم اجتياز الامتحان بنتيجة اقل من 5 درجات</div>';
                        redirectHome($theMsg, 'back');
                    echo '</div>';  
                }
            }
        } else {
            echo '<div class="ads container text-right">';
                echo '<div class="alert alert-danger">لا يوجد درس بهذا العنوان بعد يرجى الرجوع لقسم الدروس واختيار الدرس المناسب</div>';
            echo '</div>';
        }
    } else {
		header('Location: index.php');
		exit();
	}
    include $tpl . 'footer.php';
    ob_end_flush(); 
?>