<?php /// THIS FILE IS BOTH THE EDIT AND ADD OF OLD PHOTOS
     if ($mode == 'edit') {
          $currGalleries = explode(',', $this->data['OldPhoto']['galleries']);
          $currSizes = explode(',', $this->data['OldPhoto']['availSizes']);
     } else {
          $currGalleries = array();
          $currSizes = array();
     }
?>
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

<?php if ($mode == 'edit'): ?>
     <?php echo $this->Form->create('OldPhoto', array('url' => array('controller' => 'photos', 'action' => 'edit_oldphoto'), 'enctype' => 'multipart/form-data')); ?>
<?php else: ?>
     <?php echo $this->Form->create('OldPhoto', array('url' => array('controller' => 'photos', 'action' => 'add_oldphoto'), 'enctype' => 'multipart/form-data')); ?>
<?php endif; ?>
<?php
     echo $this->Form->input('displayTitle');
     echo $this->Form->input('displaySubtitle');
     echo $this->Form->input('title');
     echo $this->Form->input('altText');
     echo $this->Form->input('shotDate');
     echo $this->Form->input('description');
     echo $this->Form->input('OldPhoto.galleries', array(
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
     ));
     echo $this->Form->input('tier');
     echo $this->Form->input('used');
     echo $this->Form->input('OldPhoto.format', array(
          'type' => 'select',
          'options' => array(
               'landscape' => 'landscape',
               'portrait' => 'portrait',
               'square' => 'square',
               'panoramic' => 'panoramic'
          ),
         'selected' => $this->data['OldPhoto']['format']
     ));
     echo $this->Form->input('OldPhoto.availSizes', array(
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
     echo $this->Form->label('OldPhoto.thumbImage');
     echo $this->Form->file('OldPhoto.thumbImage');
     echo '<br/>';
     echo $this->Form->label('OldPhoto.largeImage');
     echo $this->Form->file('OldPhoto.largeImage');
     echo '<br/>';
     echo $this->Form->label('OldPhoto.extraLargeImage');
     echo $this->Form->file('OldPhoto.extraLargeImage');

?>
<?php echo $this->Form->end('Save'); ?>
<br/>
<br/>