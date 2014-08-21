<script type="text/javascript" src="/js/jquery_price_format/jquery.price_format.min.js"></script>

<div id="image_edit_container">
	<h1><?php echo __('Photo Details', true); ?>
		<?php echo $this->Element('/admin/get_help_button'); ?>
	</h1>
	
	
	<div class="actual_image_container">
		<div class="actual_image_inner_container">
			<?php $img_path = $this->Photo->get_photo_path($this->data['Photo']['id'], 247, 400); ?>
			<img src="<?php echo $img_path; ?>" />                                                                                                    
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#upload_replacement_photo_button').click(function() {
					jQuery(this).parent().find('.file input').click();
				});
			});
		</script>
		<div id="upload_replacement_photo_button" class="custom_ui">
			<div class="add_button">
				<div class="content">Upload Photo</div>
				<div class="plus_icon_lines"><div class="one"></div><div class="two"></div></div>
			</div>
		</div>
		<?php echo $this->Form->input('Photo.cdn-filename', array('type' => 'file')); ?>
	</div>
	
	<div style="clear: both;"></div>
	<?php echo $this->Form->create('Photo', array('enctype' => 'multipart/form-data')); ?>
	<?php
		echo $this->Element('admin/sub_submenu', array(
			'tabs' => array(
				'Image Details' => 'admin/photo/photo_details_image_edit',
				'Image Pricing' => 'admin/photo/photo_details_ecommerce',
			),
//			'css' => 'margin-top: -26px;',
//			'starting_tab' => $starting_tab,
		));
	?>
	<?php echo $this->Form->end('Save'); ?>


	<div class="clear"></div>
	
	
	
</div>

<?php ob_start(); ?>
<ol>
	<li>This page is where edit the settings for an individual photo</li>
	<li><a href="/img/admin_screenshots/edit_photo.jpg" target="_blank">Screenshot</a></li>
	<li>Things to remember
		<ol>
			<li>This page needs a flash message</li>
			<li>The choose available sizes section needs styling
				<ol>
					<li>The locked vs unlocked status needs style (locked the whole row is grayed out - see screenshot)</li>
				</ol>
			</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>