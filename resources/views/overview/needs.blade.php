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
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">From : </span>
                                <select class="select2" id="input-year-in" onselect="yearInSelect()">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">To : </span>
                                <select class="select2" id="input-year-out" onselect="yearOutSelect()">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Industry : </span>
                            <select class="select2" id="input-industry" oninput="industrySelect()"></select>
                        </div>
                    </div>
                </div>
                <a class="input-group-addon btn btn-primary bg-primary" onclick="searchNeeds()">Show Needs</a>
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
    let $years, $industries
    let $yearIn, $yearOut, $industrySelected

    $.getJSON('./json/industries.json', function (data) { 
        $industries = data 
        let industryHtml = ``
        data.forEach(j => {
            industryHtml += `<option value="${j.no}">${j.industry}</option>`
        });
        $('#input-industry').html(industryHtml)
        $industrySelected = $('#input-industry').val()
    })

    $.getJSON('./json/years.json', function (data) { 
        $years = data 
        let yearHtml = ``
        data.forEach(y => {
            yearHtml += `<option value="${y.no}">${y.years}</option>`
        });
        $('#input-year-in').html(yearHtml)
        $('#input-year-out').html(yearHtml)
        $yearIn = $('#input-year-in').val()
        $yearOut = $('#input-year-out').val()
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
            initChart(industryNeeds)
        },
        error: function (err) {
            console.error(err);
            $('#showing-message').html("Error occured");
        }
        });
    }

    function initChart(industry_needs) {
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
        let industryName = $industries[industry_needs[0].industry_id-1].industry

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

    function searchNeeds() {
        yearInSelect()
        yearOutSelect()
        industrySelect()

        reloadCharIndustryNeeds($yearIn, $yearOut, $industrySelected)
    }

    function yearInSelect() {
        $yearIn = $('#input-year-in').val()
    }
    function yearOutSelect() {
        $yearOut = $('#input-year-out').val()
    }
    function industrySelect() {
        console.log('Industry Selected', $('#input-industry').val())
        $industrySelected = $('#input-industry').val()
    }

</script>
