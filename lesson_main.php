        <div class="profilebody">   
            <div class="text-right container">
                <div class="profile row">
                    <div class="col-md-8 order-sm-2 order-md-1">
                        <?php include $tpl . 'last.php'; ?>
                        <?php 
                                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                            $comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                            $itemid 	= $item['lesson_id'];
                                            $userid 	= $_SESSION['uid'];
                                            if (! empty($comment)) {
                                                $stmt = $con->prepare("INSERT INTO 
                                                    comments(comment, status, comment_data, lesson_id, member_id)
                                                    VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");
                                                $stmt->execute(array(
                                                    'zcomment' => $comment,
                                                    'zitemid' => $itemid,
                                                    'zuserid' => $userid
                                                ));
                                                if ($stmt) {
                                                    echo '<div class="alert alert-success">تم اضافة تعليق في انتظار الموافقة</div>';
                                                }
                                            } else {
                                                echo '<div class="alert alert-danger">يجب عليك اضافة تعليق</div>';
                                            }
                                        }
                                    ?>
                                <div class="les cats lesson">
                                    <h2 class="text-center"><?php echo $item['lesson_name']?></h2>
                                    <p class="lead"><?php echo $item['lesson_description']?></p>
                                    <span class="text-left"><?php echo $item['lesson_data']?></span>
                                    <div class="video"><?php echo $item['video']?></div>
                                    <div class="tags text-right">
                                        <span class="lead">الاعلامات</span>
                                        <?php 
                                            $allTags = explode(",", $item['tags']);
                                            foreach ($allTags as $tag) {
                                                $tag = str_replace(' ', '', $tag);
                                                $lowertag = strtolower($tag);
                                                if (! empty($tag)) {
                                                    echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div class="buttons-box">
                                        <button class="button button_1">التعليقات</button>
                                        <div class="comment">
                                            <h2 class="text-center">التعليقات</h2>   
                                            <?php
                                            $stmt = $con->prepare("SELECT 
                                                                        comments.*, members.username AS Member  
                                                                    FROM 
                                                                        comments
                                                                    INNER JOIN 
                                                                        members 
                                                                    ON 
                                                                        members.userid = comments.member_id
                                                                    WHERE 
                                                                        lesson_id = ?
                                                                    AND 
                                                                        status = 1
                                                                    ORDER BY 
                                                                        comment_id DESC");
                                            $stmt->execute(array($item['lesson_id']));
                                            $comments = $stmt->fetchAll();
                                        ?>
                                        <?php foreach ($comments as $comment) { ?>
                                            <div class="comment-box">
                                                <div class="row">
                                                    <div class="col-sm-2 text-center">
                                                        <img class="img-responsive img-thumbnail img-circle center-block" src="layouts/images/img.png" alt="" />
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <h5 class="text-center"><?php echo $comment['Member'] ?></h5>
                                                        <p class="lead"><?php echo $comment['comment'] ?></p>
                                                    </div>
                                                </div> 
                                            </div>
                                            <hr >
                                        <?php } ?>
                                        </div>
                                    </div>     
                                    <a href="<?php echo $item['pdf'] ?>"><span class="button <?php if (strlen($item['pdf']) > 2 ){ echo 'displayful';} else {echo 'displaymins';}?>">ورق الشرح</span></a>
                                    <?php
                                        $stmt = $con->prepare("SELECT *  FROM exams WHERE lesson_id = ? ");
                                        $stmt->execute(array($lessonid));
                                        $exam = $stmt->fetch(); ?>
                                        <a href="<?php if (isset($exam['lesson_id'])){ echo 'exam.php?pageid=' . $exam['exam_id']; }?>"><span class="button <?php if (isset($exam['lesson_id'])){ echo 'displayful';} else {echo 'displaymins';}?>">اداء الأمتحان</span></a>
                                </div>    
                                <div class="add-comment ">
                                    <h3 class="text-center">اضف تعليق</h3>
                                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?pageid=' . $item['lesson_id'] ?>" method="POST">
                                        <textarea name="comment" required class="text-right form-control" ></textarea>
                                        <input class="button" type="submit" value="اضف تعليق">
                                    </form>
                                </div>
                    </div>
                    <?php include $tpl . 'intro.php'; ?>                 
                </div> 
            </div> 
        </div>               