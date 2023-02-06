<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\City;

class CityController extends BaseController
{
    public function getCitiesByCountryId($country_id)
    {
        $returnData = City::where('country_id', $country_id)->get();
        $returnData = $this->sortCity($returnData);

        return $this->sendResponse($returnData, ' Country Cities.');
    }

    public function getCitiesByStateId($state_id)
    {
        $returnData = City::where('state_id', $state_id)->get();
        $returnData = $this->sortCity($returnData);
        return $this->sendResponse($returnData, 'State Cities.');
    }

    public function sortCity($cities){

        $collectiondata = json_decode($cities, true);

        $collection = collect($collectiondata);

        $sorted = $collection->sortBy(function ($product, $key) {
            return $product['name']['en'];
        });

        $sortedcities = [];

        $index = 0;
        foreach($sorted as $sort)
        {
            $sortedcities [$index] = $sort;

            $index++;
        }
        return $sortedcities;
    }
}
