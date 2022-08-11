#!/usr/bin/php -q
<?php
//mail("sajawal@nimblewebsolutions.com","data",'at start');
include_once("db.php");
include_once("functions.php");
require_once("mime_parser.php");
require_once("rfc822_addresses_class.php");
function base64_to_image($base64_string, $output_file) {
    $ifp = fopen($output_file, "wb"); 

    //$data = explode(',', $base64_string);

    fwrite($ifp, $base64_string); 
    fclose($ifp); 

    return $output_file; 
}
//Listen to incoming e-mails

$email = '';

//Read e-mail into buffer
if (!$stdin = fopen("php://stdin", "R")) {
	echo "ERROR: UNABLE TO OPEN php://stdin " . PHP_EOL;
}else{
	while (!feof($stdin)){
		$email .= fread($stdin, 4096);
	}
	fclose($stdin);
}
//Close socket

//Assumes $email contains the contents of the e-mail
//When the script is done, $subject, $to, $message, and $from all contain appropriate values
//mail("sajawal@nimblewebsolutions.com","Email Pipe Content",$email);
//Parse "subject"
$subject1 = explode ("\nSubject: ", $email);
$subject2 = explode ("\n", $subject1[1]);
$subject = $subject2[0];

//Parse "to"
//create the email parser class
$mime=new mime_parser_class;
$mime->ignore_syntax_errors = 1;
$parameters=array(
	'Data'=>$email,
);
	
$mime->Decode($parameters, $decoded);

//print_r($decoded);;
//---------------------- GET EMAIL HEADER INFO -----------------------//

//get the name and email of the sender
$fromName = $decoded[0]['ExtractedAddresses']['from:'][0]['name'];
$fromEmail = $decoded[0]['ExtractedAddresses']['from:'][0]['address'];

//get the name and email of the recipient
$toEmail = $decoded[0]['ExtractedAddresses']['to:'][0]['address'];
$toName = $decoded[0]['ExtractedAddresses']['to:'][0]['name'];

//get the subject
$subject = $decoded[0]['Headers']['subject:'];

$removeChars = array('<','>');

//get the message id
$messageID = str_replace($removeChars,'',$decoded[0]['Headers']['message-id:']);

//get the reply id
$replyToID = str_replace($removeChars,'',$decoded[0]['Headers']['in-reply-to:']);

//---------------------- FIND THE BODY ------------------//

//get the message body
if(substr($decoded[0]['Headers']['content-type:'],0,strlen('text/plain')) == 'text/plain' && isset($decoded[0]['Body'])){
	
	$body = $decoded[0]['Body'];

} elseif(substr($decoded[0]['Parts'][0]['Headers']['content-type:'],0,strlen('text/plain')) == 'text/plain' && isset($decoded[0]['Parts'][0]['Body'])) {
	
	$body = $decoded[0]['Parts'][0]['Body'];

} elseif(substr($decoded[0]['Parts'][0]['Parts'][0]['Headers']['content-type:'],0,strlen('text/plain')) == 'text/plain' && isset($decoded[0]['Parts'][0]['Parts'][0]['Body'])) {
	
	$body = $decoded[0]['Parts'][0]['Parts'][0]['Body'];

}
$file = "";
if(isset($decoded[0]['Parts'][1]['FileName']))
{
    $img = $decoded[0]['Parts'][1]['Body'];
    //$data = base64_decode($img);
    

    $file = explode('.',$decoded[0]['Parts'][1]['FileName']);
    $file1 = "images/".uniqid().$file[1];
    base64_to_image($img,$file1);
	//$success = file_put_contents($file, $data);
    //mail("sajawal@nimblewebsolutions.com","Email Pipe Content",$file);
}
/*echo "

Message ID: $messageID

Reply ID: $replyToID

Subject: $subject

To: $toName $toEmail

From: $fromName $fromEmail

Body: $body

";*/
//mail("sajawal@nimblewebsolutions.com","Email Pipe Content",print_r($decoded,true));
$data = array("to_user"=>$toEmail,"from_user"=>$fromEmail,"text_body"=>$body,"response"=>$decoded);
$sql = insert_sql("email_pipe",$data);
mysql_query($sql);

$msg_mth = explode(PHP_EOL, $body);
$method = preg_replace('/\s+/', '', $msg_mth[0]);

$snd_body = explode(":",$msg_mth[1]);

$send_body = trim($snd_body[1]);

/***Going to send bulk sms**/
if($method=="tl_To:bulksms" || $method=="tl_To:masssms")
{
    $media = array();
    
    //mail("sajawal@nimblewebsolutions.com","Media url",$media_url);
    $sql = "select * from settings where id='1'";
    $res = mysql_query($sql);
    $data = mysql_fetch_assoc($res);
    if($file1!="")
    {
        $media_url = $data['install_url'].$file1;
    //$media = array($media_url);
    }
    $que = "select * from customers";
    $q = mysql_query($que);
    if(mysql_num_rows($q)>0)
    {
        //$url = "https://$data[twilio_sid]:$data[twilio_token]@api.twilio.com/2010-04-01/Accounts/$data[twilio_sid]/Messages";
         while($rr = mysql_fetch_assoc($q)){
            /*$arr = array(
            "To"=>$rr['customer_number'],
            "From"=>$data['twilio_number'],
            "Body"=>$send_body
            );*/
           
            sendMMS($rr['customer_number'],$send_body,$media_url);
            //$res = post_curl($url,$arr,'post');
           // $check = (array)simplexml_load_string($res);
            
            //echo "<pre>";
            //print_r($check);
           /* if(isset($check['RestException']))
            {
                echo '{"success":"false"}';
            }else
            {
                $arr_ins = array(
                "to_number"=>$rr['customer_number'],
                "from_number"=>$data['twilio_number'],
                "Body"=>$send_body,
                "SmsSid"=>$check['Message']['Sid'],
                "is_mms"=>$ismms,
                "type"=>"bulk",
                "response"=>json_encode($check)
                );
                $sql_ins = insert_sql("incoming_sms",$arr_ins);
                mysql_query($sql_ins);
            }*/
        }
    }

}
