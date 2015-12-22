<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// theme hidden settings is for theme settings that are per theme and are user defined/controlled
class ThemePrebuildCacheSize extends AppModel {
	public $name = 'ThemePrebuildCacheSize';
	public $belongsTo = array('Theme');
	
	
	public function find_matching_photo_cache_ids($theme_id = null) {
		$this->PhotoCache = ClassRegistry::init('PhotoCache');
		$conditions = array();
		if (!empty($theme_id)) {
			$conditions = array(
				'ThemePrebuildCacheSize.theme_id' => $theme_id
			);
		}
		$prebuild_cache_sizes = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => false,
		));
		$block_photo_cache_ids = array();
		foreach ($prebuild_cache_sizes as $prebuild_cache_size) {
			$block_photo_caches = $this->PhotoCache->find('all', array(
				'conditions' => array(
					'PhotoCache.max_width' => $prebuild_cache_size['ThemePrebuildCacheSize']['max_width'],
					'PhotoCache.max_height' => $prebuild_cache_size['ThemePrebuildCacheSize']['max_height'],
					'PhotoCache.unsharp_amount' => $prebuild_cache_size['ThemePrebuildCacheSize']['unsharp'],
					'PhotoCache.crop' => $prebuild_cache_size['ThemePrebuildCacheSize']['crop'],
				),
				'contain' => false
			));
			$block_photo_cache_ids += Set::combine($block_photo_caches, '{n}.PhotoCache.id', '{n}.PhotoCache.id');
		}
		
		return $block_photo_cache_ids;
	}
	
	
	public function get_prebuild_cache_sizes_current_theme() {
		$theme_id = $this->Theme->get_current_theme_id();
		$all_data_apc_key = "all_prebuild_cache_sizes_$theme_id";
		
		if (apc_exists($all_data_apc_key)) {
			return apc_fetch($all_data_apc_key);
		}
		
		$data = $this->find('all', array(
			'conditions' => array(
				'ThemePrebuildCacheSize.theme_id' => $theme_id
			),
			'contain' => false
		));
		
		apc_add($all_data_apc_key, $data, 604800); // 1 week
		
		return $data;
	}
	
	public function get_prebuild_cache_sizes_by_keys() {
		$data = $this->get_prebuild_cache_sizes_current_theme();
		
		$keyed_data = array();
		foreach ($data as $curr_data) {
			$unsharp = 'null';
			if (!empty($curr_data['ThemePrebuildCacheSize']['unsharp'])) {
				$unsharp = $curr_data['ThemePrebuildCacheSize']['unsharp'];
			}
			$new_key = "{$curr_data['ThemePrebuildCacheSize']['max_width']}_{$curr_data['ThemePrebuildCacheSize']['max_height']}_{$curr_data['ThemePrebuildCacheSize']['crop']}_{$unsharp}";
			$keyed_data[$new_key] = $curr_data['ThemePrebuildCacheSize'];
		}
		
		return $keyed_data;
	}
	
	
	public function increment_used_on_upload($id) {
		$query = "
			UPDATE theme_prebuild_cache_sizes
			SET used_on_upload = used_on_upload + 1
			WHERE id = $id
		";
		
		return $this->query($query);
	}
	
	
	public function increment_used_in_theme($width, $height, $crop, $unsharp) {
		if (empty($unsharp)) {
			$unsharp = 'null';
		}
		
		$key = "{$width}_{$height}_{$crop}_{$unsharp}";
		$data = $this->get_prebuild_cache_sizes_by_keys();
		if (isset($data[$key])) {
			$id = $data[$key]['id'];
			$query = "
				UPDATE theme_prebuild_cache_sizes
				SET used_in_theme = used_in_theme + 1
				WHERE id = $id
			";

			return $this->query($query);
		}
		
		return true;
	}
	
	
	
}