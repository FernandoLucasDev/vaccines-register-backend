<?php

namespace Database\Seeders;

use App\Models\Vaccines;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaccinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vaccines = [
            ['name'=> 'unvaccinated', 'producer_name'=>'unvaccinated'],
            ['name' => 'CoronaVac', 'producer_name' => 'Sinovac/ Butantan'],
            ['name' => 'AstraZeneca', 'producer_name' => 'AstraZeneca/Fiocruz'],
            ['name' => 'Pfizer-BioNTech', 'producer_name' => 'Pfizer/BioNTech'],
            ['name' => 'Janssen', 'producer_name' => 'Johnson & Johnson'],
            ['name' => 'Sputnik V', 'producer_name' => 'Gamaleya'],
            ['name' => 'Covaxin', 'producer_name' => 'Bharat Biotech']
        ];

        foreach ($vaccines as $vaccine) {

            $existingVaccine = Vaccines::where('name', $vaccine['name'])->first();

            if (!$existingVaccine) {
                Vaccines::create([
                    'name' => $vaccine['name'],
                    'producer_name' => $vaccine['producer_name'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
