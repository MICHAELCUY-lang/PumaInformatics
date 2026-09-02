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
        // Blade passes "" (not null) when a @section is absent, so use ?: not ??.
        $this->description = $description ?: 'The official institutional showcase for PUMA IT. Discover our projects, news, and initiatives.';
        // Falls back to the site logo, which actually ships in public/.
        // The previous default pointed at images/default-og.jpg, a file that
        // does not exist, so every share card rendered broken.
        $this->image = $image ?: asset('logo.png');
        $this->type = $type;
        $this->url = Request::url();
    }

    public function render()
    {
        return view('components.public.seo');
    }
}
