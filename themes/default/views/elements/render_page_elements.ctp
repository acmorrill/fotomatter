<?php $site_page_elements = $this->Page->get_site_pages_site_page_elements($site_page_id); ?>
<?php $count = 1; foreach ($site_page_elements as $site_page_element): ?>
	<?php $count_class = $this->Util->get_count_class($count, count($site_page_elements)); ?>
	<?php echo $this->Element('page_elements/'.$site_page_element['SitePageElement']['ref_name'], array( 'config' => $site_page_element['SitePagesSitePageElement']['config'], 'hide_debug' => true, 'classes' => $count_class )); ?>
<?php $count++; endforeach; ?>

