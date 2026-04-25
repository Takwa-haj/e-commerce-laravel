<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colors = Color::withCount(['products' => function($query) {
                    $query->where('status', 1);
                }])
                ->orderBy('order', 'asc')
                ->orderBy('name', 'asc')
                ->paginate(10);

      return view('admin.colors', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.color-add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:colors,name',
            'code' => 'required|string|max:20|unique:colors,code',
            'hex_code' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ], [
            'hex_code.regex' => 'Le code HEX doit être au format #RRGGBB (ex: #FF0000)',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Color::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'hex_code' => strtoupper($request->hex_code),
            'order' => $request->order ?? 0,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.colors')
            ->with('success', 'Couleur créée avec succès!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return view('admin.color-edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:colors,id',
            'name' => 'required|string|max:100|unique:colors,name,' . $request->id,
            'code' => 'required|string|max:20|unique:colors,code,' . $request->id,
            'hex_code' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ], [
            'hex_code.regex' => 'Le code HEX doit être au format #RRGGBB (ex: #FF0000)',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $color = Color::findOrFail($request->id);
        $color->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'hex_code' => strtoupper($request->hex_code),
            'order' => $request->order ?? 0,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.colors')
            ->with('success', 'Couleur mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $color = Color::findOrFail($id);

        // Check if color is used in products
        if ($color->products()->count() > 0) {
            return redirect()->route('admin.colors')
                ->with('error', 'Impossible de supprimer cette couleur car elle est utilisée dans des produits.');
        }

        $color->delete();

        return redirect()->route('admin.colors')
            ->with('success', 'Couleur supprimée avec succès!');
    }
}
