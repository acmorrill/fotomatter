<?php //$this->ThemeLogo->clear_expired_logo_files(); ?>
<?php //$this->ThemeLogo->delete_all_theme_base_logos(); ?>

<div class="logo_upload_cont" style="margin-bottom: 50px;">
	<?php $theme_base_logo_path = $this->ThemeLogo->get_base_logo_web_path(); ?>
	<img src="<?php echo $theme_base_logo_path; ?>" alt="" />
</div>
