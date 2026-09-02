<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use App\Models\CabinetDepartment;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $articleRepository;
    protected $eventRepository;
    protected $projectRepository;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        EventRepositoryInterface $eventRepository,
        ProjectRepositoryInterface $projectRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->eventRepository = $eventRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Display the public homepage.
     */
    public function index()
    {
        $featuredProjects = $this->projectRepository->getFeatured(2);
        $upcomingEvents = $this->eventRepository->getUpcomingEvents(3);
        $latestArticles = $this->articleRepository->getLatest(4);

        // Cabinet — get active cabinet with top members
        $activeCabinet = Cabinet::where('is_active', true)->first() ?? Cabinet::latest()->first();
        $cabinetMembers = collect();
        if ($activeCabinet) {
            $cabinetMembers = CabinetDepartment::with(['members' => function ($query) use ($activeCabinet) {
                $query->where('is_active', true)
                      ->where('cabinet_id', $activeCabinet->id)
                      ->orderBy('role_hierarchy_level', 'asc')
                      ->with('media')
                      ->limit(6);
            }])
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->get()
            ->flatMap->members
            ->take(6);
        }

        // Every generation, newest first, for the lineage strip under the hero.
        // Ordered by generation where it is known and term otherwise, so a
        // cabinet added before the field existed still lands sensibly.
        $cabinetLineage = Cabinet::withCount(['members' => fn ($q) => $q->where('is_active', true)])
            ->orderByRaw('generation IS NULL, generation DESC')
            ->orderBy('term_year', 'desc')
            ->get();

        return view('public.home', compact(
            'featuredProjects', 'upcomingEvents', 'latestArticles',
            'activeCabinet', 'cabinetMembers', 'cabinetLineage'
        ));
    }
}
