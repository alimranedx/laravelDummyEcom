<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckPagePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Super Admin bypass
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return $next($request);
        }

        $route = $request->route();
        if (!$route) {
            return $next($request);
        }

        $action = $route->getActionName();

        if (str_contains($action, '@')) {
            [$controller, $method] = explode('@', $action);

            // Normalize controller name (remove leading backslash if present)
            $controller = ltrim($controller, '\\');

            // Dashboard is accessible to all admins by default
            if ($controller === 'App\Http\Controllers\Admin\DashboardController') {
                return $next($request);
            }

            $page = DB::table('pages')
                ->join('sub_modules', 'pages.sub_module_id', '=', 'sub_modules.id')
                ->join('modules', 'pages.module_id', '=', 'modules.id')
                ->where('sub_modules.controller_name', $controller)
                ->where('pages.method_name', $method)
                ->select('pages.id', 'modules.name as module_name')
                ->first();

            if ($page) {
                // \Log::info("Checking permission for user {$user->id} on page {$page->id}");
                // Hard block RBAC for non-Super Admins
                if ($page->module_name === 'RBAC') {
                    abort(403, 'Access denied. The RBAC module is restricted to Super Admins only.');
                }
                // Check if user's roles have access to this page via the pivot table
                // We join with model_has_roles to support multiple roles per user (Spatie default)
                $hasPermission = DB::table('role_pages')
                    ->join('model_has_roles', 'role_pages.role_id', '=', 'model_has_roles.role_id')
                    ->where('model_has_roles.model_id', $user->id)
                    ->where('model_has_roles.model_type', get_class($user))
                    ->where('role_pages.page_id', $page->id)
                    ->exists();

                if (!$hasPermission) {
                    abort(403, 'Access denied. You do not have permission to perform this action.');
                }
            }
        }

        return $next($request);
    }
}
