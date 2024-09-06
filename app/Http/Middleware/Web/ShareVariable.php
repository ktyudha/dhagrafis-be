<?php

namespace App\Http\Middleware\Web;

use Closure;
use App\Models\Category;
use App\Models\Slider;
use App\Models\Management;

class ShareVariable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $categoryArticles   = Category::all();
        $social             = Management::whereIn('type', ['linkedin', 'instagram', 'youtube'])->get();
        $sliders            = Slider::all();
        // $about_id              = Setting::key(Setting::ABOUT)->locale('id')->first()->json_value;
        // $about_en              = Setting::key(Setting::ABOUT)->locale('en')->first()->json_value;

        view()->share(compact('categoryArticles'));
        view()->share(compact('social'));
        view()->share(compact('sliders'));
        // view()->share(compact('about_id'));
        // view()->share(compact('about_en'));

        return $next($request);
    }
}
