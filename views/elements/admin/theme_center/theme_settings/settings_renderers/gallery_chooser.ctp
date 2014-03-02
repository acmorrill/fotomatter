<?php $uuid = $this->Util->uuid(); ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#<?php echo $uuid; ?> select').change(function() {

			var setting_name = '<?php echo $setting_name; ?>';
			var setting_value = jQuery(this).val();;


			save_theme_setting(setting_name, setting_value, 
				function() {
//					console.log ("success");
				}, 
				function() {
//					console.log ("error");
				}
			);
		});
	});
</script>

<?php $galleries = $this->Gallery->get_all_galleries(); ?>
<div id="<?php echo $uuid; ?>" class="theme_setting_container">
	<label class="drop_down"><?php echo $curr_setting['display_name']; ?></label>
	<div class="theme_setting_inputs_container">
		<div>
			<?php if (!empty($galleries)): ?>
				<select>
					<?php foreach ($galleries as $gallery): ?>
						<option value="<?php echo $gallery['PhotoGallery']['id']; ?>" <?php if ($curr_setting['current_value'] == $gallery['PhotoGallery']['id']): ?>selected="selected"<?php endif; ?>><?php echo $gallery['PhotoGallery']['display_name']; ?></option>
					<?php endforeach; ?>
				</select>
			<?php else: ?>
				<select>
					<option value="0">No Galleries</option>
				</select>
			<?php endif; ?>
		</div>	
	</div>
	<div class="theme_setting_description drop_down_description">
		<?php echo $curr_setting['description']; ?>
	</div>
</div>
<div style="clear: both"></div>

<?php // debug($setting_name); ?>
<?php // debug($curr_setting); ?>