<?php 
	$subnav = array(); 

	$subnav['title'] = array(
		'name' => $this->data['SitePage']['title'],
		'url' => "/admin/site_pages/edit_page/{$this->data['SitePage']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Page Settings', true),
		'url' => "/admin/site_pages/edit_page/{$this->data['SitePage']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Configure Page', true),
		'url' => "/admin/site_pages/configure_page/{$this->data['SitePage']['id']}/",
		'selected' => true
	);
		
	echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
?>

<?php echo $session->flash(); ?>
<br/>

<style type="text/css">
	#configure_page_cont .page_elements_cont {
		float: left;
		width: 200px;
		min-height: 500px;
		margin-right: 30px;
	}
	#configure_page_cont .page_content_cont {
		float: left;
		width: 500px;
		min-height: 500px;
	}
</style>


<div id="configure_page_cont" class="outline clear">
	<div class="page_elements_cont outline">
		
	</div>
	<div class="page_content_cont outline">
		
	</div>
	<div class="clear">
		
	</div>
</div>
