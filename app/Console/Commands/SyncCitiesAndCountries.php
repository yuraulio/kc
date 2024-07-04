<?php

namespace App\Console\Commands;

use App\Model\City;
use App\Model\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncCitiesAndCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-cities-and-countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch a list of cities and countries and update our database with them.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Downloading city and country data...');
        $response = Http::get('https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/countries%2Bcities.json');

        $data = collect($response->json());

        $data->each(function ($country) {
            $this->info("Adding country {$country['name']}...");
            Country::firstOrCreate([
                'name' => $country['name'],
            ]);
        });

        $this->info('Fixing existing Greek cities...');

        City::whereIn('name', [
            'Thessaloniki',
            'Athens',
            'Heraklion',
        ])->update([
            'country_id' => Country::whereName('Greece')->firstOrFail()->id,
        ]);

        $data->each(function ($country) {
            $countryModel = Country::whereName($country['name'])->first();

            foreach ($country['cities'] as $city) {
                $this->info("Adding {$countryModel->name} - {$city['name']}");

                City::firstOrCreate([
                    'name' => $city['name'],
                    'country_id' => $countryModel->id,
                ]);
            }
        });

        $this->info('Done');
    }
}
