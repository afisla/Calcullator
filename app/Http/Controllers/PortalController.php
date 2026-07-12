<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    /**
     * Welcome landing page: Select role (User / Pemilik Usaha)
     */
    public function welcome()
    {
        return view('portal.welcome');
    }

    /**
     * Portal Pemilik Usaha: Step 1 (Select Unit) or Step 2 (Select Kantin Store)
     */
    public function ownerPortal(Request $request)
    {
        $unit = $request->query('unit');

        if ($unit === 'kantin') {
            $stores = Store::where('unit', 'kantin')->orderBy('sort_order')->get();
            return view('portal.owner-list-kantin', compact('stores'));
        }

        // Get koperasi store for direct login routing
        $koperasiStore = Store::where('unit', 'koperasi')->first();

        return view('portal.owner-portal-select', compact('koperasiStore'));
    }
}
