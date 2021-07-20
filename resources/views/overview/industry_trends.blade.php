<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Industry Trends</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Industry : </span>
                            <select class="select2" multiple="multiple" 
                                id="input-industry" oninput="industrySelect()"></select>
                        </div>
                    </div>
                </div>
                <a class="input-group-addon btn btn-primary bg-primary" onclick="searchIndustryTrends()">Show Trends</a>
            </div>
        </div>
        <div class="row">
            <div class="chart-container" style="margin-bottom:30px">
                <div class="chart" id="revenue-chart"></div>
            </div>
        </div>
    </div>
</div>
<script>
    let $industries, $industriesSelected

    $.getJSON('./json/industries.json', function (data) { 
        $industries = data 
        let industryHtml = ``
        data.forEach(j => {
            industryHtml += `<option value="${j.no}">${j.industry}</option>`
        });
        $('#input-industry').html(industryHtml)
        $industriesSelected = $('#input-industry').val()
    })
    
    let industryTrendsChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });

    function reloadCharIndustryTrends(year_in, year_out, industry_ids) {

        let industryTrends = [];

        // Re-initialize Datatable
        $.ajax({
        url: './json/industry_trends.json',
        method: 'get',
        dataType: 'json',
        success: function (res) {
            res.forEach(r => {
                industry_ids.forEach(industry_id => {
                    if (r.year_id >= year_in && r.year_id <= year_out && r.industry_id == industry_id) {
                        industryTrends.push(r)
                    }
                });
            });
            initChartIndustryTrends(industryTrends, industry_ids)
        },
        error: function (err) {
            console.error(err);
            $('#showing-message').html("Error occured");
        }
        });
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
            bindto: '#revenue-chart',
            point: { r: 4 },
            size: { height: 400 },
            data: {
                x: 'x',
                columns: trends,
                type: 'bar'
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
        reloadCharIndustryTrends($yearIn, $yearOut, $industriesSelected)
    }
    function industrySelect() {
        console.log('Industry Selected', $('#input-industry').val())
        $industriesSelected = $('#input-industry').val()
    }

</script>
