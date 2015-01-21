<p>One or more of your domain(s) is expiring and requires immediate action. </p>
<p>Please <a href="<?php echo $login_url; ?>">renew</a> as soon as possible to avoid a disruption in service. </p> 
<table cellpadding="5" cellspacing="5">
<tbody>

	<tr style="background:#292929; height:10px!important;">

		<td style="width:225px; color:white; font-size:12px;padding:0!important;margin:0!important; line-height:1px!important">
			<p style="margin:0px!important;padding:10px 0 10px 10px"><b>EXPIRING DOMAIN(S)</b></p>
		</td>

		<td style="width:90px;color:white;font-size:10px;padding:0!important;margin:0!important">
			<center><p style="margin:0px!important;padding:10px 0 10px 0"><b>EXPIRES ON</b></p></center>
		</td>

	</tr>
	<?php foreach ($data_to_send as $domain_name => $curr_data): ?>
		<tr style="border-right:1px solid #292929;border-bottom:1px solid #292929;">
				<td style="width:225px;color:black;background-color:#c3c3c3;font-size:11px;">
					<p style="margin:0px!important;padding:10px 0 10px 10px"><?php echo $domain_name; ?></p>
				</td>
				<td style="width:90px;color:black;background-color:#ffd4d6;font-size:11px;">
					<?php if ($curr_data['days_till_expired'] > 0): ?>
						<p style="margin:0px!important;padding:10px 0 10px 0;color:red"><span style="color: red;"><?php echo $curr_data['days_till_expired']; ?></span> day(s)
					<?php else: ?>
							<span style="color: red;">Expired</span></p>
					<?php endif; ?>
				</td>
		</tr>
	<?php endforeach; ?>

</tbody>
<tfoot>
	<tr>
		<td colspan="2" align="center">
			<p><span style="display:inline-block;margin-top:10px;font-size:16px;padding:10px 20px;border:1px solid #32373b; border-radius:3px;
					 color:#ffffff;text-overflow:clip;background:#292929"><a style="color: #ffffff; text-decoration: none;" href="<?php echo $login_url; ?>">Renew domain(s)</a></span></p>
		</td>
	</tr>
</tfoot>
</table>