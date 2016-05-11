<h2><?php echo __('Send Feedback', true); ?></h2>
<p><?php echo __('Use the form below to report any bugs or issues you have experienced.', true); ?></p>

<div class="fotomatter_form">
	<?php if (empty($feedback_created)): ?>
		<form action="/admin/accounts/fotomatter_feedback" method='post'>
			<div class="input text">
				<label for="FeedbackSubject"><?php echo __('Subject', true); ?></label>
				<input type='text' name="data[Feedback][subject]" id="FeedbackSubject" />
			</div>
			<div class="input text">
				<label for="FeedbackUrl"><?php echo __('Web Address of the Page You Experienced the Issue', true); ?></label>
				<input type='text' name="data[Feedback][url]" id="FeedbackUrl"  style="width: 700px;"/>
			</div>
			<div class="input textarea">
				<label for="FeedbackIssue"><?php echo __('Describe a Bug or Issue', true); ?></label>
				<textarea name="data[Feedback][issue]" cols="100" rows="10" id="FeedbackIssue" style="width: 700px;"></textarea>
			</div>
			<div class="javascript_submit save_button" style="margin-top: 0px;"><div class="content"><?php echo __('Send Feedback', true); ?></div></div>
		</form>
	<?php else: ?>
		<div class='hr_element'></div>
		<h2 style='max-width: 700px;'><?php echo $feedback_created; ?></h2>
	<?php endif; ?>
</div>