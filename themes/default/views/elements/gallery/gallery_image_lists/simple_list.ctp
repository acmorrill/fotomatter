<div id="mainImage">
        <?php $img_src = $this->Photo->get_photo_path($curr_photo['Photo']['id'], 700, 700, .4, true); ?>
        <img src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> alt="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
        <?php $prev_image_web_path = $this->Photo->get_prev_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
        <a class="photo_page_nav prev_image arrow">
                <img onmouseover="this.src='/images/misc/arrowLeftRed.png';" onmouseout="this.src='/images/misc/arrowLeft.png';" src="/images/misc/arrowLeftRed.png" />
        </a>
        <?php $next_image_web_path = $this->Photo->get_next_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
        <a class="photo_page_nav next_image arrow">
                <img onmouseover="this.src='/images/misc/arrowRightRed.png';" onmouseout="this.src='/images/misc/arrowRight.png';" src="/images/misc/arrowRightRed.png" />
        </a>
</div>
















<!--I moved this out to test other stuff
<?php $imgSrc = $this->Photo->get_photo_path($curr_photo['Photo']['id'], $height, $width, .5, true); ?>
<table>
        <tr>
            <td>
                <img src="<?php echo $imgSrc['url']; ?>" <?php echo $imgSrc['tag_attributes']; ?> alt="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
            </td>
        </tr> 
</table>
 <?php foreach($photos as $f_photo): ?>
<?php $img_src = $this->Photo->get_photo_path($f_photo['Photo']['id'], $height, $width, .5, true); ?> -->
<!--<img photo_id="<?php echo $f_photo['Photo']['id']; ?>" src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> />
<?php endforeach; ?>-->
