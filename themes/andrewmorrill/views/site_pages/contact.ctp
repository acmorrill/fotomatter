<?php //debug($site_page); ?>

<?php echo $this->Session->flash(); ?>
<div class="site_element">
	<?php if (!empty($site_page['SitePage']['contact_header'])): ?>
		<h2><b><?php echo $site_page['SitePage']['contact_header']; ?></b></h2>
	<?php endif; ?>
	<?php if (isset($site_page['SitePage']['contact_message'])): ?>
		<p><?php echo $site_page['SitePage']['contact_message']; ?></p>
	<?php endif; ?>
	<div style="clear: both"></div>
</div>
<div class="site_element">
	<form action="/site_pages/send_contact_us_email/<?php echo $site_page['SitePage']['id']; ?>" method="post" accept-charset="utf-8">
		<div style="display:none;"><input type="hidden" name="_method" value="PUT"></div>
		<div class="input text">
			<label for="ContactUsName"><?php echo __('Your Name', true); ?></label>
			<input name="data[SitePage][contact_us_name]" type="text" maxlength="128" id="ContactUsName">
		</div>
		<div class="input text">
			<label for="ContactUsEmail"><?php echo __('Your Email Address', true); ?></label>
			<input name="data[SitePage][contact_us_email]" type="text" maxlength="128" id="ContactUsEmail">
		</div>
		<div class="input textarea">
			<label for="ContactUsContent">Your Message</label>
			<textarea name="data[SitePage][contact_us_content]" cols="30" rows="6" id="ContactUsContent"></textarea>
		</div>
		<div class="submit">
			<input type="submit" value="Send">
		</div>
	</form>
</div>
<div style="clear: both"></div>


