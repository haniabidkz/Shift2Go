<?php

namespace App\Http\Controllers;

use App\Models\logbookcategories;
use Illuminate\Http\Request;

class LogbookcategoriesController extends Controller
{
    public function index()
    {
        //
        return view('logbookcategories/index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(logbookcategories $logbookcategories)
    {
        //
    }

    public function edit(logbookcategories $logbookcategories)
    {
        //
    }

    public function update(Request $request, logbookcategories $logbookcategories)
    {
        //
    }

    public function destroy(logbookcategories $logbookcategories)
    {
        //
    }
}
