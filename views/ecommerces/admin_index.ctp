this is the sell landing page

<?php ob_start(); ?>
This page will go away
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>