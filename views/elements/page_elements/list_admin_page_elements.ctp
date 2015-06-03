<?php $count = 1; $count_2 = 1; $step_count = 2; foreach ($sitePagesSitePageElements as $sitePagesSitePageElement): ?>
	<?php 
		$grabber_help = '';
		$x_help = '';
		if ($count === 1) {
			$grabber_help = 'data-step="' . $step_count++ . '" data-intro="' . __("Once you have added more than one page element, you can arrange them by clicking and dragging the arrows to move the section up or down.", true) . '" data-position="right"';
			$x_help = 'data-step="' . $step_count++ . '" data-intro="' . __("If you would like to remove a page element, simply click on the X to delete. All changes will be saved automatically.", true) . '" data-position="left"';
		}
	
	
		$element_help = '';
		if ($sitePagesSitePageElement['SitePageElement']['ref_name'] == 'para_header_image') {
			if ($count_2 === 1) {
				$para_help = 'data-step="' . $step_count++ . '" data-intro="' . __("Upload a photo, choose placement on the right or left of the text and select how large you would like the image to appear on the page. ", true) . '" data-position="right"';
			}
			$count_2++;
		}
	?>
	<tr class="page_element_cont container_item" site_pages_site_page_element_id="<?php echo $sitePagesSitePageElement['SitePagesSitePageElement']['id']; ?>">
		<td class="first last">
			<div class="background">
				<div class="reorder_page_grabber reorder_grabber icon-position-01" <?php echo $grabber_help; ?>></div>
			</div>
			<div class="page_element_delete add_button icon icon_close" <?php echo $x_help; ?>><div class="content icon-close-01"></div></div>
			<div class="page_content_inner_cont">
				<!--<img class="abs_image_tl reorder_page_grabber" src="/img/admin/icons/white_arrange.png" />-->
				<!--<img class="abs_image_tr page_element_delete" src="/img/admin/icons/bw_simple_close_icon.png" />-->
				<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
				<?php echo $this->Element('page_elements/admin/'.$sitePagesSitePageElement['SitePageElement']['ref_name'], array( 'config' => $sitePagesSitePageElement['SitePagesSitePageElement']['config'], 'uuid' => $uuid )); ?>
			</div>
		</td>
	</tr>
<?php $count++; endforeach; ?>

   