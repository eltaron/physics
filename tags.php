<?php 
	session_start();
	include 'inital.php';
    include "check_token.php";
    ?>
    <div class="profilebody">
        <div class="text-right container">
            <div class="profile row">
                <div class="col-md-8 order-sm-2 order-md-1 tag">
                    <?php include $tpl . 'last.php'; ?> 
                            <?php
                            if (isset($_GET['name'])) {
                                $tag = $_GET['name'];
                                echo "<h1 class='text-center alls'>" . $tag . "</h1>";
                                ?> <div class="row"> <?php
                                $stmt = $con->prepare("SELECT groupid FROM members WHERE username = ?");
                                $stmt->execute(array($_SESSION['user']));
                                $get = $stmt->fetch();
                                $getAll = $con->prepare("SELECT * FROM category where parent = 0 And ordering= ? ORDER BY ordering asc");
                                $getAll->execute(array($get['groupid']));
                                $all = $getAll->fetch();
                                $allItems = getAllFrom("*", "category","where parent = {$all['category_id']}","", "ordering");
                                foreach ($allItems as $cat) {
                                    $tagItems = getAllFrom("*", "lessons", "where tags like '%$tag%'", "AND cat_id = {$cat['category_id']}", "lesson_id");
                                    foreach ($tagItems as $item) {?>
                                        <div class=" col-lg-6 cats">
                                            <h3><a href="lesson.php?pageid=<?php echo $item['lesson_id']?>"><?php echo $item['lesson_name']?></a></h3>
                                            <p class="lead"><?php echo $item['lesson_description']?></p>
                                            <span class="lead text-left"><?php echo $item['lesson_data']?></span>
                                        </div> <?php }}
                                $tagItems = getAllFrom("*", "post", "where tags like '%$tag%'", "", "post_id");
                                foreach ($tagItems as $posts) {?>
                                    <div class=" col-lg-6 cats">
                                        <h3><a href="post.php"><?php echo $posts['post_name']?></a></h3>
                                        <p class="lead"><?php echo $posts['post_description']?></p>
                                        <span class="lead text-center"><?php echo $posts['post_data']?></span>
                                    </div>
                                <?php }
                            } else {
                                echo 'يجب عليك ادخال اعلام';
                            }
                            ?>
                    </div>  
                    </div>
                    <?php include $tpl . 'intro.php'; ?>                   
            </div>
        </div>
    </div>    
<?php include $tpl . 'footer.php'; ?>