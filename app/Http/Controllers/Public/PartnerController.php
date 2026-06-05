<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::with(['category', 'media'])
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('name', 'asc')
            ->get()
            ->groupBy(function($partner) {
                return $partner->category ? $partner->category->name : 'General Partners';
            });

        return view('public.partners.index', compact('partners'));
    }
}
