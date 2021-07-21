<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Graduates vs Industry Needs</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Industry : </span>
                        <select class="select2" id="input-industry-needs" oninput="industryNeedsSelect()"></select>
                    </div>
                </div>
                <div class="form-group float-right">
                    <div class="col-md-1">
                        <a class="input-group-addon btn btn-primary bg-primary" onclick="searchIndustryNeeds()">Show Needs</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="chart-container" style="margin-bottom:30px">
                <div class="chart" id="industry-needs-chart"></div>
            </div>
        </div>
    </div>
</div>
<script>
    let $industryNeedsIndustries, $industryNeedsSelected

    $.getJSON('./json/industries.json', function (data) { 
        $industryNeedsIndustries = data 
        let industryHtml = ``
        data.forEach(j => {
            industryHtml += `<option value="${j.no}">${j.industry}</option>`
        });
        $('#input-industry-needs').html(industryHtml)
        $('#input-industry-needs').val(Math.floor(Math.random() * data.length))
        $industryNeedsSelected = $('#input-industry-needs').val()
    })
    
    let industryNeedsChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });

    function reloadChartIndustryNeeds(year_in, year_out, industry_id) {
        $.ajax({
            url: 'api/comparison/graduate',
            method: 'get',
            data: {
                'year_start': year_in, 
                'year_end': year_out, 
                'industry_ids': [industry_id], 
            },
            success: function (res) {
                let industryNeeds = res.result.data
                initChartIndustryNeeds(industryNeeds, industry_id)
            },
            error: function(err) {
                console.error(err);
                $('#showing-message').html("Error occured");
            }
        })
    }

    function initChartIndustryNeeds(industry_needs) {
        let years = []
        $years.forEach(year => { years.push(year.years) });

        let yearDelta = $yearOut-$yearIn
        let xAxis = ['x']
        for (let i = 0; i <= yearDelta; i++) {
            xAxis.push(`${years[$yearIn-1]+i}`)
        }
        
        let needs = []
        needs[0] = ['Graduates']
        needs[1] = ['Industry Needs']

        industry_needs.forEach(need => {
            needs[0].push(need.supply)
            needs[1].push(need.demand)
        });
        let industryName = $industryNeedsIndustries[industry_needs[0].industry_id-1].industry

        needs.push(xAxis)

        industryNeedsChart.destroy();

        // Room Sold & Revenue Chart
        industryNeedsChart = c3.generate({
            bindto: '#industry-needs-chart',
            point: { r: 4 },
            size: { height: 400 },
            data: {
                x: 'x',
                columns: needs,
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

    function searchIndustryNeeds() {
        yearInSelect()
        yearOutSelect()
        industryNeedsSelect()
        reloadChartIndustryNeeds($yearIn, $yearOut, $industryNeedsSelected)
    }

    function industryNeedsSelect() {
        $industryNeedsSelected = $('#input-industry-needs').val()
    }

</script>
