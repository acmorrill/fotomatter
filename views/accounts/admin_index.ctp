<script type="text/javascript">
$(document).ready(function() {
   $("#line_item_cont .line_item .bar .bar-right").click(function() {
       console.log('move');
       if ($(this).closest('.bar').next(".item_ad_area").is(':hidden')) {
           $(this).closest('.bar').next(".item_ad_area").slideDown();
           var icon = $(this).closest('.bar').find(".bar-right i.icon-angle-down");
           icon.removeClass('icon-angle-down').addClass('icon-angle-left');
       } else {
           $(this).closest('.bar').next(".item_ad_area").slideUp();
           var icon = $(this).closest('.bar').find(".bar-right i.icon-angle-left");
           icon.removeClass('icon-angle-left').addClass('icon-angle-down');
       }
   });
   
   $("#line_item_cont .line_item .bar input").change(function() {
      console.log('change'); 
   });
});
</script>
<div id="line_item_cont">
    <div class="body_container">   
        <?php foreach ($line_items['items'] as $line_item): ?>
           <div class="line_item">
               <div class="bar hidden">
                   <h5><?php echo $line_item['AccountLineItem']['name']; ?> - $<?php echo $line_item['AccountLineItem']['current_cost']; ?></h5>
                       <div class="bar-right">
                           <span>Active</span><input <?php echo $line_item['AccountLineItem']['active']?'CHECKED':''; ?> type='checkbox' name='data[AccountLineItem][]' data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' />
                           <span>Show Details</span><i class="icon-angle-down"></i>
                       </div>
               </div>
               <div class="item_ad_area">
                   <?php
                   $ad_file = "line_item_ads/" . str_replace(' ' , '_', strtolower($line_item['AccountLineItem']['name']));
                   if (is_file(ROOT . '/app/views/elements/' . $ad_file . '.ctp')) {
                       echo $this->Element($ad_file);
                   } else {
                       echo $this->Element('line_item_ads/default');
                   }
                   ?>
               </div>
           </div>
        <?php endforeach; ?>
    </div>
</div>