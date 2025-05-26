<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            'platforms' => Platform::all()
        ]);
    }

    // public function togglePlatform(Request $request)
    // {
    //     $request->validate([
    //         'platform_id' => 'required|exists:platforms,id',
    //         'is_active' => 'required|boolean',
    //     ]);

    //     auth()->user()->platforms()->syncWithoutDetaching([
    //         $request->platform_id => ['is_active' => $request->is_active]
    //     ]);

    //     return response()->json(['message' => 'Platform updated']);
    // }
}
