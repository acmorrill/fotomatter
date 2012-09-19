<?php //$this->ThemeLogo->clear_expired_logo_files(); ?>
<?php //$this->ThemeLogo->delete_all_theme_base_logos(); ?>


<?php $theme_base_logo_path = $this->ThemeLogo->get_base_logo_web_path(); ?>
<?php $theme_cache_logo_path = $this->ThemeLogo->get_logo_cache_size_path(200, 200); ?>
<img src="<?php echo $theme_base_logo_path; ?>" />
<img src="<?php echo $theme_cache_logo_path; ?>" />