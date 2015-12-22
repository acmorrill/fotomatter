<?php
class PhotoPrebuildCacheSize extends AppModel {
	public $name = 'PhotoPrebuildCacheSize';

	public function find_matching_photo_cache_ids() {
		$this->PhotoCache = ClassRegistry::init('PhotoCache');
		$prebuild_cache_sizes = $this->find('all', array(
			'contain' => false,
		));
		$block_photo_cache_ids = array();
		foreach ($prebuild_cache_sizes as $prebuild_cache_size) {
			$block_photo_caches = $this->PhotoCache->find('all', array(
				'conditions' => array(
					'PhotoCache.max_width' => $prebuild_cache_size['PhotoPrebuildCacheSize']['max_width'],
					'PhotoCache.max_height' => $prebuild_cache_size['PhotoPrebuildCacheSize']['max_height'],
					'PhotoCache.unsharp_amount' => $prebuild_cache_size['PhotoPrebuildCacheSize']['unsharp'],
					'PhotoCache.crop' => $prebuild_cache_size['PhotoPrebuildCacheSize']['crop'],
				),
				'contain' => false
			));
			$block_photo_cache_ids += Set::combine($block_photo_caches, '{n}.PhotoCache.id', '{n}.PhotoCache.id');
		}
		
		return $block_photo_cache_ids;
	}
	
}
