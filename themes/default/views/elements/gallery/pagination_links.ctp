<a name="pagination_start"></a>
<?php // DREW TODO - make it so that the pagination links go to the above spot on the next page -- ie the likns end in #pagination_start ?>
<?php // DREW TODO - make it so the pagination is not there at all if there are no pages ?>
<div class="paginationDiv" style="<?php echo $extra_css; ?>">
	<?php 
		$current_page = $this->Paginator->params['paging']['PhotoGalleriesPhoto']['page'];
		$total_pages = $this->Paginator->params['paging']['PhotoGalleriesPhoto']['pageCount'];
	?>
	<?php 
		if ($current_page - 3 >= 1) {
			echo $this->Paginator->first(__('First', true));
		}
	?>
	<?php 
		echo $this->Paginator->prev(__('Prev&nbsp;', true), array(
			'escape' => false
		));
	?>
	<?php if ($current_page - 2 > 1): ?>
		<span>...</span>
	<?php endif; ?>
	<?php 
		echo $this->Paginator->numbers(array(
			'separator' => ' ',
			'modulus' => 4,
		));
	?>
	<?php if ($total_pages - $current_page > 2): ?>
		<span>...</span>
	<?php endif; ?>
	<?php 
		echo $this->Paginator->next(__('&nbsp;Next', true), array(
			'escape' => false
		));
	?>
	<?php 
		if ($total_pages - $current_page >= 3) {
			echo $this->Paginator->Last(__('Last', true));
		}
	?>
</div>