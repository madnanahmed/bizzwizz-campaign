<?php
namespace App\Traits;
use App\PhoneNumbers;
use App\School;
use App\Settings;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Twilio\Rest\Client;

trait MainTrait
{
  /**
   * Function to generate random string for unique url
   **/
    static function getClient()
    {
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes(\Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAuthConfig(base_path('credentials.json') );
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = base_path('token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = '4/-QCyFluaB2Y5v2IgR4yyqgOB93tgTWmO91u0NC1kKhdcI5UV9Nit-ak';// trim(fgets(STDIN));


                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    static function getChildSheets($sheet_id){
        try{
            // Get the API client and construct the service object.
            $client = MainTrait::getClient();
            $service = new \Google_Service_Sheets($client);
            $spreadsheetId = $sheet_id;
            $sheets = array();

            $response = $service->spreadsheets->get($spreadsheetId);
            foreach($response->getSheets() as $s) {
                $sheets[] = $s['properties']['title'];
            }
            return $sheets;
        }catch (\Exception $e){
            return [];
        }
    }

    static function countSheetRecords($spreadsheetId, $sheet_name){
        try{
            $client = MainTrait::getClient();
            $service = new \Google_Service_Sheets($client);
            $range = $sheet_name.'!A1:D'; // all records from sheet of sheet1

            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $total_sheets_records = $response->count();
            return $total_sheets_records;

        }catch (\Exception $e){
            return [];
        }
    }


    static function TwilioSendSms($sid, $token, $to, $from, $message, $mms = false){

        $url = "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages";

        $data = array (
            'From' => $from,
            'To' => $to,
            'Body' => $message,

        );

        if($mms){
            $ext = pathinfo($mms, PATHINFO_EXTENSION);
            $data["mediaUrl"] = $mms;
            $data["MediaContentType"] = 'image/'.$ext;
        }

        //mail('adnang7274@gmail.com', 'curl DATA',  print_r($data, true) );



        $post = http_build_query($data);
        $x = curl_init($url );
        curl_setopt($x, CURLOPT_POST, true);
        curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($x, CURLOPT_USERPWD, "$sid:$token");
        curl_setopt($x, CURLOPT_POSTFIELDS, $post);
        $y = curl_exec($x);
        curl_close($x);

        return $y;
    }

    static function nexmoSMS($api_key, $api_secret, $from, $to, $message){
        $url = 'https://rest.nexmo.com/sms/json?' . http_build_query([
                'api_key' => $api_key,
                'api_secret' => $api_secret,
                'to' => $to,
                'from' => $from,
                'text' => $message
            ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        return $response;
    }

    static function twilioPhoneNumber(){

        $settings = Settings::where('user_id', auth()->user()->id)->first();

        if($settings) {

            $twilio = new Client($settings->twilio_sid, $settings->twilio_token);

            $incoming_phone_number = $twilio->incomingPhoneNumbers->read(array(), 100);
            echo '<pre>';
            foreach ($incoming_phone_number as $record) {
                $incoming_phone = $twilio->incomingPhoneNumbers($record->sid)
                    ->fetch();

                $data = [
                    'friendlyName' => $incoming_phone->friendlyName,
                    'identitySid' => $incoming_phone->identitySid,
                    'phoneNumber' => $incoming_phone->phoneNumber,
                    'origin' => $incoming_phone->origin,
                    'sid' => $incoming_phone->sid,
                    'smsApplicationSid' => $incoming_phone->smsApplicationSid,
                    'smsFallbackMethod' => $incoming_phone->smsFallbackMethod,
                    'smsFallbackUrl' => $incoming_phone->smsFallbackUrl,
                    'smsMethod' => $incoming_phone->smsMethod,
                    'smsUrl' => $incoming_phone->smsUrl,
                    'statusCallback' => $incoming_phone->statusCallback,
                    'statusCallbackMethod' => $incoming_phone->statusCallbackMethod,
                    'trunkSid' => $incoming_phone->trunkSid,
                    'uri' => $incoming_phone->uri,
                    'voiceApplicationSid' => $incoming_phone->voiceApplicationSid,
                    'voiceCallerIdLookup' => $incoming_phone->voiceCallerIdLookup,
                    'voiceFallbackMethod' => $incoming_phone->voiceFallbackMethod,
                    'voiceFallbackUrl' => $incoming_phone->voiceFallbackUrl,
                    'voiceMethod' => $incoming_phone->voiceMethod,
                    'voiceUrl' => $incoming_phone->voiceUrl,
                    'emergencyStatus' => $incoming_phone->emergencyStatus,
                    'emergencyAddressSid' => $incoming_phone->emergencyAddressSid,
                ];

                if (!PhoneNumbers::where('number', $incoming_phone->phoneNumber)->exists()) {
                    PhoneNumbers::create(['number' => $incoming_phone->phoneNumber, 'phone_sid' => $incoming_phone->sid, 'data' => json_encode($data)]);
                }
            }

            return response()->json(['success' => true]);
        }
    }
}
