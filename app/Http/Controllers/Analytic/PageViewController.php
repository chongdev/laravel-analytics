<?php

namespace App\Http\Controllers\Analytic;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;
use Illuminate\Http\Request;

class PageViewController extends Controller
{
    public function index()
    {

        // https://www.googleapis.com/analytics/v3/data/ga?ids=ga:1234456789&dimensions=ga:pagePath&metrics=ga:pageviews&filters=ga:pagePath==/about-us.html&start-date=2013-10-15&end-date=2013-10-29&max-results=50
        $viewId = env('ANALYTICS_VIEW_ID');
        $analytics = Analytics::getAnalyticsService();
        $optParams = [
            'dimensions' => 'rt:pagePath,rt:pageTitle',
        ];

        $startDate = Carbon::now()->second(0);
        $endDate = Carbon::now()->addMinutes(1)->second(0);
        $period = Period::create($startDate, $endDate);
    }
}
