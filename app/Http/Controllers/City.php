<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class City extends Controller
{
    public function single($id){
        $city = \App\Models\City::query()->find($id);

        if(!$city){
            abort(404);
        }

        return view("city_single", [
            "city" => $city
        ]);
    }

    public function ajaxSearch(Request $request) : array{
        $search = $request->get("search");

        if(!$search)
            abort(400);

        $cities = \App\Models\City::query()->where("name", "like", "%".$search."%")->limit(5)->get(["id", "name"]);

        return [
            "success" => true,
            "results" => $cities->toArray()
        ];
    }
}
