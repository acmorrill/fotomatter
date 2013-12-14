<script type="text/javascript" src="/js/angular/lib/angular.min.js"></script>
<script type="text/javascript" src="/js/angular/lib/ui-bootstrap/ui-bootstrap-tpls-0.6.0.min.js"></script>

<!-- Application specific -->
<script type="text/javascript" src="/js/angular/app.js"></script>
<script type="text/javascript" src="/js/angular/controllers/domains.js"></script>
<script type='text/javascript' src='/js/angular/directives/fm_ui_directives.js'></script>
<script type='text/javascript' src='/js/angular/services/model_services.js'></script>
<script type='text/javascript' src='/js/angular/services/util_services.js'></script>
<script type='text/javascript'>
	angular.module('fmAdmin.constants', []).
			value('serverConstants', {
				REQUEST_URI : '<?php echo $_SERVER['REQUEST_URI']; ?>'
			}); 
</script>
