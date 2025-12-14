<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    /**
     * Clear all cache and optimize
     */
    public function clear(Request $request)
    {
        try {
            // Clear application cache
            Artisan::call('cache:clear');
            
            // Clear config cache
            Artisan::call('config:clear');
            
            // Clear route cache
            Artisan::call('route:clear');
            
            // Clear view cache
            Artisan::call('view:clear');
            
            // Clear compiled classes
            Artisan::call('clear-compiled');
            
            // Optimize
            Artisan::call('optimize:clear');
            
            // Clear all cache using facade
            Cache::flush();
            
            $response = [
                'success' => true,
                'message' => 'Cache cleared successfully',
                'commands' => [
                    'cache:clear',
                    'config:clear',
                    'route:clear',
                    'view:clear',
                    'clear-compiled',
                    'optimize:clear',
                    'Cache::flush()'
                ]
            ];
            
            // Return HTML if request expects HTML, otherwise JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json($response);
            }
            
            return redirect()->back()->with('success', 'Cache cleared successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Optimize application
     */
    public function optimize(Request $request)
    {
        try {
            // Optimize
            Artisan::call('optimize');
            
            // Cache config
            Artisan::call('config:cache');
            
            // Cache routes
            Artisan::call('route:cache');
            
            // Cache views
            Artisan::call('view:cache');
            
            $response = [
                'success' => true,
                'message' => 'Application optimized successfully',
                'commands' => [
                    'optimize',
                    'config:cache',
                    'route:cache',
                    'view:cache'
                ]
            ];
            
            // Return HTML if request expects HTML, otherwise JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json($response);
            }
            
            return redirect()->back()->with('success', 'Application optimized successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error optimizing: ' . $e->getMessage()
            ], 500);
        }
    }
}

