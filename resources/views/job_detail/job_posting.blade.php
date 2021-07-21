<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-12">
            <h6 class="panel-title text-semibold">Company with more Job Posting</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="chart-container" style="margin-bottom:30px; text-align:center">
                    <div style="width: 100%, margin:auto" class="chart" id="job-posting-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let $jobPosting

    let jobPostingChart = c3.generate({ data: { columns: [ [] ], } });

    $(document).ready(function () {
        reloadChartJobPosting()
    })

    function reloadChartJobPosting() {
        $.ajax({
            url: 'api/job-detail/job-posting',
            data: {
                'job_id': [9]
            },
            method: 'get',
            success: function (res) {
                $jobPosting = res.result.data
                initChartJobPosting($jobPosting)
            },
            error: function(err) {
                console.error(err);

            }
        })
    }

    function initChartJobPosting(jobPosting) {

        let needs = []
        jobPosting.forEach((company, i) => {
            needs[i] = [company.company.company]
            needs[i].push(company.demand)
        });
        console.log('jobPosting', needs);

        jobPostingChart.destroy();

        jobPostingChart = c3.generate({
            bindto: '#job-posting-chart',
            size: { width: 600 },
            data: {
                columns: needs,
                type : 'pie'
            }
        });
    }

</script>
