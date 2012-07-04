<?php
class SitePagesSitePageElement extends AppModel { 
	public $name = 'SitePagesSitePageElement'; 
	public $belongsTo = array(
		'SitePage',
		'SitePageElement'
	);
	public $actsAs = array(
		'Ordered' => array(
			'field' => 'page_element_order',
			'foreign_key' => 'site_page_id'
		),
		'Serialize' => array(
			'fields' => array(
				'config'
			)
		)
	);
	 
}
