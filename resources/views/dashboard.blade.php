@extends('layouts.app')
@section('content')
    <div class="col-xl-12">



        <div class="row">
            <div class="col-md-12 col-xs-12 col-xxs-12">
            <div id="chart_container"></div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    @if(auth()->user()->user_type == 'a')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>

        // high chart
        Highcharts.chart('chart_container', {
            chart: {
                height: 250,
            },
            title: {
                text: 'Stats of last 12 months',
                align: 'center'
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom'
            },

            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {
                    month: '%b',
                    year: '%Y'
                }
            },
            exporting: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [
                {
                    data: [{{$data['campaign_stats']}}],
                    name: 'Campaign Logs',
                    color: '#1B53B7',
                    pointStart: Date.UTC({{date("Y,m", strtotime("-12 months"))}}),
                    pointIntervalUnit: 'month'
                }
            ],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 1000,
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });
    </script>
    @endif
@endsection
