<template>
    <div>
        <div class="row">
            <div class="col">
                <div class="ui segment">
                    <template v-if="loading">
                        <div class="p-5">
                            <div class="ui tiny teal progress"  data-value="30" data-total="100" >
                                <div class="bar"></div>
                                <div class="label">Loading Project Data..</div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <template v-if="project">
                            <div class="text-center p-5">
                                <h3 class="ui header text-uppercase">
                                    <div class="sub header">Created {{ createdAt }} </div>
                                    {{ project.name }}
                                </h3>
                                <p class="w-75 mx-auto text-gray">
                                    <a href="#" class="ui tiny teal button text-uppercase mx-auto">Edit Project</a>
                                </p>
                            </div>
                        </template>
                        <template v-else>
                            <div class="p-5">
                                <div class="ui tiny teal progress"  data-value="30" data-total="100" >
                                    <div class="bar"></div>
                                    <div class="label">Loading Failed</div>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="ui segment mt-5 p-5">
                    <a href="#" class="ui tiny right floated teal icon button"  data-tooltip="Refresh Data"><i class="icon sync"></i></a>
                    <h4 class="ui header text-uppercase d-inline">
                        <div class="sub header">April 25, 2018 </div>
                        Today
                    </h4>
                    <div class="ui divider"> </div>
                    <div style="padding-top:10px;">
                        <line-chart :chart-data="project.data" :height="100"/>
                    </div>
                    <div class="ui hidden divider"> </div>
                    <p class="w-75 mx-auto text-gray text-center">
                        <a href="#" class="ui tiny gray button text-uppercase mx-auto">View History</a>
                    </p>
                </div>
            </div>
            <div class="col-6">
                <div class="ui segment mt-5 p-5">
                    <a href="#" class="ui tiny right floated teal icon button"  data-tooltip="Backup Now"><i class="icon play"></i></a>
                    <h4 class="ui header text-uppercase d-inline">
                        <div class="sub header">Latest Backup </div>
                        2 days ago
                    </h4>
                    <div class="ui divider"> </div>
                    <div class="ui list">
                        <div class="item">
                            <i class="clock icon"></i>
                            <div class="content">
                                <div class="header">05:30</div>
                                <div class="description">Total back up time</div>
                            </div>
                        </div>
                        <div class="item">
                            <i class="save icon"></i>
                            <div class="content">
                                <div class="header">256 MB</div>
                                <div class="description">Size of back up file</div>
                            </div>
                        </div>
                        <div class="item">
                            <i class="fast forward icon"></i>
                            <div class="content">
                                <div class="header">06 days 12 hrs</div>
                                <div class="description">Next scheduled backup</div>
                            </div>
                        </div>
                    </div>
                    <div class="ui hidden divider"> </div>
                    <p class="w-75 mx-auto text-gray text-center">
                        <a href="#" class="ui tiny gray button text-uppercase mx-auto">View all backups</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LineChart from '../components/charts/BarChart'
import * as moment from 'moment-timezone';
export default {
    data(){
        return {
            loading : true,
            project : false
        }
    },
    mounted(){
        moment.tz.setDefault("UTC");
        moment.utc();

        $('.ui.progress').progress();

        this.getProjectData();


    },
    components: {
        LineChart
    },
    computed: {
        id () {
            // We will see what `params` is shortly
            return this.$route.params.id
        },
        createdAt(){
            if(!this.project) return '-';

            return moment(this.project.created_at).fromNow();
        }
    },
    methods: {
        timeAgo(date){

            let theDate = moment.parseZone(date);

            if(!theDate.isValid()) return '-';

            return theDate.fromNow();
        },
        timeDefault(date){

            let theDate = moment(date);

            if(!theDate.isValid()) return '-';

            return theDate.utc().format('llll z');
        },
        getProjectData(){
            let _this = this;
            _this.loading = true;
            // Get Information about this project
            axios.get('/projects/' + this.id)
            .then( response => {
                _this.project = response.data.project
            }).catch(err => {
                console.log(err);
                _this.project = false;
                $('.ui.progress').progress('set error');
            }).then(res => {

                _this.loading = false;

                $('.ui.progress').progress('complete');
            });
        },
        backupProject(id,event){

            let _this = this;

            $(event.target).addClass('loading');

            axios.patch('/project/' + id + '/backup')
            .then(res => {
                console.log(res);
            }).catch(err => {
                console.log(err);
                $(event.target).removeClass('loading');
            }).then(res => {
                $(event.target).removeClass('loading');
                _this.getProjectData();
            });
        },
    }
}
</script>

<style lang="css">
</style>
