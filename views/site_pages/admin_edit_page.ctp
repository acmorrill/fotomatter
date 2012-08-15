<?php echo $this->Session->flash(); ?>
<br/>


<?php 
	echo $this->Form->create('SitePage');
	echo $this->Form->input('title');
	echo $this->Form->end('Save'); 
?>
