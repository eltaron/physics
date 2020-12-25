<?php
    ob_start();
	session_start();
	if (isset($_SESSION['username'])) {
		$pageTitle = 'Dashboard';
		include 'inital.php';
        $numUsers = 6;
        $latestUsers = getLatest("*", "members", "userid", $numUsers);
        $numlessons = 6;
        $latestlessons = getLatest("*", 'lessons', 'lesson_id', $numlessons);
        $numposts = 6;
        $latestposts = getLatest("*", 'post', 'post_id', $numposts);
        $numComments = 4;
?>
<div class="home-stats ">
			<div class="container text-center">
				<h1>لوحة التحكم</h1>
				<div class="row">
					<div class="col-md-6 col-lg-4 order-md-3">
						<div class="stat">
							<i class="fa fa-users"></i>
							<div class="info">
								المشتركين بالصفحة
								<span>
									<a href="member.php"><?php echo countItems('userid', 'members') ?></a>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-4 order-md-2">
						<div class="stat">
							<i class="fa fa-user-plus"></i>
							<div class="info">
								الطلاب المميزين
								<span>
									<a href="member.php?do=Manage&page=Activate">
										<?php echo checkItem("regstatus", "members", 1) ?>
									</a>
								</span>
							</div>
						</div>
					</div>
                    <div class="col-md-6 col-lg-4 order-md-1">
						<div class="stat">
							<i class="fa fa-book"></i>
							<div class="info">
								كافة الدروس
								<span>
									<a href="lessons.php">
										<?php echo countItems('lesson_id', 'lessons') ?>
									</a>
								</span>
							</div>
						</div>
					</div> 
					<div class="col-md-6 col-lg-4 order-md-6">
						<div class="stat">
							<i class="fa fa-question"></i>
							<div class="info">
								كافة الامتحانات
								<span>
									<a href="question_list.php">
                                      <?php echo countItems('question_id', 'question') ?>
                                    </a>
								</span>
							</div>
						</div>
					</div>
                    
					<div class="col-md-6 col-lg-4 order-md-4">
						<div class="stat">
							<i class="fa fa-comments"></i>
							<div class="info">
								كل التعليقات
								<span>
									<a href="comments.php">
                                        <?php echo countItems('comment_id', 'comments') ?>
                                    </a>
								</span>
							</div>
						</div>
					</div>
                    <div class="col-md-6 col-lg-4 order-md-5">
						<div class="stat">
							<i class="fa fa-edit"></i>
							<div class="info">
								كل المنشروات
								<span>
									<a href="post.php">
                                        <?php echo countItems('post_id', 'post') ?>
                                    </a>
								</span>
							</div>
						</div>
					</div>  
				</div>
			</div>
		</div>

		<div class="latest text-right">
			<div class="container">
				<div class="row">
					<div class="col-md-6 order-md-2">
						<div class="card">
							<div class="card-heading">
								<i class="fa fa-users fa-lg"></i> 
								اخر الاعضاء
								<span class="toggle-info pull-left">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div>
							<div class="card-body">
								<ul class="list-group">
                                    <?php
									if (! empty($latestUsers)) {
										foreach ($latestUsers as $user) {
											echo '<li class="list-group-item">';
												echo $user['username'];
												echo '<a href="member.php?do=Edit&userid=' . $user['userid'] . '">';
													echo '<span class="btn btn-success pull-left">';
														echo '<i class="fa fa-edit"></i> تعديل';
														if ($user['regstatus'] == 0) {
															echo "<a 
																	href='member.php?do=Activate&userid=" . $user['userid'] . "' 
																	class='btn btn-info pull-left activate'>
																	<i class='fa fa-check'></i> مميز</a>";
														}
                                                        echo '</span>';
                                                    echo '</a>';
                                                echo '</li>';
                                            }
                                        } else {
                                            echo 'لا يوجد اعضاء';
                                        }
                                    ?>
                                </ul>
							</div>
						</div>
					</div>
					<div class="col-md-6 order-md-1">
						<div class="card">
							<div class="card-heading">
								<i class="fa fa-book fa-lg"></i> اخر الدروس      
								<span class="toggle-info pull-left">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div>
							<div class="card-body">
								<ul class="list-group">
                                    <?php
									if (! empty($latestlessons)) {
										foreach ($latestlessons as $lesson) {
											echo '<li class="list-group-item">';
												echo $lesson['lesson_name'] . " " .  $lesson['lesson_data'];
												echo '<a href="lessons.php?do=Edit&itemid=' . $lesson['lesson_id'] . '">';
													echo '<span class="btn btn-success pull-left">';
														echo '<i class="fa fa-edit"></i> تعديل';
                                                        echo '</span>';
                                                    echo '</a>';
                                                echo '</li>';
                                            }
                                        } else {
                                            echo 'لا يوجد دروس بعد';
                                        }
                                    ?>
                                </ul>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 order-md-2">
						<div class="card">
							<div class="card-heading">
								<i class="fa fa-edit fa-lg"></i> 
								 اخر المنشورات 
								<span class="toggle-info pull-left">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div>
							<div class="card-body">
								<ul class="list-group">
                                    <?php
									if (! empty($latestposts)) {
										foreach ($latestposts as $post) {
											echo '<li class="list-group-item">';
												echo $post['post_name'] . " " . $post['post_data'] . " " . $post['users'];    
												echo '<a href="post.php?do=Edit&postid=' . $post['post_id'] . '">';
													echo '<span class="btn btn-success pull-left">';
														echo '<i class="fa fa-edit"></i> تعديل';
                                                        echo '</span>';
                                                    echo '</a>';
                                                echo '</li>';
                                            }
                                        } else {
                                            echo 'لا يوجد منشروات بعد';
                                        }
                                    ?>
                                </ul>
							</div>
						</div>
					</div>
                    <div class="col-md-6 order-md-1">
						<div class="card">
							<div class="card-heading">
								<i class="fa fa-comments-o fa-lg"></i> 
								 اخر التعليقات
								<span class="toggle-info pull-left">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div>
							<div class="card-body">
                                    <?php
									$stmt = $con->prepare("SELECT 
																comments.*, members.username AS Member  
															FROM 
																comments
															INNER JOIN 
																members 
															ON 
																members.userid = comments.member_id
															ORDER BY 
																comment_id DESC
															LIMIT $numComments");

									$stmt->execute();
									$comments = $stmt->fetchAll();
									if (! empty($comments)) {
										foreach ($comments as $comment) {
											echo '<div class="comment-box">';
												echo '<span class="member-n">
													<a href="member.php?do=Edit&userid=' . $comment['member_id'] . '">
														' . $comment['Member'] . '</a></span>';
												echo '<p class="member-c">' . $comment['comment'] . '</p>';
											echo '</div>';
										}
									} else {
										echo 'لا يوجد كومنتات لعرضها';
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


<?php
		include $tpl . 'footer.php';
	} else {
		header('Location: index.php');
		exit();
	}
	ob_end_flush(); 
?>