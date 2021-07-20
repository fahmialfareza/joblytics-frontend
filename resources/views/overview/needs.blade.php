<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Industry Needs</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Industry : </span>
                            <select class="select2" id="input-industry" oninput="industryNeedsSelect()"></select>
                        </div>
                    </div>
                </div>
                <a class="input-group-addon btn btn-primary bg-primary" onclick="searchIndustryNeeds()">Show Needs</a>
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
    let $industryNeedsIndustries, $industryNeedsSelected

    $.getJSON('./json/industries.json', function (data) { 
        $industryNeedsIndustries = data 
        let industryHtml = ``
        data.forEach(j => {
            industryHtml += `<option value="${j.no}">${j.industry}</option>`
        });
        $('#input-industry').html(industryHtml)
        $industryNeedsSelected = $('#input-industry').val()
    })
    
    let industryNeedsChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });

    function reloadCharIndustryNeeds(year_in, year_out, industry_id) {

        let industryNeeds = [];

        // Re-initialize Datatable
        $.ajax({
        url: './json/industry_needs.json',
        method: 'get',
        dataType: 'json',
        success: function (res) {
            res.forEach(r => {
                if (r.year_id >= year_in && r.year_id <= year_out && r.industry_id == industry_id) {
                    industryNeeds.push(r)
                }
            });
            console.log('Needs', industryNeeds);
            initChartIndustryNeeds(industryNeeds)
        },
        error: function (err) {
            console.error(err);
            $('#showing-message').html("Error occured");
        }
        });
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
            bindto: '#revenue-chart',
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
        reloadCharIndustryNeeds($yearIn, $yearOut, $industryNeedsSelected)
    }

    function industryNeedsSelect() {
        console.log('Industry Selected', $('#input-industry').val())
        $industryNeedsSelected = $('#input-industry').val()
    }

</script>
