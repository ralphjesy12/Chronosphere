<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="ui segment p-5 mb-5">
                    <h4 class="ui header text-uppercase d-inline">
                        <div class="sub header">Showing data for the past 60 minutes </div>
                        Uptime Status
                    </h4>
                    <div class="ui hidden divider"> </div>
                    <line-chart :chart-data="overview.stats" :height="400"/>
                </div>
            </div>
            <div class="col-6">
                <div class="ui segment p-5 mb-5">
                    <router-link to="/projects"  class="ui tiny right floated teal icon button"  data-tooltip="Go to Projects"><i class="icon server"></i></router-link>
                    <h4 class="ui header text-uppercase d-inline">
                        <div class="sub header">Manage Projects </div>
                        PROJECTS
                    </h4>
                    <div class="ui divider"> </div>
                    <div class="ui list">
                        <div class="item">
                            <i class="clock icon"></i>
                            <div class="content">
                                <div class="header">10 Projects</div>
                                <div class="description">Currently monitored in total</div>
                            </div>
                        </div>
                        <div class="item">
                            <i class="save icon"></i>
                            <div class="content">
                                <div class="header">Phinma Energy Customer Portal</div>
                                <div class="description">Newly added project</div>
                            </div>
                        </div>
                        <div class="item">
                            <i class="fast forward icon"></i>
                            <div class="content">
                                <div class="header">9 out of 10</div>
                                <div class="description">Projects are live</div>
                            </div>
                        </div>
                    </div>
                    <div class="ui hidden divider"> </div>
                    <p class="w-75 mx-auto text-gray text-center">
                        <router-link to="/projects" class="ui tiny gray button text-uppercase mx-auto">View all projects</router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import LineChart from '../components/charts/LiveBarChart'
import * as moment from 'moment-timezone';

export default {
    data(){
        return {
            overview : {
                stats : {
                    labels: Array.from(new Array(60),(item, index) => {
                        return moment().subtract(60 - index, 'minutes').format('LT');
                    }),
                    datasets: [{
                        label: 'Uptime ',
                        data: Array.from(new Array(60), function(){
                            return ((Math.random() * 15) + 85).toFixed(2);
                        }),
                        backgroundColor: '#00b5ad',
                        borderColor: '#00b5ad',
                        borderWidth: 1
                    }]
                }
            },
            projectsLoading : true,
            projects : [{
                id : 1,
                name : 'Phinma Energy Customer Portal',
                type : 'Laravel',
                data : {
                    labels: [ "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday" ],
                    datasets: [{
                        label: 'Uptime ',
                        data: [99.5, 99.3, 99.4, 99.1, 99.9, 100, 100],
                        backgroundColor: '#00b5ad',
                        borderColor: '#00b5ad',
                        borderWidth: 1
                    }]
                }
            }]
        }
    },
    mounted() {
        let _this = this;

        moment.tz.setDefault("UTC");
        moment.utc();

        _this.getProjectList();

    },
    components: {
        LineChart
    },
    methods: {
        getProjectList(){

            let _this = this;
            _this.projects = [];

            $('.ui.progress').progress();

            _this.projectsLoading = true;
            // Get the list of projects
            axios.get('/projects').then(res => {
                if(res.status == 200){
                    _this.projects = res.data.data;


                    $('.ui.progress').progress('complete');
                }
            }).catch(err => {
                console.log(err);
                _this.projects = [];
                $('.ui.progress').progress('set error');
            }).then(res => {
                _this.projectsLoading = false;


                $('.ui.progress').progress('complete');
            });
        },
    }
}
</script>
