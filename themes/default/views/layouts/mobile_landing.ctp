<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Element('theme_mobile_global_includes'); ?>
	

	<script type="text/javascript">
		
		/*
		 * IMPORTANT!!!
		 * REMEMBER TO ADD  rel="external"  to your anchor tags. 
		 * If you don't this will mess with how jQuery Mobile works
		 */
		
		(function(window, $, PhotoSwipe){
			
			$(document).ready(function(){
				
				$('div.gallery-page')
					.live('pageshow', function(e){
						
						var 
							currentPage = $(e.target),
							options = {},
							photoSwipeInstance = $("ul.gallery a", e.target).photoSwipe(options,  currentPage.attr('id'));
							
						return true;
						
					})
					
					.live('pagehide', function(e){
						
						var 
							currentPage = $(e.target),
							photoSwipeInstance = PhotoSwipe.getInstance(currentPage.attr('id'));

						if (typeof photoSwipeInstance != "undefined" && photoSwipeInstance != null) {
							PhotoSwipe.detatch(photoSwipeInstance);
						}
						
						return true;
						
					});
				
			});
		
		}(window, window.jQuery, window.Code.PhotoSwipe));
		
	</script>
	
</head>
<body>

<div data-role="page" id="Home">

	<div data-role="header">
		<h1>PhotoSwipe</h1>
	</div>
	
	
	<div data-role="content" >	
		<ul data-role="listview" data-inset="true">
			<?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
			<?php foreach ($all_galleries as $curr_gallery): ?>
				<?php 
					$curr_gallery_href = $this->Html->url(array(    
						'controller' => 'photo_galleries',    
						'action' => 'view_gallery',    
						$curr_gallery['PhotoGallery']['id']
					));
				?>
				<li><a href="<?php echo $curr_gallery_href; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></a></li> 
			<?php endforeach; ?>
		</ul>
	</div>

	<div data-role="footer">
		<h4>&copy; 2012 Code Computerlove</h4>
	</div>

</div>

</body>
</html>
