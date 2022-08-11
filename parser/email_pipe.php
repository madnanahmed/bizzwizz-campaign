#!/usr/bin/php -q
<?php
//mail('adnang7274@gmail.com','email pipe', 'at start');

/*die();*/

$con = mysqli_connect('localhost','seepak_sent','Sent_12345','seepak_campaigns');

if($con){

    require_once("mime_parser.php");
    require_once("rfc822_addresses_class.php");

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

    //create the email parser class
    $mime=new mime_parser_class;
    $mime->ignore_syntax_errors = 1;
    $parameters=array(
        'Data'=>$email,
    );
    $mime->Decode($parameters, $decoded);


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
    
    $body = $body. 'from email ='.$fromEmail.'<br> to email ='.$toEmail;
    

    //if (strpos($fromEmail, '@theitobjects') !== false) {
    
    $pat = '/\%([^\"]*?)\%/'; // text between quotes excluding quotes
        
        preg_match($pat, $body, $matches);
        
        
        //mail("adnang7274@gmail.com",$subject, print_r($matches, true) );

        if(isset($matches[1])){
            
            $list_name = $matches[1];
            mail("adnang7274@gmail.com",$subject, $body.$list_name);
            
            mysqli_query($con, "insert into `incomming_emails` (`from_email`,	`to_email`, `subject`, `body`, `list_name`, `attachment`)VALUES ('$fromEmail', '$toEmail', '$subject', '$body', '$list_name', '')");
        }
//}
    
    
    
    
    
    
    
    
    

    /*$file = "";
    if(isset($decoded[0]['Parts'][1]['FileName']))
    {
        $img = $decoded[0]['Parts'][1]['Body'];
        //$data = base64_decode($img);


        $file = explode('.',$decoded[0]['Parts'][1]['FileName']);
        $file1 = "images/".uniqid().$file[1];
        base64_to_image($img,$file1);
        //$success = file_put_contents($file, $data);
        //mail("sajawal@nimblewebsolutions.com","Email Pipe Content",$file);
    }*/

    /*echo "
    Message ID: $messageID
    Reply ID: $replyToID
    Subject: $subject
    To: $toName $toEmail
    From: $fromName $fromEmail
    Body: $body
    ";*/


    


}