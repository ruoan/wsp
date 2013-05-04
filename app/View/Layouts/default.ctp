<?php
/*
初期ホーム画面
 */
$mainTitle ='The Mall 次世代のショッピングサイト';






?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $mainTitle ?>:
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
   
     <!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

    <!-- Le styles -->
    
	<?php
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('elusive-webfont');
		echo $this->Html->css('main');
	?>
</head>
<body>
 			<?php echo $this->fetch('content'); ?>
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    		<?php echo $this->Html->script('bootstrap-with-responsive.min'); ?>
    		<!--[if lte IE 7]><?php echo $this->Html->script('lte-ie7'); ?><![endif]-->
</body>
</html>
