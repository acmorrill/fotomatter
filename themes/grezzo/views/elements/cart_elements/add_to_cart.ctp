<?php echo $this->Session->flash(); ?>
<?php $photo_sellable_prints = $this->Photo->get_enabled_photo_sellable_prints($photo_id); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#add_to_cart_buttons_cont .submit_button_cont').click(function () {
            jQuery(this).closest('form').submit();
        });
    });
</script>

<div id="add_to_cart_buttons_cont">
    <?php if (empty($photo_sellable_prints)): ?>

        <!-- <?php __('The add to cart buttons have not been fully setup'); ?> -->

    <?php else: ?>
        <?php foreach ($photo_sellable_prints as $print_type_name => $print_type_sizes): ?>
            <div class='print_type_outer_cont'>
                <h2><?php echo $print_type_name; ?></h2>
                <form action="/ecommerces/add_to_cart/" method="post">
                    <input type='hidden' name='data[redirect_url]' value='<?php echo isset($redirect_url) ? $redirect_url : ''; ?>' />
                    <input type="hidden" name="data[Photo][id]" value="<?php echo $photo_id; ?>" />
                    <input type="hidden" name="data[PhotoPrintType][id]" value="<?php echo $print_type_sizes['print_type_id']; ?>" />
                    <select class='sizes_avail_for_print_type' name="data[Photo][chosen_size_data]" style='width:200px' tabindex='1'>
                        <?php foreach ($print_type_sizes['items'] as $print_type_size): ?>
                            <option value="<?php echo $print_type_size['short_side_inches'] . "|" . $print_type_size['type']; ?>"><?php echo $print_type_size['short_side_feet_inches']; ?> x <?php echo $print_type_size['long_side_feet_inches']; ?> --- <?php echo $this->Number->currency($print_type_size['price']); ?></option>  
                        <?php endforeach; ?>
                    </select> 
                    <div class="submit_button_cont">
                        <button class="submit_inner"><?php __('Add to Cart'); ?></button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


