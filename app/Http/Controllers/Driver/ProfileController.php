<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $driver = Auth::user();

        abort_unless($driver->role === 'driver', 403);

        $driver->load([
            'activeAssignment.vehicle',
        ]);

        return view(
            'driver.profile.show',
            compact('driver')
        );
    }
}