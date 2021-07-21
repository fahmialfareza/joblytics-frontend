<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Bootcamp by Industry Needs</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Bootcamp : </span>
                        <select class="select2" id="input-bootcamp-needs" oninput="bootcampNeedsSelect()"></select>
                    </div>
                </div>
                <div class="form-group float-right">
                    <div class="col-md-1">
                        <a class="input-group-addon btn btn-primary bg-primary" onclick="searchBootcampNeeds()">Show Needs</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="chart-container" style="margin-bottom:30px">
                <div class="chart" id="bootcamp-needs-chart"></div>
            </div>
        </div>
    </div>
</div>
<script>
    let $bootcampNeedsIndustries, $bootcampNeedsSelected

    $.getJSON('./json/industries.json', function (data) { 
        $bootcampNeedsIndustries = data 
        let bootcampHtml = ``
        data.forEach(j => {
            bootcampHtml += `<option value="${j.no}">${j.bootcamp}</option>`
        });
        $('#input-bootcamp-needs').html(bootcampHtml)
        $('#input-bootcamp-needs').val(Math.floor(Math.random() * data.length))
        $bootcampNeedsSelected = $('#input-bootcamp-needs').val()
    })
    
    let bootcampNeedsChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });

    function reloadChartBootcampNeeds(year_in, year_out, bootcamp_id) {

        let bootcampNeeds = [];

        // Re-initialize Datatable
        $.ajax({
        url: './json/bootcamp_needs.json',
        method: 'get',
        dataType: 'json',
        success: function (res) {
            res.forEach(r => {
                if (r.year_id >= year_in && r.year_id <= year_out && r.bootcamp_id == bootcamp_id) {
                    bootcampNeeds.push(r)
                }
            });
            console.log('Needs', bootcampNeeds);
            initChartBootcampNeeds(bootcampNeeds)
        },
        error: function (err) {
            console.error(err);
            $('#showing-message').html("Error occured");
        }
        });
    }

    function initChartBootcampNeeds(bootcamp_needs) {
        let years = []
        $years.forEach(year => { years.push(year.years) });

        let yearDelta = $yearOut-$yearIn
        let xAxis = ['x']
        for (let i = 0; i <= yearDelta; i++) {
            xAxis.push(`${years[$yearIn-1]+i}`)
        }
        
        let needs = []
        needs[0] = ['Graduates']
        needs[1] = ['Bootcamp Needs']

        bootcamp_needs.forEach(need => {
            needs[0].push(need.supply)
            needs[1].push(need.demand)
        });
        let bootcampName = $bootcampNeedsIndustries[bootcamp_needs[0].bootcamp_id-1].bootcamp

        needs.push(xAxis)

        bootcampNeedsChart.destroy();

        // Room Sold & Revenue Chart
        bootcampNeedsChart = c3.generate({
            bindto: '#bootcamp-needs-chart',
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

    function searchBootcampNeeds() {
        yearInSelect()
        yearOutSelect()
        bootcampNeedsSelect()
        reloadChartBootcampNeeds($yearIn, $yearOut, $bootcampNeedsSelected)
    }

    function bootcampNeedsSelect() {
        console.log('Bootcamp Selected', $('#input-bootcamp-needs').val())
        $bootcampNeedsSelected = $('#input-bootcamp-needs').val()
    }

</script>
