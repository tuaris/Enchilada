<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title><?php print $title; ?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo theme_dir(); ?>style.css" />
	</head>

	<body id="holder">	
		<div id="page">
			<div id="container">
				<div class="module">

					<?php if($heading) { ?><h4 class="heading-style"><?php print $heading; ?></h4><?php } ?>
				
					<div id="header">
					</div>
					
					<?php print $navigation; ?>								
					<div class="clear"></div>
					
					<div id="wrap">
						<div id="content">						
							<?php print $content; ?>							
						</div>
					</div>

					<div id="footer">
						<div>&nbsp;</div>
					</div>
				
				</div>
			</div>
		</div>
		<p id="copyright"><?php print $copyright; ?></p>
	</body>
</html>