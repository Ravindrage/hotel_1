<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ImageManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Amenity; // Import the correct model

use DataTables;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $hotels = Hotel::select(['id', 'name','phone', 'address']); // Select only necessary columns
            return DataTables::of($hotels)
                ->addColumn('action', function ($hotel) {
                    // Add your action buttons here
                    return '<a href="' . route('admin.hotels.edit', $hotel->id) . '" class="btn btn-sm btn-primary">Edit</a>
                            <form action="' . route('admin.hotels.destroy', $hotel->id) . '" method="POST" style="display: inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            <a href="'.route('admin.hotels.editDetails', $hotel->id) . '" class="btn btn-sm btn-primary"> Details </a>';
                })          
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.hotel.index');
    }

    

    public function create()
    {
        return view('admin.hotel.create');
    }

    public function store(Request $request)
    {   
        $request->validate([
            'name' => 'required|string|max:255',
            'hotel_url' => 'required|string|unique:hotels,hotel_url|max:255', // Hotel URL (Slug)
            'contact_person' => 'nullable|string|max:255',
            'faxnumber' => 'required|string|max:255', // Fax number with specific format
            'hotel_contact_email' => 'nullable|email|max:255',
            'hotel_accounting_emails' => 'nullable|string|max:255',
            'authorised_by' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'hotel_telephone' => 'nullable|string|max:20',
            'hotel_fax_number' => 'nullable|string|max:20',
            'main_hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hotel_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
            'destination_keywords' => 'nullable|string|max:255',
            'avg_room_rate' => 'nullable|numeric',
            'tax_rate' => 'nullable|numeric',
            'cancellation_days' => 'nullable|integer',
            'special_offers' => 'nullable|string|max:255',
            'no_of_rooms' => 'nullable|integer',
            'booking_days' => 'nullable|integer',
            'auto_send_arrival_report' => 'nullable|boolean',
            'is_asi_hotel' => 'nullable|boolean',
        ]);
         
        // Store the hotel
        $hotel = new Hotel();
        $hotel->name = $request->name;
        $hotel->hotel_url = $request->hotel_url;
        $hotel->contact_person = $request->contact_person;
        $hotel->faxnumber = $request->faxnumber;
        $hotel->hotel_contact_email = $request->hotel_contact_email;
        $hotel->hotel_accounting_emails = $request->hotel_accounting_emails;
        $hotel->authorised_by = $request->authorised_by;
        $hotel->address = $request->address;
        $hotel->zip_code = $request->zip_code;
        $hotel->city = $request->city;
        $hotel->state = $request->state;
        $hotel->country = $request->country;
        $hotel->hotel_telephone = $request->hotel_telephone;
        $hotel->hotel_fax_number = $request->hotel_fax_number;
        
        if ($request->hasFile('main_hotel_image')) {
            $hotel->main_hotel_image = $request->file('main_hotel_image')->store('images/hotels');
        }
    
        if ($request->hasFile('hotel_logo')) {
            $hotel->hotel_logo = $request->file('hotel_logo')->store('images/hotels');
        }
    
        $hotel->username = $request->username;
        $hotel->password = bcrypt($request->password);
        $hotel->destination_keywords = $request->destination_keywords;
        $hotel->avg_room_rate = $request->avg_room_rate;
        $hotel->tax_rate = $request->tax_rate;
        $hotel->cancellation_days = $request->cancellation_days;
        $hotel->special_offers = $request->special_offers;
        $hotel->no_of_rooms = $request->no_of_rooms;
        $hotel->booking_days = $request->booking_days;
        $hotel->auto_send_arrival_report = $request->auto_send_arrival_report;
        $hotel->is_asi_hotel = $request->is_asi_hotel;
    
        $hotel->save();
            
        return redirect()->route('admin.hotel.index')->with('success', 'Hotel created successfully.');
    }
    

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'hotel_url' => 'required|string|max:255|unique:hotels,hotel_url,' . $id, // Ensure uniqueness except for the current record
        'contact_person' => 'nullable|string|max:255',
        'faxnumber' => 'required|string|max:255',
        'hotel_contact_email' => 'nullable|email|max:255',
        'hotel_accounting_emails' => 'nullable|string|max:255',
        'authorised_by' => 'nullable|string|max:255',
        'address' => 'required|string|max:255',
        'zip_code' => 'nullable|string|max:20',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'country' => 'required|string|max:255',
        'hotel_telephone' => 'nullable|string|max:20',
        'hotel_fax_number' => 'nullable|string|max:20',
        'main_hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'hotel_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'username' => 'nullable|string|max:255',
        'password' => 'nullable|string|min:8',
        'destination_keywords' => 'nullable|string|max:255',
        'avg_room_rate' => 'nullable|numeric',
        'tax_rate' => 'nullable|numeric',
        'cancellation_days' => 'nullable|integer',
        'special_offers' => 'nullable|string|max:255',
        'no_of_rooms' => 'nullable|integer',
        'booking_days' => 'nullable|integer',
        'auto_send_arrival_report' => 'nullable|boolean',
        'is_asi_hotel' => 'nullable|boolean',
    ]);

    $hotel = Hotel::findOrFail($id);

    $data = $request->only([
        'name', 'hotel_url', 'contact_person', 'faxnumber', 'hotel_contact_email',
        'hotel_accounting_emails', 'authorised_by', 'address', 'zip_code', 'city',
        'state', 'country', 'hotel_telephone', 'hotel_fax_number', 'username',
        'destination_keywords', 'avg_room_rate', 'tax_rate', 'cancellation_days',
        'special_offers', 'no_of_rooms', 'booking_days', 'auto_send_arrival_report', 'is_asi_hotel'
    ]);

    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->password);
    }

    if ($request->hasFile('main_hotel_image')) {
        $data['main_hotel_image'] = $request->file('main_hotel_image')->store('hotels');
    }

    if ($request->hasFile('hotel_logo')) {
        $data['hotel_logo'] = $request->file('hotel_logo')->store('hotels');
    }

    $path = storage_path('public/storage/hotels');
    if ($request->hasFile('main_hotel_image')) {
    $image = ImageManager::imagick()->read($file);
    $image->save($path.$filename);
    }

    $hotel->update($data);

    return redirect()->route('admin.hotel.index')->with('success', 'Hotel updated successfully.');
}


public function edit($id)
{
    $hotel = Hotel::find($id);
    return view('admin.hotel.edit', compact('hotel'));
}

    
public function destroy($id)
{
    Hotel::find($id)->delete();

    return redirect()->route('admin.hotel.index')->with('success', 'Hotel deleted successfully.');
}

// Method to show the form for adding/updating hotel amenities, policies, and attractions
public function editDetails($id)
{
    // Fetch hotel details with relationships
    $hotel = Hotel::with(['amenities', 'policies', 'attractions', 'images'])->findOrFail($id);

    // Fetch parent amenities with their child amenities
    $parentAmenities = Amenity::whereNull('parent_id')->with('children')->get();

    // Pass data to the view
    return view('admin.hotel.edit-details', [
        'hotel' => $hotel,
        'parentAmenities' => $parentAmenities,
    ]);
}


// Method to store/update hotel amenities, policies, and attractions
public function updateDetails(Request $request, $id)
{
    $hotel = Hotel::findOrFail($id);

    

    // Validate hotel details
    $request->validate([
        'description' => 'required|string',
        'check_in' => 'nullable|string',
        'check_out' => 'nullable|string',
        'cancellation_pre_payment' => 'nullable|string',
        'children_extra_beds' => 'nullable|string',
        'pets' => 'nullable|string',
        'cards_accepted' => 'nullable|string',
        'resort_fee' => 'nullable|string',
        'amenities' => 'array',
        'attractions.*.type' => 'nullable|string',
        'attractions.*.name' => 'nullable|string',
        'attractions.*.distance' => 'nullable|string',
    ]);

    // Update hotel details
    $hotel->update($request->only([
        'description'
    ]));

    // Update hotel policies
    if ($hotel->policies) {
        $hotel->policies->update($request->only([
            'check_in',
            'check_out',
            'cancellation_pre_payment',
            'children_extra_beds',
            'pets',
            'cards_accepted',
            'resort_fee'
        ]));
    } else {
        $hotel->policies()->create($request->only([
            'check_in',
            'check_out',
            'cancellation_pre_payment',
            'children_extra_beds',
            'pets',
            'cards_accepted',
            'resort_fee'
        ]));
    }

    // Sync amenities
    $amenities = $request->input('amenities', []);
    $hotel->amenities()->sync($amenities);

    // Handle attractions
    $hotel->attractions()->delete(); // Remove existing attractions to avoid duplicates

    foreach ($request->input('attractions', []) as $attraction) {
        $hotel->attractions()->create($attraction);
    }

    // Handle image uploads
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $imagePath = $image->store('hotel_images', 'public');
            $hotel->images()->create([
                'image_path' => $imagePath,
            ]);
        }
    }

    // Update other hotel details
    $hotel->update($request->except('images'));

    return redirect()->route('admin.hotels.editDetails', $hotel->id)
        ->with('success', 'Hotel details updated successfully');
}




}

?>