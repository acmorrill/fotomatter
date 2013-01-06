<?php $img_path = $this->Photo->get_photo_path($this->data['Photo']['id'], 350, 350); ?>
<img src="<?php echo $img_path; ?>" />


<?php echo $session->flash(); ?>
<br/>

<?php /*<script type="text/javascript">
     jQuery(document).ready(function() {
          jQuery('#OldPhotoEditOldphotoForm').validate({
               rules: {
                    firstname: "required",
                    lastname: "required",
                    username: {
                         required: true,
                         minlength: 2,
                         remote: "users.php"
                    },
                    password: {
                         required: true,
                         minlength: 5
                    },
                    password_confirm: {
                         required: true,
                         minlength: 5,
                         equalTo: "#password"
                    },
                    email: {
                         required: true,
                         email: true,
                         remote: "emails.php"
                    },
                    dateformat: "required",
                    terms: "required"
               },
               messages: {
                    firstname: "Enter your firstname",
                    lastname: "Enter your lastname",
                    username: {
                    required: "Enter a username",
                    minlength: jQuery.format("Enter at least {0} characters"),
                    remote: jQuery.format("{0} is already in use")
                    },
                    password: {
                         required: "Provide a password",
                         rangelength: jQuery.format("Enter at least {0} characters")
                    },
                    password_confirm: {
                         required: "Repeat your password",
                         minlength: jQuery.format("Enter at least {0} characters"),
                         equalTo: "Enter the same password as above"
                    },
                    email: {
                         required: "Please enter a valid email address",
                         minlength: "Please enter a valid email address",
                         remote: jQuery.format("{0} is already in use")
                    },
                    dateformat: "Choose your preferred dateformat",
                    terms: " "
               }
          });
     });
</script>*/ ?>

<?php //if ($mode == 'edit'): ?>
     <?php echo $this->Form->create('Photo', array('enctype' => 'multipart/form-data')); ?>
<?php //else: ?>
     <?php //echo $this->Form->create('Photo', array('url' => array('controller' => 'photos', 'action' => 'add'), 'enctype' => 'multipart/form-data')); ?>
<?php //endif; ?>
<?php
     echo $this->Form->input('display_title');
     echo $this->Form->input('display_subtitle');
     //echo $this->Form->input('title');
     echo $this->Form->input('alt_text');
     //echo $this->Form->input('shotDate');
     echo $this->Form->input('description');
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
     echo $this->Form->input('enabled');
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
	echo $this->Form->input('Photo.cdn-filename', array('type' => 'file'));
	echo $this->Form->input('Photo.photo_format_id');
?>
	<div class="input text">
		<label><?php __('Photo Tags'); ?></label><br style="clear: both;"/>
		<select name="data[Photo][tag_ids][]" multiple="multiple" class="chzn-select" data-placeholder="Find Tags ..." style="width: 300px;">
			<?php foreach ($tags as $tag): ?>
				<option value="<?php echo $tag['Tag']['id']; ?>" <?php if (in_array($tag['Tag']['id'], $photo_tag_ids)): ?>selected="selected"<?php endif; ?> ><?php echo $tag['Tag']['name']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	
<?php echo $this->Form->end('Save'); ?>
<br/>
<br/>