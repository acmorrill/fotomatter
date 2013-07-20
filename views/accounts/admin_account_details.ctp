<script type='text/javascript'>
    $(document).ready(function() {
        $(".payment-summary .edit-details button").click(function() {
                $.ajax({
                   url: '/admin/accounts/ajax_update_payment/closeWhenDone:true',
                   dataType: 'json',
                   type: 'GET',
                   success: function(data) {
                                var div = $("<div></div>");
                                div.html(data.html);
                                div.dialog({
                                    width:'600',
                                    height:'500',
                                    title: '<?php echo __('Edit Payment Details'); ?>'
                                });
                            }
                });
        });
        
        $(".payment-summary .payment-details button").click(function() {
            
        });
    });
</script>

<div class="clear" id='account-details'>
    <fieldset class='payment'>
        <legend>Payment Details</legend>
            <?php if (empty($accountDetails['AuthnetProfile'])): ?>
            <div class='payment-summary'>
                <span>Free Account</span>
                <a class='free-link' href='/admin/accounts'>Add Premium Features</a>
            </div>
            <?php else: ?>
            <div class='payment-summary'>
                <div class='edit-details'>
                    <span>Card ending in <?php echo $accountDetails['AuthnetProfile']['payment_cc_last_four']; ?>.<button class='rounded-corners-tiny'>Edit Billing Details</button></span>
                </div>
                <div class='payment-details'>
                    <span>Last payment received January 12, 2013</span><button class='rounded-corners-tiny'>View Payment History</button>
                </div>
            </div>
            <?php endif; ?>
        
    </fieldset>
    <fieldset class='account-summary'>
        <legend>Account Summary</legend>
        
    </fieldset>
    <fieldset class='password-change'>
        <legend>Change Password</legend>
        
    </fieldset>
</div>