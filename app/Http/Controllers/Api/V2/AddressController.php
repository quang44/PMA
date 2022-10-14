<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\AddressResource;
use App\Http\Resources\V2\AddressSearchCollection;
use App\Http\Resources\V2\DistrictsCollection;
use App\Http\Resources\V2\ProvincesCollection;
use App\Http\Resources\V2\WardsCollection;
use App\Http\Resources\V2\AddressCollection;
use App\Models\Address;
use App\Models\AddressSearch;
use App\Models\CustomerGroup;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $address = Address::query();
        $address = $address->where('user_id', auth()->user()->id);
        if(!empty($request->type)){
            $address = $address->where('type', $request->type);
        }
        $keyword = $request->keyword;
        if ($keyword != "") {
            $address = $address->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')->orWhere('phone', 'like', '%' . $keyword . '%');
            });
        }
        $address = $address->with(['province', 'district', 'ward'])->orderBy('set_default', 'desc')->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return new AddressCollection($address);
    }

    public function store(Request $request)
    {
        if ($request->set_default == 1 && $request->type == 'send') {
            Address::where('user_id', auth()->user()->id)->update(['set_default' => 0]);
        }
        $address = new Address;
        $address->user_id = auth()->user()->id;
        $address->address = $request->address;
        $address->province_id = $request->province_id;
        $address->district_id = $request->district_id;
        $address->ward_id = $request->ward_id;
        $address->phone = $request->phone;
        $address->name = $request->name;
        $address->type = $request->type;
        $address->set_default = (int)$request->set_default;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Shipping information has been added successfully')
        ]);
    }

    public function update($id, Request $request)
    {
        $address = Address::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($address == null) {
            return response()->json([
                'result' => false,
                'message' => translate('Address not found')
            ]);
        }
        if ($request->set_default == 1 && $address->type == 'send') {
            Address::where('user_id', auth()->user()->id)->update(['set_default' => 0]);
        }
        $address->address = $request->address;
        $address->province_id = $request->province_id;
        $address->district_id = $request->district_id;
        $address->ward_id = $request->ward_id;
        $address->phone = $request->phone;
        $address->name = $request->name;
        $address->set_default = (int)$request->set_default;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Shipping information has been updated successfully')
        ]);
    }

    /*public function updateShippingAddressLocation(Request $request)
    {
        $address = Address::find($request->id);
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Shipping location in map updated successfully')
        ]);
    }*/


    public function delete($id)
    {
        $address = Address::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($address == null) {
            return response()->json([
                'result' => false,
                'message' => translate('Address not found')
            ]);
        }
        $address->delete();
        return response()->json([
            'result' => true,
            'message' => translate('Shipping information has been deleted')
        ]);
    }

    public function show($id){
        $address = Address::where('id', $id)->where('user_id', auth()->user()->id)->with(['province', 'district', 'ward'])->first();
        return new AddressResource($address);
    }

    public function showDefault(){
        $address = Address::where('set_default', 1)->where('user_id', auth()->user()->id)->with(['province', 'district', 'ward'])->first();
        return new AddressResource($address);
    }

    /*public function makeShippingAddressDefault(Request $request)
    {
        Address::where('user_id', auth()->user()->id)->update(['set_default' => 0]); //make all user addressed non default first

        $address = Address::find($request->id);
        $address->set_default = 1;
        $address->save();
        return response()->json([
            'result' => true,
            'message' => translate('Default shipping information has been updated')
        ]);
    }

    public function updateAddressInCart(Request $request)
    {
        try {
            Cart::where('user_id', auth()->user()->id)->update(['address_id' => $request->address_id]);

        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => translate('Could not save the address')
            ]);
        }
        return response()->json([
            'result' => true,
            'message' => translate('Address is saved')
        ]);


    }

    public function getCities()
    {
        return new CitiesCollection(City::where('status', 1)->get());
    }

    public function getStates()
    {
        return new StatesCollection(State::where('status', 1)->get());
    }

    public function getCountries(Request $request)
    {
        $country_query = Country::where('status', 1);
        if ($request->name != "" || $request->name != null) {
             $country_query->where('name', 'like', '%' . $request->name . '%');
        }
        $countries = $country_query->get();

        return new CountriesCollection($countries);
    }

    public function getCitiesByState($state_id,Request $request)
    {
        $city_query = City::where('status', 1)->where('state_id',$state_id);
        if ($request->name != "" || $request->name != null) {
             $city_query->where('name', 'like', '%' . $request->name . '%');
        }
        $cities = $city_query->get();
        return new CitiesCollection($cities);
    }

    public function getStatesByCountry($country_id,Request $request)
    {
        $state_query = State::where('status', 1)->where('country_id',$country_id);
        if ($request->name != "" || $request->name != null) {
            $state_query->where('name', 'like', '%' . $request->name . '%');
       }
        $states = $state_query->get();
        return new StatesCollection($states);
    }*/

    public function getProvinces()
    {
        return new ProvincesCollection(Province::orderBy('order')->get());
    }

    public function getDistrictsByProvince($province_id)
    {
        return new DistrictsCollection(District::where('province_id', $province_id)->get());
    }

    public function getWardsByDistrict($district_id)
    {
        return new WardsCollection(Ward::where('district_id', $district_id)->get());
    }

    public function search(Request $request){
        //$address = AddressSearch::search($request->keyword);
        $address = AddressSearch::query();
        $keyword = $request->keyword;
        if ($keyword != "") {
            $address = $address->where(function ($query) use ($keyword) {
                /*$keywords = explode(' ', trim($keyword));
                foreach ($keywords as $word) {
                    $query->where('name', 'like', '%'.$word.'%');
                }*/
                $keyword2 = str_replace('d', 'đ' , $keyword);
                $query->where('name', 'like', '%'.$keyword.'%')->orWhere('name', 'like', '%'.$keyword2.'%');
            });
        }
        $address = $address->orderBy('name', 'asc')->paginate($request->limit ?? 10);
        return new AddressSearchCollection($address);
    }

    public function convertAddress(Request $request){

        $min = $request->min;
        $max = $min + 1000;
        for ($i = $min; $i < $max; $i++) {
            $add = AddressSearch::find($i);
            if($add){
                $string = $add->name;
                $string = explode(',', $string);
                $string = trim($string[2]) . ', ' . trim($string[1]) . ', ' . trim($string[0]);
                $add->name = $string;
                $add->save();
            }
        }
        echo 'done';
    }
}
