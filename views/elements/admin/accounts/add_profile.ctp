<script type="text/javascript">

    function send_form() {
        $.ajax({
              type: "POST",
              url: "/admin/accounts/ajax_save_client_billing/closeWhenDone:<?php echo $closeWhenDone?'true':'false'; ?>",
              data: $("#payment_details_client").serialize(),
              success: function(data) {

                  <?php if($closeWhenDone): ?>
					   if(data.result !== undefined && data.result == false) {
						   $(".ui-dialog-content").html(data.html);
					   } else {
							window.location.reload();
					   }
                  <?php else: ?>
                      $(".ui-dialog-content").html(data.html);
                  <?php endif; ?>
              },
              dataType: 'json'
           });
    }
    
    function getCountries(country_id) {
            $.post('/admin/accounts/ajax_get_states_for_country/'+country_id,function(data){
                $("#billing_state").html(data.html);
            }, 'json');
    }
	
	setTimeout(function() {
		$("input[type=submit]").button()
	}, 50);
    
</script>
<div class="profile-outer-cont">
	<?php if (empty($error_message) === false): ?>
		<?php echo $this->element('admin/flashMessage/error', array('message'=>$error_message)); ?>
	<?php endif; ?>
	<h3><?php echo __('Add credit Card'); ?></h3>
    <form id="payment_details_client" action="#" onSubmit="send_form(); return false;">
        <div class="address">
            <input type='hidden' id='billing_id' name='data[AuthnetProfile][id]' value="<?php echo empty($current_data['AuthnetProfile']['id'])==false?$current_data['AuthnetProfile']['id']:''; ?>" />
            <input type='hidden' id='billing_id' name='data[AuthnetProfile][created]' value="<?php echo empty($current_data['AuthnetProfile']['created'])==false?$current_data['AuthnetProfile']['created']:''; ?>" />
            <input type='hidden' id='billing_id' name='data[AuthnetProfile][modified]' value="<?php echo empty($current_data['AuthnetProfile']['modified'])==false?$current_data['AuthnetProfile']['modified']:''; ?>" />
            <div class="input">
                <label for="billing_firstname"><?php echo __('First Name'); ?></label>
                <input type="text" id="billing_firstname" name="data[AuthnetProfile][billing_firstname]" value="<?php echo empty($current_data['AuthnetProfile']['billing_firstname'])==false?$current_data['AuthnetProfile']['billing_firstname']:''; ?>" />
            </div>
            <div class="input">
                <label for="billing_lastname"><?php echo __('Last Name'); ?></label>
                <input type="text" id="billing_lastname" name="data[AuthnetProfile][billing_lastname]" value="<?php echo empty($current_data['AuthnetProfile']['billing_lastname'])==false?$current_data['AuthnetProfile']['billing_lastname']:''; ?>" />
            </div>
            <div class="input">
                <label for="billing_address"><?php echo __('Address'); ?></label>
                <input type="text" id="billing_address" name="data[AuthnetProfile][billing_address]" value="<?php echo empty($current_data['AuthnetProfile']['billing_address'])==false?$current_data['AuthnetProfile']['billing_address']:''; ?>" />
            </div>
            <div class="input">
                <label for="billing_country"><?php echo __('Country'); ?></label>
                <select name="data[AuthnetProfile][country_id]" id="billing_country" onChange="getCountries($(this).val())">
                    <?php foreach ($countries as $key => $country): ?>
                    <option <?php echo empty($current_data['AuthnetProfile']['country_id'])===false&&$current_data['AuthnetProfile']['country_id']==$country['GlobalCountry']['country_code_2']?'SELECTED':''; ?> value="<?php echo $country['GlobalCountry']['country_code_2']; ?>"><?php echo $country['GlobalCountry']['country_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input">
                <label for="billing_city"><?php echo __('City'); ?></label>
                <input type="text" id="billing_city" name="data[AuthnetProfile][billing_city]" value="<?php echo empty($current_data['AuthnetProfile']['billing_city'])==false?$current_data['AuthnetProfile']['billing_city']:''; ?>" />
            </div>
            <div class="input">
                <label for="billing_state"><?php echo __('State'); ?></label>
                <select id="billing_state" name="data[AuthnetProfile][country_state_id]">
                    <?php if (empty($current_data['AuthnetProfile']['country_id'])): ?>
                        <?php echo $this->element('admin/accounts/state_list', array('country_code'=>'US')); ?>
                    <?php else: ?>
                        <?php echo $this->element('admin/accounts/state_list', array('country_code'=>$current_data['AuthnetProfile']['country_id'], 'selected'=>$current_data['AuthnetProfile']['country_state_id'])); ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="input">
                <label for="billing_zip"><?php echo __('Zip'); ?></label>
                <input type="text" id="billing_zip" name="data[AuthnetProfile][billing_zip]" value="<?php echo empty($current_data['AuthnetProfile']['billing_zip'])===false?$current_data['AuthnetProfile']['billing_zip']:''; ?>" />
            </div>
        </div>
        <div class="payment">
            <div class="input">
                <label for="billing_zip"><?php echo __('Card Number'); ?></label>
                <input type="text" id="billing_cardNumber" name="data[AuthnetProfile][payment_cardNumber]" />
            </div>
            <div class="input exp_date">
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
                <input type="submit" class="payment_details_submit ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" value="<?php echo __('Continue'); ?>" /> 
            </div>
        </div>
    </form>
	<div class='profile_info rounded-corners'>
		<p>This is the card that we will use every month to pay your fotomatter subscription. </p>
		<p>Please remember to keep it up to date so that your service or website will not be interrupted.</p>
	</div>
</div>