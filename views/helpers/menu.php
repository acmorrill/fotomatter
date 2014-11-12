<?php
class MenuHelper extends AppHelper {

	public function adminMenuItem($controller, $currContr) {
		if (strtolower($currContr) == $controller) {
			$name = ucwords($controller);
			$class = 'active';
		} else {
			$name = '<a href="/'.$controller.'/admin">'. ucwords($controller).'</a>';
			$class = '';
		}

		return "<li class='$class'><span><span>$name</span></span>";
	}

	public function adminLeftMenu($menus) {
		$html = '';
		$html .= '<div id="left-column">';
		foreach ($menus as $title => $menu) {
			$html .= '<h3>'.$title.'</h3>';
			$html .= '<ul class="nav">';
			foreach ($menu as $item) {
				$html .= '<li><a href="'.$item['path'].'">'.$item['name'].'</a></li>';
			}
			$html .= '</ul>';
		}
		$html .= '</div>';

		return $html;
	}
}