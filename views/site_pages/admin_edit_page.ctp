

<?php 
	echo $this->Form->create('SitePage');
	echo $this->Form->input('title');
	if (isset($this->data['SitePage']['type']) && $this->data['SitePage']['type'] == 'external') {
		echo $this->Form->input('external_link');
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