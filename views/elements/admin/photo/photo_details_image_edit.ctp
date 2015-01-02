<div class="generic_inner_container" data-step="1" data-intro="<?php echo __ ('Change all the photo details below. Such as title, subtitle, and so on.',true); ?>" data-position="top">
	<div class="generic_dark_cont fotomatter_form">
		<div class="input text">
			<?php $display_title = $this->Util->get_isset_or($this->data, array('Photo', 'display_title'), ''); ?>
			<?php $display_subtitle = $this->Util->get_isset_or($this->data, array('Photo', 'display_subtitle'), ''); ?>
			<input class="defaultText <?php if (empty($display_title)): ?> defaultTextActive<?php endif; ?>" title="Photo Title" name="data[Photo][display_title]" type="text" maxlength="64" value="<?php echo $display_title; ?>" id="PhotoDisplayTitle">
			<input class="defaultText <?php if (empty($display_subtitle)): ?> defaultTextActive<?php endif; ?>" title="Photo Subtitle" name="data[Photo][display_subtitle]" type="text" maxlength="128" value="<?php echo $display_subtitle; ?>" id="PhotoDisplaySubtitle">
		</div>
		
		<?php
			echo $this->Form->input('description');
			echo $this->Form->input('alt_text');
			echo $this->Form->input('date_taken', array(
				'dateFormat' => 'DMY',
				'minYear' => date('Y') - 100,
				'maxYear' => date('Y'),
			));
		?>
		
		<div class="input checkbox" data-step="2" data-intro="<?php echo __ ('Enabling the image will allow it to show up in galleries and tags. Disabling it will keep the photo on your site just no one will be able to see it.',true); ?>" data-position="top">
			<label for="PhotoEnabled">Enable Image</label>
			<input type="hidden" name="data[Photo][enabled]" id="PhotoEnabled_" value="0">
			<input type="checkbox" name="data[Photo][enabled]" value="1" id="PhotoEnabled" checked="checked">
		</div>
		
		<div class="input text tag_select" data-step="3" data-intro="<?php echo __ ("Add a tag to your photo or click on 'manage tags' to organize all the tags.",true); ?>" data-position="top">
			<label><?php echo __('Photo Tags', true); ?>&nbsp;&nbsp;(<a href="/admin/tags/manage_tags"><?php echo __('manage tags', true); ?></a>)</label>
			<select name="data[Photo][tag_ids][]" multiple="multiple" class="chzn-select" data-placeholder="Find Tags ...">
				<?php $tags = $this->Util->get_all_tags(); ?>
				<?php foreach ($tags as $tag): ?>
					<option value="<?php echo $tag['Tag']['id']; ?>" <?php if (in_array($tag['Tag']['id'], $photo_tag_ids)): ?>selected="selected"<?php endif; ?> ><?php echo $tag['Tag']['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		
		<?php echo $this->Form->input('Photo.photo_format_id'); ?>

	</div>
</div>
<div class="photo_details_save_button save_button" data-step="4" data-intro="<?php echo __ ('Save your changes.',true); ?>" data-position="top"><div class="content">Save</div></div>