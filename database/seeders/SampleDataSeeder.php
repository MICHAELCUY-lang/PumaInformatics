<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Cabinet;
use App\Models\CabinetDepartment;
use App\Models\CabinetMember;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventTag;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // AdminUserSeeder owns the Super Admin account; fall back to any user so
        // this seeder can still run standalone in a scratch database.
        $admin = User::where('email', env('ADMIN_EMAIL', 'admin@puma.it'))->first()
            ?? User::first()
            ?? User::factory()->create(['name' => 'Content Author']);

        // 1. Cabinet Seeding
        $cabinet = Cabinet::create([
            'name' => 'Komorebi Cabinet 2026',
            'term_year' => '2026/2027',
            'is_active' => true,
            'slug' => 'komorebi-2026',
        ]);

        $deptExec = CabinetDepartment::create([
            'name' => 'Executive Office',
            'slug' => 'executive-office',
            'description' => 'Steering institutional governance and strategy.',
            'is_active' => true,
            'order' => 1,
        ]);

        $deptTech = CabinetDepartment::create([
            'name' => 'Department of Tech & Labs',
            'slug' => 'tech-and-labs',
            'description' => 'Pioneering internal software, laboratory architecture, and research.',
            'is_active' => true,
            'order' => 2,
        ]);

        $deptDesign = CabinetDepartment::create([
            'name' => 'Creative & Brand Innovation',
            'slug' => 'creative-and-brand',
            'description' => 'Crafting aesthetics, print assets, and intuitive digital interfaces.',
            'is_active' => true,
            'order' => 3,
        ]);

        // Executive members
        CabinetMember::create([
            'cabinet_id' => $cabinet->id,
            'department_id' => $deptExec->id,
            'name' => 'Hiroshi Tanaka',
            'slug' => 'hiroshi-tanaka',
            'role_title' => 'Student Body President',
            'role_hierarchy_level' => 1,
            'term_year' => '2026/2027',
            'is_active' => true,
        ]);

        CabinetMember::create([
            'cabinet_id' => $cabinet->id,
            'department_id' => $deptExec->id,
            'name' => 'Aoi Sato',
            'slug' => 'aoi-sato',
            'role_title' => 'Vice President of Internal Affairs',
            'role_hierarchy_level' => 2,
            'term_year' => '2026/2027',
            'is_active' => true,
        ]);

        // Tech members
        CabinetMember::create([
            'cabinet_id' => $cabinet->id,
            'department_id' => $deptTech->id,
            'name' => 'Kenji Takahashi',
            'slug' => 'kenji-takahashi',
            'role_title' => 'Chief Technology Officer',
            'role_hierarchy_level' => 1,
            'term_year' => '2026/2027',
            'is_active' => true,
        ]);

        CabinetMember::create([
            'cabinet_id' => $cabinet->id,
            'department_id' => $deptTech->id,
            'name' => 'Yuki Watanabe',
            'slug' => 'yuki-watanabe',
            'role_title' => 'Lead Lab Engineer',
            'role_hierarchy_level' => 2,
            'term_year' => '2026/2027',
            'is_active' => true,
        ]);

        // Design members
        CabinetMember::create([
            'cabinet_id' => $cabinet->id,
            'department_id' => $deptDesign->id,
            'name' => 'Emiko Nakamura',
            'slug' => 'emiko-nakamura',
            'role_title' => 'Creative Director',
            'role_hierarchy_level' => 1,
            'term_year' => '2026/2027',
            'is_active' => true,
        ]);

        CabinetMember::create([
            'cabinet_id' => $cabinet->id,
            'department_id' => $deptDesign->id,
            'name' => 'Ren Ito',
            'slug' => 'ren-ito',
            'role_title' => 'Head of UI/UX',
            'role_hierarchy_level' => 2,
            'term_year' => '2026/2027',
            'is_active' => true,
        ]);


        // 2. News/Articles Seeding
        Article::create([
            'title' => 'Unveiling the Komorebi Digital System',
            'slug' => 'unveiling-the-komorebi-digital-system',
            'content' => '<p>Today, the PUMA IT Laboratory officially unveils the Komorebi digital system. Incorporating classic Japanese design philosophies with state-of-the-art web performance, the new design system prioritizes semantic clarity, subtle movement, and minimalist grids.</p><p>Led by the Department of Tech & Labs, the release outlines a complete overhaul of the institutional portal, community voting systems, and open research repositories.</p>',
            'status' => 'published',
            'author_id' => $admin->id,
            'published_at' => now()->subDays(2),
        ]);

        Article::create([
            'title' => 'PUMA IT Laboratory Annual Research Agenda 2026',
            'slug' => 'puma-it-laboratory-annual-research-agenda-2026',
            'content' => '<p>Our laboratory has finalized the research roadmap for the upcoming academic year. Major focuses include distributed ledger voting authentication, responsive student-governance middleware, and performance optimization in complex nested environments.</p>',
            'status' => 'published',
            'author_id' => $admin->id,
            'published_at' => now()->subDays(5),
        ]);

        Article::create([
            'title' => 'Empowering Student Voices Through Secure Aspirations',
            'slug' => 'empowering-student-voices-through-secure-aspirations',
            'content' => '<p>Security is the cornerstone of trust. We are upgrading our Aspiration portal to feature full TLS verification, rate-limiting, and encrypted attachments, guaranteeing that student input reaches decision-makers with zero chance of intercept.</p>',
            'status' => 'published',
            'author_id' => $admin->id,
            'published_at' => now()->subDays(10),
        ]);


        // 3. Events Seeding
        $evtCategory = EventCategory::create([
            'name' => 'Technology Showcase',
            'slug' => 'technology-showcase',
        ]);

        $evtTag = EventTag::create([
            'name' => 'Exhibition',
            'slug' => 'exhibition',
        ]);

        $event1 = Event::create([
            'category_id' => $evtCategory->id,
            'title' => 'Annual Digital Craftsmanship Exhibition',
            'slug' => 'annual-digital-craftsmanship-exhibition',
            'description' => 'Experience the intersection of digital utility and elegant design at our annual laboratory showcase. Highlighting 12 new interactive systems and tools compiled during the spring semester.',
            'status' => 'published',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(10)->addHours(6),
            'location_address' => 'Main Innovation Hall, Building C',
            'location_name' => 'Main Innovation Hall',
        ]);
        $event1->tags()->attach($evtTag);

        $event2 = Event::create([
            'category_id' => $evtCategory->id,
            'title' => 'Komorebi Design System Workshop',
            'slug' => 'komorebi-design-system-workshop',
            'description' => 'A deep dive into our typography tokens, minimalist cream styling, and micro-interaction patterns utilizing Alpine.js.',
            'status' => 'published',
            'start_date' => now()->addDays(15),
            'end_date' => now()->addDays(15)->addHours(3),
            'location_address' => 'Room 402, IT Wing',
            'location_name' => 'IT Seminar Room',
        ]);
        $event2->tags()->attach($evtTag);


        // 4. Projects Seeding
        $projCategory = ProjectCategory::create([
            'name' => 'Internal Software',
            'slug' => 'internal-software',
        ]);

        $tech1 = Technology::create(['name' => 'Laravel 12', 'slug' => 'laravel-12']);
        $tech2 = Technology::create(['name' => 'Alpine.js', 'slug' => 'alpine-js']);
        $tech3 = Technology::create(['name' => 'TailwindCSS', 'slug' => 'tailwindcss']);

        $project1 = Project::create([
            'category_id' => $projCategory->id,
            'title' => 'PUMA IT Governance Platform',
            'slug' => 'puma-it-governance-platform',
            'description' => 'An institutional governance platform managing student credentials, voting booths, secure digital assets, and structured community aspirations. Built from the ground up to support responsive rendering and HSL wave transitions.',
            'status' => 'published',
            'is_featured' => true,
        ]);
        $project1->technologies()->attach([$tech1->id, $tech2->id, $tech3->id]);

        $project2 = Project::create([
            'category_id' => $projCategory->id,
            'title' => 'Minimalist Seigaiha Portal',
            'slug' => 'minimalist-seigaiha-portal',
            'description' => 'A public portal utilizing highly optimized SVG graphics, custom typography layouts, and a responsive grayscale-to-color portrait grid representing active student cabinet members.',
            'status' => 'published',
            'is_featured' => true,
        ]);
        $project2->technologies()->attach([$tech1->id, $tech2->id]);


        // 5. Partners Seeding
        $partCategory = PartnerCategory::create([
            'name' => 'Corporate Collaborators',
            'slug' => 'corporate-collaborators',
        ]);

        Partner::create([
            'category_id' => $partCategory->id,
            'name' => 'Nippon Digital Systems',
            'slug' => 'nippon-digital-systems',
            'website_url' => 'https://nippon.digital.example.com',
            'is_active' => true,
            'is_featured' => true,
        ]);

        Partner::create([
            'category_id' => $partCategory->id,
            'name' => 'Kyoto Creative Labs',
            'slug' => 'kyoto-creative-labs',
            'website_url' => 'https://kyotocreative.example.com',
            'is_active' => true,
            'is_featured' => true,
        ]);
    }
}
