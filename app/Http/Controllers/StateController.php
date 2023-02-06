<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\State;

class StateController extends BaseController
{
  public function getStatesByCountryId($country_id)
  {
    $returnData = State::where('country_id', $country_id)->get();
    return $this->sendResponse($returnData, ' Country States.');
  }
}
