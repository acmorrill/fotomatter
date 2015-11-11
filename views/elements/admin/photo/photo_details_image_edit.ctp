<div class="generic_inner_container">
	<div class="generic_dark_cont fotomatter_form">
		<div class="input text" data-step="1" data-intro="<?php echo __('Add a photo title and subtitle here or leave blank and no titles will appear under your photo.', true); ?>" data-position="top">
			<?php $display_title = $this->Util->get_isset_or($this->data, array('Photo', 'display_title'), ''); ?>
			<?php $display_subtitle = $this->Util->get_isset_or($this->data, array('Photo', 'display_subtitle'), ''); ?>
			<input class="defaultText <?php if (empty($display_title)): ?> defaultTextActive<?php endif; ?>" title="Photo Title" name="data[Photo][display_title]" type="text" maxlength="64" value="<?php echo $display_title; ?>" id="PhotoDisplayTitle">
			<input class="defaultText <?php if (empty($display_subtitle)): ?> defaultTextActive<?php endif; ?>" title="Photo Subtitle" name="data[Photo][display_subtitle]" type="text" maxlength="128" value="<?php echo $display_subtitle; ?>" id="PhotoDisplaySubtitle">
		</div>
		
		<?php echo $this->Form->input('description'); ?>
		<div class="input text" data-step="2" data-intro="<?php echo __('This is the text that will appear when someone hovers over your image with their cursor.', true); ?>" data-position="top">
			<?php
				echo $this->Form->input('alt_text', array(
					'label' => 'Hover Text',
					'div' => false,
				));
			?>
		</div>
		<?php
			echo $this->Form->input('use_date_taken', array(
				'type' => 'checkbox',
				'format' => array('before', 'label', 'input', 'between', 'after', 'error' ),
				'label' => 'Display Date Taken?'
			));
		?>
		<div class="input date" data-step="3" data-intro="<?php echo __('Enter the date the photo was taken. This will only display if &ldquo;Display Date Taken&rdquo; is selected above.', true); ?>" data-position="top">
			<?php
				echo $this->Form->input('date_taken', array(
					'dateFormat' => 'DMY',
					'minYear' => date('Y') - 100,
					'maxYear' => date('Y'),
					'div' => false,
				));
			?>
		</div>
		<div class="input date" data-step="4" data-intro="<?php echo __('This box is selected by default. Disabled images won\'t show on your website or in galleries.', true); ?>" data-position="top">
			<?php
				echo $this->Form->input('enabled', array(
					'type' => 'checkbox',
					'format' => array('before', 'label', 'input', 'between', 'after', 'error' ),
					'label' => 'Photo Enabled?',
					'div' => false,
				));
			?>
		</div>
		<div class="input text tag_select" data-step="5" data-intro="<?php echo __('Tag this photo with tags you have already set up in &ldquo;Manage Tags&rdquo; under &ldquo;Site Settings&rdquo; at the top of the page. Tagged images can be referenced by tag in &ldquo;Smart Galleries&rdquo;.', true); ?>" data-position="top">
			<label><?php echo __('Photo Tags', true); ?>&nbsp;&nbsp;(<a href="/admin/tags/manage_tags"><?php echo __('manage tags', true); ?></a>)</label>
			<select name="data[Photo][tag_ids][]" multiple="multiple" class="chzn-select" data-placeholder="Find Tags ...">
				<?php $tags = $this->Util->get_all_tags(); ?>
				<?php foreach ($tags as $tag): ?>
					<option value="<?php echo $tag['Tag']['id']; ?>" <?php if (in_array($tag['Tag']['id'], $photo_tag_ids)): ?>selected="selected"<?php endif; ?> ><?php echo $tag['Tag']['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="input select" data-step="6" data-intro="<?php echo __('Choose the appropriate orientation of the image here so that you can search for and arrange your photos by orientation (in Galleries section), if desired. The correct setting is set by default and usually shouldn\'t need to be changed.', true); ?>" data-position="top">
			<?php 
				echo $this->Form->input('Photo.photo_format_id', array(
					'div' => false,
					'label' => 'Photo Orientation'
				)); 
			?>
		</div>

	</div>
</div>
<div class="photo_details_save_button save_button" data-step="8" data-intro="<?php echo __('Don’t forget to save your photo details.', true); ?>" data-position="top"><div class="content">Save</div></div>