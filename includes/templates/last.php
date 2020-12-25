<div class="setting text-left">
						<li class="dropdown ellipsis">
							<i class="fa fa-ellipsis-v dropdown-toggle"data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></i>
							<ul class="dropdown-menu text-center">
								<li>
									<a href="settings.php?do=Edit&userid=<?php echo $userid; ?>">الاعدادات</a>
								</li>
								<li><a href="logout.php">تسجيل الخروج</a></li>                        
							</ul>
						</li>
						<li class="dropdown bars">
							<i class="fa fa-bars hidden dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></i>
							<ul class="dropdown-menu text-center">
								<li><a href="profile.php">الرئيسية</a></li>
								<li><a href="categories.php">الاقسام الدراسية</a></li>
								<li><a href="post.php">المنشورات</a></li>
								<li><a href="settings.php?do=Edit&userid=<?php echo $userid; ?>">الاعدادات</a></li>
								<li><a href="logout.php">تسجيل الخروج</a></li>
							</ul>
						</li>
						<li class="lis">
							<span>
								<i class="fa fa-moon-o" data-value="<?php echo $night ?>"></i>
								<i class="fa fa-flash theme" data-value="<?php echo $sun ?>"></i>
									الاوضاع
							</span>
						</li>
						<li class="dropdown note">
							<i class="fa fa-bell dropdown-toggle"data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></i>
							<ul class="dropdown-menu text-center">
										<li>اخر المنشورات</li>
										<?php  $stmt = $con->prepare("SELECT post.*, members.username AS Member  
																			FROM 
																				post
																			INNER JOIN 
																				members 
																			ON 
																				members.userid = post.users
																			ORDER BY post_id DESC");
											$stmt->execute();
											$posts = $stmt->fetch();
										?>
								<li><a href="post.php"><strong class="colorpost"><?php echo $posts['post_name'] ;?></strong></a> بواسطة <?php echo $posts['Member'] ;?> </li>
							</ul>
                        </li>
                        <li><span><?php echo date('D-M-Y'); ?></span></li>
					</div>