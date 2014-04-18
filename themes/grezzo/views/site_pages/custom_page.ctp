
<?php $site_page_elements = $this->Page->get_site_pages_site_page_elements($site_page_id); ?>
<?php foreach ($site_page_elements as $site_page_element): ?>
	<?php echo $this->Element('page_elements/'.$site_page_element['SitePageElement']['ref_name'], array( 'config' => $site_page_element['SitePagesSitePageElement']['config'], 'hide_debug' => true )); ?>
<?php endforeach; ?>
<?php echo $this->Element('grezzo_includes'); ?>
<?php echo $this->Element('theme_global_includes'); ?>