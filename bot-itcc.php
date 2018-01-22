<?php
$access_token = 'So62epFJ8L16MGZG7ACpYESWJwWSF61ulR2XedNBuwhlFhCT/+p1GlDmgx3f1PVoAgwk9CeWOLNPvgSi9pCoBF+Utk3yRwnMjEhEhAlrzUtN+sKWQ7TPht2lr22HNEUDRom/e+ovWyaQsvJSYNdwOwdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (isset($events['events'])){
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];

			// Get replyToken
			$replyToken = $event['replyToken'];

			if(substr($text,0,7) == '@regis '){
			// Build message to reply back
				$name = substr($text,7);

				$response = 'ลงทะเบียนสำเร็จ';

				// $response = 'ไม่พบชื่อนี้ในระบบ';
			}else{
				$response = 'ระบุไม่ถูกต้อง';
			}

			$messages = array(
					'type' => 'text',
					'text' => $response
			);	
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => array($messages),
			];

			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
elseif(isset($events['rid'])){
       	$title = $events['title'];
		$messages = array (
			  'type' => 'template',
				  'altText' => 'แจ้งปัญหา '.$title,
				  "template" => array (
					    'type' => 'buttons',
					    'thumbnailImageUrl' => 'https://goo.gl/FCTcny',
					    'imageAspectRatio' => 'rectangle',
					    'imageSize' => 'cover',
					    'imageBackgroundColor' => '#FFFFFF',
					    'title' => $title,
					    'text' => $events['text'],
					    'actions' => [
					      array (
					        'type' => 'uri',
					        'label' => 'ดูรายละเอียด',
					        'uri' => $events['url'],
					      ) ]
					    )
			 );

		$url = 'https://api.line.me/v2/bot/message/push';
		$data = [
			'to' => 'U941733818ea3dec842a5a7126c787382',
			'messages' => array($messages),
		];

		$post = json_encode($data);
		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);

		echo $result . "\r\n";
}
echo "OK";
