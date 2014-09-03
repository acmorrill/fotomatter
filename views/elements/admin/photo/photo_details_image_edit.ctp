<div class="sub_submenu_inner_cont">
	<div class="sub_submenu_dark_cont fotomatter_form">

		<?php //if ($mode == 'edit'): ?>
		<?php //else: ?>
			<?php //echo $this->Form->create('Photo', array('url' => array('controller' => 'photos', 'action' => 'add'), 'enctype' => 'multipart/form-data')); ?>
		<?php //endif; ?>
			<div class="input text">
				<?php $display_title = $this->Util->get_isset_or($this->data, array('Photo', 'display_title'), ''); ?>
				<?php $display_subtitle = $this->Util->get_isset_or($this->data, array('Photo', 'display_subtitle'), ''); ?>
				<input class="defaultText <?php if (empty($display_title)): ?> defaultTextActive<?php endif; ?>" title="Photo Title" name="data[Photo][display_title]" type="text" maxlength="64" value="<?php echo $display_title; ?>" id="PhotoDisplayTitle">
				<input class="defaultText <?php if (empty($display_subtitle)): ?> defaultTextActive<?php endif; ?>" title="Photo Subtitle" name="data[Photo][display_subtitle]" type="text" maxlength="128" value="<?php echo $display_subtitle; ?>" id="PhotoDisplaySubtitle">
			</div>
		<?php
//			echo $this->Form->input('display_title', array(
//				'label' => false,
//			));
//			echo $this->Form->input('display_subtitle');
			//echo $this->Form->input('title');
			//echo $this->Form->input('shotDate');
			echo $this->Form->input('description');
			echo $this->Form->input('alt_text');
			echo $this->Form->input('date_taken', array(
				'dateFormat' => 'DMY',
				'minYear' => date('Y') - 100,
				'maxYear' => date('Y'),
			));
			?>
		<!--	<div class="input text">
					<label>Date Taken</label>
					<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#date_taken').datepicker({

							});
						});
					</script>
					<input id="date_taken" name="data[Photo][date_taken]" type="text" value="" />
				</div>-->
			<?php

			/*echo $this->Form->input('Photo.galleries', array(
				'type' => 'select',
				'multiple' => 'checkbox',
				'options' => array(
					'largeFormatColor' => 'largeFormatColor',
					'largeFormatBW' => 'largeFormatBW',
					'digitalIdeas' => 'digitalIdeas',
					'panoramics' => 'panoramics',
					'favorites' => 'favorites',
					'temples' => 'temples',
					'noPano' => 'noPano'
				),
				'selected' => $currGalleries
			));*/
			//echo $this->Form->input('tier');
			//echo $this->Form->label('Photo.enabled');
			?>
			<div class="input checkbox">
				<label for="PhotoEnabled">Enable Image</label>
				<input type="hidden" name="data[Photo][enabled]" id="PhotoEnabled_" value="0">
				<input type="checkbox" name="data[Photo][enabled]" value="1" id="PhotoEnabled" checked="checked">
			</div>
			<?php 
//			echo $this->Form->input('enabled');
			/*echo $this->Form->input('Photo.format', array(
				'type' => 'select',
				'options' => array(
					'landscape' => 'landscape',
					'portrait' => 'portrait',
					'square' => 'square',
					'panoramic' => 'panoramic'
				),
				'selected' => $this->data['Photo']['format']
			));*/
			/*echo $this->Form->input('Photo.availSizes', array(
				'type' => 'select',
				'multiple' => 'checkbox',
				'options' => array(
					'5' => '5',
					'8' => '8',
					'10' => '10',
					'11' => '11',
					'16' => '16',
					'20' => '20',
					'22' => '22',
					'24' => '24',
					'26' => '26',
					'29' => '29',
					'30' => '30',
					'35' => '35',
					'40' => '40',
					'44' => '44',
					'48' => '48'
				),
				'selected' => $currSizes
			));
			echo $this->Form->input('pricePerFoot');
			echo $this->Form->label('Photo.thumbImage');
			echo $this->Form->file('Photo.thumbImage');
			echo '<br/>';
			echo $this->Form->label('Photo.largeImage');
			echo $this->Form->file('Photo.largeImage');
			echo '<br/>';
			echo $this->Form->label('Photo.extraLargeImage');
			echo $this->Form->file('Photo.extraLargeImage');
			* 
			*/
			echo $this->Form->input('Photo.photo_format_id');
		?>
			<div class="input text tag_select">
				<label><?php echo __('Photo Tags', true); ?>&nbsp;&nbsp;(<a href="/admin/tags/manage_tags"><?php echo __('manage tags', true); ?></a>)</label>
				<select name="data[Photo][tag_ids][]" multiple="multiple" class="chzn-select" data-placeholder="Find Tags ...">
					<?php $tags = $this->Util->get_all_tags(); ?>
					<?php foreach ($tags as $tag): ?>
						<option value="<?php echo $tag['Tag']['id']; ?>" <?php if (in_array($tag['Tag']['id'], $photo_tag_ids)): ?>selected="selected"<?php endif; ?> ><?php echo $tag['Tag']['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

	</div>
</div>
<div class="photo_details_save_button save_button"><div class="content">Save</div></div>