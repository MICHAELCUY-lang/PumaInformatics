<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AspirationService;
use App\Http\Requests\StoreAspirationRequest;
use App\DTOs\AspirationData;

class AspirationController extends Controller
{
    protected AspirationService $service;

    public function __construct(AspirationService $service)
    {
        $this->service = $service;
    }

    public function create()
    {
        $categories = \App\Models\AspirationCategory::orderBy('name')->get();
        return view('public.aspirations.create', compact('categories'));
    }

    public function store(StoreAspirationRequest $request)
    {
        $data = $request->validated();
        
        // Setup IP hash for abuse tracking
        $ipHash = hash('sha256', $request->ip() . config('app.key'));
        $data['ip_hash'] = $ipHash;
        
        // Attach user if not anonymous
        if (empty($data['is_anonymous']) && $request->user()) {
            $data['user_id'] = $request->user()->id;
        }

        $dto = AspirationData::fromArray($data);
        $aspiration = $this->service->createAspiration($dto);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $aspiration->addMedia($file)->toMediaCollection('attachments');
            }
        }

        return redirect()->back()->with('success', 'Your aspiration has been submitted securely.');
    }
}
