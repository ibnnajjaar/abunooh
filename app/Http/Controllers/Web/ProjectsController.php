<?php

namespace App\Http\Controllers\Web;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Support\Enums\PostTypes;

class ProjectsController
{
    public function index(Request $request)
    {
        $project_groups = Project::query()
            ->published()
                                 ->get()
                                 ->sortByDesc('year')
                                 ->groupBy('year');
        return view('web.projects.index', compact('project_groups'));
    }
}
