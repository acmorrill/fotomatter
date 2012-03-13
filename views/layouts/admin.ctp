<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Admin'); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<style media="all" type="text/css">@import "/css/admin.css";</style>
        <script type="text/javascript" src="/js/jquery-1.6.min.js"></script>
        <script type="text/javascript" src="/js/jquery-ui-1.8.12/js/jquery-ui-1.8.12.custom.min.js"></script>
        <script type="text/javascript" src="/js/jquery-validation-1.8.0/jquery.validate.min.js"></script>
</head>
<body>
<div id="main">
     <div id="header">
           <a href="/admins/index" class="logo"><img src="/img/misc/MyName.png" alt="" /></a>
           <ul id="top-navigation">
                <?php echo $this->Menu->adminMenuItem('photos', $this->name); ?>
                <?php echo $this->Menu->adminMenuItem('admins', $this->name); ?>
           </ul>
     </div>
     <div id="middle">
          <?php if (empty($adminLeftMenu)) $adminLeftMenu = array(); ?>
          <?php echo $this->Menu->adminLeftMenu($adminLeftMenu); ?>
          <div id="center-column">
                <?php echo $content_for_layout; ?>
          </div>
     </div>
     <div id="footer"></div>
</div>


</body>
</html>