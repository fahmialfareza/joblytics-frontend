<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Job Trends</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Job : </span>
                        <select class="select2" multiple="multiple" 
                            id="input-job" oninput="jobSelect()"></select>
                    </div>
                </div>
                <div class="form-group float-right">
                    <div class="col-md-1">
                        <a class="input-group-addon btn btn-primary bg-primary" onclick="searchJobTrends()">Show Trends</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="chart-container" style="margin-bottom:30px">
                <div class="chart" id="job-trends-chart"></div>
            </div>
        </div>
    </div>
</div>
<script>
    let $jobs, $jobsSelected

    $.getJSON('./json/jobs.json', function (data) { 
        $jobs = data 
        let randomIndexs = []
        let jobHtml = ``

        data.forEach(j => {
            jobHtml += `<option value="${j.no}">${j.jobs}</option>`
        });

        $('#input-job').html(jobHtml)

        // automatically select 3 random jobs
        while (randomIndexs.length < 3) {
            let randomInt = Math.floor(Math.random() * data.length)
            if (!randomIndexs.includes(randomInt)) {
                randomIndexs.push(randomInt)
            }
        }
        $('#input-job').val(randomIndexs)
        $jobsSelected = randomIndexs
    })

    let jobTrendsChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });

    function reloadChartJobTrends(year_in, year_out, job_ids) {
        $.ajax({
            url: 'api/trend/job',
            method: 'get',
            data: {
                'year_start': year_in, 
                'year_end': year_out, 
                'job_ids': job_ids, 
            },
            success: function (res) {
                let jobTrends = res.result.data
                initJobTrendsChart(jobTrends, job_ids)
            },
            error: function(err) {
                console.error(err);
                $('#showing-message').html("Error occured");
            }
        })
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

        jobTrendsChart.destroy();

        // Room Sold & Revenue Chart
        jobTrendsChart = c3.generate({
            bindto: '#job-trends-chart',
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
        reloadChartJobTrends($yearIn, $yearOut, $jobsSelected)
    }

    function jobSelect() {
        console.log('Job Selected', $('#input-job').val())
        $jobsSelected = $('#input-job').val()
    }

</script>
