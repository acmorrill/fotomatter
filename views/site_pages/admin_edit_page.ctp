<?php echo $this->Session->flash(); ?>
<br/>


<?php 
	echo $this->Form->create('SitePage');
	echo $this->Form->input('title');
	if (isset($this->data['SitePage']['type']) && $this->data['SitePage']['type'] == 'external') {
		echo $this->Form->input('external_link');
	}
	echo $this->Form->end('Save'); 
?>
