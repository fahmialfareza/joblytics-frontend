<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-12">
            <h6 class="panel-title text-semibold">Job Roles</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="chart-container" style="margin-bottom:30px; text-align:center">
                    <div style="width: 100%, margin:auto" class="chart" id="job-role-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let $role

    let roleChart = c3.generate({ data: { columns: [ [] ], } });

    $(document).ready(function () {
        reloadChartRole()
    })

    function reloadChartRole() {
        $.ajax({
            url: 'api/job-detail/role',
            method: 'get',
            success: function (res) {
                $role = res.result.data
                console.log($role);
                initChartRole($role)
            },
            error: function(err) {
                console.error(err);

            }
        })
    }

    function initChartRole(role) {

        let needs = []
        let xAxis = []

        needs[0] = ['Roles']
        role.forEach((role, i) => {
            needs[0].push(role.amount)
            xAxis.push(role.role)
        });

        roleChart.destroy();

        roleChart = c3.generate({
            bindto: '#job-role-chart',
            point: { r: 4 },
            size: { height: 400 },
            data: {
                // x: 'x',
                columns: needs,
                type : 'bar'
            },
            legend: {
                show: false,
                position: 'top'
            },
            grid: {
                y: {
                    show: true
                }
            },
            axis: {
                x: {
                    type: 'category',
                    categories: xAxis
                },
                y: {
                    label: {
                        text: 'Demands (in hundred)',
                        position: 'outer-middle'
                    }
                }
            }
        });
    }

</script>
