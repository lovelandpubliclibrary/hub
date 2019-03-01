<?php

namespace App\Http\Controllers;

use App\Division;
use App\Staff;
use App\User;
use Illuminate\Http\Request;
use RandomLib\Factory as RandomLib;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for adding a new staff member.
     *
     * @return \Illuminate\Http\Response
     */
    public function showForm()
    {
        $divisions = Division::all();
        
        $supervisors = User::all()->filter(function ($user) {
            return $user->isSupervisor();
        });

        // generate a random string for the default password
        $factory = new RandomLib;
        $generator = $factory->getMediumStrengthGenerator();
        $charset = '23456789abcdefghijklmnopqrstuvwxyz';
        $password = $generator->generateString(12, $charset);
        return view('staff.add', compact('divisions', 'supervisors', 'password'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaff $request)
    {
        $staff = new Staff;
        $staff->name = $request->name;
        $staff->supervisor = $request->supervisor;
        foreach ($request->divisions as $division) {
            $staff->division = $division;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\rv  $rv
     * @return \Illuminate\Http\Response
     */
    public function show(rv $rv)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\rv  $rv
     * @return \Illuminate\Http\Response
     */
    public function edit(rv $rv)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\rv  $rv
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rv $rv)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\rv  $rv
     * @return \Illuminate\Http\Response
     */
    public function destroy(rv $rv)
    {
        //
    }
}
