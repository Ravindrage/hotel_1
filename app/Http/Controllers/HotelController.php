<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{   

    if (request()->ajax()) {
        $hotels = Hotel::all();
        return DataTables::of($hotels)
            ->addColumn('action', function($row){
                $btn = '<a href="'.route('admin.hotels.edit', $row->id).'" class="edit btn btn-success btn-sm">Edit</a>';
                $btn .= '<form action="'.route('admin.hotels.destroy', $row->id).'" method="POST" style="display:inline;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="delete btn btn-danger btn-sm">Delete</button>
                        </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('admin.hotels.index');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotel $hotel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        //
    }
}
