<p>The following fotomatter.net domain(s) are set to expire. Please <a href="<?php echo $login_url; ?>">login</a> to renew domains before time expires.</p>
<table cellpadding="10" cellspacing="10">
	<thead>
		<tr>
			<th style="text-align: left;">Domain</th>
			<th style="text-align: left;">Expires</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($data_to_send as $domain_name => $curr_data): ?>
			<tr>
				<td style="text-align: left;"><?php echo $domain_name; ?></td>
				<td style="text-align: left;">
					<?php if ($curr_data['days_till_expired'] > 0): ?>
						<span style="color: red;"><?php echo $curr_data['days_till_expired']; ?></span> day(s)
					<?php else: ?>
						<span style="color: red;">Expired</span>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>