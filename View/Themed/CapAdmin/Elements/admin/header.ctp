<?php
$dashboardUrl = Configure::read('Croogo.dashboardUrl');
?>

<header class="navbar navbar-default navbar-static-top">
	<!-- start: NAVBAR HEADER -->
	<div class="navbar-header">
		<a href="#" class="sidebar-mobile-toggler pull-left hidden-md hidden-lg" class="btn btn-navbar sidebar-toggle" data-toggle-class="app-slide-off" data-toggle-target="#app" data-toggle-click-outside="#sidebar">
			<i class="ti-align-justify"></i>
		</a>
		<a class="navbar-brand" href="#">
			<?php echo $this->Html->image("logo.png", array('style = "height:60px;"')); ?>
		</a>
		<a href="#" class="sidebar-toggler pull-right visible-md visible-lg" data-toggle-class="app-sidebar-closed" data-toggle-target="#app">
			<i class="ti-align-justify"></i>
		</a>
		<a class="pull-right menu-toggler visible-xs-block" id="menu-toggler" data-toggle="collapse" href=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<i class="ti-view-grid"></i>
		</a>
	</div>
	<!-- end: NAVBAR HEADER -->
	<!-- start: NAVBAR COLLAPSE -->
	<div class="navbar-collapse collapse">
		<ul class="nav navbar-right">
			<!-- start: LANGUAGE SWITCHER -->
			<li class="dropdown">
				<a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<span class="<?php if($unread_messages_count > 0) echo "dot-badge partition-red"; ?>"></span> <i class="ti-comment"></i> <span>MESSAGES</span>
				</a>
				<ul class="dropdown-menu dropdown-light dropdown-messages dropdown-large">
					<li>
						<span class="dropdown-header">Messages</span>
					</li>
					<?php foreach ($unread_messages as $key => $message) { ?>
					<li>
						<div class="drop-down-wrapper ps-container">
							<ul>
								<li class="messages-item <?php if(!$message['Message']['status']) echo ' highlight' ; ?>">
									<a href="<?php echo Router::url(array('plugin' => 'message_managment', 'controller' => 'messages', 'action' => 'index', 'admin' => true, $message['Message']['id'])); ?>" class="unread">
										
											<div class="thread-image">
												<?php 
												
												if(!empty($message['Sender']['image'])){
													echo $this->Html->image('../uploads/users/'.$message['Sender']['image'], array(
														'class' =>'messages-item-avatar bordered border-primary',
														'alt' =>  $message['Sender']['image'],
														'width' => '100%'
													)); 
												}else{
													echo $this->Html->image('../uploads/users/default-user.png', array(
														'class' =>'messages-item-avatar bordered border-primary',
														'alt' =>  'User', 
													));
												}
												?>
											</div>
											<div class="thread-content">
												<span class="author"><?php echo $message['Sender']['first_name'] .' '.$message['Sender']['last_name'];?></span>
												<span class="preview">
												<?php echo $this->Text->truncate(
														$message['Message']['title'],
														200,
														array(
															'ellipsis' => '...',
															'exact' => true
														)
													);
												?>
												<br>
												<?php echo $this->Text->truncate(
														$message['Message']['body'],
														200,
														array(
															'ellipsis' => '...',
															'exact' => true
														)
													);
												?>
												</span>
												<span class="time"><?php echo date('d-m-Y', strtotime($message['Message']['created'])); ?></span>
											</div>
											<div class="clearfix">
										</div>
									</a>
								</li>
							</ul>
						</div>
					</li>
					<?php } ?>
					<li class="view-all">
						<a href="<?php echo Router::url(array('plugin' => 'message_managment', 'controller' => 'messages', 'action' => 'index', 'admin' => true)); ?>">
							Afficher tout
						</a>
					</li>
				</ul>
			</li>
			<li class="dropdown">
				<!--<a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<i class="ti-check-box"></i> <span>Notifications</span>
				</a>-->
				<ul class="dropdown-menu dropdown-light dropdown-messages dropdown-large">
					<li>
						<span class="dropdown-header"> Nouvelles notifications</span>
					</li>
					<li>
						<div class="drop-down-wrapper ps-container">
							<ul>
								<li class="unread">
									<a href="javascript:;" class="unread">
										<div class="clearfix">
											<div class="thread-content">
												<span class="author">Dossier N 2/2015</span>
												<span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula...</span>
											</div>
										</div>
									</a>
								</li>
							</ul>
						</div>
					</li>
					<li class="view-all">
						<a href="#">
							Afficher tout
						</a>
					</li>
				</ul>
			</li>
			<!-- start: USER OPTIONS DROPDOWN -->
			<li class="dropdown current-user">
				<?php
				echo $this->CapTheme->topMenus(CroogoNav::items('top-right'), array(
						'type' => 'dropdown',
						'htmlAttributes' => array(
								'id' => 'top-right-menu',
								'class' => 'dropdown-menu dropdown-dark',
						),
				));
				?>
			</li>
			<!-- end: USER OPTIONS DROPDOWN -->
		</ul>
		<!-- start: MENU TOGGLER FOR MOBILE DEVICES -->
		<div class="close-handle visible-xs-block menu-toggler" data-toggle="collapse" href=".navbar-collapse">
			<div class="arrow-left"></div>
			<div class="arrow-right"></div>
		</div>
		<!-- end: MENU TOGGLER FOR MOBILE DEVICES -->
	</div>
	<!-- end: NAVBAR COLLAPSE -->
</header>