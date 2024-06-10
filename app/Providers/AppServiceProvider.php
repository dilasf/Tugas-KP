<?php

namespace App\Providers;

use App\Models\AttitudeScore;
use App\Models\KnowledgeScore;
use App\Models\SkillScore;
use App\Models\Student;
use App\Observers\AttitudeScoreObserver;
use App\Observers\ScoreObserver;
use App\Observers\SkillScoreObserver;
use App\Observers\StudentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Student::observe(StudentObserver::class);
        KnowledgeScore::observe(ScoreObserver::class);
        AttitudeScore::observe(AttitudeScoreObserver::class);
        SkillScore::observe(SkillScoreObserver::class);
    }
}
