<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider; 

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
{
    // Scoped route binding for tasks inside projects
    Route::bind('task', function (string $id, \Illuminate\Routing\Route $route) {
        $project = $route->parameter('project');
        if ($project instanceof \App\Models\Project) {
            return \App\Models\Task::where('id', $id)
                ->where('project_id', $project->id)
                ->firstOrFail();
        }
        return \App\Models\Task::findOrFail($id);
    });

    // Share label color map with all views
    \Illuminate\Support\Facades\View::share('labelColorMap', [
        'slate'   => 'bg-slate-100 text-slate-700 border-slate-200',
        'blue'    => 'bg-blue-100 text-blue-700 border-blue-200',
        'emerald' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'red'     => 'bg-red-100 text-red-700 border-red-200',
        'yellow'  => 'bg-yellow-100 text-yellow-700 border-yellow-200',
        'purple'  => 'bg-purple-100 text-purple-700 border-purple-200',
        'pink'    => 'bg-pink-100 text-pink-700 border-pink-200',
        'orange'  => 'bg-orange-100 text-orange-700 border-orange-200',
    ]);
}

}
