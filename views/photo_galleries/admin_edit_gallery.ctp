<?php echo $session->flash(); ?>
<?php echo $this->Element('/admin/get_help_button'); ?>
			<div style="clear: both;"></div> 
<br/>


<?php 
	echo $this->Form->create('PhotoGallery');
	echo $this->Form->input('display_name');
	echo $this->Form->input('description');
	echo $this->Form->end('Save'); 
?>


<?php //debug($photo_gallery); ?>