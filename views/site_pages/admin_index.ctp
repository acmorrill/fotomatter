<?php //debug($galleries); ?>

<?php echo $this->Element('/admin/get_help_button'); ?>
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
				var newPosition = ui.item.index() + 1;// TODO - this must always be set - fail otherwise -- not sure if it will be from jquery ui
				
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


<div class="right">
	<?php echo $this->Element('admin/pages/add_page'); ?>
</div>
<div class="clear"></div>

<div class="table_container">
	<div class="fade_background_top"></div>
	<div class="table_top"></div>
	<table class="list">
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
				<th class="">
					<div class="content one_line">
						<?php __('Created'); ?>
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

			<?php foreach($site_pages as $curr_page): ?> 
				<tr page_id="<?php echo $curr_page['SitePage']['id']; ?>">
					<td class="page_id first">
						<div class="rightborder"></div>
						<div class="reorder_page_grabber reorder_grabber" />
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
					<td class="page_created">
						<div class="rightborder"></div>
						<span><?php echo $created_date; ?></span>
					</td> 
					<td class="page_action last">
						<span class="custom_ui">
							<a href="/admin/site_pages/edit_page/<?php echo $curr_page['SitePage']['id']; ?>/"><?php __('Edit'); ?></a>
							<?php if (isset($curr_page['SitePage']['type']) && $curr_page['SitePage']['type'] == 'custom'): ?>
								<a href="/admin/site_pages/configure_page/<?php echo $curr_page['SitePage']['id']; ?>/"><?php __('Configure'); ?></a>
							<?php endif; ?>
							<a class="delete_link" href="/admin/site_pages/delete_page/<?php echo $curr_page['SitePage']['id']; ?>/"><div class="add_button icon"><div class="content">X</div></div></a>
						</span>
					</td>
				</tr>
			<?php endforeach; ?> 
		</tbody>
	</table>
</div>


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