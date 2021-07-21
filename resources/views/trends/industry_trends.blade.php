<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Industry Trends</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Industry : </span>
                        <select class="select2" multiple="multiple" id="input-industry-trends" oninput="industrySelect()"></select>
                    </div>
                </div>
                <div class="form-group float-right">
                    <div class="col-md-1">
                        <a class="input-group-addon btn btn-primary bg-primary" onclick="searchIndustryTrends()">Show Trends</a>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
            </div>
        </div>
        <div class="row">
            <div class="chart-container" style="margin-bottom:30px">
                <div class="chart" id="industry-trends-chart"></div>
            </div>
        </div>
    </div>
</div>
<script>
    let $industries, $industriesSelected

    $.getJSON('./json/industries.json', function (data) { 
        $industries = data 
        let randomIndexs = []
        let industryHtml = ``

        data.forEach(j => {
            industryHtml += `<option value="${j.no}">${j.industry}</option>`
        });
        
        $('#input-industry-trends').html(industryHtml)
        
        // automatically select 3 random jobs
        while (randomIndexs.length < 3) {
            let randomInt = Math.floor(Math.random() * data.length)
            if (!randomIndexs.includes(randomInt)) {
                randomIndexs.push(randomInt)
            }
        }
        $('#input-industry-trends').val(randomIndexs)
        $jobsSelected = randomIndexs
    })
    
    let industryTrendsChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });

    function reloadChartIndustryTrends(year_in, year_out, industry_ids) {

        $.ajax({
            url: 'api/trend/industry',
            method: 'get',
            data: {
                'year_start': year_in, 
                'year_end': year_out, 
                'industry_ids': industry_ids, 
            },
            success: function (res) {
                let industryTrends = res.result.data
                initChartIndustryTrends(industryTrends, industry_ids)
            },
            error: function(err) {
                console.error(err);
                $('#showing-message').html("Error occured");
            }
        })
    }

    function initChartIndustryTrends(industry_trends, industry_ids) {
        let years = []
        $years.forEach(year => { years.push(year.years) });

        let yearDelta = $yearOut-$yearIn
        let xAxis = ['x']
        for (let i = 0; i <= yearDelta; i++) {
            xAxis.push(`${years[$yearIn-1]+i}`)
        }
        
        let trends = []
        let industryNames = []

        industry_ids.forEach(industry_id => {
            let trend_temp = []
            industry_trends.forEach(trend => {
                if (trend.industry_id == industry_id) {
                    trend_temp.push(trend.demand)
                }
            });
            let industryName = $industries[industry_id-1].industry
            industryNames.push(industryName)
            trends.push(trend_temp)
        });
        trends.forEach((trend, i) => {
            trend[0] = industryNames[i]
        });
        trends.push(xAxis)

        industryTrendsChart.destroy();

        // Room Sold & Revenue Chart
        industryTrendsChart = c3.generate({
            bindto: '#industry-trends-chart',
            point: { r: 4 },
            size: { height: 400 },
            data: {
                x: 'x',
                columns: trends
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

    function searchIndustryTrends() {
        yearInSelect()
        yearOutSelect()
        industrySelect()
        reloadChartIndustryTrends($yearIn, $yearOut, $industriesSelected)
    }
    function industrySelect() {
        console.log('Industry Selected', $('#input-industry-trends').val())
        $industriesSelected = $('#input-industry-trends').val()
    }

</script>
