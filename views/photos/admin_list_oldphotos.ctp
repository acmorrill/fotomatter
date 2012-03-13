<h1>Old photos list</h1>
<?php //debug($allOldPhotos); ?>

<script type="text/javascript">
     jQuery(document).ready(function() {
          jQuery('#oldPhotoListCont').sortable({
               update: function() {
                    jQuery('#oldPhotoListCont').sortable("option", "disabled", true);
                    //alert('sorting stopped');

                    var idsArr = [];
                    jQuery('#oldPhotoListCont .photoListItem').each(function() {
                         var id = jQuery(this).attr('id');
                         idsArr.push(id);
                    });

                    //console.log(idsArr);
                    jQuery.post('/photos/update_order/', {'imageOrder': idsArr}, function(data) {
                         jQuery('#oldPhotoListCont').sortable("option", "disabled", false);
                         //console.log(data);
                    }, 'json');
               }
          });

          /*jQuery('#oldPhotoListCont .photoActions .editImage').click(function() {
               var id = jQuery(this).closest('.photoListItem').attr('id');

               console.log(id);
          });*/
     });
</script>

<?php /*
<label>Categories</label>
<select>
     <option value="largeFormatColor">Large Format Color</option>
     <option value="favorites">Large Format Color</option>
</select>
 *
 */?>

<div id="oldPhotoListCont">
<?php foreach ($allOldPhotos as $oldPhoto): ?>
     <div class="photoListItem" style="border: 1px solid #333333; margin-bottom: 10px; background: white;" id="<?php echo $oldPhoto['OldPhoto']['id']; ?>">
          <div class="photoIcon" style="float: left; width: 33%; overflow: hidden;">
               <img src="<?php echo "/photos/smallThumbs/".$oldPhoto['OldPhoto']['title'];?>" />
          </div>
          <div class="photoInfo" style="float: right; width: 50%;">
               <h3><?php echo $oldPhoto['OldPhoto']['displayTitle']; ?></h3>
               <div class="photoActions" style="float: right;">
                    <h3 class="editImage" style="margin-right: 10px; cursor: pointer;"><a href="/photos/edit_oldphoto/<?php echo $oldPhoto['OldPhoto']['id']; ?>">Edit</a></h3>
                    <h3 class="calcPhotoPrice" style="margin-right: 10px; cursor: pointer;"><a href="/photos/calc_photoprice/<?php echo $oldPhoto['OldPhoto']['id']; ?>">Calc Price</a></h3>
               </div>
          </div>
          <div style="clear: both;"></div>
     </div>
<?php endforeach; ?>
</div>