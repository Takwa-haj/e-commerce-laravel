<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopBanner;
use Illuminate\Http\Request;

class TopBannerController extends Controller
{
    /**
     * Display a listing of top banners
     */
    public function index()
    {
        $banners = TopBanner::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.top-banners.index', compact('banners'));
    }

    /**
     * Show form for creating a new top banner
     */
    public function create()
    {
        return view('admin.top-banners.create');
    }

    /**
     * Store a newly created top banner
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'pinterest_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer'
        ]);

        try {
            $banner = new TopBanner();
            $banner->message = $request->message;
            $banner->free_shipping_threshold = $request->free_shipping_threshold;
            $banner->is_active = $request->is_active;
            $banner->facebook_url = $request->facebook_url;
            $banner->instagram_url = $request->instagram_url;
            $banner->twitter_url = $request->twitter_url;
            $banner->pinterest_url = $request->pinterest_url;
            $banner->youtube_url = $request->youtube_url;
            $banner->tiktok_url = $request->tiktok_url;
            $banner->background_color = $request->background_color ?? '#0a0a0a';
            $banner->text_color = $request->text_color ?? '#ffffff';
            $banner->sort_order = $request->sort_order ?? 0;
            $banner->save();

            return redirect()->route('admin.top-banners.index')
                            ->with('status', 'Bannière créée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Show form for editing a top banner
     */
    public function edit($id)
    {
        $banner = TopBanner::findOrFail($id);
        return view('admin.top-banners.edit', compact('banner'));
    }

    /**
     * Update the specified top banner
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:255',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'pinterest_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer'
        ]);

        try {
            $banner = TopBanner::findOrFail($id);
            $banner->message = $request->message;
            $banner->free_shipping_threshold = $request->free_shipping_threshold;
            $banner->is_active = $request->is_active;
            $banner->facebook_url = $request->facebook_url;
            $banner->instagram_url = $request->instagram_url;
            $banner->twitter_url = $request->twitter_url;
            $banner->pinterest_url = $request->pinterest_url;
            $banner->youtube_url = $request->youtube_url;
            $banner->tiktok_url = $request->tiktok_url;
            $banner->background_color = $request->background_color ?? '#0a0a0a';
            $banner->text_color = $request->text_color ?? '#ffffff';
            $banner->sort_order = $request->sort_order ?? 0;
            $banner->save();

            return redirect()->route('admin.top-banners.index')
                            ->with('status', 'Bannière mise à jour avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Delete the specified top banner
     */
    public function destroy($id)
    {
        try {
            $banner = TopBanner::findOrFail($id);
            $banner->delete();

            return redirect()->route('admin.top-banners.index')
                            ->with('status', 'Bannière supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Toggle banner active status
     */
    public function toggleActive($id)
    {
        try {
            $banner = TopBanner::findOrFail($id);
            $banner->is_active = !$banner->is_active;
            $banner->save();

            return redirect()->back()
                            ->with('status', 'Statut de la bannière mis à jour avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());
        }
    }
}
