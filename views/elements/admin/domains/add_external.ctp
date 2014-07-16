<section ng-switch-when='add_external'>
	<div class="gen_confirm alert ui-dialog-content ui-widget-content" scrolltop="0" scrollleft="0" style="width: auto; min-height: 63.03999996185303px; height: auto;" >
		<p ng-show="errorMessage != undefined && errorMessage != ''" class='error flashMessage rounded-corners-tiny'><i class='icon-warning-sign'></i><span>{{errorMessage}}</span></p>
		<p>
			<?php echo __('Follow the instructions below to connect {{external_domain}} to your fotomatter.net website.'); ?>
		</p>
		<ol>
			<li>Change the dns nameservers for {{external_domain}} to:
				<ul>
					<li>ns1.fotomatter.net</li>
					<li>ns2.fotomatter.net</li>
				</ul>
				This is usually done through the registar's domain settings (ie godaddy.com, name.com etc).
			</li>
			<li>Press "Add External Domain" on this page.</li>
			<li>Wait 1 to 48 hours and then visit your site at {{external_domain}}.</li>
		</ol>
	</div> 
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<div class="ui-dialog-buttonset">
			<button fm-button ng-click='cancel()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Cancel', true); ?>
			</button>
			<button fm-button ng-click='submit_external_domain()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Add External Domain', true); ?>
			</button>
		</div>
	</div>
</section>