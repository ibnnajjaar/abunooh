<?php

namespace App\Http\Controllers\Web;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController
{
    public function index(Request $request)
    {
        $project_groups = Project::query()
            ->with('tags')
            ->published()
                                 ->get()
                                 ->sortByDesc('year')
                                 ->groupBy('year');
        return view('web.projects.index', compact('project_groups'));
    }
}
