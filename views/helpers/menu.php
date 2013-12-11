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
        /*
              		<div id="left-column">
			<h3>Header</h3>
			<ul class="nav">
				<li><a href="#">Lorem Ipsum dollar</a></li>
				<li><a href="#">Dollar</a></li>
				<li><a href="#">Lorem dollar</a></li>
				<li><a href="#">Ipsum dollar</a></li>
				<li><a href="#">Lorem Ipsum dollar</a></li>
				<li class="last"><a href="#">Dollar Lorem Ipsum</a></li>
			</ul>
			<a href="#" class="link">Link here</a>
			<a href="#" class="link">Link here</a>
		</div>
		<div id="center-column">
                    <?php echo $content_for_layout; ?>


		</div>
                <div id="right-column">
                    <strong class="h">INFO</strong>
                    <div class="box">Detect and eliminate viruses and Trojan horses, even new and unknown ones. Detect and eliminate viruses and Trojan horses, even new and </div>
                </div>
         */

}


                    /*
                    <div class="top-bar">
				<a href="#" class="button">ADD NEW </a>
				<h1>Contents</h1>
				<div class="breadcrumbs"><a href="#">Homepage</a> / <a href="#">Contents</a></div>
			</div><br />
		  <div class="select-bar">
		    <label>
		    <input type="text" name="textfield" />
		    </label>
		    <label>
			<input type="submit" name="Submit" value="Search" />
			</label>
		  </div>
			<div class="table">
				<img src="img/admin/bg-th-left.gif" width="8" height="7" alt="" class="left" />
				<img src="img/admin/bg-th-right.gif" width="7" height="7" alt="" class="right" />
				<table class="listing" cellpadding="0" cellspacing="0">
					<tr>
						<th class="first" width="177">Header Here</th>
						<th>Header</th>
						<th>Head</th>
						<th>Header</th>
						<th>Header</th>
						<th>Head</th>
						<th>Header</th>
						<th class="last">Head</th>
					</tr>
					<tr>
						<td class="first style1">- Lorem Ipsum </td>
						<td><img src="img/admin/add-icon.gif" width="16" height="16" alt="" /></td>
						<td><img src="img/admin/hr.gif" width="16" height="16" alt="" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="" /></td>
						<td><img src="img/admin/edit-icon.gif" width="16" height="16" alt="" /></td>
						<td><img src="img/admin/login-icon.gif" width="16" height="16" alt="" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td class="last"><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
					</tr>
					<tr class="bg">
						<td class="first style2">- Lorem Ipsum </td>
						<td><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
						<td><img src="img/admin/hr.gif" width="16" height="16" alt="" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td><img src="img/admin/edit-icon.gif" width="16" height="16" alt="edit" /></td>
						<td><img src="img/admin/login-icon.gif" width="16" height="16" alt="login" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td class="last"><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
					</tr>
					<tr>
						<td class="first style3">- Lorem Ipsum </td>
						<td><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
						<td><img src="img/admin/hr.gif" width="16" height="16" alt="" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td><img src="img/admin/edit-icon.gif" width="16" height="16" alt="edit" /></td>
						<td><img src="img/admin/login-icon.gif" width="16" height="16" alt="login" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td class="last"><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
					</tr>
					<tr class="bg">
						<td class="first style1">- Lorem Ipsum </td>
						<td><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
						<td><img src="img/admin/hr.gif" width="16" height="16" alt="" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td><img src="img/admin/edit-icon.gif" width="16" height="16" alt="edit" /></td>
						<td><img src="img/admin/login-icon.gif" width="16" height="16" alt="login" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td class="last"><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
					</tr>
					<tr>
						<td class="first style2">- Lorem Ipsum </td>
						<td><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
						<td><img src="img/admin/hr.gif" width="16" height="16" alt="" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td><img src="img/admin/edit-icon.gif" width="16" height="16" alt="edit" /></td>
						<td><img src="img/admin/login-icon.gif" width="16" height="16" alt="login" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td class="last"><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
					</tr>
					<tr class="bg">
						<td class="first style3">- Lorem Ipsum </td>
						<td><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
						<td><img src="img/admin/hr.gif" width="16" height="16" alt="" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td><img src="img/admin/edit-icon.gif" width="16" height="16" alt="edit" /></td>
						<td><img src="img/admin/login-icon.gif" width="16" height="16" alt="login" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td class="last"><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
					</tr>
					<tr>
						<td class="first style4">- Lorem Ipsum </td>
						<td><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
						<td><img src="img/admin/hr.gif" width="16" height="16" alt="" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td><img src="img/admin/edit-icon.gif" width="16" height="16" alt="edit" /></td>
						<td><img src="img/admin/login-icon.gif" width="16" height="16" alt="login" /></td>
						<td><img src="img/admin/save-icon.gif" width="16" height="16" alt="save" /></td>
						<td class="last"><img src="img/admin/add-icon.gif" width="16" height="16" alt="add" /></td>
					</tr>
				</table>
				<div class="select">
					<strong>Other Pages: </strong>
					<select>
						<option>1</option>
					</select>
			  </div>
			</div>
		  <div class="table">
				<img src="img/admin/bg-th-left.gif" width="8" height="7" alt="" class="left" />
				<img src="img/admin/bg-th-right.gif" width="7" height="7" alt="" class="right" />
				<table class="listing form" cellpadding="0" cellspacing="0">
					<tr>
						<th class="full" colspan="2">Header Here</th>
					</tr>
					<tr>
						<td class="first" width="172"><strong>Lorem Ipsum</strong></td>
						<td class="last"><input type="text" class="text" /></td>
					</tr>
					<tr class="bg">
						<td class="first"><strong>Lorem Ipsum</strong></td>
						<td class="last"><input type="text" class="text" /></td>
					</tr>
					<tr>
						<td class="first""><strong>Lorem Ipsum</strong></td>
						<td class="last"><input type="text" class="text" /></td>
					</tr>
					<tr class="bg">
						<td class="first"><strong>Lorem Ipsum</strong></td>
						<td class="last"><input type="text" class="text" /></td>
					</tr>
				</table>
	        <p>&nbsp;</p>
              </div>*/