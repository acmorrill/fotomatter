<div class="large_container <?php /*no_td_as_block*/ ?>">
	<div class="table_border configure_table_list">
		<table class="list custom_ui">
			<tbody>
				<?php foreach ($sitePagesSitePageElements as $sitePagesSitePageElement): ?>
					<tr class="page_element_cont container_item" site_pages_site_page_element_id="<?php echo $sitePagesSitePageElement['SitePagesSitePageElement']['id']; ?>">
						<td class="first last">
							<div class="background">
								<div class="reorder_page_grabber reorder_grabber icon-position-01"></div>
							</div>
							<div class="page_element_delete add_button icon icon_close"><div class="content icon-close-01"></div></div>
							<div class="page_content_inner_cont">
								<!--<img class="abs_image_tl reorder_page_grabber" src="/img/admin/icons/white_arrange.png" />-->
								<!--<img class="abs_image_tr page_element_delete" src="/img/admin/icons/bw_simple_close_icon.png" />-->
								<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
								<?php echo $this->Element('page_elements/admin/'.$sitePagesSitePageElement['SitePageElement']['ref_name'], array( 'config' => $sitePagesSitePageElement['SitePagesSitePageElement']['config'], 'uuid' => $uuid )); ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

   