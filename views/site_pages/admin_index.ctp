<?php //debug($galleries); ?>

<h1><?php echo __('Pages', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p>
	<?php echo __('Add custom pages and external links to your website. Be sure to connect your pages to the main menu after they are created.', true); ?>
</p>
<div style="clear: both;"></div>
<div style="clear: both;"></div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.list tbody').sortable(jQuery.extend(verticle_sortable_defaults, {
			items : 'tr',
			handle : '.reorder_page_grabber',
			axis : 'y',
			update : function(event, ui) {
				var context = this;
				jQuery(context).sortable('disable');
				
				// figure the the now position of the dragged element
				var pageId = jQuery(ui.item).attr('page_id');
				var newPosition = position_of_element_among_siblings(jQuery("#pages_list .ui-sortable tr:not(.spacer)"), jQuery(ui.item));
				
				jQuery.ajax({
					type: 'post',
					url: '/admin/site_pages/ajax_set_page_order/'+pageId+'/'+newPosition+'/',
					data: {},
					success: function(data) {
						if (data.code != 1) {
							// TODO - maybe revert the draggable back to its start position here
						}
					},
					complete: function() {
						jQuery(context).sortable('enable');
					},
					dataType: 'json'
				});
			}
		})).disableSelection();
	});
</script>


<div class="right" data-step="1" data-intro="<?php echo __('Select the type of page you would like to add to your site: design a custom page, add a link to an external page (such as a blog) or create a contact page. Then click Go. Only one contact page can be created at a time.', true); ?>" data-position="bottom">
	<?php echo $this->Element('admin/pages/add_page'); ?>
</div>
<div class="clear"></div>

<div id="pages_list" class="table_container">
	<div class="fade_background_top"></div>
	<div class="table_top"></div>
	<table class="list" data-step="2" data-intro="<?php echo __('This area will display all of the pages that have been created or connected to your site.', true); ?>" data-position="top">
		<thead>
			<tr> 
				<?php /* <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?> */ ?>
				<?php /* <?php echo $this->Paginator->sort(__('Photo ID', true), 'Photo.id'); ?> */ ?>
				<th class="first"></th> 
				<th class="">
					<div class="content one_line">
						<?php __('Title'); ?>
					</div>
				</th> 
				<th class="">
					<div class="content one_line">
						<?php __('Page Type'); ?>
					</div>
				</th> 
				<th class="last"></th>
			</tr> 
		</thead>
		<tbody>
			<tr class="spacer"><td colspan="3"></td></tr>

			<?php if (empty($site_pages)): ?>
				<tr class="first last">
					<td class="first last" colspan="5">
						<div class="rightborder"></div>
						<span>You have not added any pages yet.</span>
					</td>
				</tr>
			<?php endif; ?>

			<?php $count = 1; $count_2 = 1; $step_count = 3; foreach($site_pages as $curr_page): ?> 
				<?php
					$is_custom_page = isset($curr_page['SitePage']['type']) && $curr_page['SitePage']['type'] == 'custom';
				
				
					$edit_button_help = "";
					$configure_button_help = "";
					$order_button_help = "";
					$x_button_help = "";
					if ($is_custom_page) {
						if ($count_2 === 1) {
							$configure_button_help = 'data-step="' . $step_count++ . '" data-intro="' . __("Click “Configure Page” and you will be able change the layout, text, and photos of your custom page.", true) . '" data-position="left"';
						}
						$count_2++;
					} else {
						if ($count === 1) {
							$order_button_help = 'data-step="' . $step_count++ . '" data-intro="' . __("Reordering pages changes the order pages are listed in various places.", true) . '" data-position="right"';
							$edit_button_help = 'data-step="' . $step_count++ . '" data-intro="' . __("Select “Edit” to name the page as it will appear on the main menu.", true) . '" data-position="left"';
							$x_button_help = 'data-step="' . $step_count++ . '" data-intro="' . __("To delete a page, simply click on the X beside the page you wish to delete.", true) . '" data-position="left"';
						}
						$count++;
					}
				?>
				
				<tr page_id="<?php echo $curr_page['SitePage']['id']; ?>">
					<td class="page_id first">
						<div class="rightborder"></div>
						<div class="reorder_page_grabber reorder_grabber icon-position-01" <?php echo $order_button_help; ?> />
					</td> 
					<td class="page_name ">
						<div class="rightborder"></div>
						<span><?php echo $curr_page['SitePage']['title']; ?></span>
					</td> 
					<?php $created_date = $this->Util->get_formatted_created_date($curr_page['SitePage']['created']); ?>
					<td class="page_modified">
						<div class="rightborder"></div>
						<span><?php echo ucwords(str_replace('_', ' ', $curr_page['SitePage']['type'])); ?></span>
					</td>
					<td class="page_action last table_actions">
						<span class="custom_ui">
							<a href="/admin/site_pages/edit_page/<?php echo $curr_page['SitePage']['id']; ?>/">
								<div class="add_button" <?php echo $edit_button_help; ?>>
									<div class="content"><?php echo __('Edit', true); ?></div>
									<div class="right_arrow_lines icon-arrow-01"><div></div></div>
								</div>
							</a>
							<?php if ($is_custom_page): ?>
								<a href="/admin/site_pages/configure_page/<?php echo $curr_page['SitePage']['id']; ?>/">
									<div class="add_button" <?php echo $configure_button_help; ?>>
										<div class="content"><?php echo __('Configure', true); ?></div>
										<div class="right_arrow_lines icon-arrow-01"><div></div></div>
									</div>
								</a>
							<?php endif; ?>
							<a class="delete_link" href="/admin/site_pages/delete_page/<?php echo $curr_page['SitePage']['id']; ?>/">
								<div class="add_button icon icon_close" <?php echo $x_button_help; ?>><div class="content icon-close-01"></div></div>
							</a>
						</span>
					</td>
				</tr>
			<?php endforeach; ?> 
		</tbody>
	</table>
</div>


<?php /*
<?php ob_start(); ?>
<ol>
	<li>This page is where you can see all the pages you've already added</li>
	<li>Things to remember
		<ol>
			<li>This page needs a flash message</li>
			<li>We need style for the sorting etc</li>
			<li>We need style for the add page button</li>
			<li>Don't forget the page settings and configure pages :)</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>
 * 
 */ ?>