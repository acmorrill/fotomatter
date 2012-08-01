// some javascript
$(document).ready(function() {
  $('div.showHideBars:eq(0)> div:gt(0)').hide();  
  $('div.showHideBars:eq(0)> h1').click(function() {
    $(this).next().slideToggle('fast');
  });
});