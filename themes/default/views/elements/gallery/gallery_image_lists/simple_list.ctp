<?php $imgSrc = $this->Photo->get_photo_path($curr_photo['Photo']['id'], $height, $width, .5, true); ?>
<table>
        <tr>
            <td>
                <img src="<?php echo $imgSrc['url']; ?>" <?php echo $imgSrc['tag_attributes']; ?> alt="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
            </td>
        </tr> 
</table>
 <?php foreach($photos as $f_photo): ?>
<?php $img_src = $this->Photo->get_photo_path($f_photo['Photo']['id'], $height, $width, .5, true); ?> 
<img photo_id="<?php echo $f_photo['Photo']['id']; ?>" src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> />
<?php endforeach; ?>
