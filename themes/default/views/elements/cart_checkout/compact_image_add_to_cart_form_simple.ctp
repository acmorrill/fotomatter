<?php if (!empty($current_on_off_features['basic_shopping_cart'])): ?>
	<?php
            if (empty($photo_sellable_prints)) {
                    $photo_sellable_prints = $this->Photo->get_enabled_photo_sellable_prints($photo_id);
            }
            if (empty($beforeHtml)) {
                    $beforeHtml = '';
            }
	?>
	<div class="compact_add_to_cart_buttons_cont">
		<?php if (empty($photo_sellable_prints)): ?>
			<!-- <?php __('The add to cart buttons have not been fully setup'); ?> -->
		<?php else: ?>
			<?php echo $beforeHtml; ?>
			<div class="compact_add_to_cart_buttons_inner_cont">
				<?php /*<h1 id='print_types_heading'><?php echo $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'global_photo_page_add_to_cart_text') ?></h1>*/ ?>
				<?php $total_photo_sellable_prints = count($photo_sellable_prints); ?>
				<select class="print_type_chooser">
					<?php $count = 1; foreach ($photo_sellable_prints as $print_type_name => $print_type_sizes): ?>
						<?php 
							$selected_str = '';
							if ($count == 1) { $selected_str = 'selected="selected"'; }
						?>
						<option value="<?php echo $print_type_sizes['print_type_id']; ?>" <?php echo $selected_str; ?>><?php echo $print_type_name; ?></option>
					<?php $count++; endforeach; ?>
				</select>
				<?php $count = 1; foreach ($photo_sellable_prints as $print_type_name => $print_type_sizes): ?>
					<?php 
						$classes = array('print_type_data');
						if ($count == 1) { $classes[] = 'first'; }
						if ($total_photo_sellable_prints == $count) { $classes[] = 'last'; }
					?>
					<div class="<?php echo implode(' ', $classes); ?>" data-print_type_id="<?php echo $print_type_sizes['print_type_id']; ?>" >
						<form action="/ecommerces/add_to_cart/" method="post">
							<select name='data[qty]'>
								<?php for ($i = 1; $i < 1000; $i++): ?>
									<option value='<?php echo $i; ?>'><?php echo $i; ?></option>
								<?php endfor; ?>
							</select>
							<span>x</span>
							<select name="data[Photo][short_side_inches]">
								<?php foreach ($print_type_sizes['items'] as $print_type_size): ?>
									<option value="<?php echo $print_type_size['short_side_inches']; ?>"><?php echo $print_type_size['short_side_inches']; ?> x <?php echo $print_type_size['long_side_feet_inches']; ?> --- <?php echo $this->Number->currency($print_type_size['price']); ?></option>  
								<?php endforeach; ?>
							</select>
							<input type='hidden' name='data[redirect_url]' value='<?php echo isset($redirect_url)? $redirect_url: ''; ?>' />
							<input type="hidden" name="data[Photo][id]" value="<?php echo $photo_id; ?>" />
							<input type="hidden" name="data[PhotoPrintType][id]" value="<?php echo $print_type_sizes['print_type_id']; ?>" />
							<div class="submit_button_cont">
								<div class="frontend_form_submit_button submit_button"><span class='content'><?php echo __('Add to Cart', true); ?></span><span class='extra'></span></div>
							</div>
						</form>
					</div>
				<?php $count++; endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>


