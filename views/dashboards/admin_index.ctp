<?php ob_start(); ?>
<ol>
	<li>Ideas for what could go on this page
		<ol>
			<li>Credit card summary (with a link to edit page) - ask Adam about where the current one is
				<ol>
					<li>Also next bill date summary etc</li>
				</ol>
			</li>
			<li>Stats
				<ol>
					<li>Total Galleries (ie 30/50)</li>
					<li>Total Photos (ie 30/50)</li>
					<li>Total Pages (ie 30/50)</li>
				</ol>
			</li>
			<li>We may want some getting started stuff on the dashboard - so if they haven't added any photos - then a link to add photos is there</li>
			<li>Maybe Don't do - A log of recent logins (for security)</li>
			<li>Your current site - with line to edit theme</li>
			<li>Maybe Don't do - Recent Orders</li>
			<li>Maybe Don't do - Internal Adds based on what features they have</li>
			<li>Maybe Don't do - money you are owed (if you have sales)</li>
			<li>Maybe Don't do - Domain Summary</li>
		</ol>
	</li>
	<li>Maybe make the design 'widgety' so we can dynamically add/remove stuff</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>