<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Bootcamp Trends</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Bootcamp : </span>
                        <select class="select2" multiple="multiple" id="input-bootcamp" oninput="bootcampSelect()"></select>
                    </div>
                </div>
                <div class="form-group float-right">
                    <div class="col-md-1">
                        <a class="input-group-addon btn btn-primary bg-primary" onclick="searchBootcampTrends()">Show Trends</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="chart-container" style="margin-bottom:30px">
                <div class="chart" id="bootcamp-trends-chart"></div>
            </div>
        </div>
    </div>
</div>
<script>
    let $bootcamps, $bootcampsSelected

    $.ajax({
        url: 'api/topic/bootcamp',
        method: 'get',
        success: function (res) {
            $bootcamps = res.result.data
            let bootcampHtml = ``

            $bootcamps.forEach(j => {
                bootcampHtml += `<option value="${j.id}">${j.bootcamp}</option>`
            });

            $('#input-bootcamp').html(bootcampHtml)

            // automatically select 3 random jobs
            let randomIndexs = []
            while (randomIndexs.length < 3) {
                let randomInt = Math.floor(Math.random() * $bootcamps.length)
                if (!randomIndexs.includes(randomInt)) {
                    randomIndexs.push(randomInt)
                }
            }
            $('#input-bootcamp').val(randomIndexs)
            $bootcampsSelected = randomIndexs
        },
        error: function(err) {
            console.error(err);
        }
    })

    let bootcampTrendsChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });

    function reloadChartBootcampTrends(year_in, year_out, bootcamp_ids) {

        let bootcampTrends = [];

        $.ajax({
            url: 'api/trend/bootcamp',
            method: 'get',
            data: {
                'year_start': year_in, 
                'year_end': year_out, 
                'bootcamp_ids': bootcamp_ids, 
            },
            success: function (res) {
                let bootcampTrends = res.result.data
                initChartBootcampTrends(bootcampTrends, bootcamp_ids)
            },
            error: function(err) {
                console.error(err);
            }
        })
    }

    function initChartBootcampTrends(bootcamp_trends, bootcamp_ids) {
        let years = []
        $years.forEach(year => { years.push(year.years) });

        let yearDelta = $yearOut-$yearIn
        let xAxis = ['x']
        for (let i = 0; i <= yearDelta; i++) {
            xAxis.push(`${years[$yearIn-1]+i}`)
        }
        
        let trends = []
        let bootcampNames = []

        bootcamp_ids.forEach(bootcamp_id => {
            let trend_temp = []
            bootcamp_trends.forEach(trend => {
                if (trend.bootcamp_id == bootcamp_id) {
                    trend_temp.push(trend.demand)
                }
            });
            let bootcampName = $bootcamps[bootcamp_id-1].bootcamp
            bootcampNames.push(bootcampName)
            trends.push(trend_temp)
        });
        trends.forEach((trend, i) => {
            trend[0] = bootcampNames[i]
        });
        trends.push(xAxis)

        bootcampTrendsChart.destroy();

        // Room Sold & Revenue Chart
        bootcampTrendsChart = c3.generate({
            bindto: '#bootcamp-trends-chart',
            point: { r: 4 },
            size: { height: 400 },
            data: {
                x: 'x',
                columns: trends,
            },
            legend: {
                show: true,
                position: 'top'
            },
            grid: {
                y: {
                    show: true
                }
            },
            axis: {
                x: {
                    label: {
                        text: 'Years',
                        position: 'outer-middle'
                    }
                },
                y: {
                    label: {
                        text: 'Demands (in thousand)',
                        position: 'outer-middle'
                    }
                }
            }
        });
    }

    function searchBootcampTrends() {
        yearInSelect()
        yearOutSelect()
        bootcampSelect()
        reloadChartBootcampTrends($yearIn, $yearOut, $bootcampsSelected)
    }
    function bootcampSelect() {
        console.log('Bootcamp Selected', $('#input-bootcamp').val())
        $bootcampsSelected = $('#input-bootcamp').val()
    }

</script>
