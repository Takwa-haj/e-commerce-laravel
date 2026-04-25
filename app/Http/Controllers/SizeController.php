<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = Size::withCount(['products' => function($query) {
                        $query->where('status', 1);
                    }])
                    ->orderBy('order', 'asc')
                    ->orderBy('name', 'asc')
                    ->paginate(10);

        return view('admin.sizes', compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.size-add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:sizes,name',
            'code' => 'required|string|max:10|unique:sizes,code',
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Size::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'order' => $request->order ?? 0,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.sizes')
            ->with('success', 'Taille créée avec succès!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $size = Size::findOrFail($id);
        return view('admin.size-edit', compact('size'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:sizes,id',
            'name' => 'required|string|max:100|unique:sizes,name,' . $request->id,
            'code' => 'required|string|max:10|unique:sizes,code,' . $request->id,
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $size = Size::findOrFail($request->id);
        $size->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'order' => $request->order ?? 0,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.sizes')
            ->with('success', 'Taille mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $size = Size::findOrFail($id);

        // Check if size is used in products
        if ($size->products()->count() > 0) {
            return redirect()->route('admin.sizes')
                ->with('error', 'Impossible de supprimer cette taille car elle est utilisée dans des produits.');
        }

        $size->delete();

        return redirect()->route('admin.sizes')
            ->with('success', 'Taille supprimée avec succès!');
    }
}
