<section ng-switch-when='add_external'>
	<div class="fade_background_top"></div>
	<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle">
		<span id="ui-id-1" class="ui-dialog-title"><?php echo __('Connect External Domain &nbsp;&nbsp;|&nbsp;&nbsp; Instructions', true); ?></span>
		<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close" ng-click='cancel()'>
			<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>
			<span class="ui-button-text">Close</span>
		</button>
	</div>
	
	<div id="error_and_content_cont" class="error_and_content_cont">
		<p ng-show="errorMessage != undefined && errorMessage != ''" class='warning flashMessage'><i class='icon-warning-01'></i><span>{{errorMessage}}</span></p>
		<div class="ui-dialog-content ui-widget-content fotomatter_form" style="width: auto; min-height: 0px;">
			<p>
				<?php echo __('Follow the instructions below to connect {{external_domain}} to your fotomatter.net website.', true); ?>
			</p>
			<ol>
				<li>Change the dns nameservers for {{external_domain}} to:
					<ul>
						<li>dns1.stabletransit.com</li>
						<li>dns1.stabletransit.com</li>
					</ul>
					This is usually done through the registar's domain settings (ie godaddy.com, name.com etc).
				</li>
				<li>Press "Add External Domain" on this page.</li>
				<li>Wait 1 to 48 hours.</li>
				<li>Check to be sure your new domain is set as the primary domain.</li>
				<li>Visit your site at {{external_domain}}.</li>
			</ol>
		</div> 
	</div>
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<div class="ui-dialog-buttonset">
			<button fm-button ng-click='cancel()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Cancel', true); ?>
			</button>
			<button fm-button ng-click='submit_external_domain()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Connect External Domain', true); ?>
			</button>
		</div>
	</div>
</section>