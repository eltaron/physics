<?php
    ob_start();
	session_start();
    $pageTitle = 'post';
	include 'inital.php';
    include "check_token.php";
    if (isset($_SESSION['user'])) {?>
        <div class="profilebody">
            <div class="text-right container">
                <div class="profile row">
                    <div class="col-md-8 order-sm-2 order-md-1">
                        <?php include $tpl . 'last.php'; ?>
                        <?php 
                                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                            $comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                            $itemid 	= $_POST['parents'];
                                            $userid 	= $_SESSION['uid'];
                                            if (! empty($comment)) {
                                                $stmt = $con->prepare("INSERT INTO 
                                                    comments(comment, status, comment_data, post_id, member_id)
                                                    VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");
                                                $stmt->execute(array(
                                                    'zcomment' => $comment,
                                                    'zitemid' => $itemid,
                                                    'zuserid' => $userid
                                                ));
                                                if ($stmt) {
                                                    echo '<div class="alert alert-success"> تم اضافة تعليق في انتظار الموافقة</div>';
                                                }
                                            } else {
                                                echo '<div class="alert alert-danger">يجب عليك اضافة تعليق</div>';
                                            }
                                        }
                        ?>
                        <div class="cats">
                            <h1 class="text-center">جميع المنشورات</h1>
                            <?php $stmt = $con->prepare("SELECT *  FROM post ORDER BY post_id desc");
                            $stmt->execute();
                            $count = $stmt->rowCount();
                            $posts = $stmt->fetchAll(); 
                            foreach ($posts as $post) {   ?>
                            <div class="les">
                                <h2 class="text-center"><?php echo $post['post_name']?></h2>
                                <p class="lead"><?php echo $post['post_description']?></p>
                                <span class="text-left"><?php echo $post['post_data']?></span>
                                <img class="img-thumbnail photo" src="admin/uploads/avatars/<?php echo $post['post_image']; ?>" alt="" />
                                <div class="tags text-right">
                                    <span class="lead">الاعلامات</span>
                                    <?php 
                                        $allTags = explode(",", $post['tags']);
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
                                                                    post_id = ?
                                                                AND 
                                                                    status = 1
                                                                ORDER BY 
                                                                    post_id desc");
                                        $stmt->execute(array($post['post_id']));
                                        $comments = $stmt->fetchAll();
                                    ?>
                                    <div class="comment">
                                        <h2 class="text-center">جميع التعليقات</h2>
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
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>    
                            <hr class="custom-hr">
                            <?php } ?>
                        </div>
                        <div class="add-comment">
                                    <h3 class="text-center">اضف تعليق</h3>   
                                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                        <div class="text-center">    
                                            <div class="row "> 
                                                <div class="col-md-12">
                                                    <div class="input-container">
                                                        <textarea name="comment" required class="text-right form-control" ></textarea>
                                                    </div> 
                                                    <div class="input-container top">
                                                    <label>اختر المنشور</label>    
                                                    <select name="parents" class="text-center">
                                                        <option value="0" class="text-center">فارغ</option>
                                                        <?php
                                                            $stmt = $con->prepare("SELECT * FROM post order by post_id");
                                                            $stmt->execute(array());
                                                            $get = $stmt->fetchAll();
                                                            foreach($get as $pos) {
                                                                echo "<option class= 'text-center' value='" . $pos['post_id'] . "'>" . $pos['post_name'] . "</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                    </div>    
                                                </div>      
                                            </div>     
                                                <input type="submit" class="button" value="اضف تعليق">
                                            </div>
                                    </form>
                                </div>
                    </div>
                    <?php include $tpl . 'intro.php'; ?>          
                </div>           
            </div>
        </div>            
<?php } else {
		header('Location: index.php');
		exit();
	}
    include $tpl . 'footer.php'; 
	ob_end_flush();
?>