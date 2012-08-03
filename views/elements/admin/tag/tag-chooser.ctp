<?php echo $this->Html->script('/js/jQuery-Tags-Input/jquery.tagsinput.min.js'); ?>
<?php echo $this->Html->css('/js/jQuery-Tags-Input/jquery.tagsinput.css'); ?>
<script type='text/javascript'>
    $(document).ready(function() {
        $(".outer-tag-chooser-cont input.tag_chooser").tagsInput({
            width:'80%',
            height:'80px',
            defaultText: '<?php __('Add Tag'); ?>',
            onAddTag: function(tag_added) {
                var input_field = $("<input />");
                input_field.attr('type', 'hidden');
                input_field.attr("name", "data[Tag][]");
                input_field.val(tag_added);
                input_field.addClass(tag_added);
                $(".outer-tag-chooser-cont").append(input_field);
            },
            onRemoveTag: function(tag_removed) {
                $(".outer-tag-chooser-cont input."+tag_removed).remove();
            }
            
        }); 
    });
</script>
<div class='outer-tag-chooser-cont'>
    <div class='header'>
        <div class='title'><?php __('Tags'); ?></div>
        <p><?php __('Specifty a few key words that describe your new photos. Photos can also be tagged once they are uploaded.'); ?></p>
    </div>
    <input class='tag_chooser' type='text' />
</div>