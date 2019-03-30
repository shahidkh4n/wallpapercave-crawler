<!DOCTYPE html>
<html>
<head>
	<title>WallpaperCave Image Crawler</title>
	<link rel="stylesheet" type="text/css" href="/includes/bootstrap.min.css">
	<style type="text/css">
	.content{
		margin-top: 12%; 
		margin-left: 20%;
		padding: 2%;
	}
	.form-control{
		padding: 2%;
	}
	</style>
</head>
<body>
<div class="container">
<div class="">
<?php
if(isset($_POST['url'])){

	$url = $_POST['url'];
	$documentRoot = getcwd();

	$folder = (isset($_POST['dir']))? $_POST['dir'] : 'downloads';
	if (!is_dir($documentRoot.'/'.$folder)) {
		echo (mkdir($documentRoot.'/'.$folder, 0755, true))?"Folder Created<br>" : 'There is some problem in creating folder. Try creating folder manually and try again';
	}
	//$url = 'http://wallpapercave.com/hackers-wallpaper';
	function curl($url)
	{
		$ch = curl_init();
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => 1
			);
		curl_setopt_array($ch, $options);
		$res = curl_exec($ch);
		$header = curl_getinfo($ch);
		curl_close($ch);
		return array($res, $header);
	}

	$dom = new DOMDocument();
	@$dom->loadHTML(curl($url)[0]);
	$imgs = $dom->getElementsByTagName('img');
	$i = 0;
	foreach ($imgs as $eachImg) {
		if ($eachImg->hasAttribute('data-url')) {
			$imgUrl = $eachImg->getAttribute('src');
			$imgFile = curl($imgUrl);
			//file name
		    $stripped_url = parse_url($imgUrl);
		    
		    $path = (isset($stripped_url['path']))? $stripped_url['path'] : 'img'."$i";

		    $path = explode('/', $path);
		    $realfilename = $path[2];
			//store image
			$fh = fopen($folder.'/'.$realfilename, 'w');
			if ($imgFile[0]){
		    	fwrite($fh, $imgFile[0] );
		    	echo "<p>$i> ".$realfilename."\n</p>";
		    	$i++;
		    }
	}
	}
	print "<p class='text text-primary'>Total $i image fetched </p>";
}else{
?>
<div class="row">
<div class="col-md-6 content">
	<div class="panel panel-primary">
	<div class="panel-heading">
		<h3>Welcome , Enter Url to Fetch Images!</h3>
	</div>
	<div class="panel-body">
		<form class="form" role="form" method="POST" action="">
		<div class="form-group">
		<label for="url">URL</label>
			<input type="text" name="url" class="form-control" required>
		<label for="dir">Directory</label>
			<input type="text" name="dir" class="form-control" required>
		</div>
		<input type="submit" value="submit" class="btn btn-primary">	
		</form>
	</div>							
	</div>
</div>
</div>
</div>
</body>
</html>
<?php 
} ?>