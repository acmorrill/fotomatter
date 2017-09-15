<?php if (!empty($current_on_off_features['basic_shopping_cart'])): ?>
    <?php
        if (empty($photo_sellable_prints)) {
            $photo_sellable_prints = $this->Photo->get_enabled_photo_sellable_prints($photo_id);
            $this->log($photo_sellable_prints, '$photo_sellable_prints');
        }
        if (empty($beforeHtml)) {
            $beforeHtml = '';
        }
    ?>
    <div class="add_to_cart_buttons_cont">
        <?php if (empty($photo_sellable_prints)): ?>
            <!-- <?php __('The add to cart buttons have not been fully setup'); ?> -->
        <?php else: ?>
            <?php echo $beforeHtml; ?>
            <div class="add_to_cart_buttons_inner_cont">
                <table>
                    <thead>
                        <tr>
                            <th colspan='3'><h1 id='print_types_heading'><?php echo $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'global_photo_page_add_to_cart_text') ?></h1></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($photo_sellable_prints as $print_type_name => $print_type_sizes): ?>
                            <tr>
                                <td class='first'>&nbsp;</td>
                                <td class='print_type_name'>
                                    <h2><?php echo $print_type_name; ?></h2>
                                </td>
                                <td class='last'>
                                    <form action="/ecommerces/add_to_cart/" method="post">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class='first'>
                                                        <select name='data[qty]'>
                                                            <?php for ($i = 1; $i < 1000; $i++): ?>
                                                                <option value='<?php echo $i; ?>'><?php echo $i; ?></option>
                                                            <?php endfor; ?>
                                                        </select>
                                                    </td>
                                                    <td><span>x</span></td>
                                                    <td>
                                                        <select name="data[Photo][chosen_size_data]">
                                                            <?php foreach ($print_type_sizes['items'] as $print_type_size): ?>
                                                                <option value="<?php echo $print_type_size['short_side_inches'] . "|" . $print_type_size['type']; ?>"><?php echo $print_type_size['short_side_feet_inches']; ?> x <?php echo $print_type_size['long_side_feet_inches']; ?> --- <?php echo $this->Number->currency($print_type_size['price']); ?></option>  
                                                            <?php endforeach; ?>
                                                        </select> 
                                                    </td>
                                                    <td class='last'>
                                                        <input type='hidden' name='data[redirect_url]' value='<?php echo isset($redirect_url) ? $redirect_url : ''; ?>' />
                                                        <input type="hidden" name="data[Photo][id]" value="<?php echo $photo_id; ?>" />
                                                        <input type="hidden" name="data[PhotoPrintType][id]" value="<?php echo $print_type_sizes['print_type_id']; ?>" />
                                                        <div class="submit_button_cont">
                                                            <div class="frontend_form_submit_button submit_button"><span class='content'><?php echo __('Add to Cart', true); ?></span><span class='extra'></span></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </body>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>