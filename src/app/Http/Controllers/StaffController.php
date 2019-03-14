<?php

namespace App\Http\Controllers;

use App\Division;
use App\Http\Requests\StoreStaff;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use RandomLib\Factory as RandomLib;
use Session;

class StaffController extends Controller
{
    // ensure user is authenticated in order to use this controller
    public function __construct() {
        $this->middleware('auth');
    }
    
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

        $staff = User::all();

        // generate a random string for the default password
        $factory = new RandomLib;
        $generator = $factory->getMediumStrengthGenerator();
        $charset = '23456789abcdefghijklmnopqrstuvwxyz';
        $password = $generator->generateString(12, $charset);
        return view('staff.add', compact('divisions', 'supervisors', 'staff', 'password'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaff $request)
    {
        // create a new staff member
        $new_staff_member = new User;
        $new_staff_member->name = $request->name;
        $new_staff_member->email = $request->email;
        $new_staff_member->password = bcrypt($request->password);
        $new_staff_member->save();  // save required to create an ID in the database in order to create relationships

        // set relationships for a supervisor
        if (!empty($request->supervises)) {
            $role_id = Role::where('role', 'Supervisor')->pluck('id')->first();
            $new_staff_member->role()->attach($role_id);

            foreach ($request->supervises as $supervised) {
                $subordinate = User::find($supervised);
                $subordinate->reportsTo()->associate($new_staff_member);
                $subordinate->save();
            }
        } else {
            // the new user isn't a supervisor
            $new_staff_member->role()->attach(Role::where('role', 'User')->pluck('id')->first());
        }
        $new_staff_member->save();  // save again to make roles available on the model

        // assign divisions
        foreach ($request->divisions as $division) {
            $new_staff_member->isSupervisor() ?
            $new_staff_member->divisions()->attach($division, ['supervisor' => true]) :
            $new_staff_member->divisions()->attach($division);
        }

        // set supervisor
        if ($request->supervisor) {
            $supervisor = User::find($request->supervisor);
            $new_staff_member->reportsTo()->associate($supervisor);
        }

        // set Admin role
        if ($request->administrator) {
            $role_id = Role::where('role', 'Administrator')->pluck('id')->first();
            $new_staff_member->role()->attach($role_id);
        }


        // final save to ensure all relationships are recorded in the database
        $new_staff_member->save();

        // redirect back the new staff form with a success message
        Session::flash('success_message', "Staff member saved.");
        return redirect()->route('addStaff');
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
