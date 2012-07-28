<?php $all_gallery_choices = $this->Gallery->get_all_galleries(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$(".gallery-chooser table tr").click(function() {
				if ($(this).hasClass('selected')) {
					$(this).removeClass('selected');
				} else {
					$(this).addClass('selected');
				}
		});
	});
</script>
<div class = "gallery-chooser">
	<table class="list">
		<tbody>
			<?php foreach ($all_gallery_choices as $gallery): ?>
			<tr>
				<td class="photo"><div><img style="width:40px;height:40px;" src="/img/photo_default/photo_processing.jpg" /></div></td>
				<td class="name"><?php echo $gallery['PhotoGallery']['display_name']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
</div>