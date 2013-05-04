<script type="text/javascript">
    function send_form() {
        $.ajax({
              type: "POST",
              url: "/admin/accounts/ajax_save_client_billing",
              data: $("#payment_details_client").serialize(),
              success: function(data) {
                  
              }
           });
    }
</script>
<div class="profile-outer-cont">
    <form id="payment_details_client" action="#" onSubmit="send_form(); return false;">
        <div class="address">
            <div class="input">
                <label for="billing_firstname">First Name</label>
                <input type="text" id="billing_firstname" name="data[AuthnetProfile][billing_firstname]" />
            </div>
            <div class="input">
                <label for="billing_lastname">Last Name</label>
                <input type="text" id="billing_lastname" name="data[AuthnetProfile][billing_lastname]" />
            </div>
            <div class="input">
                <label for="billing_address">Address</label>
                <input type="text" id="billing_address" name="data[AuthnetProfile][billing_address]" />
            </div>
            <div class="input">
                <label for="billing_city">City</label>
                <input type="text" id="billing_city" name="data[AuthnetProfile][billing_city]" />
            </div>
            <div class="input">
                <label for="billing_state">State</label>
                <input type="text" id="billing_state" name="data[AuthnetProfile][billing_state]" />
            </div>
            <div class="input">
                <label for="billing_zip">Zip</label>
                <input type="text" id="billing_zip" name="data[AuthnetProfile][billing_zip]" />
            </div>
        </div>
        <div class="payment">
            <div class="input">
                <label for="billing_zip">Card Number</label>
                <input type="text" id="billing_cardNumber" name="data[AuthnetProfile][billing_cardNumber]" />
            </div>
            <div class="input">
                <label for ="card_exp">Expiration Date</label>
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
                <label for="billing_zip">Csv Code</label>
                <input type="text" id="billing_csv" name="data[AuthnetProfile][csv]" />
            </div>
            <div class="input continue">
                <input type="submit" class="payment_details_submit" value="<?php echo __('Continue'); ?>" /> 
            </div>
        </div>
    </form>
    <script type="text/javascript">
        $("form").submit(function() {
            console.log('here');
            return false;
        })
    </script>
</div>