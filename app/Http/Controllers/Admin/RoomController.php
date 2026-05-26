<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function create(Hotel $hotel)
    {
        return view('admin.rooms.create', compact('hotel'));
    }

    public function store(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'room_type'       => 'required|in:Single,Double,Suite,Villa',
            'max_guests'      => 'required|integer|min:1',
            'size_sqm'        => 'nullable|numeric',
            'bed_type'        => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'total_rooms'     => 'required|integer|min:1',
            'image'           => 'nullable|url',
            'is_active'       => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $hotel->rooms()->create($validated);

        return redirect()->route('admin.hotels.show', $hotel)
            ->with('success', 'Đã thêm loại phòng thành công!');
    }

    public function edit(Hotel $hotel, Room $room)
    {
        return view('admin.rooms.edit', compact('hotel', 'room'));
    }

    public function update(Request $request, Hotel $hotel, Room $room)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'room_type'       => 'required|in:Single,Double,Suite,Villa',
            'max_guests'      => 'required|integer|min:1',
            'size_sqm'        => 'nullable|numeric',
            'bed_type'        => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'total_rooms'     => 'required|integer|min:1',
            'image'           => 'nullable|url',
            'is_active'       => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $room->update($validated);

        return redirect()->route('admin.hotels.show', $hotel)
            ->with('success', 'Đã cập nhật loại phòng thành công!');
    }

    public function destroy(Hotel $hotel, Room $room)
    {
        $room->delete();
        return redirect()->route('admin.hotels.show', $hotel)
            ->with('success', 'Đã xóa loại phòng thành công!');
    }
}