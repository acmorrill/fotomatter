<?php $all_gallery_choices = $this->Gallery->get_all_galleries(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$(".gallery-chooser table tr").click(function() {
				if ($(this).hasClass('selected')) {
					$(this).removeClass('selected');
					$("input[gallery_id="+$(this).attr("gallery_id")+"]").val(0);
				} else {
					$(this).addClass('selected');
					$("input[gallery_id="+$(this).attr("gallery_id")+"]").val(1);
				}
		});
	});
</script>
<div class='gallery-chooser-cont'>
    <div class='basic_page_heading heading'>
            <div class='title'><?php __('Galleries'); ?></div>
            <p><?php __('Choose which galleries your photos should start out in.'); ?></p>
    </div>
    <?php if (empty($all_gallery_choices)): ?>
        <div class="no_galleries_added rounded-corners">
        <p><?php __('You have not added any galleries yet.'); ?></p>
        </div>
    <?php else: ?>
	<div class = "gallery-chooser">
		<table class="list">
			<tbody>
				<?php foreach ($all_gallery_choices as $gallery): ?>
				<input gallery_id="<?php echo $gallery['PhotoGallery']['id']; ?>" type="hidden" name="data[GalleryPhoto][<?php echo $gallery['PhotoGallery']['id']; ?>]" value="0" />
				<tr gallery_id="<?php echo $gallery['PhotoGallery']['id']; ?>">
					<td class="photo"><div><img style="width:40px;height:40px;" src="/img/photo_default/photo_processing.jpg" /></div></td>
					<td class="name"><?php echo $gallery['PhotoGallery']['display_name']; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
    <?php endif; ?>
</div>
