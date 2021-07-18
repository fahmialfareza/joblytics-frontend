<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Skill Trends</h6>
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
                            <span class="input-group-addon">Skill : </span>
                            <select class="select2" multiple="multiple" 
                                id="input-skill" oninput="skillSelect()"></select>
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
    let $years, $skills
    let $yearIn, $yearOut, $skillsSelected

    $.getJSON('./json/skills.json', function (data) { 
        $skills = data 
        let skillHtml = ``
        data.forEach(j => {
            skillHtml += `<option value="${j.no}">${j.skill}</option>`
        });
        $('#input-skill').html(skillHtml)
        $skillsSelected = $('#input-skill').val()
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
        // reloadCharSkillTrends($yearIn, $yearOut, $skillsSelected)
    }, 5000);

    let skillTrendsChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });

    function reloadCharSkillTrends(year_in, year_out, skill_ids) {

        let skillTrends = [];

        // Re-initialize Datatable
        $.ajax({
        url: './json/skill_trends.json',
        method: 'get',
        dataType: 'json',
        success: function (res) {
            res.forEach(r => {
                skill_ids.forEach(skill_id => {
                    if (r.year_id >= year_in && r.year_id <= year_out && r.skill_id == skill_id) {
                        skillTrends.push(r)
                    }
                });
            });
            initChart(skillTrends, skill_ids)
        },
        error: function (err) {
            console.error(err);
            $('#showing-message').html("Error occured");
        }
        });
    }

    function initChart(skill_trends, skill_ids) {
        let years = []
        $years.forEach(year => { years.push(year.years) });

        let yearDelta = $yearOut-$yearIn
        let xAxis = ['x']
        for (let i = 0; i <= yearDelta; i++) {
            xAxis.push(`${years[$yearIn-1]+i}`)
        }
        
        let trends = []
        let skillNames = []

        skill_ids.forEach(skill_id => {
            let trend_temp = []
            skill_trends.forEach(trend => {
                if (trend.skill_id == skill_id) {
                    trend_temp.push(trend.demand)
                }
            });
            let skillName = $skills[skill_id-1].skill
            skillNames.push(skillName)
            trends.push(trend_temp)
        });
        trends.forEach((trend, i) => {
            trend[0] = skillNames[i]
        });
        trends.push(xAxis)
        // console.log('xAxis', xAxis);
        // console.log('trends Final', trends);

        skillTrendsChart.destroy();

        // Room Sold & Revenue Chart
        skillTrendsChart = c3.generate({
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
        skillSelect()

        reloadCharSkillTrends($yearIn, $yearOut, $skillsSelected)
    }

    function yearInSelect() {
        $yearIn = $('#input-year-in').val()
    }
    function yearOutSelect() {
        $yearOut = $('#input-year-out').val()
    }
    function skillSelect() {
        console.log('Skill Selected', $('#input-skill').val())
        $skillsSelected = $('#input-skill').val()
    }

</script>
