<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use App\Models\ActivityLog;
class TrackActivity {
    public function handle(Request $request, Closure $next) {
        $response = $next($request);
        if (auth()->check() && !$request->ajax() && in_array($request->method(), ['POST','PUT','PATCH','DELETE'])) {
            ActivityLog::create(['user_id'=>auth()->id(),'action'=>$request->method(),'description'=>$request->path(),'ip_address'=>$request->ip(),'user_agent'=>$request->userAgent()]);
        }
        return $response;
    }
}
