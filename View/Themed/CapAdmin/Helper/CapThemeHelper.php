<?php

App::uses('CroogoHelper', 'Croogo.Helper');

/**
 * Croogo Helper
 *
 * @category Helper
 * @package  Croogo.Croogo.View.Helper
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class CapThemeHelper extends CroogoHelper {


/** Generate Admin menus added by CroogoNav::add()
 *
 * @param array $menus
 * @param array $options
 * @return string menu html tags
 */
	public function adminMenus($menus, $options = array(), $depth = 0) {

		
		/*$options = Hash::merge(array(
			'type' => 'sidebar',
			'children' => true,
			'htmlAttributes' => array(
				'class' => 'nav nav-stacked',
			),
		), $options);*/

		$aclPlugin = Configure::read('Site.acl_plugin');
		$userId = AuthComponent::user('id');
		if (empty($userId)) {
			return '';
		}

		$htmlAttributes = $options['htmlAttributes'];
		$out = null;
		$sorted = Hash::sort($menus, '{s}.weight', 'ASC');
		
		if (empty($this->Role)) {
			$this->Role = ClassRegistry::init('Users.Role');
			$this->Role->Behaviors->attach('Croogo.Aliasable');
		}

		$currentRole = $this->Role->byId($this->Layout->getRoleId());

		foreach ($sorted as $menu) {

			if (isset($menu['separator'])) {
				continue;
			}
			
			if ($currentRole != 'admin' && !$this->{$aclPlugin}->linkIsAllowedByUserId($userId, $menu['url'])) {
				continue;
			}

			if (empty($menu['htmlAttributes']['class'])) {
				$menuClass = Inflector::slug(strtolower('menu-' . $menu['title']), '-');
				$menu['htmlAttributes'] = Hash::merge(array(
					'class' => $menuClass
				), $menu['htmlAttributes']);
			}
			$title = '';
			if (empty($menu['icon']) || $menu['icon'] === false) {
				$menu['icon'] = '';
			}
		
			$title .= '<div class = "item-content">
							<div class="item-media">
								<i class="ti-' . $menu['icon'] . '"></i>
							</div>
							<div class="item-inner">
								<span class="title">' . $menu['title'] . '</span>
								<i class="icon-arrow"></i>
							</div>
						</div>';

			$children = '';
			
			if (!empty($menu['children'])) {
				$childClass = '';

				$childClass = 'sub-menu';
				$childClass .= ' submenu-' . Inflector::slug(strtolower($menu['title']), '-');

				$children = $this->adminMenus($menu['children'], array(
					'children' => true,
					'htmlAttributes' => array('class' => $childClass),
				), $depth + 1);

			}

			$menuUrl = $this->url($menu['url']);
			$liOptions = array();

			if ($menuUrl == env('REQUEST_URI') || (isset($menu['url']['controller']) && $menu['url']['controller'] == 
				$this->request->params['controller'] && $menu['url']['action'] == $this->request->params['action']) || (isset($menu['url']['controller']) && $menu['url']['controller'] == 
				$this->request->params['controller'] && $this->request->params['action'] == 'admin_open')) {
				$liOptions = array('class' => 'active');
			}

			if (!empty($menu['children'])) {

				foreach ($menu['children'] as $key => $child) {

					if($child['url']['controller'] == $this->request->params['controller'] && 'admin_'.$child['url']['action'] == $this->request->params['action'])
					{
						$liOptions = array('class' => 'active open');
					}
				}
			}

			$link = $this->Html->link($title, $menu['url'], $menu['htmlAttributes']);
			

			$out .= $this->Html->tag('li', $link . $children, $liOptions);
		}

		return $this->Html->tag('ul', $out, $htmlAttributes);
	}

	public function getConnectedUserRole()
	{

		if (empty($this->Role)) {
			$this->Role = ClassRegistry::init('Users.Role');
			$this->Role->Behaviors->attach('Croogo.Aliasable');
		}

		$currentRole = $this->Role->byId($this->Layout->getRoleId());		

		return $currentRole;
	}

	public function isUserAutorized($user_id, $link)
	{
		$aclPlugin = Configure::read('Site.acl_plugin');
		
		return $this->{$aclPlugin}->linkIsAllowedByUserId($user_id, $link);
	}


	public function topMenus($menus, $options = array(), $depth = 0) {
		
		$options = Hash::merge(array(
			'type' => 'sidebar',
			'children' => true,
			'htmlAttributes' => array(
				'class' => 'nav nav-stacked',
			),
		), $options);

		$aclPlugin = Configure::read('Site.acl_plugin');
		$userId = AuthComponent::user('id');
		if (empty($userId)) {
			return '';
		}

		$sidebar = $options['type'] === 'sidebar';
		$htmlAttributes = $options['htmlAttributes'];
		$out = null;
		$sorted = Hash::sort($menus['user']['children'], '{s}.weight', 'ASC');
		if (empty($this->Role)) {
			$this->Role = ClassRegistry::init('Users.Role');
			$this->Role->Behaviors->attach('Croogo.Aliasable');
		}
		$currentRole = $this->Role->byId($this->Layout->getRoleId());
		$menus['user']['before'] = $this->Html->image('default-user.png');
		$toggler = $this->Html->link($menus['user']['before'].'<span class="username">'.$menus['user']['title'].'<i class="ti-angle-down"></i></span>', $menus['user']['url'], array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'));

		foreach ($sorted as $menu) {
			if (isset($menu['separator'])) {
				$liOptions['class'] = 'divider';
				$out .= $this->Html->tag('li', null, $liOptions);
				continue;
			}
			if ($currentRole != 'admin' && !$this->{$aclPlugin}->linkIsAllowedByUserId($userId, $menu['url'])) {
				continue;
			}

			if (empty($menu['htmlAttributes']['class'])) {
				$menuClass = Inflector::slug(strtolower('menu-' . $menu['title']), '-');
				$menu['htmlAttributes'] = Hash::merge(array(
					'class' => $menuClass
				), $menu['htmlAttributes']);
			}
			$title = '';
			if ($menu['icon'] === false) {
			} elseif (empty($menu['icon'])) {
				$menu['htmlAttributes'] += array('icon' => 'white');
			} else {
				$menu['htmlAttributes'] += array('icon' => $menu['icon']);
			}
			if ($sidebar) {
				$title .= '<span>' . $menu['title'] . '</span>';
			} else {
				$title .= $menu['title'];
			}
			$children = '';
			if (!empty($menu['children'])) {
				$childClass = '';
				if ($sidebar) {
					$childClass = 'nav nav-stacked sub-nav ';
					$childClass .= ' submenu-' . Inflector::slug(strtolower($menu['title']), '-');
					if ($depth > 0) {
						$childClass .= ' dropdown-menu';
					}
				} else {
					if ($depth == 0) {
						$childClass = 'dropdown-menu';
					}
				}
				$children = $this->adminMenus($menu['children'], array(
					'type' => $options['type'],
					'children' => true,
					'htmlAttributes' => array('class' => $childClass),
				), $depth + 1);

				$menu['htmlAttributes']['class'] .= ' hasChild dropdown-close';
			}
			$menu['htmlAttributes']['class'] .= ' sidebar-item';

			$menuUrl = $this->url($menu['url']);
			if ($menuUrl == env('REQUEST_URI')) {
				if (isset($menu['htmlAttributes']['class'])) {
					$menu['htmlAttributes']['class'] .= ' current';
				} else {
					$menu['htmlAttributes']['class'] = 'current';
				}
			}

			if (!$sidebar && !empty($children)) {
				if ($depth == 0) {
					$title .= ' <b class="caret"></b>';
				}
				$menu['htmlAttributes']['class'] = 'dropdown-toggle';
				$menu['htmlAttributes']['data-toggle'] = 'dropdown';
			}

			if (isset($menu['before'])) {
				$title = $menu['before'] . $title;
			}

			if (isset($menu['after'])) {
				$title = $title . $menu['after'];
			}

			$link = $this->Html->link($title, $menu['url'], $menu['htmlAttributes']);
			$liOptions = array();
			if ($sidebar && !empty($children) && $depth > 0) {
				$liOptions['class'] = ' dropdown-submenu';
			}
			if (!$sidebar && !empty($children)) {
				if ($depth > 0) {
					$liOptions['class'] = ' dropdown-submenu';
				} else {
					$liOptions['class'] = '';
				}
			}
			$out .= $this->Html->tag('li', $link . $children, $liOptions);
		}

		if (!$sidebar && $depth > 0) {
			$htmlAttributes['class'] = 'dropdown-menu';
		}

		return $toggler.$this->Html->tag('ul', $out, $htmlAttributes);
	}

}
