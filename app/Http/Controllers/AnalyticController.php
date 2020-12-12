<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;

class AnalyticController extends Controller
{
    public function index()
    {

        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();


        $period = Period::create($startDate, $endDate);


        // Most visited pages: หน้าที่เข้าชมบ่อยที่สุด
        $analyticsData = Analytics::fetchMostVisitedPages(Period::months(1), 10);
        dd( $analyticsData );

        // $service = Anal      ytics::getAnalyticsService();
        // dd( $service );

        // dd( storage_path('app') );
        // dd( env('ANALYTICS_VIEW_ID') );

        // $analyticsData = $this->fetchTopBrowsers();
        $analyticsData = Analytics::fetchUserTypes(Period::days(1));
        dd( $startDate, $endDate, $period, $analyticsData );

        //retrieve visitors and pageview data for the current day and the last seven days
        $analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));

        //retrieve visitors and pageviews since the 6 months ago
        $analyticsData = Analytics::fetchMostVisitedPages(Period::months(6), 10);


        //retrieve sessions and pageviews with yearMonth dimension since 1 year ago
        $analyticsData = Analytics::performQuery(
            Period::years(1),
            'ga:sessions',
            [
                'metrics' => 'ga:sessions, ga:pageviews',
                'dimensions' => 'ga:yearMonth'
            ]
        );

        dd( $analyticsData );

        return view('analytic.index');
    }


    public function fetchVisitorsAndPageViews(Period $period)
    {
        return Analytics::fetchVisitorsAndPageViews($period);
    }

    public function fetchTopBrowsers() // Period $period, int $maxResults = 10
    {
        return Analytics::fetchTopBrowsers(Period::days(7));
    }
}
