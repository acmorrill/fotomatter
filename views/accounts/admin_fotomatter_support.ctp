<!-- Start of LiveChat (www.livechatinc.com) code -->
<?php /*<script type="text/javascript">
window.__lc = window.__lc || {};
window.__lc.license = 7203911;
(function() {
  var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
  lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
})();
</script>*/ ?>
<!-- End of LiveChat code -->

<h2><?php echo __('Fotomatter.net Support', true); ?></h2>
<p><?php echo __('Create a support ticket below. Will we contact you as soon as possible, but please allow up to 12 hours for a reponse via email. If you need help over the phone we can setup a time to call you &mdash; just leave your phone number below and the best times to call.', true); ?></p>
<?php /*<div data-id="07b62296e3" class="livechat_button">
	<a href="https://www.livechatinc.com/customer-service-software/?partner=lc_7203911&amp;utm_source=chat_button">&nbsp;</a>
</div>*/ ?>

<div class="fotomatter_form">
	<?php if (empty($ticket_created)): ?>
		<form action="/admin/accounts/fotomatter_support" method='post'>
			<div class="input text">
				<label><?php echo __('Account Email', true); ?></label>
				<label><?php echo $account_email; ?></label>
			</div>
			<div class="input text">
				<label for="SupportSubject"><?php echo __('Support Subject', true); ?></label>
				<input type='text' name="data[Support][subject]" id="SupportSubject" />
			</div>
			<div class="input textarea">
				<label for="SupportIssue"><?php echo __('Describe Your Issue', true); ?></label>
				<textarea name="data[Support][issue]" cols="100" rows="10" id="SupportIssue" style="width: 700px;"></textarea>
			</div>
			<div class="javascript_submit save_button" style="margin-top: 0px;"><div class="content"><?php echo __('Create Support Ticket', true); ?></div></div>
		</form>
	<?php else: ?>
		<div class='hr_element'></div>
		<h2 style='max-width: 700px;'><?php echo $ticket_created; ?></h2>
	<?php endif; ?>
</div>