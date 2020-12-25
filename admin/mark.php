<?php
ob_start(); 
session_start();
$pageTitle = 'lessons';
if (isset($_SESSION['username'])) {
		include 'inital.php';
        $stmt = $con->prepare("SELECT 
                                    answer.*, 
                                    exams.exam_name AS exam_name, 
                                    members.username AS username
                                FROM 
                                    answer
                                INNER JOIN 
                                    exams 
                                ON 
                                    exams.exam_id = answer.exam_id 
                                INNER JOIN 
                                    members 
                                ON 
                                    members.userid = answer.user_id
                                ORDER BY 
                                    id DESC");
        $stmt->execute();
        $exams = $stmt->fetchAll();
        if (! empty($exams)) {
        ?>
        <div class="manages container">
            <h1 class="text-center">الامتحانات</h1>
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>نتيجة الاختبار</td>
                        <td>اسم الطالب</td>
                        <td>تاريخ اداء الامتحان</td>
                        <td>اسم الامتحان</td>
                        <td>الرقم التعريفي</td>
                    </tr>
                    <?php
                        foreach($exams as $exam) {
                           echo "<tr>";
                            echo "<td>" . $exam['mark'] ."</td>";
                            echo "<td>" . $exam['username'] ."</td>";
                            echo "<td>" . $exam['date'] . "</td>";
                            echo "<td>" . $exam['exam_name'] . "</td>";
                            echo "<td>" . $exam['id'] . "</td>";
                           echo "</tr>";
                        }
                    ?>
                </table>
            </div>
        </div>
        <?php } else {
                echo '<div class="container">';
                    echo '<div class="message">لا يوجد امتحانات</div>';
                echo '</div>';
            }
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>                