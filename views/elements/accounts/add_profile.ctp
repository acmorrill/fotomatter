<div class="profile-outer-cont">
    <div class="address">
        <div class="input">
            <label for="billing_firstname">First Name</label>
            <input type="text" id="billing_firstname" name="billing_firstname" />
        </div>
        <div class="input">
            <label for="billing_lastname">Last Name</label>
            <input type="text" id="billing_lastname" name="billing_lastname" />
        </div>
        <div class="input">
            <label for="billing_address">Address</label>
            <input type="text" id="billing_address" name="billing_address" />
        </div>
        <div class="input">
            <label for="billing_city">City</label>
            <input type="text" id="billing_city" name="billing_city" />
        </div>
        <div class="input">
            <label for="billing_state">State</label>
            <input type="text" id="billing_state" name="billing_state" />
        </div>
        <div class="input">
            <label for="billing_zip">Zip</label>
            <input type="text" id="billing_zip" name="billing_zip" />
        </div>
    </div>
    <div class="payment">
        <div class="input">
            <label for="billing_zip">Card Number</label>
            <input type="text" id="billing_cardNumber" name="billing_cardNumber" />
        </div>
        <div class="input">
            <label for ="card_exp">Expiration Date</label>
            <select name="exp_month">
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
            <select name="exp_year">
                <?php for($i=0; $i < 3; $i++): ?>
                <option><?php echo date('Y', strtotime("+".$i." years")); ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="input">
            <label for="billing_zip">Csv Code</label>
            <input type="text" id="billing_csv" name="billing_csv" />
        </div>
    </div>
    
    
</div>