<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Job Trends</h6>
        </div>
    </div>

    <div class="panel-body" style="height: 80vh">
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
                            <span class="input-group-addon">Job : </span>
                            <select class="select2" multiple="multiple" 
                                id="input-job" oninput="jobSelect()"></select>
                        </div>
                    </div>
                </div>
                <a class="input-group-addon btn btn-primary bg-primary" onclick="searchTrends()">Show Trends</a>
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
    let $years, $jobs
    let $yearIn, $yearOut, $jobsSelected

    $.getJSON('./json/jobs.json', function (data) { 
        $jobs = data 
        let jobHtml = ``
        data.forEach(j => {
            jobHtml += `<option value="${j.no}">${j.jobs}</option>`
        });
        $('#input-job').html(jobHtml)
        $jobsSelected = $('#input-job').val()
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

    setTimeout(() => {
        // reloadCharJobTrends($yearIn, $yearOut, $jobsSelected)
    }, 5000);

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
            initChart(jobTrends, job_ids)
        },
        error: function (err) {
            console.error(err);
            $('#showing-message').html("Error occured");
        }
        });
    }

    function initChart(job_trends, job_ids) {
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

    function searchTrends() {
        yearInSelect()
        yearOutSelect()
        jobSelect()

        reloadCharJobTrends($yearIn, $yearOut, $jobsSelected)
    }

    function yearInSelect() {
        $yearIn = $('#input-year-in').val()
    }
    function yearOutSelect() {
        $yearOut = $('#input-year-out').val()
    }
    function jobSelect() {
        console.log('Job Selected', $('#input-job').val())
        $jobsSelected = $('#input-job').val()
    }

</script>
