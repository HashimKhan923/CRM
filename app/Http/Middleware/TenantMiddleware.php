<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $tenantId = '622e0dd1eed64ae89a3965da2406c3a1';

        $Tenant = Tenant::where('tenant_id',$tenantId)->first();

        if($Tenant)
        {
            $databaseName = 'database_' . $tenantId;
            $username = 'user_' . $tenantId;
            $password = 'password_' . $tenantId;
    
            config(['database.connections.tenant.database' => $databaseName]);
            config(['database.connections.tenant.username' => $username]);
            config(['database.connections.tenant.password' => $password]);
    
            DB::purge('tenant');
            DB::reconnect('tenant');
            DB::setDefaultConnection('tenant');
    
            return $next($request);
        }
        else
        {
            abort(404);
        }

    }
}
