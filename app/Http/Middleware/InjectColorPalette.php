<?php

namespace App\Http\Middleware;

use App\Services\ColorService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectColorPalette
{
    protected ColorService $colorService;

    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $activePalette = $this->colorService->getActivePalette();

        // Share with all views
        view()->share('activePalette', $activePalette);

        return $next($request);
    }
}
