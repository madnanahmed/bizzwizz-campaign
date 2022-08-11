<?php

namespace App\Http\Controllers;

use App\Agents;
use App\AgentsSheets;
use App\Campaign;
use App\Categories;
use App\Contacts;
use App\Mms;
use App\QuestionFields;
use App\SheetsContacts;
use App\Survey;
use App\Traits\MainTrait;
use App\Traits\SurveyTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use DB;

class AjaxController extends Controller
{
    /* load surveys of user */
    private $index = 0;

    /*delete*/
    public function delete(Request $request)
    {
        if ($request->id!='' && $request->obj!='')
        {
            $delete = 1;
            $msg = 'Deleted Successfully.';
            // delete_content
            if ($request->obj=='delete_contact'){
                $delete = DB::table('contacts')->where('id', $request->id)->delete();
            }
            // delete_lead
            if ($request->obj=='delete_lead'){

                $delete = SheetsContacts::where('id', $request->id)->delete();
            }
            // delete_content
            if ($request->obj=='delete_list'){
                DB::table('contacts_list')->where('id', $request->id)->delete();
                DB::table('contacts')->where('list_id', $request->id)->delete();
            }

            if ($request->obj=='categories'){
                $delete= Categories::where('id', $request->id)->delete();
            }

            if ($request->obj=='agents_sheets'){
                DB::table('agents_sheets')->where('id', $request->id)->delete();
                DB::table('sheets_history')->where('sheet_id', $request->id)->delete();
            }

            if ($request->obj=='mms'){
                Mms::find($request->id)->delete();
            }
            if ($request->obj=='campaign'){
                Campaign::find($request->id)->delete();
            }

            if($request->obj == 'agents')
            {
                $delete = User::whereId([$request->id])->delete();
                if(!$delete)
                {
                    $delete = 0;
                }
            }

            if($delete==1)
            {
                echo '{"type":"success","msg":"'.$msg.'"}';
            }else{
                echo '{"type":"error","msg":"'.$msg.'"}';
            }
        }else{
            echo '0';
        }
    }
    /* change status */
    function changeStatus(Request $request){
        $object = $request->obj;

        if ($object == 'mms')
        {
            $stat = Mms::whereId( $request->id )->value('status');
            if ($stat == 1)
                $status = 0;
            if ($stat == 0 || $stat == 2)
                $status = 1;

            Mms::whereId( $request->id )->update(['status' => $status]);

            echo json_encode(array('res' => $status));
        }

        if ($object == 'campaigns')
        {
            $stat = Campaign::whereId( $request->id )->value('status');
            if ($stat == 1)
                $status = 0;
            if ($stat == 0 || $stat == 2)
                $status = 1;

            Campaign::whereId( $request->id )->update(['status' => $status]);

            echo json_encode(array('res' => $status));
        }

        if ($object == 'agents_leads')
        {
            $stat = SheetsContacts::whereId( $request->id )->value('status');
            if ($stat == 1)
                $status = 0;
            if ($stat == 0 || $stat == 2)
                $status = 1;

            SheetsContacts::whereId( $request->id )->update(['status' => $status]);

            echo json_encode(array('res' => $status));
        }

        if ($object == 'agents')
        {
            $stat = User::whereId( $request->id )->value('status');
            if ($stat == 1)
                $status = 0;
            if ($stat == 0 || $stat == 2)
                $status = 1;

            User::whereId( $request->id )->update(['status' => $status]);
            AgentsSheets::where(['user_id' => $request->id])->update(['status' => $status]);

            echo json_encode(array('res' => $status));
        }

        if ($object == 'agents_sheets')
        {
            $stat = AgentsSheets::whereId( $request->id )->value('status');
            if ($stat == 1)
                $status = 0;
            if ($stat == 0 || $stat == 2)
                $status = 1;

            $update = AgentsSheets::whereId( $request->id )->where(['user_id' => Auth::user()->id])->update(['status' => $status]);

            echo json_encode(array('res' => $status));
        }
    }

    function screenShot(Request $request){
        $image = $request->image;
        $filedir = base_path() . '/assets/images/screens/';

        $name = 'survey_'.$request->survey.'_'.$request->screen.'_'.Auth::user()->id;

        $image = str_replace('data:image/png;base64,', '', $image);
        $decoded = base64_decode($image);

        file_put_contents($filedir . "/" . $name . ".png", $decoded, LOCK_EX);

        return response()->json( ['img' => asset('assets/images/screens/'.$name.'.png?id='.rand(99,999)) ]);
    }

    function loadChildSheet(Request $request){
        if($request->sheet_id!='') {
            $data = MainTrait::getChildSheets($request->sheet_id);

            if(count($data) > 0){
                return response()->json(['data' => $data]);
            }else{
                return response()->json(['res' => 2, 'msg' => 'Invalid sheet id, or sheet not found' ]);
            }
        }else{
            return response()->json(['res' => 2, 'msg' => 'Sheet id required' ]);
        }
    }

    function loadContacts(Request $request)
    {
        $count = 0;
        $contacts = DB::table('contacts')->join('contacts_list', 'contacts.list_id', 'contacts_list.id')
            ->where( 'contacts_list.user_id', Auth::user()->id )
            ->select('contacts.name', 'contacts.email', 'contacts.id', 'contacts.phone', 'contacts_list.title');
        return Datatables::of($contacts)
            ->editColumn('id', function($contacts)
            {
                global $count;
                $count++;
                return $count;
            })
            ->addColumn('action', function ($contacts){
                $b= '<button onclick="deleteRow(this)" data-id="'.$contacts->id.'" data-obj="delete_contact" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';
                $b.=' <a href="'.url('edit-contact/'.$contacts->id).'" data-id="'.$contacts->id.'"  class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></button>';

                return $b;
            })
            ->make(true);
    }

    function loadLists(Request $request)
    {
        $count = 0;
        $list = DB::table('contacts_list')
            ->where( 'user_id', Auth::user()->id );
        return Datatables::of($list)
            ->editColumn('id', function($list)
            {
                global $count;
                $count++;
                return $count;
            })
            ->addColumn('action', function ($list){
                $b= '<button onclick="deleteRow(this)" data-id="'.$list->id.'" data-obj="delete_list" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';
                $b.=' <button onclick="editList(this)" data-id="'.$list->id.'"  class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></button>';

                return $b;
            })
            ->make(true);
    }

    function getSheetLeads(Request $request)
    {
        $count = 0;
        $data = SheetsContacts::where(['sheet_id' => $request->id]);

        return Datatables::of($data)
            ->editColumn('id', function($data)
            {
                global $count;
                $count++;
                return $count;
            })
            ->editColumn('name', function($data)
            {
             $name = $data->first_name.' '. $data->last_name;
                return $name;
            })
            ->editColumn('is_pin', function($data)
            {
             $is_qualified = 'No';
             if($data->is_pin == 1){
                 $is_qualified = 'Yes';
             }
                return $is_qualified;
            })
            ->editColumn('pin_count', function($data)
            {
             $b = '';
             if($data->pin_count > 0){
                 $b.= '<a href="'.url('view-pin-leads/'.$data->id).'" title="view leads" class="btn btn-xs btn-success">'.$data->pin_count.'</button> ';;
             }
                return $b;
            })
            ->addColumn('action', function ($data){
                if ($data->status == 1){ $status = "fa-unlock";$title = 'Lead is active'; $btn_clr = 'btn-success'; }
                if ($data->status == 0){ $status = "fa-lock"; $title = 'Lead is deactivated'; $btn_clr = 'btn-danger'; }
                $b= '<button onclick="deleteRow(this)" data-id="'.$data->id.'" data-obj="delete_lead" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button> ';
                //$b.=' <button onclick="editList(this)" data-id="'.$data->id.'"  class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></button>';
                $b .= '<button onclick="changeStatus(this)" data-obj="agents_leads" data-id="'.$data->id.'" data-toggle="tooltip"  title="'.$title.'" class="btn btn-xs '.$btn_clr.' "><i class="fa '.$status.' "></i></button>';

                return $b;
            })->rawColumns(['action', 'pin_count'])
            ->make(true);
    }

    function getAllLeads(Request $request)
    {
        $count = 0;
        $data = Contacts::all();

        return Datatables::of($data)
            ->editColumn('id', function($data)
            {
                global $count;
                $count++;
                return $count;
            })
            ->addColumn('category', function($data)
            {
                $category = Categories::whereId($data->list_id)->value('title');
                return $category;
            })
            ->editColumn('name', function($data)
            {
             $name = $data->first_name.' '. $data->last_name;
                return $name;
            })
            ->addColumn('action', function ($data){
                $status = "fa-unlock";$title = 'Lead is active'; $btn_clr = 'btn-success';
                if ($data->status == 1){ $status = "fa-unlock";$title = 'Lead is active'; $btn_clr = 'btn-success'; }
                if ($data->status == 0){ $status = "fa-lock"; $title = 'Lead is deactivated'; $btn_clr = 'btn-danger'; }
                $b= '<button onclick="deleteRow(this)" data-id="'.$data->id.'" data-obj="delete_lead" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button> ';
                //$b.=' <button onclick="editList(this)" data-id="'.$data->id.'"  class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></button>';
                $b .= '<button onclick="changeStatus(this)" data-obj="agents_leads" data-id="'.$data->id.'" data-toggle="tooltip"  title="'.$title.'" class="btn btn-xs '.$btn_clr.' "><i class="fa '.$status.' "></i></button>';

                return $b;
            })
            ->make(true);
    }
}
