<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    public function index()
    {
       $countries = Country::withCount('arrivals')->get();

        return response()->json([
            'success' => true,
            'count' => $countries->count(),
            'data' => $countries
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:countries',
            'code' => 'required|size:2|unique:countries|uppercase',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $country = Country::create($request->all());

        return response()->json([
            'message' => 'Pays créé',
            'data' => $country
        ], 201);
    }

    public function show(Country $country)
    {
        return response()->json([
            'success' => true,
            'data' => $country->load('arrivals')
        ]);
    }

    public function update(Request $request, Country $country)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|unique:countries,name,' . $country->id,
            'code' => 'sometimes|size:2|unique:countries,code,' . $country->id . '|uppercase'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $country->update($request->all());

        return response()->json([
            'message' => 'Pays mis à jour',
            'data' => $country
        ]);
    }

    public function destroy(Country $country)
    {
         if ($country->arrivals()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer un pays avec des arrivages'
            ], 400);
        }

        $country->delete();

        return response()->json([
            'message' => 'Pays supprimé avec succès'
        ]);
    }
}
