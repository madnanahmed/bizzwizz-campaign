<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Categories;
use App\Contacts;
use App\Mms;
use App\SheetsContacts;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;
use  App\Timeslot;
use App\CampaignEmailTime;
use App\CampaignEmailMeta;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('campaign.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $folderList = glob(base_path() . '/assets/gif/*');
        $category = Categories::where('status', 1)->where('user_id', auth()->user()->id)->get();
        $list = DB::table('categories')->where('user_id', auth()->user()->id )->get();

        return view('campaign.create', compact( 'list', 'folderList', 'category'));
    }

    public function store(Request $request)
    {
        $campaign = new Campaign();
        if ($request->id != '') {
            $campaign = $campaign->findOrFail($request->id);
        }
        $campaign->fill($request->all());

        $campaign->user_id = auth()->user()->id;
        if ($request->has('category_ids')) {
            $campaign->category_ids = implode(',', $request->category_ids);
        }

        if ($request->has('match_names')) {
            $campaign->match_names = 1;
        }

        if ($request->has('is_email')) {
            $campaign->type = 2;
        } elseif ($request->has('is_message')) {
            $campaign->type = 1;
        } elseif ($request->has('is_email') && $request->has('is_message')) {
            $campaign->type = 0;
        }

        if ($campaign->save()) {

            {
                if(isset($request->is_template)){
                    return response()->json(['success' => true, 'message' => 'Template Successfully Saved! Please Check Template Section', 'id' => $campaign->id]);
                }else{
                    return response()->json(['success' => true, 'message' => 'Campaign Launched Successfully!', 'id' => $campaign->id]);
                }
            }

            return response()->json(['success' => true, 'message' => 'campaign queues successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Some unknown error. refresh page and try again']);

        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $folderList = glob(base_path() . '/assets/gif/*');
        $campaign = Campaign::find($id);
        $mms_page = Mms::where('user_id', auth()->user()->id)->get();
        //$category = Categories::where('status', 1)->get();
        $list = DB::table('contacts_list')->where('user_id', auth()->user()->id )->get();
        $timeslot =  Timeslot::all();
        return view('campaign.create', compact( 'list', 'mms_page', 'campaign', 'timeslot', 'folderList'));
    }


    function get_campaigns(){
        $data = Campaign::where([ 'user_id' => auth()->user()->id ]);
        $index=0;
        return DataTables::of($data)
            ->editColumn('id', function ($data){
                global $index;
                $index++;
                return $index;
            })
            ->addColumn('type', function ($data){
                $type = '';

                if($data->type == 0){
                    $type .= '<span class="pointer label label-success" onclick="view_email('.$data->id.')">Email</span>';
                    $type .= '<span class="pointer label label-info" onclick="view_sms('.$data->id.')">Message</span>';
                }elseif ($data->type ==1){
                    $type .= '<span class="pointer label label-info" onclick="view_sms('.$data->id.')">Message</span>';
                }elseif ($data->type == 2){
                    $type .= '<span class="pointer label label-success" onclick="view_email('.$data->id.')">Email</span>';
                }

                return $type;
            })
            ->addColumn('category', function ($data)
            {
                $category = '';
                foreach ( explode(',', $data->category_ids) as $vl ){
                    $category .= '<span class="label label-info pointer">'. DB::table('categories')->where('id',$vl)->value('title').'</span>';
                }
                return $category;
            })
            ->addColumn('action', function ($data)
            {
                if ($data->status == 1){ $status = "fa-unlock";$title = 'Campaign is active'; $btn_clr = 'btn-success'; }
                if ($data->status == 0){ $status = "fa-lock"; $title = 'Campaign is deactivated'; $btn_clr = 'btn-danger'; }
                $b = '<a onclick="deleteRow(this)" data-id="' . $data->id . '"  data-obj="campaign" href="javascript:;" data-toggle="tooltip" title="Delete agent" class="btn btn-sm btn-danger danger-alert"><i class="glyphicon glyphicon-trash"></i></a> ';
                $b .= '&nbsp;<a onclick="changeStatus(this)" data-obj="campaigns" data-id="'.$data->id.'" data-toggle="tooltip"  title="'.$title.'" class="btn btn-sm '.$btn_clr.' "><i class="fa '.$status.' "></i></a>';
                $b .= '&nbsp;<a href="'.route('campaign.edit', $data->id).'" data-toggle="tooltip" title="Edit campaign" class="btn btn-sm btn-primary "><i class="fa fa-edit "></i></a>';
                $b .= '&nbsp;<a href="'.url('campaign-stats/'.$data->id).'" data-toggle="tooltip" title="campaigns stats" class="btn btn-sm btn-info "><i class="fa fa-bar-chart "></i></a>';

                return $b;
            })
            ->rawColumns(['action', 'category', 'type'])
            ->make(true);
    }

    function get_campaigns_stats(Request $request){

        $data = DB::table('campaign_logs')->where('campaign_id', $request->id);
        $index=0;
        return DataTables::of($data)
            ->editColumn('id', function ($data){
                global $index;
                $index++;
                return $index;
            })

            ->editColumn('is_open', function ($data){
                $r='--';
                if($data->type == 2) {
                    if ($data->is_open == 1)
                        $r = 'yes';
                    else
                        $r = 'no';
                }
                return $r;
            })
            ->addColumn('type', function ($data){
                $type = '';

                if($data->type == 0){
                    $type .= '<span class="pointer label label-success" onclick="view_email('.$data->id.')">Email</span>';
                    $type .= '<span class="pointer label label-info" onclick="view_sms('.$data->id.')">Message</span>';
                }elseif ($data->type ==1){
                    $type .= '<span class="pointer label label-info" onclick="view_sms('.$data->id.')">Message</span>';
                }elseif ($data->type == 2){
                    $type .= '<span class="pointer label label-success" onclick="view_email('.$data->id.')">Email</span>';
                }

                return $type;
            })
            ->addColumn('title', function ($data)
            {
                $campaign = Campaign::where('id', $data->campaign_id)->value('title');
                return ucfirst($campaign);
            })

            ->addColumn('leads_name', function ($data)
            {
                $leads = Contacts::where('id', $data->contact_id)->first();
                $name = $leads->name. ' '. $leads->last_name;
                return $name;
            })
            ->addColumn('to_email', function ($data)
            {
                $leads = Contacts::where('id', $data->contact_id)->first();
                $to_email = $leads->email;
                return $to_email;
            })
            ->editColumn('status', function ($data)
            {
                if ($data->status == 1){
                    $status = "fa-check";$title = 'Successfully delivered'; $btn_clr = 'btn-success';
                    $b = '&nbsp;<a data-toggle="tooltip"  title="'.$title.'" class="btn btn-sm '.$btn_clr.' "><i class="fa '.$status.' "></i></a>';

                }
                if ($data->status == 2){
                    $status = "fa-bug"; $title = 'Some error'; $btn_clr = 'btn-danger';
                    $b = '&nbsp;<a data-toggle="tooltip" onclick="show_bug('.$data->id.')" title="'.$title.'" class="btn btn-sm '.$btn_clr.' "><i class="fa '.$status.' "></i></a>';

                }
                if ($data->status == 0){
                    $status = "fa-times"; $title = 'pending'; $btn_clr = 'btn-warning';
                    $b = '&nbsp;<a data-toggle="tooltip"  title="'.$title.'" class="btn btn-sm '.$btn_clr.' "><i class="fa '.$status.' "></i></a>';

                }
                return $b;
            })
            ->addColumn('action', function ($data)
            {
                $b = '<a onclick="deleteRow(this)" data-id="' . $data->id . '"  data-obj="agents_sheets" href="javascript:;" data-toggle="tooltip" title="Delete agent" class="btn btn-sm btn-danger danger-alert"><i class="glyphicon glyphicon-trash"></i></a> ';

                return $b;
            })
            ->rawColumns(['action', 'status', 'type'])
            ->make(true);
    }

    function campaign_stats($id){
        $campaign = Campaign::whereId($id)->first();
        $success = DB::table('campaign_logs')->where( ['campaign_id' => $id, 'status' => 1] )->count();
        $error = DB::table('campaign_logs')->where( ['campaign_id' => $id, 'status' => 2] )->count();

        return view('campaign.stats', compact('campaign', 'success', 'error'));
    }

    function campaign_error(Request $request){
       // header('Content-type: text/xml');
        $campaign_error = DB::table('campaign_logs')->whereId($request->id )->value('response');
        if($campaign_error != '0') {
            $xml = simplexml_load_string($campaign_error);
            $json = json_encode($xml);
            $arr = json_decode($json, true);
            if(isset($arr['RestException']['Message'])){
                return response()->json( ['message' => $arr['RestException']['Message'] ]);
            }else{
                return response()->json( ['message' => 'not found' ]);
            }
        }else{
            return response()->json( ['message' => 'not found' ]);
        }
    }

    public function generate_followup(Request $request)
    {
        $timeslot =  Timeslot::all();

        return view('campaign/followup',['timeslot' => $timeslot]);
    }

    function delete_followups(Request $request){


        $delete = CampaignEmailMeta::whereId($request->id)->delete();

        if($delete){
            return response()->json([ 'success' => true ]);
        }else{
            return response()->json([ 'success' => false ]);
        }
    }
}
