<?php 

namespace App\Services;

use App\Models\Vaccines;
use Illuminate\Support\Facades\Cache;

class VaccinesCacheService
{
    protected $key = 'vaccines';

    /**
     * Store vaccines in a cache
     */
    public function getAllVacinas()
    {
        return Cache::remember($this->key, 60, function () {
            return Vaccines::all();
        });
    }

    /**
     * Clear vaccine cache
     */
    public function clearCache()
    {
        Cache::forget($this->key);
    }
}