<?php

namespace App\Http\Controllers\Analytic;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;

class RealtimeController extends Controller
{
    public function index(Request $request)
    {
        $viewId = env('ANALYTICS_VIEW_ID');
        $analytics = Analytics::getAnalyticsService();
        $optParams = [
            'dimensions' => 'rt:pagePath,rt:pageTitle',
        ];

        $startDate = Carbon::now()->second(0);
        $endDate = Carbon::now()->addMinutes(1)->second(0);
        $period = Period::create($startDate, $endDate);



        // dd(  $results );

        // RealtimeSample.RealtimeGetOptionalParms param = new RealtimeSample.RealtimeGetOptionalParms();

        // $live_users = $analytics->data_realtime->get('ga:'.$viewId, 'rt:activeVisitors,rt:activeUsers,rt:browser');

        // $live_users = $analytics->data_realtime->get('ga:'.$viewId, 'rt:activeVisitors', [
        //     'dimensions' => 'ga:pagePath,ga:dateHourMinute',
        // ]);

        // ->totalsForAllResults['rt:activeVisitors'];

        // dd( $live_users );

        $users = $analytics->data_realtime->get('ga:' . $viewId, 'rt:activeVisitors', $optParams);


        $live_users = $users->totalsForAllResults['rt:activeVisitors'];



        //

        // dimensions

        // $analyticsData = Analytics::fetchVisitorsAndPageViews($period, 5);
        // $analyticsData = Analytics::fetchMostVisitedPages($period, 5);

        ## Top referrers: ผู้อ้างอิงสูงสุด
        // $analyticsData = Analytics::fetchTopReferrers($period, 5);

        ## Total visitors and pageviews: ผู้เข้าชมทั้งหมดและจำนวนหน้าที่มีการเปิด
        // $analyticsData = Analytics::fetchTotalVisitorsAndPageViews($period, 5);

        ## User Types
        // $analyticsData = Analytics::fetchUserTypes($period, 5);

        ## Top browsers
        // $analyticsData = Analytics::fetchTopBrowsers($period, 5);


        ## All other Google Analytics queries : To perform all other queries on the Google Analytics resource use performQuery. Google's Core Reporting API provides more information on which metrics and dimensions might be used.
        ### https://ga-dev-tools.appspot.com/query-explorer/
        $items = $this->printDataTable($users);
        // if ($live_users > 0) {

        //     $results = Analytics::performQuery(
        //         // $period,
        //         Period::days(7),
        //         'ga:' . $viewId,
        //         [
        //             'metrics' => 'ga:users',
        //             'dimensions' => 'ga:pagePath,ga:dateHourMinute,rt:operatingSystem', // ,ga:pageTitle

        //             // 'sort-order' => 'ASCENDING',


        //             'sort' => '-ga:dateHourMinute',
        //             // 'segment' => 'gaid::-1',
        //             // 'samplingLevel' => 'FASTER',
        //             'max-results' => $live_users < 30 ? $live_users : 30,

        //             'start-index' => 1,
        //             'start-date' => date('Y-m-d'),
        //             'end-date' => date("Y-m-d", strtotime("+1 day")),

        //             // 'orderBys' => [
        //             //     "fieldName"=> "ga:dateHourMinute",
        //             //     // "orderType"=> "HISTOGRAM_BUCKET",
        //             //     "sortOrder"=> "ASCENDING"
        //             // ]
        //         ],

        //     );

        //     // $ga->requestReportData(8digitidwhichIcorrectlyplaced, array('browser', 'browserVersion'), array('pageviews'), $sort_metric=null, $filter=null,  start_date='30daysAgo', $end_date='today', $start_index=1, $max_results=30);

        //     // https://ga-dev-tools.appspot.com/query-explorer/?start-date=2020-12-12&end-date=2020-12-13&metrics=ga%3Ausers&dimensions=ga%3ApagePath%2Cga%3ApageTitle%2Cga%3AdateHourMinute&sort=-ga%3AdateHourMinute&segment=gaid%3A%3A-1&samplingLevel=FASTER&max-results=5


        //     $items = $this->printDataTable($results);
        // }
        // dd( $live_users, $this->printDataTable($results) );

        return response()->json([
            'items' => $items,
            'live_users' => $live_users ?? 0,
        ]);


        // $live_users = $active->data_realtime->get('ga:'.$viewId, 'rt:activeVisitors')->totalsForAllResults['rt:activeVisitors'];

        try {
            $results = $analytics->data_realtime->get(
                'ga:' . $viewId,
                'rt:activeUsers',
                $optParams
            );


            $printDataTable = $this->printDataTable($results);

            dd($results, $printDataTable);
        } catch (\Exception $e) {
            // Handle API service exceptions.
            $error = $e->getMessage();
        }


        // return response()->json(
        //     $active->data_realtime->get(
        //         'ga:'.$viewId,
        //         'rt:activeUsers'
        //     )
        // );


    }


    public function printRealtimeReport($results)
    {

        // printReportInfo($results);
        // printQueryInfo($results);
        // printProfileInfo($results);
        // printColumnHeaders($results);
        // printDataTable($results);
        // printTotalsForAllResults($results);

    }


    public function __printDataTable($results)
    {
        $data = [];
        if (count($results->getRows()) > 0) {

            // Print headers.
            // foreach ($results->getColumnHeaders() as $header) {
            //     $data['headers'][] = [
            //         'header'=>$header->name,
            //     ];
            // }

            // Print table rows.
            foreach ($results->getRows() as $row) {

                list($path, $date) = $row; //$title,
                $id = $this->searchForId($path, $data);

                // $key = array_search ($path, $data);

                if ($id === null) {
                    $id = count($data);
                    $data[] = [
                        'path' => $path,
                        // 'title' => $title,
                        'date' => $date,
                        'count' => 0
                    ];
                }

                $data[$id]['count']++;
            }
        }

        return $data;
    }

    function searchForId($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['path'] == $id) {
                return $key;
            }
        }

        return null;
    }

    public function test()
    {
        $viewId = env('ANALYTICS_VIEW_ID');
        $analytics = Analytics::getAnalyticsService();
        $optParams = [
            'dimensions' => 'rt:pagePath,rt:pageTitle',
            // rt:pageviews
        ];

        // rt:browser,

        // https://developers.google.com/analytics/devguides/reporting/realtime/v3/reference/data/realtime#resource
        // https://developers.google.com/analytics/devguides/reporting/realtime/dimsmets/
        $users = $analytics->data_realtime->get('ga:' . $viewId, 'rt:activeVisitors', $optParams); //,rt:activeUsers


        $live_users = $users->totalsForAllResults['rt:activeVisitors'];


        // ,rt:browser
        // $live_users = $analytics->data_realtime->get('ga:'.$viewId, 'rt:activeVisitors', [
        //     'dimensions' => 'ga:pagePath,ga:dateHourMinute',
        // ]);

        // ->totalsForAllResults['rt:activeVisitors'];

        dd($this->printDataTable($users), $live_users);
    }

    public function printDataTable($results)
    {
        $keys = ['path', 'title', 'count'];
        $rows = [];
        if (count($results->getRows()) > 0) {

            // Print headers.
            $headers = [];
            foreach ($results->getColumnHeaders() as $header) {
                $headers[] = $header->name;
                // $table .= '<th>' . $header->name . '</th>';
            }

            // Print table rows.
            $rows = [];
            foreach ($results->getRows() as $row) {

                $cells = [];
                foreach ($row as $i => $cell) {


                    $cells[$keys[$i]] = htmlspecialchars($cell, ENT_NOQUOTES);
                    // $table .= '<td>'
                    //     . htmlspecialchars($cell, ENT_NOQUOTES)
                    //     . '</td>';
                }

                $rows[] = $cells;
                // $table .= '</tr>';
            }
            // $table .= '</table>';
        } else {
            // $table .= '<p>No Results Found.</p>';
        }

        return $rows;
    }
}
