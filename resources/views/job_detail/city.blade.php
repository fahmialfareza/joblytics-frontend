<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-12">
            <h6 class="panel-title text-semibold">City Coverage</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="chart-container" style="margin-bottom:30px;">
                <div class="chart" id="city-coverage-chart" style="margin: auto; text-align:center"></div>
            </div>
        </div>
    </div>
</div>
<script>
    let $city

    let cityCoverageChart = c3.generate({ data: { columns: [ [] ], } });

    $(document).ready(function () {
        reloadChartCityCoverage()
    })

    function reloadChartCityCoverage() {
        $.ajax({
            url: 'api/job-detail/city',
            data: {
                'job_id': [9]
            },
            method: 'get',
            success: function (res) {
                $city = res.result.data
                initChartCityCoverage($city)
            },
            error: function(err) {
                console.error(err);

            }
        })
    }

    function initChartCityCoverage(cityCoverage) {

        let needs = []
        cityCoverage.forEach((city, i) => {
            needs[i] = [city.city.city]
            needs[i].push(city.demand)
        });
        console.log(needs);

        cityCoverageChart.destroy();

        cityCoverageChart = c3.generate({
            bindto: '#city-coverage-chart',
            size: { width: 600 },
            data: {
                columns: needs,
                type : 'donut'
            }
        });
    }

</script>
