<?php

namespace App\Observers;

use App\Models\HeightWeight;
use App\Models\Rapor;

class RaporObserver
{
    /**
     * Handle the Rapor "created" event.
     */
    public function created(Rapor $rapor): void
    {
        HeightWeight::create([
            'student_id' => $rapor->student_id, // Asumsikan rapor memiliki student_id
            'rapor_id' => $rapor->id,
            // Tambahkan field lainnya sesuai kebutuhan
        ]);
    }

    /**
     * Handle the Rapor "updated" event.
     */
    public function updated(Rapor $rapor): void
    {
        HeightWeight::updateOrCreate(
            ['rapor_id' => $rapor->id],
            [
                'student_id' => $rapor->student_id,
                // Tambahkan field lainnya sesuai kebutuhan
            ]
        );
    }

    /**
     * Handle the Rapor "deleted" event.
     */
    public function deleted(Rapor $rapor): void
    {
        //
    }

    /**
     * Handle the Rapor "restored" event.
     */
    public function restored(Rapor $rapor): void
    {
        //
    }

    /**
     * Handle the Rapor "force deleted" event.
     */
    public function forceDeleted(Rapor $rapor): void
    {
        //
    }
}
