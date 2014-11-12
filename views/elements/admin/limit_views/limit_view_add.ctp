<div class="buy_now_box">
	<div class="page_content_header">
		<h2><?php echo $feature_name; ?> is available month to month. Use it when you need it.</h2>
	</div>
	<?php $price = isset($current_feature_prices[$feature_ref_name]) ? $this->Number->currency($current_feature_prices[$feature_ref_name]) : ''; ?>
	<div class="buy_add_container custom_ui">
		<p>ADD IT TODAY FOR JUST &nbsp;<span><?php echo $price; ?></span> / MO.</p>
		<div id="add_feature_button" class="add_button highlight bigger add_feature_button" type="submit" ref_feature_name="<?php echo $feature_ref_name; ?>"><div class="content"><?php echo __("Add $feature_name"); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div></div>
		<br />
		<?php if (!empty($feature_reasons)): ?>
			<ol>
				<?php $count = 1; foreach ($feature_reasons as $feature_reason): ?>
					<li <?php if ($count === 1): ?>class='first'<?php endif; ?>>
						<span><?php echo $feature_reason; ?></span>
					</li>
				<?php $count++; endforeach; ?>
			</ol>
		<?php endif; ?>
	</div>
	<img src="<?php echo $feature_image_src; ?>" alt="" />
</div>