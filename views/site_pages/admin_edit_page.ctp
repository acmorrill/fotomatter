<?php echo $this->Element('/admin/get_help_button'); ?>
			<div style="clear: both;"></div> 

<?php 
	echo $this->Form->create('SitePage');
	echo $this->Form->input('title');
	if (isset($this->data['SitePage']['type']) && $this->data['SitePage']['type'] == 'external') {
		echo $this->Form->input('external_link');
	}
	if (isset($this->data['SitePage']['type']) && $this->data['SitePage']['type'] == 'contact_us') {
		echo $this->Form->input('contact_header');
		echo $this->Form->input('contact_message');
	}
	echo $this->Form->end('Save'); 
?>


<?php ob_start(); ?>
<ol>
	<li>This page is where you can edit a page title (and potentially other settings later) :)</li>
	<li>Things to remember
		<ol>
			<li>This page needs a flash message</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>