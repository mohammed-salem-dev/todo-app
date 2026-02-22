<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Enums\TaskState;
use App\Enums\RecurrenceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── User ──────────────────────────────────────────────
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name'     => 'Demo User',
                'password' => Hash::make('password'),
            ]
        );

        // ── Labels ────────────────────────────────────────────
        $bug     = Label::firstOrCreate(['user_id' => $user->id, 'name' => 'Bug'],     ['color' => 'red']);
        $feature = Label::firstOrCreate(['user_id' => $user->id, 'name' => 'Feature'], ['color' => 'blue']);
        $urgent  = Label::firstOrCreate(['user_id' => $user->id, 'name' => 'Urgent'],  ['color' => 'orange']);
        $design  = Label::firstOrCreate(['user_id' => $user->id, 'name' => 'Design'],  ['color' => 'purple']);

        // ── Project 1 ─────────────────────────────────────────
        $p1 = Project::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Website Redesign'],
            ['description' => 'Revamp the company website with a modern look.']
        );

        $t1 = Task::create([
            'project_id'   => $p1->id,
            'title'        => 'Design new homepage mockup',
            'details'      => 'Create wireframes and high-fidelity designs in Figma.',
            'state'        => TaskState::Done,
            'due_at'       => now()->subDays(2),
            'completed_at' => now()->subDays(1),
        ]);
        $t1->labels()->sync([$design->id]);

        $t2 = Task::create([
            'project_id' => $p1->id,
            'title'      => 'Implement responsive navbar',
            'details'    => 'Mobile-first, collapsible on small screens.',
            'state'      => TaskState::Doing,
            'due_at'     => now()->addDays(2),
        ]);
        $t2->labels()->sync([$feature->id]);

        $t3 = Task::create([
            'project_id'          => $p1->id,
            'title'               => 'Weekly design review',
            'details'             => 'Review progress with the team every week.',
            'state'               => TaskState::Todo,
            'due_at'              => now()->addDays(5),
            'recurrence_type'     => RecurrenceType::Weekly,
            'recurrence_interval' => 1,
        ]);
        $t3->labels()->sync([$design->id, $urgent->id]);

        $t4 = Task::create([
            'project_id' => $p1->id,
            'title'      => 'Fix broken image links on blog',
            'details'    => 'Several posts have 404 images after the migration.',
            'state'      => TaskState::Todo,
            'due_at'     => now()->subDay(),
        ]);
        $t4->labels()->sync([$bug->id, $urgent->id]);

        // ── Project 2 ─────────────────────────────────────────
        $p2 = Project::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Mobile App MVP'],
            ['description' => 'Build and ship the first version of the mobile app.']
        );

        $t5 = Task::create([
            'project_id'   => $p2->id,
            'title'        => 'Set up React Native project',
            'state'        => TaskState::Done,
            'completed_at' => now()->subDays(3),
        ]);
        $t5->labels()->sync([$feature->id]);

        $t6 = Task::create([
            'project_id' => $p2->id,
            'title'      => 'Build auth screens (login + register)',
            'details'    => 'Use JWT tokens. Match the web app design.',
            'state'      => TaskState::Doing,
            'due_at'     => now()->addDays(3),
        ]);
        $t6->labels()->sync([$feature->id, $design->id]);

        $t7 = Task::create([
            'project_id'          => $p2->id,
            'title'               => 'Daily standup reminder',
            'details'             => 'Post update in Slack every morning.',
            'state'               => TaskState::Todo,
            'due_at'              => now()->addDay(),
            'recurrence_type'     => RecurrenceType::Daily,
            'recurrence_interval' => 1,
        ]);

        $t8 = Task::create([
            'project_id' => $p2->id,
            'title'      => 'Crash on Android when opening notifications',
            'details'    => 'Reproducible on Android 12. Null pointer in NotifHandler.',
            'state'      => TaskState::Todo,
            'due_at'     => now()->subDay(),
        ]);
        $t8->labels()->sync([$bug->id, $urgent->id]);
    }
}
