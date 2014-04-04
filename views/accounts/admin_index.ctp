<script type="text/javascript">
	var account_info = JSON.parse('<?php echo json_encode($overlord_account_info); ?>')
		
	function reload_buttons() {
		$("button").button();
	}
	
	function format_numbers() {
		$(".line_item .bar .cost").each(function() {
			$(this).html(accounting.formatMoney($(this).html()));
		});
		
		$(".details .current_bill .info").html(accounting.formatMoney($(".details .current_bill .info").html()));
		$(".details .current_credit .info").html(accounting.formatMoney($(".details .current_credit .info").html()));
	}
	
	function roundTwoDecimal(number) {
		return Math.round(number * 100) / 100;
	}
	
	function update_total(line_item, is_add) {
		if (account_info.pending_bill === undefined) {	
			account_info.pending_bill = account_info.total_bill;
		}
		
		var floatNum = roundTwoDecimal(parseFloat(line_item.attr('data-customer_cost')), 2);
		if(is_add) {
			 account_info.pending_bill += floatNum;
		} else {
			 account_info.pending_bill -= floatNum;
		}
		account_info.pending_bill = roundTwoDecimal(account_info.pending_bill, 2);
		
		if (account_info.pending_bill === account_info.total_bill) {
			$('.current_bill .pending_total').html('');
		} else {
			$('.current_bill .pending_total').html( " (" + accounting.formatMoney(account_info.pending_bill) + ")"); //TODO Adam need to format money	
		}
	}
	
	$(document).ready(function() {
		
		reload_buttons();
		format_numbers();
		$("#middle").css('min-height', '450px'); //TODO Adam remove this once we have all the alacart items added
		$("#line_item_cont .line_item .bar .bar-right span.toggle").click(function() {
			if ($(this).closest('.bar').next(".item_ad_area").is(':hidden')) {
				$(this).closest('.bar').next(".item_ad_area").slideDown();
				var icon = $(this).closest('.bar').find(".bar-right i.icon-angle-down");
				icon.removeClass('icon-angle-down').addClass('icon-angle-right');
			} else {
				$(this).closest('.bar').next(".item_ad_area").slideUp();
				var icon = $(this).closest('.bar').find(".bar-right i.icon-angle-right");
				icon.removeClass('icon-angle-right').addClass('icon-angle-down');
			}
		});

		var inAjaxCall = false;
		$("#line_item_cont .line_item .bar button.cancel_remove").click(function(e) {
			e.preventDefault();
			if (inAjaxCall) {
				return false;
			}

			$.ajax({
				url: '/admin/accounts/ajax_undo_cancellation/' + $(this).attr('data_id'),
				dataType: 'json',
				type: 'GET',
				success: function(data) {
					window.location.reload();
				},
				error: function(data) {
					window.location.reload();
				}
			});
		});

		$("#line_item_cont .line_item .bar button.remove_item").click(function(e) {
			e.preventDefault();
			if (inAjaxCall) {
				return false;
			}
			
			var line_item_id = $(this).attr('data_id');
			jQuery.foto('confirm', {
				message: '<?php echo __('This feature will remain on your account until your next monthly subscription is charged.'); ?><br /><br /><?php echo __('Are you sure you want to remove this item?'); ?>',
				onConfirm: function() {
					jQuery.ajax({
						type: 'GET',
						url: '/admin/accounts/ajax_remove_item/'+ line_item_id,
							success: function(data) {
								window.location.reload();
							},
							error: function(data) {
								window.location.reload();
						}
					});
				}
			});
			
		/*	jQuery.ajax({
				type: 'GET',
				url: '/admin/accounts/ajax_remove_item/'+ $(this).attr('data_id'),
				success: function(data) {
					window.location.reload();
				},
				error: function(data) {
					window.location.reload();
				}
			}); */

		});

		$("#line_item_cont .line_item .bar button.add_item").click(function(e) {
			e.preventDefault();
			if (inAjaxCall) {
				return false;
			}
			inAjaxCall = true;
			var argsToSend = {};
			argsToSend.id = $(this).attr('data_id');
			if ($(this).hasClass('pending')) {
				argsToSend.checked = 0;
				$(this).removeClass('pending');
				$(this).find('span').html('<?php echo __('Add'); ?>');
			} else {
				argsToSend.checked = 1;
				$(this).addClass('pending');
				$(this).find('span').html('<?php echo __('Added'); ?>');
			}
			
			var line_item = $(this).closest('.line_item');
			if(line_item.hasClass('line_not_added')) {
				update_total(line_item, true);
				line_item.removeClass('line_not_added');
			} else {
				update_total(line_item, false);
				line_item.addClass('line_not_added');
			}
			
			if ($("button.pending").length > 0) {
				$(".account-details").addClass('finish-shown');
				$(".finish-outer-cont").fadeIn();
			} else {
				$(".account-details").removeClass('finish-shown');
				$(".finish-outer-cont").hide();
			}
			
			$.post('/accounts/ajax_setItemChecked', argsToSend,
					function(data) {
						inAjaxCall = false;
						//$("#line_item_cont .line_item .bar input").removeAttr('disabled');
					}, 'json');

		});


		/* $("#line_item_cont .line_item .bar input").change(function(e) {
		 e.preventDefault();
		 if (inAjaxCall) {
		 return false;
		 }
		 inAjaxCall = true;
		 var args_item = {};
		 args_item.checked = 0;
		 if ($(this).is(':checked')) {
		 args_item.checked = 1;
		 }
		 args_item.id = $(this).attr('data_id');
		 var this_check_box = $(this);
		 $("#line_item_cont .finish input[type=button]").fadeIn();
		 $.post('/accounts/ajax_setItemChecked', args_item, 
		 function(data) {
		 inAjaxCall = false;
		 $("#line_item_cont .line_item .bar input").removeAttr('disabled');
		 }, 'json');
		 return false;
		 }); */

		$(".account-details .finish_account_add").click(function() {
			$.get("/admin/accounts/ajax_finishLineChange", function(data) {
				var div = $("<div></div>");
				div.html(data.html);
				div.dialog({
					width: '600',
					height: '570',
					title: '<?php echo __('Finish Account Changes'); ?>',
					open: function(event, ui) {
						$(this).find("button").button();
					}
				});
			}, 'json')
		});
		
		$(".pay_fail_message a").click(function(e) {
			e.preventDefault();
			$.get("/admin/accounts/ajax_addPreviousItems", function(data) {
				$(".account-details .finish_account_add").trigger('click');
			}, 'json');
		});
		
	});
</script>
<div class='clear' id="line_item_cont">
	<?php echo $this->Element('/admin/get_help_button'); ?>
			<div style="clear: both;"></div> 
	<p>Adam TODO investigate adding feature in which no value is charged</p>
	<?php echo $this->Session->flash(); ?>
	<?php if($overlord_account_info['is_pay_fail']): ?>
	<div class='pay_fail_message rounded-corners'>
		<p>We failed to charge your credit card. Your premium features have been suspended. If you wish to restore your account with the same features, <a href='#' class='payment_restore'>click here.</a></p>
	</div>
	<?php endif; ?>
    <div class="finish">
        <input type="button" value="Finish Changes" />
    </div>
    <div class="body_container">   
		<?php foreach ($overlord_account_info['items'] as $line_item): ?>
			<div data-customer_cost="<?php echo $line_item['AccountLineItem']['current_cost']; ?>" 
				class="line_item <?php echo $line_item['AccountLineItem']['addable'] ||  $line_item['AccountLineItem']['removed_scheduled'] ? 'line_not_added' : ''; ?> ">
				<div class="bar hidden rounded-corners-tiny">
					<h5><?php echo $line_item['AccountLineItem']['name']; ?> - <span class='cost'><?php echo $line_item['AccountLineItem']['current_cost']; ?></span></h5>
					<div class="bar-right custom_ui">
						<?php if ($line_item['AccountLineItem']['active']): ?>
							<button data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class='remove_item'>Remove</button>
						<?php elseif ($line_item['AccountLineItem']['removed_scheduled']): ?>
							<button data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class='cancel_remove'>Undo Cancellation</button>
						<?php else: ?>
							<button data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class='add_item'>Add</button>
						<?php endif; ?>
	<!--<span>Active</span><input <?php echo $line_item['AccountLineItem']['active'] ? 'CHECKED' : ''; ?> type='checkbox' name='data[AccountLineItem][]'  data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' /> -->
						<span class="toggle">Show Details</span><i class="icon-angle-down"></i>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="item_ad_area">
					<?php
					$ad_file = "line_item_ads/" . str_replace(' ', '_', strtolower($line_item['AccountLineItem']['name']));
					if (is_file(ROOT . '/app/views/elements/' . $ad_file . '.ctp')) {
						echo $this->Element($ad_file);
					} else {
						echo $this->Element('line_item_ads/default');
					}
					?>
				</div>
			</div>
		<?php endforeach; ?>
    </div>
	<div class="account-details rounded-corners-small">
		<div style="display:none;" class='finish-outer-cont'>
			<p><?php echo __('When you are done making feature changes, click below.'); ?></p>
			<button class='finish_account_add'><?php echo __('Finalize Feature Changes'); ?></button>
		</div>
		<div class='details'>
			<div class='detail current_bill'><span class='title'><?php echo __('Current Bill:'); ?></span><span class='info'><?php echo $overlord_account_info['total_bill']; ?></span><span class='pending_total'></span></div>
			<div class='detail current_credit'><span class='title'><?php echo __('Fotomatter Credit:'); ?></span><span class='info'><?php echo $overlord_account_info['Account']['promo_credit_balance']; ?></span></div>
		</div>
	</div>
	<?php //TODO Adam format all money all page ?>
	
	
</div>