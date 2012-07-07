<?php foreach ($sitePagesSitePageElements as $sitePagesSitePageElement): ?>
	<div class="page_element_cont rounded-corners-small no-bottom-rounded" site_pages_site_page_element_id="<?php echo $sitePagesSitePageElement['SitePagesSitePageElement']['id']; ?>">
		<img class="abs_image_tr reorder_page_grabber" src="/img/admin/icons/white_arrange.png" />
		<?php echo $this->Element('page_elements/admin/'.$sitePagesSitePageElement['SitePageElement']['ref_name'], array( 'config' => $sitePagesSitePageElement['SitePagesSitePageElement']['config'] )); ?>
	</div>
<?php endforeach; ?>