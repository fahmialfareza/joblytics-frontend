<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Bootcamp Needs by Job</h6>
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
    let $bootcampNeeds, $bootcampNeedsSelected

    $.ajax({
        url: 'api/topic/bootcamp',
        method: 'get',
        success: function (res) {
            $bootcampNeeds = res.result.data
            let bootcampHtml = ``

            $bootcampNeeds.forEach(j => {
                bootcampHtml += `<option value="${j.id}">${j.bootcamp}</option>`
            });

            $('#input-bootcamp-needs').html(bootcampHtml)
            $('#input-bootcamp-needs').val(Math.floor(Math.random() * $bootcampNeeds.length))
            $bootcampsSelected = $('#input-bootcamp-needs').val()
        },
        error: function(err) {
            console.error(err);
        }
    })

    let bootcampNeedsChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });
    
    function reloadChartBootcampNeeds(year_in, year_out, job_id) {
        $.ajax({
            url: 'api/comparison/bootcamp',
            method: 'get',
            data: {
                'year_start': year_in, 
                'year_end': year_out, 
                'job_id': [job_id], 
            },
            success: function (res) {
                let bootcampNeeds = res.result.data
                initChartBootcampNeeds(bootcampNeeds, job_id)
            },
            error: function(err) {
                console.error(err);
            }
        })
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
        needs[0] = ['Job Employee']
        needs[1] = ['Bootcamp Needs']

        bootcamp_needs.forEach(need => {
            needs[0].push(need.bootcamp_count)
            needs[1].push(need.job_count)
        });
        let bootcampName = $bootcampNeeds[bootcamp_needs[0].bootcamp_id-1].bootcamp

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
            color: {
                pattern: ['#FF9800', '#4CAF50']
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
        $bootcampNeedsSelected = $('#input-bootcamp-needs').val()
    }

</script>
