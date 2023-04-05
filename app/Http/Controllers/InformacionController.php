<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InformacionController extends Controller
{
    public function index()
    {
        $cars = Cars::where('sold', false)
                ->where('visible', true)
                ->orderByDesc('created_at')
                ->paginate(21);

        foreach ($cars as $car) {
            $car->files = json_decode($car->files)[0];
        }

        return response()->json($cars, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    public function search(Request $request, Cars $cars)
    {
            foreach (['financed', 'cash'] as $param) {
                $value = $request->input($param);
                $request->merge([$param => filter_var($value, FILTER_VALIDATE_BOOLEAN)]);
            }

        if (!$request->has('text')) {
           if (!$request->financed and $request->cash) {
                $cars = $cars->where('sold', false)
                             ->where('financed', false)
                             ->where('visible', true)
                             ->orderByDesc('created_at')
                             ->paginate(21);
           } elseif ($request->financed and !$request->cash) {
                $cars = $cars->where('sold', false)
                             ->where('financed', true)
                             ->where('visible', true)
                             ->orderByDesc('created_at')
                             ->paginate(21);
           } else {
                $cashCars = $cars->where('sold', false)
                             ->where('financed', false)
                             ->where('visible', true)
                             ->orderByDesc('created_at');
                $financedCars = $cars->where('sold', false)
                                     ->where('financed', true)
                                     ->where('visible', true)
                                     ->orderByDesc('created_at');
                $cars = $cashCars->union($financedCars)
                                 ->orderByDesc('created_at')
                                 ->paginate(21);
           }
        } else {
            if (!$request->financed and $request->cash) {
                $cars = $cars->where('sold', false)
                             ->where('financed', false)
                             ->where('visible', true)
                             ->where('description', 'LIKE', '%'. $request->text .'%')
                             ->orderByDesc('created_at')
                             ->paginate(21);
           } elseif ($request->financed and !$request->cash) {
                $cars = $cars->where('sold', false)
                             ->where('financed', true)
                             ->where('visible', true)
                             ->where('description', 'LIKE', '%'. $request->text .'%')
                             ->orderByDesc('created_at')
                             ->paginate(21);
           } else {
                $cashCars = $cars->where('sold', false)
                             ->where('financed', false)
                             ->where('visible', true)
                             ->where('description', 'LIKE', '%'. $request->text .'%')
                             ->orderByDesc('created_at');
                $financedCars = $cars->where('sold', false)
                                     ->where('financed', true)
                                     ->where('visible', true)
                                     ->where('description', 'LIKE', '%'. $request->text .'%')
                                     ->orderByDesc('created_at');
                $cars = $cashCars->union($financedCars)
                                 ->orderByDesc('created_at')
                                 ->paginate(21);
           }
        }

        foreach ($cars as $car) {
            $car->files = json_decode($car->files)[0];
        }

        return response()->json($cars, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    public function view(Cars $car, $id)
    {
        $car = $car->where('id', $id)
                   ->where('visible', true)
                   ->get();
        if ($car->count() > 0) {
            $car[0]->files = json_decode($car[0]->files);
            return response()->json($car, 200, [
                'Content-Type' => 'application/json'
            ]);
        } else {
            return response()->json([
                'error' => 'El artículo no existe.'
            ], 404);
        }
        
    }
}
