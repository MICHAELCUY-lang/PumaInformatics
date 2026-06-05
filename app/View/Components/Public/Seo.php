<?php

namespace App\View\Components\Public;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Request;

class Seo extends Component
{
    public $title;
    public $description;
    public $image;
    public $type;
    public $url;

    public function __construct(
        $title = null, 
        $description = null, 
        $image = null, 
        $type = 'website'
    ) {
        $appName = config('app.name', 'PUMA IT');
        
        $this->title = $title ? "{$title} - {$appName}" : "{$appName} - Excellence in Technology";
        $this->description = $description ?? 'The official institutional showcase for PUMA IT. Discover our projects, news, and initiatives.';
        $this->image = $image ?? asset('images/default-og.jpg'); // Fallback image
        $this->type = $type;
        $this->url = Request::url();
    }

    public function render()
    {
        return view('components.public.seo');
    }
}
