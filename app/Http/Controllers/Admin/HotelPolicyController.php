<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Policy;
use App\Models\Hotel;
use DataTables;

class HotelPolicyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $policies = Policy::select(['id', 'hotel_id', 'check_in', 'check_out', 'cancellation_pre_payment', 
                                        'children_extra_beds', 'pets', 'cards_accepted', 'resort_fee']);
            return DataTables::of($policies)
                ->addColumn('hotel_name', function ($policy) {
                    return $policy->hotel->name;
                })
                ->addColumn('action', function ($policy) {
                    return '<a href="' . route('admin.policies.edit', $policy->id) . '" class="btn btn-sm btn-primary">Edit</a>
                            <form action="' . route('admin.policies.destroy', $policy->id) . '" method="POST" style="display: inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.policies.index');
    }

    public function create()
    {
        $hotels = Hotel::all();
        return view('admin.policies.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'check_in' => 'required|string|max:255',
            'check_out' => 'required|string|max:255',
            'cancellation_pre_payment' => 'required|string|max:255',
            'children_extra_beds' => 'required|string|max:255',
            'pets' => 'required|string|max:255',
            'cards_accepted' => 'required|string|max:255',
            'resort_fee' => 'required|string|max:255',
        ]);

        Policy::create($request->all());

        return redirect()->route('admin.policies.index')->with('success', 'Policy created successfully.');
    }

    public function edit($id)
    {
        $policy = Policy::findOrFail($id);
        $hotels = Hotel::all();
        return view('admin.policies.edit', compact('policy', 'hotels'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'check_in' => 'required|string|max:255',
            'check_out' => 'required|string|max:255',
            'cancellation_pre_payment' => 'required|string|max:255',
            'children_extra_beds' => 'required|string|max:255',
            'pets' => 'required|string|max:255',
            'cards_accepted' => 'required|string|max:255',
            'resort_fee' => 'required|string|max:255',
        ]);

        $policy = Policy::findOrFail($id);
        $policy->update($request->all());

        return redirect()->route('admin.policies.index')->with('success', 'Policy updated successfully.');
    }

    public function destroy($id)
    {
        $policy = Policy::findOrFail($id);
        $policy->delete();

        return redirect()->route('admin.policies.index')->with('success', 'Policy deleted successfully.');
    }
}
