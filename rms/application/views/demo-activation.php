<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>Review Vendor Applications</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
</head>
<body>
	
	<div class="logout" >
	<a href="/">Login</a>
	</div>

<h1>Vendor Account Activation</h1>

<p>Normally, after the manager has made a decision on whether to hire or deny the
vendor who applied, an email would be sent out to the applicant. If the person
was hired, then an account activation link would be included.</p>

<p>For this prototype I&#8217;m not sending out emails to the vendor applicants.
Instead, this page will list the vendor account activation links. To activate 
a vendor account, login as the manager (email: admin@admin.com, password: admin) 
and click on “Review Vendor Applications.”</p>

<?php if ($apps): ?>
<?php foreach ($apps as $app): ?>
	<a href="/vendor/activate/<?=$app->app_id?>/
	<?php echo md5($app->email . $this->config->item('app_pass_phrase')); ?>">
		<?=$app->vendor_name?> — <?=$app->email?>
	</a><br>
<?php endforeach; ?>
<?php else: ?>
	<p><em>No vendor accounts to be activated.</em></p>
<?php endif; ?>

<div id="footer">
	&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
</div>

</body>
</html>

