<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Element('admin/meta_and_tags/title', array('layout_default' => 'Login')); // can also $title_for_layout in the controller ?>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body id="login_page">
<div id="main" class='no_subnav login_page'>
	<div id="header">
		<?php echo $this->Element('admin/logo'); ?>
		<div id='login_tagline'><?php echo __('Photo Management Made Simple', true); ?></div>
	</div>
	<div id="middle">
		<div id='login_forms_cont'>
			<table>
				<tbody>
					<tr>
						<td>
							<?php echo $content_for_layout; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php echo $this->Element('admin/global_footer'); ?>
</div>
<?php echo $this->Element('admin/global_after_footer'); ?>
</body>
</html>