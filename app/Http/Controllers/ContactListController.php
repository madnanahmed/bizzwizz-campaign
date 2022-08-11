<?php

namespace App\Http\Controllers;

use App\Contacts;
use App\EmailSettings;
use App\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Validator;

class ContactListController extends Controller
{
    function index(){

        $list = DB::table('contacts_list')
            ->where(['user_id' => auth::user()->id ] )
            ->get();

        /*email settings*/
        $email_settings = EmailSettings::where( 'user_id', Auth::user()->id )->first();

        return view('contacts.csv_contacts', compact('list', 'email_settings' ) );

    }

    function store(Request $request){
        $data = 2;

        if($request->type == 'update_list'){
            $list_UP = DB::table('contacts_list')
                ->where('id', $request->id)
                ->update(['title' => $request->title]);
            if($list_UP){
                $data = 1;
            }

        }else if($request->type == 'add_list'){

            if($request->title!=''){
                // insert data to list
                $list_id = DB::table('contacts_list')
                    ->insertGetId(
                        [
                            'title'     => $request->title,
                            'user_id'   => auth::user()->id,
                            'status'    => 1
                        ]
                    );
                if($list_id){
                    return response()->json(['msg' => 1, 'list_id' => $list_id]);
                }
            }

        }else if($request->type == 'add'){
            if($request->email!='' && $request->name!=''){

                $list_id = $request->list;

                /* add to contact */
                $addList = DB::table('contacts')
                    ->insertGetId(
                        [
                            'user_id'   => auth::user()->id,
                            'name'      => $request->name,
                            'email'     => $request->email,
                            'phone'     => $request->phone,
                            'list_id'   => $list_id,
                            'status'    => 0
                        ]
                    );
                if($addList){
                    $data=1;
                }
            }


        }else if($request->type == 'import') {

            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:csv,txt',
                'list' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['msg' => 2, 'error' => $validator->errors()->all()]);
            }

            $file = time() . '.' . $request->file->getClientOriginalExtension();
            $request->file->move(base_path() . '/assets/csv/', $file);
            $file_path = base_path() . '/assets/csv/' . $file;
            $data = $this->csvToArray($file_path, $request->list);
        }

        if($data == 2){
            return response()->json(['msg' => 2]);
        }else{
            return response()->json(['msg' => 1]);
        }
    }

    private function csvToArray($filename, $list_id, $delimiter = ',')
    {
        header('Content-Type: text/html; charset=utf-8');

        try {
            if (!file_exists($filename) || !is_readable($filename))
                return false;
            // insert data to list

            if ($list_id) {
                $header = null;
                if (($handle = fopen($filename, 'r')) !== false) {
                    while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                        if (!$header) {
                            $header = $row;
                        } else {
                            if ($row[1] != '') {
                                $name = $row[0];
                                $last_name = $row[1];
                                $email = $row[2];
                                $phone = $row[3];
                                $number = str_replace(array('-',' '), array('', ''), $phone);

                                /* populate contacts */
                                DB::table('contacts')
                                ->insertGetId(
                                    [
                                        'user_id'   => auth::user()->id,
                                        'name'      => $name,
                                        'last_name' => $last_name,
                                        'email'     => $email,
                                        'list_id'   => $list_id,
                                        'phone'     => '+1'.$number,
                                        'status'    => 0
                                    ]
                                );
                            }
                        }
                    }
                    fclose($handle);
                    unlink($filename);
                }
            }

        }catch(\Exception $e){
            echo $e->getMessage() . $e->getLine();
        }
    }

    function loadList(Request $request){
        if($request->id!=''){
            $list_name = DB::table('contacts_list')->where('id', $request->id)->value('title');
            return response()->json(['msg'=> 1, 'rec' => $list_name, 'id' => $request->id]);
        }
    }

    function editContact($id){
        $err = false;
        if($id) {
            $list = DB::table('contacts_list')
                ->where(['user_id' => auth::user()->id ] )
                ->get();

            $contact = DB::table('contacts')->join('contacts_list', 'contacts.list_id', 'contacts_list.id')
                ->where(['contacts_list.user_id' => Auth::user()->id, 'contacts.id' => $id])
                ->select('contacts.name', 'contacts.email', 'contacts.id', 'contacts.phone', 'contacts_list.title', 'contacts_list.id as list_id')
                ->first();

            if ($contact) {
                return view('contacts.contact_edit', compact('contact', 'list'));
            } else {
                $err = true;
            }
        }else{
            $err = true;
        }

        if($err){
            return back()->with('error', 'invalid request');
        }
    }

    function contactStore(Request $request){
        $obj = new Contacts();

        if ($request->id != '') {
            $obj = $obj->findOrFail($request->id);
        }
        $obj->fill($request->all());
        $obj->user_id = Auth::user()->id;
        $obj->list_id = $request->list_id;

        if($obj->save()){
            return back()->with('success', 'Contact updated successfully');
        }
    }
    function saveEmailSettings(Request $request){
        $obj = new EmailSettings();

        if ($request->id != '') {
            $obj = $obj->findOrFail($request->id);
        }
        $obj->fill($request->all());
        $obj->user_id = Auth::user()->id;

        if($obj->save()){
            return response()->json( [ 'msg'=> 1, 'id' => $obj->id ]);
        }
    }



    function get_sec(){
        include( getcwd(). '/simple_html_dom.php');

        $html = file_get_html('https://numverify.com');
        $sec='';
        if($html->find('input[name="scl_request_secret"]', 0)){
            $sec = $html->find('input[name="scl_request_secret"]', 0)->value;
        }

        return $sec;

    }





    function testAddContacts(){
//
//        $contacts = Contacts::where('phone', '!=', '')
//            ->where('list_id', 63)
//
//            ->get();
//
//        echo '<pre>';
//        foreach ($contacts as $contact) {
//
//                   Contacts::where('id', $contact->id)
//                       ->update([ 'email' => $contact->phone, 'phone' => $contact->email ]);
//
//
//        }
//
//
//
//        exit;

/*        $api = new \EveryonePHP();
        $api->sid = "AC3950450b5c0241ee85637e5dd0372739";
        $api->token = "AUee661f3a8f3a4e7387c7b17fcbae6f0b";

        $data = array( "linetype");
        $contacts = Contacts::where('phone', '!=', '')
            ->where('list_id', 62)
            ->where('status', 0)
            ->orderBy('id', 'desc')
            //->limit(1)
            ->get();


        foreach ($contacts as $contact) {

            try {
               $number = $ph = str_replace(array('+'), (''), $contact->phone);
                if(is_numeric(   str_replace( array('-',')', '(' ), array('', '', ''), $number) ) ){
                $api->query($number, $data);
                echo "<pre>";
                //print_r($api);

                Contacts::where('id', $contact->id)
                    ->update(['data' => json_encode($api->results)]);

                if ($api->error) {               // If there's an error
                    echo "Error: $api->error";    // Print it out
                    Contacts::where('id', $contact->id)
                        ->update(['status' => 2]);                    // Exit with status 1
                } else {

                    if(@$api->results->data ) {

                        $type = $api->results->data->linetype;
                        Contacts::where('id', $contact->id)
                            ->update(['status' => 1, 'type' => $type]);
                        print_r($api->results->data->linetype);
                    }else{
                        Contacts::where('id', $contact->id)
                            ->update(['status' => 2]);
                        print_r($api->results->data);
                    }
                }
            }else{
                echo 'string';
                Contacts::where('id', $contact->id)
                ->update(['status' => 2]);

            }
            }catch (\Exception $e){
                echo $e->getMessage();
            }
        }
        exit;*/

/*        $sec = $this->get_sec();

        $contacts = Contacts::where('phone', '!=', '')
        ->where('status', 2)
        ->get();

        foreach ($contacts as $contact) {



                $number = $ph = str_replace( array('+'), (''), $contact->phone);

                    $hash = md5($number.$sec);

                  echo  $res = file_get_contents("https://numverify.com/php_helper_scripts/phone_api.php?secret_key=$hash&number=$number");

                    if($res !='Unauthorized'){

                    $res = json_decode($res);

                    if(@$res->valid == 1){
                       $type = $res->line_type;
                       $location = $res->location;
                        $contacts = Contacts::where('id', $contact->id)->update(['status' => 1, 'type' => $type, 'location' => $location]);
                    }
                }else{
                    $sec = $this->get_sec();
                    $hash = md5($number.$sec);
                    $res = file_get_contents("https://numverify.com/php_helper_scripts/phone_api.php?secret_key=$hash&number=$number");
                    if($res !='Unauthorized'){

                    $res = json_decode($res);

                    if(@$res->valid == 1){
                       $type = $res->line_type;
                       $location = $res->location;
                        $contacts = Contacts::where('id', $contact->id)->update(['status' => 1, 'type' => $type, 'location' => $location]);
                    }

                }

            }


        }
        exit;*/

/*        echo '<pr>';
        $twilio_settings = Settings::first();

        $contacts = Contacts::where('phone', '!=', '')->where('id', 23884)
        ->orderBy('id', 'desc')
        ->get();

        foreach ($contacts as $contact) {

            $ph = str_replace( array('(',')', '-', ' '), array('','', '', ''), $contact->phone);

            if(!DB::table('contacts')->where('phone', '+1'.$ph )->exists() ) {

                    $x = curl_init("https://lookups.twilio.com/v1/PhoneNumbers/" . $contact->phone);
                    //curl_setopt($x, CURLOPT_GET, true);
                    curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($x, CURLOPT_USERPWD, "$twilio_settings->twilio_sid:$twilio_settings->twilio_token");

                    $y = curl_exec($x);
                    curl_close($x);

                    $arr = json_decode($y, true);
                    echo '<pre>';
                    print_r($arr);

                    if (isset($arr['phone_number'])) {

                        if (!DB::table('contacts')->where('phone', $arr['phone_number'])->exists()) {

                            DB::table('contacts')->where('id', $contact->id)->update(
                                [
                                    'phone' => $arr['phone_number'],
                                ]
                            );
                        }
                    }
                }
        }

        exit;*/

        $file =base_path() . '/test.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$file.'"');

        $fileList = glob(base_path() . '/assets/contacts/*');
        foreach($fileList as $filename){
            //Use the is_file function to make sure that it is not a directory.
            if(is_file($filename)) {
                $header = null;
                if (($handle = fopen($filename, 'r')) !== false) {
                    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                        if (!$header) {
                            $header = $row;
                        } else {
                            if ($row[1] != '') {


                                $fp = fopen('php://output', 'wb');
                                foreach ($row as $line) {
                                    $val = explode(",", $line);
                                    fputcsv($fp, $val);
                                }
                                fclose($fp);

                            }
                        }
                    }
                    fclose($handle);
                    unlink($filename);
                }
               exit;
            }
        }

        exit;
        $fileList = glob(base_path() . '/assets/contacts/*');
        foreach($fileList as $filename){
            //Use the is_file function to make sure that it is not a directory.
            if(is_file($filename)){

               $temp = explode('contacts/', $filename);
                $list_name = str_replace('.csv', '', $temp[1]);
                $list_id = DB::table('contacts_list')
                    ->insertGetId([ 'title' => $list_name, 'user_id' => auth()->user()->id]);
                $this->csvToArray($filename, $list_id);
            }
        }


        /*$file_path = base_path() . '/assets/csv/' . $file;
        $data = $this->csvToArray($file_path, $request->list);*/
    }


}
