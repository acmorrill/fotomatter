<?php foreach ($sitePagesSitePageElements as $sitePagesSitePageElement): ?>
	<div class="page_element_cont" site_pages_site_page_element_id="<?php echo $sitePagesSitePageElement['SitePagesSitePageElement']['id']; ?>">
		<img class="abs_image_tr reorder_page_grabber" src="/img/admin/icons/white_arrange.png" />
		<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
		<?php echo $this->Element('page_elements/admin/'.$sitePagesSitePageElement['SitePageElement']['ref_name'], array( 'config' => $sitePagesSitePageElement['SitePagesSitePageElement']['config'], 'uuid' => $uuid )); ?>
	</div>
<?php endforeach; ?>