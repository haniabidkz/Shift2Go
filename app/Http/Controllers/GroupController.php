<?php

namespace App\Http\Controllers;

use App\Models\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $groups = Group::where('created_by', $created_by)->get();
        return view('group.index',compact('groups'));
    }
    public function create()
    {
        return view('group.create');
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $group = new Group();
        $group->name         = $request->name;
        $group->created_by   = $created_by;
        $group->save();
        return redirect()->back()->with('success', __('Group Add Successfully'));
    }
    public function show(group $group)
    {
        //
    }
    public function edit(group $group)
    {
        return view('group.edit',compact('group'));
    }

    public function update(Request $request, group $group)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $group['name']         = $request->input('name');
        $group['created_by']   = $created_by;
        $group->save();
        return redirect()->back()->with('success', __('Group Update Successfully'));
    }
    public function destroy(group $group)
    {
        $group->delete();
        return redirect()->back()->with('success', __('Delete Succsefully'));
    }
}
