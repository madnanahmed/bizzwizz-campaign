<?php

namespace App\Http\Controllers;

use App\AgentsSheets;
use App\PhoneNumbers;
use App\Recordings;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    function index(){

        if(auth()->user()->user_type == 'a' ){
        $campaign_stats = $this->stats();
        $data=[
            'campaign_stats'    => $campaign_stats
        ];
        }else{


        }
        return view('dashboard', compact('data'));
    }


    private function stats(){
        $date = new Carbon();
        $date->subMonth(12);
            $stats = DB::table('campaign_logs')
                ->select(DB::raw('count(id) as `total`'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"), DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
                ->where('created_at', '>', $date->toDateTimeString())
                ->groupby('year', 'month')
                ->get();

        $month = array(1,2,3,4,5,6,7,8,9,10,11,12);

        $months = array();
        foreach($month as  $val){
            foreach($stats as $date){
                if($val == $date->month){
                    $months[$val] = $date->total;
                    break;
                    //$user_stats.= $date->total .',';
                }else{
                    $months[$val] = 0;

                }
            }
        }
        $result = implode(',', $months);

        if(strlen($result) > 0){
            $result = $result;
        }else{
            $result = '0,0,0,0,0,0,0,0,0,0,0,0';
        }

        return $result;
    }
}
