<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Cars::where('sold', false)
                ->orderByDesc('created_at')
                ->paginate(21);

        foreach ($cars as $car) {
            $car->files = json_decode($car->files)[0];
        }

        return response()->json($cars, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Search by description.
     */
    public function search(Request $request, Cars $cars)
    {
        if (!$request->has('text')) {
           if (!$request->financed and $request->cash) {
               // solo cash
                $cars = $cars->where('sold', $request->sold)
                             ->where('financed', false)
                             ->orderByDesc('created_at')
                             ->paginate(21);
           } elseif ($request->financed and !$request->cash) {
                // solo financiado
                $cars = $cars->where('sold', $request->sold)
                             ->where('financed', true)
                             ->orderByDesc('created_at')
                             ->paginate(21);
           } else {
                // ambos
                $cashCars = $cars->where('sold', $request->sold)
                             ->where('financed', false)
                             ->orderByDesc('created_at');
                $financedCars = $cars->where('sold', $request->sold)
                                     ->where('financed', true)
                                     ->orderByDesc('created_at');
                $cars = $cashCars->union($financedCars)
                                 ->orderByDesc('created_at')
                                 ->paginate(21);
           }
        } else {
            if (!$request->financed and $request->cash) {
               // solo cash
                $cars = $cars->where('sold', $request->sold)
                             ->where('financed', false)
                             ->where('description', 'LIKE', '%'. $request->text .'%')
                             ->orderByDesc('created_at')
                             ->paginate(21);
           } elseif ($request->financed and !$request->cash) {
                // solo financiado
                $cars = $cars->where('sold', $request->sold)
                             ->where('financed', true)
                             ->where('description', 'LIKE', '%'. $request->text .'%')
                             ->orderByDesc('created_at')
                             ->paginate(21);
           } else {
                // ambos
                $cashCars = $cars->where('sold', $request->sold)
                             ->where('financed', false)
                             ->where('description', 'LIKE', '%'. $request->text .'%')
                             ->orderByDesc('created_at');
                $financedCars = $cars->where('sold', $request->sold)
                                     ->where('financed', true)
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

        return response()->json($cars);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->merge(['created_by' => auth()->id()]);
        $request->validate([
            'description' => [
                'required'
            ],
            'financed' => [
                'boolean'
            ],
            'visible' => [
                'boolean'
            ],
            'created_by' => [
                'exists:users,id',
            ],
            'filenames' => [
                'required',
                'array',
                'min:1'
            ],
            'filenames.*' => [
                'required',
                'max:2048',
                'mimes:jpeg,jpg,png,gif',
            ]
        ]);

        if ($request->hasFile('filenames')) {
            foreach ($request->filenames as $file) {
                $size = filesize($file);
                if ($size > 2097152000) {
                    return redirect()->back()->withErrors(['errors', 'The Message']);
                }
                $name = time().$file->getClientOriginalName();
                $file->storeAs(
                    'files', 
                    $name,
                    'public'
                );
                $filesToSave[] = $name;
            }
            $filesToSave = json_encode($filesToSave);
            $request->merge(['files' => $filesToSave]);
        }

        $car = Cars::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Vehículo cargado correctamente.',
            'car' => $car,
        ], 201, [
            'Content-Type' => 'application/json'
        ]);

    }

    /**
     * Upload images.
     */
    public function uploadImage(Request $request, $id)
    {
        $request->validate([
            'filenames' => [
                'required',
                'array',
                'min:1'
            ],
            'filenames.*' => [
                'required',
                'max:2048',
                'mimes:jpeg,jpg,png,gif',
            ]
        ]);

        if ($request->hasFile('filenames')) {
            foreach ($request->filenames as $file) {
                $size = filesize($file);
                if ($size > 2097152000) {
                    return redirect()->back()->withErrors(['errors', 'The Message']);
                }
                $name = time().$file->getClientOriginalName();
                $file->storeAs(
                    'files', 
                    $name,
                    'public'
                );
                $filesToSave[] = $name;
            }
        }

        $car = Cars::find($id);
        $mergedArray = array_merge(json_decode($car->files),$filesToSave);
        $car->files = $mergedArray;
        $car->save();

        return response()->json($car, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

    /**
     * Remove images
     */
    public function removeImage(Request $request, $id)
    {

        Storage::delete('public/files/'.$request->image);
        $car = Cars::find($id);
        $files = json_decode($car->files, true);
        $files = array_values(array_filter($files, function($file) use ($request) {
            return $file !== $request->image;
        }));
        $car->files = json_encode($files);
        $car->save();

        return response()->json(json_decode($car->files), 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cars $cars, $id)
    {
        $cars = $cars->find($id);
        $cars->files = json_decode($cars->files);
        return response()->json($cars, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cars $car, $id)
    {
         $request->validate([
            'description' => [
                'required'
            ],
            'financed' => [
                'boolean'
            ],
            'sold' => [
                'boolean'
            ],
            'visible' => [
                'boolean'
            ]
        ]);

        $car = $car->find($id);
        $car->description = $request->description;
        $car->financed = $request->financed;
        $car->sold = $request->sold;
        $car->visible = $request->visible;
        $car->save();

        if ($car->wasChanged()) {
            return response()->json([
            'success' => true,
            'message' => 'Vehículo modificado correctamente.',
            'car' => $car,
            ], 200, [
                'Content-Type' => 'application/json'
            ]);
        } else {
            return response()->json([
            'car' => $car,
        ], 200, [
            'Content-Type' => 'application/json'
        ]);
        }
    }

    /**
     * Hand Sold status
     */
    public function updateSold(Cars $car, $id)
    {
        $car = $car->find($id);
        $car->sold = !$car->sold;
        $car->save();

        return response()->json($car, 200, [
        'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Cars $car, $id)
    {
        $car = $car->find($id);
        foreach (json_decode($car->files) as $file) {
            Storage::delete('public/files/'.$file);
        }
        $car->delete();
        return response()->json([
        'success' => true,
        'message' => 'Vehículo eliminado correctamente.',
        ], 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
