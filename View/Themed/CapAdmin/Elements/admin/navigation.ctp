<nav class="navbar-inverse sidebar">
	<div class="navbar-inner">
	<?php
		$cacheKey = 'adminnav_' . $this->Layout->getRoleId() . '_' . $this->request->url . '_' . md5(serialize($this->request->query));
		$navItems = Cache::read($cacheKey, 'croogo_menus');
		
		if ($navItems === false) {
			$navItems = $this->CapTheme->adminMenus(CroogoNav::items(), array(
				'htmlAttributes' => array(
					'id' => 'sidebar-menu',
				),
			));
			Cache::write($cacheKey, $navItems, 'croogo_menus');
		}
		echo $navItems;
	?>
	</div>
</nav>