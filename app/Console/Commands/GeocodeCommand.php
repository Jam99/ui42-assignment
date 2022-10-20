<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GeocodeCommand extends Command
{
    protected $api_key;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:geocode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates latitude and longitude properties of imported cities.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->api_key = env("GOOGLE_GEOCODE_API_KEY");
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cities = City::all();

        foreach ($cities as $city){
            $geocode = $this->getGeocode($city->getAttributeValue("name"), $city->getAttributeValue("ch_address"));

            $city->update([
                "latitude"  => $geocode->lat,
                "longitude" => $geocode->lng
            ]);
        }

        return 0;
    }

    /**
     * @param $city_name
     * @param $ch_address
     * @return false|object
     * @throws \Exception
     */
    private function getGeocode($city_name, $ch_address)
    {
        if (!$this->api_key) {
            throw new \Exception("You must add GOOGLE_GEOCODE_API_KEY to your .env file.");
        }

        if (env("APP_ENV") === "local")
            $tmp = Http::withoutVerifying();
        else
            $tmp = Http::class;

        $response = $tmp->get("https://maps.googleapis.com/maps/api/geocode/json", [
            "address" => "Slovakia, Nitriansky kraj, " . $city_name . ", " . $ch_address, //added prefix value to be more accurate
            "key" => $this->api_key
        ]);

        if ($response->status() === 200) {
            $response = $response->object();

            if ($response->status === "OK") {
                if (empty($response->results)) {
                    echo "Skipped '" . $city_name . " " . $ch_address . ': results array was empty in response.';
                    return false;
                }

                echo "Processed ";
                $this->info($response->results[0]->formatted_address);

                return $response->results[0]->geometry->location;
            } else {
                throw new \Exception("Failed to import geocode data. API responded with an error code: " . $response->status . ".");
            }
        } else {
            throw new \Exception("Failed to fetch geocode data. Server responded with HTTP " . $response->status() . ".");
        }
    }
}
