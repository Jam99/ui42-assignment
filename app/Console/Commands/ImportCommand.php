<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\District;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use KubAT\PhpSimple\HtmlDomParser;

class ImportCommand extends Command
{
    /**
     * Target URL
     *
     * @var string
     */
    protected $target = "https://www.e-obce.sk/kraj/NR.html";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:import {--clear : Deletes all records from tables before executing insert queries.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses and imports data from https://www.e-obce.sk/kraj/NR.html';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $data = [];

            $dom = HtmlDomParser::file_get_html($this->target, false, null, 0);

            $matches = []; //helper array to extract data using regex
            preg_match_all("/href=\"(http.+?)\"/", trim($dom->find("#telo td[valign=top]", 9)), $matches);
            $this->checkMatches($matches);

            //old images will be deleted each time the import is triggered
            $file = new Filesystem();
            $file->cleanDirectory('storage/app/public/coa_images');

            foreach($matches[1] as $sub_district){
                $sub_dom = HtmlDomParser::file_get_html($sub_district, false, null, 0);

                $sub_district_data = [
                    "name" => preg_replace("/^<b>Vyberte si obec alebo mesto z okresu |:<\/b>$/", "", trim($sub_dom->find("b", 3))),
                    "cities" => []
                ];

                $sub_matches = [];
                preg_match_all("/href=\"(http.+?)\"/", trim($sub_dom->find("#telo table", 10)), $sub_matches);
                $this->checkMatches($sub_matches);

                foreach($sub_matches[1] as $city){
                    echo "Parsing... ". $city;

                    try { //if the target url is not accessible (for example: HTTP 404 response) then we must handle this exception separately
                        $city_dom = HtmlDomParser::file_get_html($city, false, null, 0);
                    }
                    catch (\Exception $e) {
                        $this->error("Failed to fetch content from ". $city);
                        continue;
                    }

                    $coa_matches = [];
                    preg_match("/src=\"(http[s]?:\/\/www.e-obce\.sk\/erb\/.+?)\"/", (string)$city_dom, $coa_matches);

                    if(!empty($coa_matches)){
                        echo " downloading image... ". $coa_matches[1];
                        $tmp_file_name = tempnam(sys_get_temp_dir(), "coa_tmp_");
                        copy($coa_matches[1], $tmp_file_name);
                        $file_path = Storage::putFile("public/coa_images", new File($tmp_file_name));
                    }
                    else{
                        echo " image not found";
                    }

                    $tmp = [
                        "coa_path"      => $file_path ?? null,
                        "city_name"     => preg_replace("/Obec |Mesto | \(.*\)/", "", trim(strip_tags($city_dom->find("h1")[0]))),
                        "mayor_name"    => trim(strip_tags($city_dom->find("#telo td", 55))),
                        "websites"      => explode(" ", trim(strip_tags($city_dom->find("#telo td", 38)))),
                        "phone_numbers" => explode(", ", trim(strip_tags($city_dom->find("#telo td", 29)))),
                        "ch_address"    => trim(strip_tags($city_dom->find("#telo td", 33))),
                        "faxes"         => explode(", ", trim(strip_tags($city_dom->find("#telo td", 32)))),
                        "emails"        => explode(" ", trim(strip_tags($city_dom->find("#telo td", 35)))),
                    ];

                    $sub_district_data["cities"][] = $tmp; //pushing city array
                    echo "\n";
                }

                $data[] = $sub_district_data;
            }

            echo "Saving parsed data into database...\n";
            $this->saveData($data);
            $this->info("Districts and cities were imported successfully.");
            //echo base64_encode(json_encode($data));
        }
        catch(\Exception $e) {
            $this->error("An error occurred. Import was NOT successful.\n" . $e->getMessage());
        }

        return 0;
    }

    /**
     * Throws an exception with a specific message if the $matches array is empty
     *
     * @param array $matches
     * @throws \Exception
     */
    private function checkMatches($matches){
        if(empty($matches) || empty($matches[1])){
            throw new \Exception("Target elements not found. Maybe the DOM of the target page was changed.");
        }
    }

    /**
     * @param $data - $data array built in handle() method
     */
    private function saveData($data){

        if($this->hasOption("clear")){
            City::query()->delete();
            District::query()->delete();
        }

        foreach($data as $district_item){

            $district = District::query()->where("name", $district_item["name"])->first();

            if(!$district){
                $district = new District();
                $district->name = $district_item["name"];
                $district->save();
            }

            foreach($district_item["cities"] as $city_item){
                $city = City::query()->where("name", $city_item["city_name"])->first();

                if(!$city)
                    $city = new City();

                $city->district_id = $district->id;
                $city->coa_path = $city_item["coa_path"];
                $city->name = $city_item["city_name"];
                $city->mayor_name = $city_item["mayor_name"];
                $city->ch_address = $city_item["ch_address"];
                $city->websites = implode(";", $city_item["websites"]);
                $city->phone_numbers = implode(";", $city_item["phone_numbers"]);
                $city->faxes = implode(";", $city_item["faxes"]);
                $city->emails = implode(";", $city_item["emails"]);
                $city->save();
            }
        }
    }
}
