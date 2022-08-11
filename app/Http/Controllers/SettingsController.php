<?php

namespace App\Http\Controllers;

use App\EmailSettings;
use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{

    public function index()
    {
        $settings = Settings::where('user_id', Auth::user()->id)->first();
        $email_settings = EmailSettings::where( 'user_id', Auth::user()->id )->first();

        return view('settings.list', compact('settings', 'email_settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $obj = new Settings();

        if ($request->id != '') {
            $obj = $obj->findOrFail($request->id);
        }
        $obj->fill($request->all());
        $obj->user_id = Auth::user()->id;

        if($obj->save()){
            return back()->with('success', 'Settings updated successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
