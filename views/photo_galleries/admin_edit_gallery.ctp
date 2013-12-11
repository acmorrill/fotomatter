<?php echo $session->flash(); ?>
<br/>


<?php 
	echo $this->Form->create('PhotoGallery');
	echo $this->Form->input('display_name');
	echo $this->Form->input('description');
	echo $this->Form->end('Save'); 
?>


<?php //debug($photo_gallery); ?>