<?php
//	$photo_sellable_prints = array();
/*
  <div class="add_button highlight add_feature_button" type="submit" ref_feature_name="basic_shopping_cart" style="margin-top: 10px;">
  <div class="content" style="font-size: 18px;"><?php echo __('Add Ecommerce', true); ?></div>
  <div class="right_arrow_lines icon-arrow-01"><div></div></div>
  </div> */
?>


<?php if (empty($current_on_off_features['basic_shopping_cart'])): ?>
    <div class="custom_ui">
        <div class="add_button highlight add_feature_button" type="submit" ref_feature_name="basic_shopping_cart" style="margin-bottom: 50px;">
            <div class="content" style="font-size: 18px;"><?php echo __('Add Ecommerce', true); ?></div>
            <div class="right_arrow_lines icon-arrow-01"><div></div></div>
        </div>
    </div>
<?php else: ?>
    <?php if (empty($this->data['Photo']['override_pricing'])): ?>
        <div class="large_container">
            <div class="tab_tools_container">
                <h2><?php echo __('To setup image pricing for all of your images at once go to &ldquo;Manage Print Types&rdquo; in E-commerce.', true); ?></h2>
                <a href="/admin/ecommerces/manage_print_types_and_pricing" class="custom_ui">
                    <div class="add_button">
                        <div class="content"><?php echo __('Manage Print Types Now', true); ?></div>
                        <div class="right_arrow_lines icon-arrow-01"><div></div></div>
                    </div>
                </a>
                <br />
                <br />
                <br />
                <h2><?php echo __('Though not recommended, you can also set pricing per image. Turn on pricing override for this image below.', true); ?></h2>

                <a href="/admin/photos/set_override_photo_pricing/<?php echo $this->data['Photo']['id']; ?>" class="custom_ui">
                    <div class="add_button">
                        <div class="content"><?php echo __('Override Image Pricing', true); ?></div>
                        <div class="right_arrow_lines icon-arrow-01"><div></div></div>
                    </div>
                </a>
            </div>
        </div>
    <?php else: ?>
        <script type="text/javascript">
            function setRowValues(attr_name, row_tr) {
                jQuery('.disabled_cont', row_tr).each(function () {
                    var key_value = jQuery(this).attr(attr_name);
                    if (key_value !== undefined) {
                        var checkbox = jQuery('input:checkbox', this);
                        if (checkbox.length > 0) {
                            if (key_value === '1') {
                                checkbox.attr('checked', 'checked');
                            } else {
                                checkbox.removeAttr('checked');
                            }
                        }

                        var input = jQuery('input:text', this);
                        if (input.length > 0) {
                            input.val(key_value);
                        }
                    }
                });
            }

            function saveRowValues(attr_name, row_tr) {
                jQuery('.disabled_cont', row_tr).each(function () {
                    var key_value = jQuery(this).attr(attr_name);
                    if (key_value !== undefined) {
                        var checkbox = jQuery('input:checkbox', this);
                        if (checkbox.length > 0) {
                            if (checkbox.is(':checked')) {
                                jQuery(this).attr(attr_name, '1');
                            } else {
                                jQuery(this).attr(attr_name, '0');
                            }
                        }

                        var input = jQuery('input:text', this);
                        if (input.length > 0) {
                            jQuery(this).attr(attr_name, input.val());
                        }
                    }
                });
            }


            jQuery(document).ready(function () {
                $('#image_sellable_prints .money_format').priceFormat({
                    prefix: '',
                    thousandsSeparator: ''
                });


                // unlock the row
                jQuery('#image_sellable_prints .lock_img').click(function () {
                    if (!jQuery(this).hasClass('unlockable')) {
                        jQuery(this).parent().find('.unlock_img').css('display', 'inline-block');
                        jQuery(this).css('display', 'none');
                        jQuery(this).parent().find('.override_for_photo').val('1');
                        setRowValues('current', jQuery(this).closest('tr'));
                        jQuery(this).closest('tr').find('.disablable').removeClass('opacity_50');
                        jQuery(this).closest('tr').addClass('unlocked');
                    } else {
                        jQuery.foto('alert', '<?php echo __('This row is unlockable because of a global setting in Manage Print Types and Pricing.', true); ?>');
                    }
                });

                // lock the row
                jQuery('#image_sellable_prints .unlock_img').click(function () {
                    jQuery(this).parent().find('.lock_img').css('display', 'inline-block');
                    jQuery(this).css('display', 'none');
                    jQuery(this).parent().find('.override_for_photo').val('0');
                    var closest_tr = jQuery(this).closest('tr');
                    saveRowValues('current', closest_tr);
                    setRowValues('default', closest_tr);
                    jQuery(this).closest('tr').find('.disablable').addClass('opacity_50');
                    jQuery(this).closest('tr').removeClass('unlocked');
                });
            });
        </script>
        <div id="image_sellable_prints">
            <a href="/admin/photos/unset_override_photo_pricing/<?php echo $this->data['Photo']['id']; ?>" class="custom_ui">
                <div class="add_button">
                    <div class="content"><?php echo __('Switch to Global Pricing', true); ?></div>
                    <div class="right_arrow_lines icon-arrow-01"><div></div></div>
                </div>
            </a>
            <h2><?php echo __('Change pricing, shipping, and turnaround times for various sizes and print types on a specific photo. Be sure to click the unlock button under &ldquo;Override Global Default&rdquo; if you want to make changes to pricing created in &ldquo;Manage Print Types and Default Pricing&rdquo; under the E-commerce tab above. Changes will apply to this photo only.', true); ?></h2>
            <br />
            <table class="list">
                <thead>
                    <tr>
                        <th class="first">
                            <div class="content one_line">
                                <?php echo __('Override Global Default', true); ?>
                            </div>
                        </th>
                        <th class="photo_use_sizes">
                            <div class="content one_line">
                                <?php echo __('Photo Uses Size', true); ?>
                            </div>
                        </th>
                        <th>
                            <div class="content one_line">
                                <?php echo __('Print Type and Size', true); ?>
                            </div>
                        </th>
                        <th>
                            <div class="content">
                                <?php echo __('Pricing & Settings', true); ?>
                            </div>
                        </th>
                        <?php /* <th>
                          <div class="content one_line">
                          <?php echo __('Shipping Price', true); ?>
                          </div>
                          </th>
                          <th class="last">
                          <div class="content one_line">
                          <?php echo __('Turnaround Time', true); ?>
                          </div>
                          </th> */ ?>
                    </tr>
                </thead>
                <tbody>
                    <tr class="spacer"><td colspan="6"></td></tr>
                    <?php if (empty($photo_sellable_prints)): ?>
                        <tr>
                            <td class="first last" colspan="6" style="text-align: center;">
                                <div class="rightborder"></div>
                                <a href="/admin/ecommerces/manage_print_types_and_pricing" class="custom_ui">
                                    <div class="add_button">
                                        <div class="content"><?php echo __('Manage Print Types Now', true); ?></div>
                                        <div class="right_arrow_lines icon-arrow-01"><div></div></div>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php $count = 0;
                    foreach ($photo_sellable_prints as $photo_sellable_print): ?>
                        <?php
                            $override_for_photo = $photo_sellable_print['CurrentPrintData']['override_for_photo'];
                            $force_defaults = $photo_sellable_print['PrintTypeJoin']['force_settings'];
                            if ($force_defaults === '1') {
                                $override_for_photo = '0';
                            }
                        ?>
                        <tr class="<?php if ($override_for_photo === '1'): ?> unlocked <?php endif; ?>">
                            <td class="first disabled_cont">
                                <input class="override_for_photo" type="hidden" name="data[PhotoSellablePrint][<?php echo $count; ?>][override_for_photo]" value="<?php echo $override_for_photo; ?>" />
                                <i class="icon-priceLock-01-01 lock_img <?php if ($force_defaults === '1'): ?>unlockable<?php endif; ?>" style="<?php if ($override_for_photo === '1'): ?>display: none;<?php endif; ?>"></i>
                                <i class="icon-priceUnLock-01 unlock_img" style="<?php if ($override_for_photo === '1'): ?>display: inline-block;<?php endif; ?>"></i>

                                <input name="data[PhotoSellablePrint][<?php echo $count; ?>][photo_avail_sizes_photo_print_type_id]" type="hidden" value="<?php echo $photo_sellable_print['PrintTypeJoin']['id']; ?>" />
                                <input name="data[PhotoSellablePrint][<?php echo $count; ?>][photo_id]" type="hidden" value="<?php echo $this->data['Photo']['id']; ?>" />
                                <?php if (isset($photo_sellable_print['PhotoSellablePrint']['id'])): ?>
                                    <input name="data[PhotoSellablePrint][<?php echo $count; ?>][id]" type="hidden" value="<?php echo $photo_sellable_print['PhotoSellablePrint']['id']; ?>" />
                                <?php endif; ?>
                            </td>
                            <td class="input photo_use_sizes disabled_cont" default="<?php echo $photo_sellable_print['PrintTypeJoin']['available']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['available']; ?>">
                                <!--<div class="rightborder"></div>-->
                                <div class="disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>">
                                    <div class="disabled_block"></div>
                                    <input type="checkbox"  name="data[PhotoSellablePrint][<?php echo $count; ?>][available]" <?php if ($photo_sellable_print['CurrentPrintData']['available'] === '1'): ?>checked="checked"<?php endif; ?> default="<?php echo $photo_sellable_print['PrintTypeJoin']['available']; ?>" custom="<?php echo isset($photo_sellable_print['PhotoSellablePrint']['override_for_photo']) ? $photo_sellable_print['PhotoSellablePrint']['override_for_photo'] : 0; ?>" />
                                    <?php /* <input type="hidden"  name="data[PhotoSellablePrint][<?php echo $count; ?>][defaults][available]" value="<?php echo $photo_sellable_print['DefaultPrintData']['default_available']; ?>"  /> */ // this is turned off so that changes to availability will always save  ?>
                                </div>
                            </td>
                            <td class="disabled_cont">
                                <div class="disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>">
                                    <?php echo $photo_sellable_print['PhotoPrintType']['print_name']; ?> &mdash; <?php echo $photo_sellable_print['CurrentPrintData']['short_side_inches']; ?>" x <?php echo $photo_sellable_print['CurrentPrintData']['long_side_feet_inches']; ?>
                                </div>
                            </td>
            <!--							<td class="input" default="<?php echo $photo_sellable_print['PrintTypeJoin']['price']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['price']; ?>">
                            </td>
                            <td class="input" default="<?php echo $photo_sellable_print['PrintTypeJoin']['handling_price']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['handling_price']; ?>">
                            </td>-->
                            <td class="input last" default="<?php echo $photo_sellable_print['PrintTypeJoin']['custom_turnaround']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['custom_turnaround']; ?>">
                                <span 
                                    class="disabled_cont subitem_container disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>"
                                    default="<?php echo $photo_sellable_print['PrintTypeJoin']['price']; ?>"
                                    current="<?php echo $photo_sellable_print['CurrentPrintData']['price']; ?>"
                                    >
                                    <label><?php echo __('Price', true); ?></label><br />
                                    <span class="disabled_block"></span>
                                    <span class="money_symbol">$</span><input class="money_format" value="<?php echo $photo_sellable_print['CurrentPrintData']['price']; ?>" name="data[PhotoSellablePrint][<?php echo $count; ?>][price]" type="text" />
                                    <input type="hidden"  name="data[PhotoSellablePrint][<?php echo $count; ?>][defaults][price]" value="<?php echo $photo_sellable_print['PrintTypeJoin']['price']; ?>"  />
                                </span>

                                <span 
                                    class="disabled_cont subitem_container disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>"
                                    default="<?php echo $photo_sellable_print['PrintTypeJoin']['handling_price']; ?>"
                                    current="<?php echo $photo_sellable_print['CurrentPrintData']['handling_price']; ?>"
                                    >
                                    <label><?php echo __('Handling Price', true); ?></label><br />
                                    <span class="disabled_block"></span>
                                    <span class="money_symbol">$</span><input class="money_format" value="<?php echo $photo_sellable_print['CurrentPrintData']['handling_price']; ?>" name="data[PhotoSellablePrint][<?php echo $count; ?>][handling_price]" type="text" />
                                    <input type="hidden"  name="data[PhotoSellablePrint][<?php echo $count; ?>][defaults][handling_price]" value="<?php echo $photo_sellable_print['PrintTypeJoin']['handling_price']; ?>"  />
                                </span>

                                <span 
                                    class="disabled_cont subitem_container disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>"
                                    default="<?php echo $photo_sellable_print['PrintTypeJoin']['custom_turnaround']; ?>"
                                    current="<?php echo $photo_sellable_print['CurrentPrintData']['custom_turnaround']; ?>"
                                    >
                                    <label><?php echo __('Turnaround Time', true); ?></label><br />
                                    <span class="disabled_block"></span>
                                    <span>
                                        <select name="data[PhotoSellablePrint][<?php echo $count; ?>][custom_turnaround]">
                                            <?php for($i = 0; $i <= 100; $i++): ?>
                                                <?php if ($i === 0): ?>
                                                    <option 
                                                        value="<?php echo $i; ?>"
                                                        <?php if ($photo_sellable_print['CurrentPrintData']['custom_turnaround'] == $i): ?> selected="selected" <?php endif; ?>
                                                        ><?php echo __('Default', true); ?></option>
                                                <?php else: ?>
                                                    <option 
                                                        value="<?php echo $i; ?>"
                                                        <?php if ($photo_sellable_print['CurrentPrintData']['custom_turnaround'] == $i): ?> selected="selected" <?php endif; ?>
                                                        ><?php echo sprintf(__('%d day(s)', true), $i); ?></option>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </select>
                                    </span>
                                    <input 
                                        type="hidden"
                                        name="data[PhotoSellablePrint][<?php echo $count; ?>][defaults][custom_turnaround]"
                                        value="<?php echo $photo_sellable_print['PrintTypeJoin']['custom_turnaround']; ?>"
                                        />
                                </span>
                            </td>
                        </tr>
            <?php $count++;
        endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="photo_details_save_button save_button"><div class="content">Save</div></div>
    <?php endif; ?>
<?php endif; ?>