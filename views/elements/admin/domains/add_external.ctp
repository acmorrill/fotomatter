<div ng-switch-when='add_external' class='add_external'>
	<div ng-show="errorMessage != undefined && errorMessage != ''" class='error flashMessage rounded-corners-tiny'>
		<i class='icon-warning-sign'></i><span>{{errorMessage}}</span>
	</div>
	<h3><?php echo __('Follow the instructions below to connect {{external_domain}} to your fotomatter.net website.'); ?></h3>
	<ol>
		<li>Change the dns nameservers for {{external_domain}} to:
			<ul>
				<li>ns1.fotomatter.net</li>
				<li>ns2.fotomatter.net</li>
			</ul>
			This is usually done through the registar's domain settings (ie godaddy.com, name.com etc).
		</li>
		<li>Press "OK" on this page.</li>
		<li>Wait 1 to 48 hours and then visit your site at {{external_domain}}.</li>
	</ol>

	<div style='position:relative' class="input continue">
		<button fm-button ng-click='submit_external_domain()'><?php echo __('OK'); ?></button>
	</div>
</div>