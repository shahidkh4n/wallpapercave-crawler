<?php
if (substr(php_uname(), 0, 7) === 'Windows') {
	print "---------------------------------------------\n";
	print "Please Use Double Qoutes to wrap your search!\n";
	print "---------------------------------------------\n";
}
if (count($argv) <=1) {
	print "Please provide dork to search!\n";
	print "Example:\n[-]\tphp $argv[0] -u <wallpapercave.com url> -d=<folder-to-save>\t[-]\n";
	print "==================================\n";
	print "+				+\n+\tScript By #ShahidKhan\t+\n+				+\n";
	print "==================================";
}else{
	//$search = getopt('',array('u:', 'd:'));
	$search = getopt('u:d::');
	//die(var_dump($search));
	$url = $search['u'];
	$documentRoot = getcwd();
	$folder = ($search['d'] !== '')? $search['d'] : 'downloads';
	if (!is_dir($documentRoot.'/'.$folder)) {
		echo (mkdir($documentRoot.'/'.$folder, 0755, true))?"Folder Created\n" : 'There is some problem in creating folder. Try creating folder manually and try again';
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
		    	echo "$i> ".$realfilename."\n";
		    	$i++;
		    	fclose($fh);
		    }
	}
	}
	print "Total $i image fetched ";
}
