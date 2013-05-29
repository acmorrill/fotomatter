<div id="finish_account_change">
   <p>Your current bill is <?php echo $current_bill; ?>.</p>
   <?php $amount_to_add = 0; ?>
   <?php if(empty($account_changes['checked']) == false): ?>
   <p>------</p>
   <p>You are adding the following items.</p>
   <ul>
       <?php foreach ($account_changes['checked'] as $id => $change): ?>
       <?php $amount_to_add += $account_info[$id]['AccountLineItem']['current_cost']; ?>
       <li><?php echo $account_info[$id]['AccountLineItem']['name']; ?> - <?php echo $account_info[$id]['AccountLineItem']['current_cost']; ?></li>
       <?php endforeach; ?>
   </ul>
   <p>This will increase your bill by <?php echo $amount_to_add; ?></p>
   <?php endif; ?>
   <?php $amount_to_remove = 0; ?>
   <?php if(empty($account_changes['unchecked']) == false): ?>
   <p>------</p>
   <ul>
       <?php foreach ($account_changes['unchecked'] as $id => $change): ?>
       <?php $amount_to_remove += $account_info[$id]['AccountLineItem']['current_cost']; ?>
       <li><?php echo $account_info[$id]['AccountLineItem']['name']; ?> - <?php echo $account_info[$id]['AccountLineItem']['current_cost']; ?></li>
       <?php endforeach; ?>
   </ul>
   <p>This will decrease your bill by <?php echo $amount_to_remove; ?></p>
   
   <?php endif; ?>
   <p>-----------</p>
   <p>Your new bill is <?php echo ($current_bill - $amount_to_remove) + $amount_to_add; ?></p>
   <script type="text/javascript">
       function finishChange() {
            console.log('finish');
            $.ajax({
               type: 'POST',
               url: "/admin/accounts/ajax_finish_account_change",
               success: function(data) {
                   window.location.reload();
               },
               dataType: 'json'
            });
       }
   </script>
   <button class="finalize_change" onClick='finishChange()'><?php __('Finalize Change'); ?></button>
   
   
</div>