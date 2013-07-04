<script type="text/javascript">
$(document).ready(function() {
   $("#line_item_cont .line_item .bar .bar-right span.toggle").click(function() {
       if ($(this).closest('.bar').next(".item_ad_area").is(':hidden')) {
           $(this).closest('.bar').next(".item_ad_area").slideDown();
           var icon = $(this).closest('.bar').find(".bar-right i.icon-angle-down");
           icon.removeClass('icon-angle-down').addClass('icon-angle-right');
       } else {
           $(this).closest('.bar').next(".item_ad_area").slideUp();
           var icon = $(this).closest('.bar').find(".bar-right i.icon-angle-right");
           icon.removeClass('icon-angle-right').addClass('icon-angle-down');
       }
   });
   
   
   var inAjaxCall = false;
   $("#line_item_cont .line_item .bar input").change(function(e) {
      e.preventDefault();
      if (inAjaxCall) {
          return false;
      }
      inAjaxCall = true;
      var args_item = {};
      args_item.checked = 0;
      if ($(this).is(':checked')) {
          args_item.checked = 1;
      }
      args_item.id = $(this).attr('data_id');
      var this_check_box = $(this);
      $("#line_item_cont .finish input[type=button]").fadeIn();
      $.post('/accounts/ajax_setItemChecked', args_item, 
           function(data) {
               inAjaxCall = false;
               $("#line_item_cont .line_item .bar input").removeAttr('disabled');
           }, 'json');
       return false;
   });
   
   $("#line_item_cont .finish input[type=button]").click(function() {
       $.get("/accounts/ajax_finishLineChange", function(data) {
           var div = $("<div></div>");
           div.html(data.html);
           div.dialog({
               width:'600',
               height:'500',
               title: '<?php echo __('Finish Account Changes'); ?>',
           });
       }, 'json') 
   });
});
</script>
<div id="line_item_cont">
    <div class="finish">
        <input type="button" value="Finish Changes" />
    </div>
    <div class="body_container">   
        <?php foreach ($line_items['items'] as $line_item): ?>
           <div class="line_item">
               <div class="bar hidden">
                   <h5><?php echo $line_item['AccountLineItem']['name']; ?> - $<?php echo $line_item['AccountLineItem']['current_cost']; ?></h5>
                       <div class="bar-right">
                           <span>Active</span><input <?php echo $line_item['AccountLineItem']['active']?'CHECKED':''; ?> type='checkbox' name='data[AccountLineItem][]'  data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' />
                           <span class="toggle">Show Details</span><i class="icon-angle-down"></i>
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