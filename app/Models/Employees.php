<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employees extends Model
{
    use HasFactory;

    protected $fillable = [
        'cpf',
        'anonymized_cpf',
        'full_name',
        'birth_date',
        'is_pcd'
    ];

    protected $hidden = [
        'cpf',
    ];

    public function setCpfAttribute($cpf)
    {
        $this->attributes['cpf'] = $this->hashCpf($cpf);
    }

    protected function hashCpf($cpf)
    {
        $key = config('app.key');
        return hash_hmac('sha256', $cpf, $key);
    }

    public function vaccines(): BelongsToMany
    {
        return $this->belongsToMany(Vaccines::class)
                    ->withPivot('batch', 'validate_date', 'first_dose_vaccine', 'second_dose_vaccine', 'third_dose_vaccine')
                    ->withTimestamps();
    }
}
