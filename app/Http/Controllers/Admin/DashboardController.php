<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){

        $total = [
            'all'=>Invoice::sum('total'),
            'paid'=>Invoice::where('value_status',1)->sum('total'),
            'unpaid'=>Invoice::where('value_status',0)->sum('total'),
            'partial'=>Invoice::where('value_status',3)->sum('total'),
        ];
        $count =[
            'all'=>Invoice::count(),
            'paid'=>Invoice::where('value_status',1)->count(),
            'unpaid'=>Invoice::where('value_status',0)->count(),
            'partial'=>Invoice::where('value_status',3)->count(),
        ];

        $chartjsLine = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['الفواتير المدفوعة', 'الفواتير الغير مدغوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['#19AB7B'],
                    'data' => [$total['paid']]
                ],
                [
                    "label" => "الفواتير الغير مدغوعة",
                    'backgroundColor' => ['#F85974'],
                    'data' => [$total['unpaid']]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئيا",
                    'backgroundColor' => ['#F38442'],
                    'data' => [$total['partial']]
                ]
            ])
            ->options([]);

        $chartjsPie = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['الفواتير المدفوعة', 'الفواتير الغير مدغوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#19AB7B', '#F85974','#F38442'],
                    'hoverBackgroundColor' => ['#19AB7B', '#F85974','#F38442'],
                    'data' => [$count['paid'], $count['unpaid'],$count['partial']]
                ]
            ])
            ->options([]);

        return view('pages.index',compact('count','total', 'chartjsLine','chartjsPie'));
    }
}
