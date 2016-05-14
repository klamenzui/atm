<!DOCTYPE html>
<html>
<title>
My ATM
</title>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>
	<meta name="keywords" content="<?php echo $keywords ?>" />
	<meta name="description" content="<?php echo $description; ?>" />
	<!-- include -->
	<?php foreach($styles as $style): ?>
    <link href="/<?php  echo $url['css'].$style; ?>.css" rel="stylesheet" type="text/css" />
	<?php endforeach; ?>
	<?php foreach($jscripts as $jscript): ?>
	   <script type="text/javascript" src="<?php echo $url['js'].$jscript; ?>.js"></script>
	<?php endforeach; ?> 
	<!-- include End-->
	<script>
		$(document).ready(function() {
			$('li[page="'+(location.pathname.replace('/',''))+'"]').addClass('li-selected')
				.parent().parent().addClass('li-selected');
		});
	</script>
</head>

<body>

<div id="wrapper">
<header>
My ATM
</header>
	<!--<div id="header">
		<a id="a_logo" href="#"></a>
	</div><!-- #header-->

	<div id="middle">
		<article id="container">
			<div id="content_head" class="gradient">
			<div id="bo_logo" >&nbsp;</div>
				<div class="navigation">
						<?php echo$sidebar;?>
				</div>
				<?php echo$account;?>
			</div>
			<div id="content_body">
				<div id="content">	
					<div style="margin-left: 5px; margin-right: 5px;">
						<?php echo $content;?>	
					</div>
				</div><!-- #content-->
			</div>
		</article><!-- #container-->
	</div><!-- #middle-->

</div><!-- #wrapper -->
</body>
</html>