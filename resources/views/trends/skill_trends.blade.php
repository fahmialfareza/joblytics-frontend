<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Skill Trends</h6>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Skill : </span>
                        <select class="select2" multiple="multiple" 
                            id="input-skill" oninput="skillSelect()"></select>
                    </div>
                </div>
                <div class="form-group float-right">
                    <div class="col-md-1">
                        <a class="input-group-addon btn btn-primary bg-primary" onclick="searchSkillTrends()">Show Trends</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="chart-container" style="margin-bottom:30px">
                <div class="chart" id="skill-trends-chart"></div>
            </div>
        </div>
    </div>
</div>
<script>
    let $skills, $skillsSelected

    $.getJSON('./json/skills.json', function (data) { 
        $skills = data
        let randomIndexs = []
        let skillHtml = ``

        data.forEach(j => {
            skillHtml += `<option value="${j.no}">${j.skill}</option>`
        });

        $('#input-skill').html(skillHtml)

        // automatically select 3 random jobs
        while (randomIndexs.length < 3) {
            let randomInt = Math.floor(Math.random() * data.length)
            if (!randomIndexs.includes(randomInt)) {
                randomIndexs.push(randomInt)
            }
        }
        $('#input-skill').val(randomIndexs)
        $skillsSelected = randomIndexs
    })

    let skillTrendsChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });

    function reloadChartSkillTrends(year_in, year_out, skill_ids) {

        let skillTrends = [];

        $.ajax({
            url: 'api/trend/skill',
            method: 'get',
            data: {
                'year_start': year_in, 
                'year_end': year_out, 
                'skill_ids': skill_ids, 
            },
            success: function (res) {
                let skillTrends = res.result.data
                initChartSkillTrends(skillTrends, skill_ids)
            },
            error: function(err) {
                console.error(err);
                $('#showing-message').html("Error occured");
            }
        })
    }

    function initChartSkillTrends(skill_trends, skill_ids) {
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

        skillTrendsChart.destroy();

        // Room Sold & Revenue Chart
        skillTrendsChart = c3.generate({
            bindto: '#skill-trends-chart',
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

    function searchSkillTrends() {
        yearInSelect()
        yearOutSelect()
        skillSelect()
        reloadChartSkillTrends($yearIn, $yearOut, $skillsSelected)
    }
    function skillSelect() {
        console.log('Skill Selected', $('#input-skill').val())
        $skillsSelected = $('#input-skill').val()
    }

</script>
