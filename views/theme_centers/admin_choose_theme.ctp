<?php echo $this->Element('admin/theme_center/choose_theme'); ?>

<?php ob_start(); ?>
<ol>
	<li>This page will need a a flash message possibly</li>
	<li>When you change current theme - get flash message that says your theme changed</li>
	<li>Do a modal popup for zoom to theme</li>
	<li>Current Theme should be highlighted somehow</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>