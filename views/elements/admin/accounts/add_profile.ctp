<script type="text/javascript">
    function send_form() {
        $.ajax({
              type: "POST",
              url: "/admin/accounts/ajax_save_client_billing",
              data: $("#payment_details_client").serialize(),
              success: function(data) {
                  
                  $(".ui-dialog-content").html(data.html);
              },
              dataType: 'json'
           });
    }
    
    function getCountries(country_id) {
            $.post('/admin/accounts/ajax_get_states_for_country/'+country_id,function(data){
                $("#billing_state").html(data.html);
            }, 'json');
    }
        
  
</script>

<div class="profile-outer-cont">
    <?php echo $this->Session->flash(); ?>
    <form id="payment_details_client" action="#" onSubmit="send_form(); return false;">
        <div class="address">
            <div class="input">
                <label for="billing_firstname"><?php echo __('First Name'); ?></label>
                <input type="text" id="billing_firstname" name="data[AuthnetProfile][billing_firstname]" />
            </div>
            <div class="input">
                <label for="billing_lastname"><?php echo __('Last Name'); ?></label>
                <input type="text" id="billing_lastname" name="data[AuthnetProfile][billing_lastname]" />
            </div>
            <div class="input">
                <label for="billing_address"><?php echo __('Address'); ?></label>
                <input type="text" id="billing_address" name="data[AuthnetProfile][billing_address]" />
            </div>
            <div class="input">
                <label for="billing_country"><?php echo __('Country'); ?></label>
                <select style="width:205px" name="data[AuthnetProfile][country_id]" id="billing_country" onChange="getCountries($(this).val())">
                    <?php foreach ($countries as $key => $country): ?>
                    <option value="<?php echo $country['GlobalCountry']['country_code_2']; ?>"><?php echo $country['GlobalCountry']['country_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input">
                <label for="billing_city"><?php echo __('City'); ?></label>
                <input type="text" id="billing_city" name="data[AuthnetProfile][billing_city]" />
            </div>
            <div class="input">
                <label for="billing_state"><?php echo __('State'); ?></label>
                <select id="billing_state" name="data[AuthnetProfile][country_state_id]">
                    <option><?php echo __('Please Choose Country'); ?></option>
                </select>
            </div>
            <div class="input">
                <label for="billing_zip"><?php echo __('Zip'); ?></label>
                <input type="text" id="billing_zip" name="data[AuthnetProfile][billing_zip]" />
            </div>
        </div>
        <div class="payment">
            <div class="input">
                <label for="billing_zip"><?php echo __('Card Number'); ?></label>
                <input type="text" id="billing_cardNumber" name="data[AuthnetProfile][payment_cardNumber]" />
            </div>
            <div class="input">
                <label for ="card_exp"><?php echo __('Expiration Date'); ?></label>
                <select name="data[AuthnetProfile][expiration][month]">
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>                
                </select>
                <select name="data[AuthnetProfile][expiration][year]">
                    <?php for($i=0; $i < 3; $i++): ?>
                    <option><?php echo date('Y', strtotime("+".$i." years")); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="input">
                <label for="billing_zip"><?php echo __('Csv Code'); ?></label>
                <input type="text" id="billing_csv" name="data[AuthnetProfile][payment_cardCode]" />
            </div>
            <div class="input continue">
                <input type="submit" class="payment_details_submit" value="<?php echo __('Continue'); ?>" /> 
            </div>
        </div>
    </form>
</div>