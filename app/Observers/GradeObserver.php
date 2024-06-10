<?php

namespace App\Observers;

use App\Models\Grade;
use App\Services\RaporSyncService;

class GradeObserver
{
    public function saved(Grade $grade)
    {
        // Panggil proses sinkronisasi rapor
        RaporSyncService::sync();
    }
}
