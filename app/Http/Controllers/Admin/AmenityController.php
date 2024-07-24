<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenity;
use DataTables;

class AmenityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $amenities = Amenity::select(['id', 'name', 'type']);
            return DataTables::of($amenities)
                ->addColumn('action', function ($amenity) {
                    return '<a href="' . route('admin.amenities.edit', $amenity->id) . '" class="btn btn-sm btn-primary">Edit</a>
                            <form action="' . route('admin.amenities.destroy', $amenity->id) . '" method="POST" style="display: inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.amenities.index');
    }

    public function amenityData()
    {
        $amenities = Amenity::select('*');
        return DataTables::of($amenities)
            ->addColumn('action', function ($amenity) {
                return '<a href="' . route('admin.amenities.edit', $amenity->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
    $amenities = Amenity::all();
    return view('admin.amenities.create', compact('amenities'));
    }


    public function store(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:amenities,id',
    ]);

    Amenity::create($request->all());

    return redirect()->route('admin.amenities.index')->with('success', 'Amenity created successfully.');
    }


    public function edit($id)
    {
    $amenity = Amenity::findOrFail($id);
    $amenities = Amenity::all();
    return view('admin.amenities.edit', compact('amenity', 'amenities'));
    }


    public function update(Request $request, $id)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:amenities,id',
    ]);

    $amenity = Amenity::findOrFail($id);
    $amenity->update($request->all());

    return redirect()->route('admin.amenities.index')->with('success', 'Amenity updated successfully.');
    }


    public function destroy($id)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->delete();

        return redirect()->route('admin.amenities.index')->with('success', 'Amenity deleted successfully.');
    }
}

