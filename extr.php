<?php
	error_reporting(E_ERROR | E_PARSE);

	//include('simple_html_dom.php');
	function fetcher($ch){
	$Status='true';
	set_time_limit(5);
	$url="https://socialblade.com/youtube/user/".$ch;
	$jobj->Channel_name=$ch;
	//$starttime = microtime(true);
	$curl = curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
	//curl_setopt($curl, CURLOPT_COOKIEJAR, 'amazoncookie.txt');
	//curl_setopt($curl, CURLOPT_COOKIEFILE, 'amazoncookie.txt');
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, 1);
	$result = curl_exec($curl);
	if(strpos(strval($result),"Content-Length: 0")!=""){
		$Status='false';
	}
	curl_close($curl);
	$document = new DOMDocument;
 
	libxml_use_internal_errors(true);
	$document->loadHTML($result);
	$xpath = new DOMXPath($document);
	$tweets = $xpath->query('//*[@id="YouTubeUserTopInfoBlock"]/div[3]/span[2]');
	$jobj->Subscribers=$tweets[0]->nodeValue;
	$tweets = $xpath->query('//*[@id="YouTubeUserTopInfoBlock"]/div[4]/span[2]');
	$jobj->Video_views=$tweets[0]->nodeValue;
	$tweets = $xpath->query('//*[@id="youtube-user-page-channeltype"]');
	$jobj->Channel_type=$tweets[0]->nodeValue;
	$tweets = $xpath->query('//*[@id="socialblade-user-content"]/div[3]/div[2]/p[1]');
	$jobj->Monthly_revenue=trim(trim($tweets[0]->nodeValue),"\n");
	$tweets = $xpath->query('//*[@id="socialblade-user-content"]/div[5]/div[1]/div[2]/p[1]');
	$jobj->Yearly_revenue=trim(trim($tweets[0]->nodeValue),"\n");
	$tweets = $xpath->query('//*[@id="YouTubeUserTopInfoAvatar"]/@src');
	$jobj->Profile_pic=trim(trim($tweets[0]->nodeValue),"\n");
	$tweets1 = $xpath->query("//*[@id='YouTubeUserTopHeaderBackground']/@style");
	$jobj->Cover_pic=explode("');",trim(trim($tweets1[0]->nodeValue),"background-image: url('"))[0];
	//print_r($jobj);
	$jobj = json_encode($jobj);
	//echo "Total time elapsed: ". (microtime(true)-$starttime." seconds");
	$jobj->Status=$Status;
	return $jobj;
	}
	echo fetcher("tseriesmusic");


?>