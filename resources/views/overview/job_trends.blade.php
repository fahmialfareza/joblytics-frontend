<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Job Trends</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Job : </span>
                            <select class="select2" multiple="multiple" 
                                id="input-job" oninput="jobSelect()"></select>
                        </div>
                    </div>
                </div>
                <a class="input-group-addon btn btn-primary bg-primary" onclick="searchJobTrends()">Show Trends</a>
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
    let $jobs, $jobsSelected

    $.getJSON('./json/jobs.json', function (data) { 
        $jobs = data 
        let jobHtml = ``
        data.forEach(j => {
            jobHtml += `<option value="${j.no}">${j.jobs}</option>`
        });
        $('#input-job').html(jobHtml)
        $jobsSelected = $('#input-job').val()
    })

    let jobTrendsChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });

    function reloadCharJobTrends(year_in, year_out, job_ids) {

        let jobTrends = [];

        // Re-initialize Datatable
        $.ajax({
        url: './json/job_trends.json',
        method: 'get',
        dataType: 'json',
        success: function (res) {
            res.forEach(r => {
                job_ids.forEach(job_id => {
                    if (r.year_id >= year_in && r.year_id <= year_out && r.job_id == job_id) {
                        jobTrends.push(r)
                    }
                });
            });
            initJobTrendsChart(jobTrends, job_ids)
        },
        error: function (err) {
            console.error(err);
            $('#showing-message').html("Error occured");
        }
        });
    }

    function initJobTrendsChart(job_trends, job_ids) {
        let years = []
        $years.forEach(year => { years.push(year.years) });

        let yearDelta = $yearOut-$yearIn
        let xAxis = ['x']
        for (let i = 0; i <= yearDelta; i++) {
            xAxis.push(`${years[$yearIn-1]+i}`)
        }
        
        let trends = []
        let jobNames = []

        job_ids.forEach(job_id => {
            let trend_temp = []
            job_trends.forEach(trend => {
                if (trend.job_id == job_id) {
                    trend_temp.push(trend.demand)
                }
            });
            let jobName = $jobs[job_id-1].jobs
            jobNames.push(jobName)
            trends.push(trend_temp)
        });
        trends.forEach((trend, i) => {
            trend[0] = jobNames[i]
        });
        trends.push(xAxis)
        // console.log('xAxis', xAxis);
        // console.log('trends Final', trends);

        jobTrendsChart.destroy();

        // Room Sold & Revenue Chart
        jobTrendsChart = c3.generate({
            bindto: '#revenue-chart',
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

    function searchJobTrends() {
        yearInSelect()
        yearOutSelect()
        jobSelect()
        reloadCharJobTrends($yearIn, $yearOut, $jobsSelected)
    }

    function jobSelect() {
        console.log('Job Selected', $('#input-job').val())
        $jobsSelected = $('#input-job').val()
    }

</script>
