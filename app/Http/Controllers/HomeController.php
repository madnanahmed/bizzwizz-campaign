<?php

namespace App\Http\Controllers;

use App\Agents;
use App\KeypadSettings;
use App\LeadFeedback;
use App\LeadResponse;
use App\PinLeads;
use App\SheetsContacts;
use App\Traits\MainTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Revolution\Google\Sheets\Facades\Sheets;
use Session;

use Validator;

class HomeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    function agentLoginPage(){
        return view('users.agents.login');
    }
    function agentLogin(Request $request){
        $validation_rules = [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $validation_rules);

        if ($validator->fails())
        {
            return back()->withErrors($validator)
                ->withInput();
        } else {
            $user  = User::where([ 'email' => $request->email, 'password' => $request->password])->first();
            if($user){
                Session::put('agent_id', $user->id);
                return redirect( 'agent-panel');
            }else{
                if(Session::has('agent_id')){
                    Session::forget('agent_id');
                }
                return back()->with('error', 'Invalid login credentials!');
            }
        }
    }

    function agentLogout(){
        if(Session::has('agent_id')){
            Session::forget('agent_id');
        }
        return back();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

    }

    function enter_pin(){
        $settings = KeypadSettings::find(1);
        return view('keypad.index_white', compact('settings'));
    }

    function message_reply(Request $request){

        try {
            if ($request->has('message_sid')) {

                $data = [
                    'from' => $request->From,
                    'to' => $request->To,
                    'body' => $request->Body,
                    'sms_message_sid' => $request->SmsMessageSid,
                    'sms_sid' => $request->SmsSid,
                    'message_sid' => $request->MessageSid,
                    'account_sid' => $request->AccountSid,
                    'data' => json_encode($request->all())
                ];

                LeadResponse::create($data);
                mail('adnang7274@gmail.com', 'sms reply', print_r($request->all(), true));
            }
        }catch (\Exception $e){
            mail('adnang7274@gmail.com', 'sms reply', print_r( $e->getMessage() , true));

        }
    }

    function page_one(Request $request){
        $pin = '';
        if($request->pin!=''){
            $pin = base64_decode($request->pin);
        }
        return view('keypad.page_one', compact('pin'));
    }

    function process_pin_code(Request $request){

        if($request->pin){
            $pin = SheetsContacts::where('pin_code', $request->pin)->first();
            if($pin){
                $pin->increment('pin_count', 1);
                return response()->json(['success' => true, 'pin' => base64_encode( $request->pin ) ]);
            }else{
                return response()->json(['success' => false ]);
            }

        }else{
            return response()->json(['success' => false ]);
        }
    }

    function save_pin_lead(Request $request){
        $validation_rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
        ];
        $valid = Validator::make($request->all(), $validation_rules);
        if ($valid->fails())
        {
            return response()->json(['success' => false, 'validate' => 0,  'message' => $valid->errors()->all()]);
        }else{
            $sheetContacts = SheetsContacts::where('pin_code', $request->pin)->first();
           if($sheetContacts) {
               $obj = new PinLeads();

               $obj->fill($request->all());
               $obj->sheet_contacts_id = $sheetContacts->id;
               $obj->ip = $request->ip();

               if ($obj->save()) {
                   return response()->json(['success' => true]);
               } else {
                   return response()->json(['success' => false, 'message' => 'Please refresh page and try again']);
               }
           }else{
               return response()->json(['success' => false, 'message' => 'invalid url, please open your pin url again']);
           }
        }
    }

    function approved(){
        return view('keypad.approved');
    }

    function keypadSettings(){
        $settings = KeypadSettings::find(1);

        return view('keypad.setting', compact('settings') );
    }

    function saveKeypadSettings(Request $request){
        echo '<pre>';
        print_r($request->bg_image);

        $obj = new KeypadSettings();
        $obj = $obj->find(1);

        $obj->fill($request->all());

        if ($request->has('bg_image')) {
            $path = base_path() . '/assets/keypad/img/';

            if ($obj->bg_image !='' && file_exists($path.$obj->bg_image)) {
                unlink($path.$obj->bg_image);
            }

            $file  = uniqid() . '.' . $request->bg_image->getClientOriginalExtension();
            $request->bg_image->move($path, $file);
            $obj->bg_image = $file;

            echo 'ok';
        }



        if ($request->has('top_logo')) {
            $path = base_path() . '/assets/keypad/img/';

            if ($obj->top_logo !='' && file_exists($path.$obj->top_logo)) {
                unlink($path.$obj->top_logo);
            }

            $file  = uniqid() . '.' . $request->top_logo->getClientOriginalExtension();
            $request->top_logo->move($path, $file);
            $obj->top_logo = $file;
        }

        if ($request->has('footer_logo')) {
            $path = base_path() . '/assets/keypad/img/';

            if ($obj->footer_logo !='' && file_exists($path.$obj->footer_logo)) {
                unlink($path.$obj->footer_logo);
            }

            $file  = uniqid() . '.' . $request->footer_logo->getClientOriginalExtension();
            $request->footer_logo->move($path, $file);
            $obj->footer_logo = $file;
        }

        if ($request->has('keypad_bg')) {
            $path = base_path() . '/assets/keypad/img/';

            if ($obj->keypad_bg !='' && file_exists($path.$obj->keypad_bg)) {
                unlink($path.$obj->keypad_bg);
            }

            $file  = uniqid() . '.' . $request->keypad_bg->getClientOriginalExtension();
            $request->keypad_bg->move($path, $file);
            $obj->keypad_bg = $file;
        }


        $obj->style = json_encode($request->style);

        if($obj->save()){
            return back()->with('success', 'Settings saved successfully');

        }else{
            return back()->with('error', 'unknown error');

        }



    }
}
