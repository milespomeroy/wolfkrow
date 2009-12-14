<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>View <?=$name?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
	
	<script type="text/javascript" src="/scripts/jquery.js"></script>
	<script type="text/javascript" src="/scripts/ui.core.min.js"></script>
	<script type="text/javascript" src="/scripts/ui.stars.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$(".stars").stars();
		});
	</script>
</head>
<body>
	
	<?php if($this->session->userdata('logged_in')): ?>
	<div class="logout" >
	<?=$this->session->userdata('full_name')?> | <a href="/login/logout">Logout</a>
	</div>
	<?php endif; ?>
	
	<a href="/" id="logo">
		<img src="/images/logo.png" alt="Wolfkrow Diner">
	</a>
	
	<h1><?=$name?> — <?=ucwords($type)?></h1>
	
	<ul id="nav">
	<?php if ($this->session->userdata('user_type') == 'vendor'): ?>
	<li><a href="/vendor">&laquo; Back to Dashboard</a></li>
	<?php else: ?>
	<li><a href="/meal">&laquo; Back to Vendor List</a></li>
	<?php endif; ?>
	</ul>

	<p><img src="/images/<?=$id?>.jpg" alt="<?=$name?>" class="vimg"></p>

	<p> 
		<form class="stars">
			<?php $rating = round($avg_rating); ?>
	        <input type="radio" name="newrate" value="1" 
				title="Poor" <?=is_rated(1, $rating)?> disabled >
	        <input type="radio" name="newrate" value="2" 
				title="Meh" <?=is_rated(2, $rating)?> disabled >
	        <input type="radio" name="newrate" value="3" 
				title="Average" <?=is_rated(3, $rating)?> disabled >
	        <input type="radio" name="newrate" value="4" 
				title="Good" <?=is_rated(4, $rating)?> disabled >
	        <input type="radio" name="newrate" value="5" 
				title="Awesome" <?=is_rated(5, $rating)?> disabled >
	    </form>
	</p>
	<h3>Qualifications:</h3>
	<p class="qualifications"><?=nl2br($qualifications)?></p>
	<p class="price"><strong>$<?=$price?></strong></p>

	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>
	
</body>
</html>