<template>
    <div>
        <div class="row">
            <div class="col">
                <h1 class="ui header">Project Name</h1>
                <div class="ui tiny breadcrumb mb-5">
                    <router-link :to="{  name : 'dashboard.index' }" class="section">Dashboard</router-link>
                    <i class="right chevron icon divider"></i>
                    <router-link :to="{  name : 'projects.index' }" class="section">Projects</router-link>
                    <i class="right chevron icon divider"></i>
                    <div class="active section">Project Name</div>
                </div>
            </div>
        </div>
        <template v-if="project">
            <div class="row">
                <div class="col-md-6 mb-5">
                    <div class="ui raised segments">
                        <div class="ui segment">
                            <h3>Overview</h3>
                        </div>
                        <div class="ui segment">
                            <h3 class="ui header">
                                <i class="globe icon"></i>
                                <div class="content">
                                    {{ project.name }}
                                    <div class="sub header"> Created {{ createdAt }}</div>
                                </div>
                            </h3>
                        </div>
                        <div class="ui segment">
                            <button class="ui mini positive button">Edit Project</button>
                            <button class="ui mini icon button"><i class="trash icon"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-5">
                    <div class="ui raised segments">
                        <div class="ui segment">
                            <h3>Backups</h3>
                        </div>
                        <div class="ui segment">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h4 class="ui header">
                                        <i class="clock icon"></i>
                                        <div class="content">
                                            2 days ago
                                            <div class="sub header">Latest Backup</div>
                                        </div>
                                    </h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h4 class="ui header">
                                        <i class="hourglass half icon"></i>
                                        <div class="content">
                                            2 days from now
                                            <div class="sub header">Scheduled Backup</div>
                                        </div>
                                    </h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="ui header">
                                        <i class="save icon"></i>
                                        <div class="content">
                                            248 Megabytes
                                            <div class="sub header">Size of Last Backup</div>
                                        </div>
                                    </h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="ui header">
                                        <i class="server icon"></i>
                                        <div class="content">
                                            2.5 Gigabytes
                                            <div class="sub header">Size of All Backups</div>
                                        </div>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <table class="ui compact celled attached table">
                            <thead>
                                <tr>
                                    <th> Description </th>
                                    <th> Backup Status </th>
                                    <th> Created At</th>
                                    <th> Started At</th>
                                    <th> Failed At</th>
                                    <th> Finished At</th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="project.backups.length">
                                    <tr v-for="backup in project.backups">
                                        <td> {{ backup.description }} </td>
                                        <td> {{ backup.status }} </td>
                                        <td> {{ timeAgo(backup.created_at) }} <br> <small>{{ timeDefault(backup.created_at) }}</small> </td>
                                        <td> {{ timeAgo(backup.backup_started_at) }} <br> <small>{{ timeDefault(backup.backup_started_at) }}</small> </td>
                                        <td> {{ timeAgo(backup.backup_failed_at) }} <br> <small>{{ timeDefault(backup.backup_failed_at) }}</small> </td>
                                        <td> {{ timeAgo(backup.backup_finished_at) }} <br> <small>{{ timeDefault(backup.backup_finished_at) }}</small> </td>
                                        <td>
                                            <div class="ui icon buttons">
                                                <button class="ui button"><i class="download icon"></i></button>
                                                <button class="ui button"><i class="trash icon"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <div class="ui bottom attached segment">
                            <button class="ui mini positive button" @click.prevent.self="backupProject(project.id,$event)">Backup Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import * as moment from 'moment-timezone';
export default {
    data(){
        return {
            project : false
        }
    },
    mounted(){
        moment.tz.setDefault("UTC");
        moment.utc();
        this.getProjectData();
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

            // Get Information about this project
            axios.get('/projects/' + this.id)
            .then( response => {
                _this.project = response.data.project
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
