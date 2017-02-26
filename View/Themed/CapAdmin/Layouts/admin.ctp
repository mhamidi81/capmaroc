<!DOCTYPE html>
<!-- Template Name: Clip-Two - Responsive Admin Template build with Twitter Bootstrap 3.x | Author: ClipTheme -->
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
	<!--<![endif]-->
	<!-- start: HEAD -->
	<head>
		<title><?php echo $title_for_layout; ?> - <?php echo __d('croogo', 'Croogo'); ?></title>
		<!-- start: META -->
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta content="" name="description" />
		<meta content="" name="author" />
		<!-- end: META -->
		<!-- start: GOOGLE FONTS 
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		 -->
		
		<?php
		echo $this->Html->css(array(
			'../plugins/bootstrap/css/bootstrap.min.css',
			'../plugins/fontawesome/css/font-awesome.min.css',
			'../plugins/themify-icons/themify-icons.min.css',
			'../plugins/animate.css/animate.min.css',
			'../plugins/perfect-scrollbar/perfect-scrollbar.min.css',
			'../plugins/switchery/switchery.min.css',
			'../plugins/select2/select2.min.css',
			'../plugins/toastr/toastr.min',
			'../plugins/DataTables/css/jquery.dataTables.css',
			'../plugins/bootstrap-datepicker/bootstrap-datepicker3.standalone.min',
			'../plugins/bootstrap-datetimepicker/bootstrap-datetimepicker',
			'../plugins/DataTables/css/DT_bootstrap',

			'styles.css',
			'plugins.css'
		));
		echo $this->Html->css('themes/theme-1.css', array('id' => 'skin_color'))
		?>

		<?php

		echo $this->Layout->js();
		echo $this->fetch('script');
		echo $this->fetch('css');
		?>
		<!-- end: CLIP-TWO CSS -->
		<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
		<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
	</head>
	<body>
		<div id="app">
			<!-- sidebar -->
			<div class="sidebar app-aside" id="sidebar">
				<div class="sidebar-container perfect-scrollbar">
					<nav>
						<!-- start: MAIN NAVIGATION MENU -->
						<div class="navbar-title">
							<span>Menu principale</span>
						</div>
						<?php
							$cacheKey = 'adminnav_' . $this->Layout->getRoleId() . '_' . $this->request->url . '_' . md5(serialize($this->request->query));
							$navItems = Cache::read($cacheKey, 'croogo_menus');
							if ($navItems === false) {
								$navItems = $this->CapTheme->adminMenus(CroogoNav::items(), array(
									'htmlAttributes' => array(
										'class' => 'main-navigation-menu',
									),
								));
								Cache::write($cacheKey, $navItems, 'croogo_menus');
							}
							echo $navItems;
						?>
						<!-- start: CORE FEATURES -->
						<div class="navbar-title">
							<span>Options</span>
						</div>
						<ul class="folders">
							<?php $menuUrl = Router::url(array('plugin' => 'calendar_managment', 'controller' => 'events', 'action' => 'index', 'admin' => true));
								if ($menuUrl == env('REQUEST_URI') || (isset($menu['url']['controller']) && $menu['url']['controller'] == 
									$this->request->params['controller'])) {
									$li_class =  'active';
								}
								else
								{
									$li_class = '';
								}
							?>
							<li class = "<?php echo $li_class; ?>">
								<a href="<?php echo $menuUrl; ?>">
									<div class="item-content">
										<div class="item-media">
											<span class="fa-stack"> <i class="fa fa-square fa-stack-2x"></i> <i class="fa fa-terminal fa-stack-1x fa-inverse"></i> </span>
										</div>
										<div class="item-inner">
											<span class="title"> Calendrier </span>
										</div>
									</div>
								</a>
							</li>
							<?php $menuUrl = Router::url(array('plugin' => 'message_managment', 'controller' => 'messages', 'action' => 'index', 'admin' => true));
								if ($menuUrl == env('REQUEST_URI') || (isset($menu['url']['controller']) && $menu['url']['controller'] == 
									$this->request->params['controller'])) {
									$li_class =  'active';
								}
								else
								{
									$li_class = '';
								}
							?>
							<li class = "<?php echo $li_class; ?>">

								<a href="<?php echo $menuUrl; ?>">
									<div class="item-content">
										<div class="item-media">
											<span class="fa-stack"> <i class="fa fa-square fa-stack-2x"></i> <i class="fa fa-folder-open-o fa-stack-1x fa-inverse"></i> </span>
										</div>
										<div class="item-inner">
											<span class="title"> Messages </span>
										</div>
									</div>
								</a>
							</li>
						</ul>
						<!-- end: CORE FEATURES -->
						<!-- start: DOCUMENTATION BUTTON -->
						<div class="wrapper">
							<a href="<?php echo $this->webroot; ?>manuel.pdf" target = "blank" class="button-o">
								<i class="ti-help"></i>
								<span>Documentation</span>
							</a>
						</div>
						<!-- end: DOCUMENTATION BUTTON -->
					</nav>
				</div>
			</div>
			<!-- / sidebar -->
			<div class="app-content">
				<!-- start: TOP NAVBAR -->
				<?php echo $this->element('admin/header'); ?>
				<!-- end: TOP NAVBAR -->
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						<?php echo $this->element('admin/breadcrumb'); ?>
						<div class = "flash">
							<?php echo $this->Layout->sessionFlash(); ?>
						</div>
						<?php echo $this->fetch('content'); ?>
						<!--</div>-->
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<?php echo $this->element('admin/footer'); ?>
			<?php echo $this->element('admin/settings'); ?>
		</div>
		<?php
		echo $this->Html->script(array(
				'../plugins/jquery/jquery.min.js',
				//'jquery-1.11.1.min',
				'../plugins/bootstrap/js/bootstrap.min.js',
				'../plugins/bootbox/bootbox.min',
				'../plugins/modernizr/modernizr.js',
				'../plugins/jquery-cookie/jquery.cookie.js',
				'../plugins/perfect-scrollbar/perfect-scrollbar.min.js',
				'../plugins/select2/select2.min',
				'../plugins/switchery/switchery.min.js',
				'../plugins/DataTables/jquery.dataTables.min.js',
				'../plugins/jquery.sparkline/jquery.sparkline.min.js',
				'../plugins/toastr/toastr.min.js',
				'../plugins/moment/moment-with-locales',
				'../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js',
				'../plugins/bootstrap-datetimepicker/bootstrap-datetimepicker',
				'main',
				'admin'
		));
		?>
		<?php
		echo $this->Blocks->get('scriptBottom');
		echo $this->Js->writeBuffer();
		?>
		<script>
			jQuery(document).ready(function() {
				Main.init();
			});
		</script>
	</body>
</html>