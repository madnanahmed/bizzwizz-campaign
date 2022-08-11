<?php

namespace App\Http\Controllers;

use App\Agents;
use App\AgentsSheets;
use App\Campaign;
use App\CampaignEmailMeta;
use App\Contacts;
use App\EmailSettings;
use App\LeadFeedback;
use App\Mms;
use App\ScheduleFeedback;
use App\Settings;
use App\SheetsContacts;
use App\SheetsData;
use App\Traits\MainTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Shivella\Bitly\Client\BitlyClient;
use Shivella\Bitly\Facade\Bitly;
use Twilio;
use Twilio\Rest\Client;

class CronController extends Controller
{
    private $client, $service, $toEmail, $fromName, $fromEmail, $replyTo, $subject;


    function executeCampaign()
    {
        $user = User::where('user_type', 'a')->first();
        $emailSettings = EmailSettings::where('user_id', $user->id)->first();
        $twilio_settings = Settings::where('user_id', $user->id)->first();

        if ($user && $emailSettings && $twilio_settings) {
            $this->fromEmail = $emailSettings->from_email;

            $campaigns = Campaign::where(['status' => 1])->get();
            foreach ($campaigns as $campaign) {

                try {
                    //mms
                    $link = $mms_cover = '';

                    $contacts = DB::table('contacts')
                        ->whereRaw("list_id in ($campaign->category_ids) and status = 0")
                        ->get();

                    foreach ($contacts as $contact) {
                       if ($campaign->type == 2) {
                            /*send email*/
                            $response = '';
                            $status = 1;
                            if (filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
                                $url = $url = env('APP_URL') . "/open-tracking?campaign_id=" . $campaign->id . '&contact_id=' . $contact->id;
                                $email_body = $campaign->email_body;
                                $email_body .= "<img src='$url' style ='display:none; width:1px;' />";
                                $email_body = str_replace('%MMS_VIDEO_LINK%', $link, $email_body);
                                $data['data'] = $email_body;
                                Mail::send('emails.template', $data, function ($msg) use ($contact, $campaign) {
                                    $msg->from($this->fromEmail);
                                    $msg->subject($campaign->email_subject);
                                    $msg->to($contact->email);
                                });
                            } else {
                                $status = 0;
                                $response = 'Invalid email address';
                            }
                            $this->saveCampaignLogs($campaign, $contact, $response, 2, $status);
                        }
                        DB::table('contacts')->where('id', $contact->id)->update(['status' => 1]);
                    }


                } catch (\Exception $exception) {
                   // dd($exception->getMessage() . ' ' . $exception->getLine());
                }
                Campaign::whereId($campaign->id)->update(['status' => 0]);
            }
        }
    }

    function sendCustomCampaign()
    {

        echo '<pre>';
        exit;

        $user = User::where('user_type', 'a')->first();

        $twilio_settings = Settings::where('user_id', $user->id)->first();

        $fileList = glob(base_path() . '/assets/gif/*');
        $text = "J’aimerais t’inviter pour le Programme Pilote (QC) Pour Générer Des Clients Pour Les Avocats. Libère + 40 % de ton temps qui est consacré à la prospection!
Regarde ma vidéo et prends ton RDV de stratégie de 20 minutes sans frais! ";


        $contacts = DB::table('contacts')
            ->whereRaw("list_id in (64) and status = 0")
            ->where('phone', '!=', '')
            ->get();

        foreach ($contacts as $contact) {

            foreach ($fileList as $filename) {
                //Use the is_file function to make sure that it is not a directory.
                if (is_file($filename)) {
                    $temp = explode('gif/', $filename);
                    $image_name = str_replace('.gif', '', $temp[1]);

                    if (strtolower($contact->name) == strtolower($image_name)) {

                        $image_name = str_replace(' ', '-', $image_name);

                        if ($contact->name == 'yann') {
                            $mms_url = 'https://bizwiz.pro/offre-video-avocat/' . time() . $contact->id;
                            $bitly = $this->bitlyShortUrl($mms_url);
                            if (count($bitly) > 0) {

                                if (isset($bitly['short_link'])) {

                                    $short_url = $bitly['short_link'];
                                    $text .= $short_url;

                                    $twilio = new Client($twilio_settings->twilio_sid, $twilio_settings->twilio_token);

                                    $message = $twilio->messages
                                        ->create($contact->phone,
                                            array(
                                                "body" => trim($text),
                                                "from" => $twilio_settings->from_number,
                                                "mediaUrl" => array('https://bizwiz.pro/console/assets/gif/' . $image_name . '.gif')
                                            )
                                        );

                                    if (isset($message->sid)) {
                                        $status = 1;
                                    } else {
                                        $status = 2;
                                    }

                                    $res = [
                                        'user_id' => 1,
                                        'campaign_id' => 2,
                                        'contact_id' => $contact->id,
                                        'type' => 'custom_sms',
                                        'response' => json_encode($message),
                                        'bitly_id' => $short_url,
                                        'status' => $status,
                                        'created_at' => Carbon::now()
                                    ];

                                    $insert = DB::table('campaign_logs')->insert($res);

                                    Contacts::whereId($contact->id)->update(['status' => 3]);

                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function saveCampaignLogs($capaign, $contact, $response, $type, $status, $bitly_id = false)
    {

        $insert = DB::table('campaign_logs')
            ->insert([
                'user_id' => $capaign->user_id,
                'campaign_id' => $capaign->id,
                'contact_id' => $contact->id,
                'type' => $type,
                'response' => $response,
                'bitly_id' => $bitly_id,
                'status' => $status,
                'created_at' => Carbon::now()
            ]);
    }

    private function replace_contact_info($contact, $body)
    {

        $final_body = str_replace('%NAME%', $contact->name, $body);
        $final_body = str_replace('%Email%', $contact->email, $final_body);

        return $final_body;
    }

    function open_tracking(Request $request)
    {
        $update = DB::table('campaign_logs')
            ->where(['campaign_id' => $request->campaign_id, 'contact_id' => $request->contact_id])
            ->update(['is_open' => 1]);

        $data['data'] = json_encode($request->all());
    }

    function schedule_feedback_cron()
    {

        $user = User::where('user_type', 'a')->first();
        $emailSettings = EmailSettings::where('user_id', $user->id)->first();
        $twilio_settings = Settings::where('user_id', $user->id)->first();

        $schedule = ScheduleFeedback::all();

        foreach ($schedule as $value) {

            $sheet_leads = SheetsContacts::where('sheet_id', $value->sheet_id)->get();

            foreach ($sheet_leads as $leads) {

                $lead_date = $leads->created_at;

                $new_date = new Carbon($lead_date);
                $new_date = $new_date->addDays($value->days);
                $new_date = $new_date->addHours($value->hours);

                $new_date = $new_date->addMinutes($value->minutes)->format('Y-m-d H:i');

                $current_date = Carbon::now()->format('Y-m-d H:i');

                if ($current_date > $new_date) {

                    try {

                        /*send sms to lead*/
                        $feedback = ScheduleFeedback::where('sheet_id', $value->id)->first();
                        if ($feedback) {

                            if ($feedback->is_email == 1) {

                                $lead_email = $leads->email;

                                if (filter_var($lead_email, FILTER_VALIDATE_EMAIL)) {
                                    $data['data'] = $feedback->email_body;


                                    Mail::send('emails.template', $data, function ($msg) use ($feedback, $emailSettings, $leads) {
                                        $msg->from($emailSettings->from_email);
                                        $msg->subject($feedback->subject);
                                        $msg->to($leads->email);
                                    });

                                    if (Mail::failures()) {
                                        $e_status = 2;
                                    } else {
                                        $e_status = 1;
                                    }

                                    DB::table('feedback_email_log')->insert([
                                        'from' => $emailSettings->from_email,
                                        'to' => $leads->email,
                                        'data' => $feedback->email_body,
                                        'user_id' => $feedback->user_id,
                                        'status' => $e_status,
                                        'sheet_id' => $value->id,
                                        'type' => 2
                                    ]);
                                }
                            }

                            if ($feedback->is_sms == 1) {
                                $feedbacke_phone = $leads->phone;

                                if (strlen($feedbacke_phone) > 10) {

                                    $sms = MainTrait::TwilioSendSms($twilio_settings->twilio_sid, $twilio_settings->twilio_token, $feedbacke_phone, $twilio_settings->from_number, trim($feedback->sms));

                                    $xml = simplexml_load_string($sms);
                                    $json = json_encode($xml);
                                    $arr = json_decode($json, true);

                                    //print_r($arr);

                                    if (isset($arr['Message'])) {
                                        //echo 'ok';
                                        $status = 1;
                                    } else {
                                        $status = 2;
                                    }

                                    DB::table('sms_log')->insert([
                                        'from' => $twilio_settings->from_number,
                                        'to' => $leads->phone,
                                        'data' => $sms,
                                        'user_id' => $leads->user_id,
                                        'status' => $status,
                                        'sheet_id' => $value->id,
                                        'type' => 2
                                    ]);
                                }
                            }
                        }

                    } catch (\Exception $e) {
                        dd($e->getMessage() . $e->getLine());
                    }
                }

            }
        }
    }

    function test_bitly(Request $request)
    {
        $access_token = env('BITLY_ACCESS_TOKEN');
        if (isset($_REQUEST['stats']) && isset($_REQUEST['short_link'])) {
            // $short_link = 'http://bit.ly/2JslsKN';
            $short_link = remove_http($_REQUEST['short_link']);
            $unit = 'month'; //default= day, "minute" "hour" "day" "week" "month"
            //units= An integer representing the time units to query data for. pass -1 to return all units of time.
            $units = '-1'; // -1 to get all units data, other you can user unit=day and units =1 it will fetch one month data,
            $url = 'https://api-ssl.bitly.com/v4/bitlinks/' . $short_link . '/clicks?&unit=' . $unit . '&units=' . $units;

            $ch = curl_init($url);
            //Set your auth headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token
            ));

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $arr_result = curl_exec($ch);
            $arr_response = json_decode($arr_result);
            print_r($arr_response);

            if (isset($arr_response->link_clicks)) {
                $chart_data = '[';
                $array_of_months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

                foreach ($array_of_months as $month) {
                    foreach ($arr_response->link_clicks as $value) {

                        $date = strtotime($value->date);
                        $bit_month = date("m", $date);

                        if ($bit_month == $month) {
                            $chart_data .= $value->clicks . ',';
                        } else {
                            $chart_data .= '0,';
                        }
                    }
                }

                $chart_data = rtrim($chart_data, ',');
                $chart_data = $chart_data . ']';
            }

        } else {


            $product_url = "https://bizwiz.pro/console/mms-video/my-test-page";
            $url = 'https://api-ssl.bitly.com/v4/bitlinks';

            $ch = curl_init($url);
            //Set your auth headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token
            ));

            $data = json_encode(array("long_url" => $product_url));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $arr_result = curl_exec($ch);
            $arr_response = json_decode($arr_result);
            echo '<pre>';
            print_r($arr_response);

            /*save following data to database, bitly short link required to fetch stats data from bitly api*/

            if (isset($arr_response->created_at)) {
                $id = $arr_response->id;
                $short_link = $arr_response->link;
                $long_url = $arr_response->long_url;
            }
        }
    }

    private function remove_http($url)
    {
        $disallowed = array('http://', 'https://');
        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }

    function bitlyShortUrl($product_url)
    {
        //$product_url = 'https://bizwiz.pro/console/mms-video/my-test-page/'.time();
        $access_token = 'c45d4d58896c3d3da7ff3f7cc2947f49f1014546';
        $url = 'https://api-ssl.bitly.com/v4/bitlinks';

        $ch = curl_init($url);
        //Set your auth headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token
        ));

        $data = json_encode(array("long_url" => $product_url));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $arr_result = curl_exec($ch);
        $arr_response = json_decode($arr_result);

        $res = [];
        if (isset($arr_response->created_at)) {
            $res['id'] = $arr_response->id;
            $res['short_link'] = $arr_response->link;
            $res['long_url'] = $arr_response->long_url;
            return $res;
        } else {
            return $res;
        }
    }


}
