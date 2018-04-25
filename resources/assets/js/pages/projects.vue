<template>
    <div>
        <div class="ui segments">
            <div class="ui segment">
                <div class="text-center p-5">
                    <h3 class="ui header text-uppercase">
                        <div class="sub header">Manage Projects</div>
                        Projects List
                    </h3>
                    <p class="w-75 mx-auto text-gray">
                        You can check here the uptime status and backup health of your projects.
                    </p>
                </div>
                <div class="ui hidden divider"></div>

                <template v-if="projectsLoading">
                    <div class="ui teal tiny progress"  data-value="30" data-total="100" >
                        <div class="bar"></div>
                        <div class="label">Loading Project List..</div>
                    </div>
                </template>
                <template v-else>
                    <template v-if="projects.length > 0">
                        <div class="ui cards p-5">
                            <router-link :to="{  name : 'project.show' , params: { id : project.id } }" class="card" v-for="project in projects" :key="project.id">
                                <div class="content text-center">
                                    <h5 class="ui header">
                                        <div class="content text-uppercase">
                                            <div class="sub header">{{ project.type }}</div>
                                            {{ project.name }}
                                        </div>
                                    </h5>
                                    <line-chart :chart-data="project.data" :height="200"/>
                                </div>
                            </router-link>
                            <a class="card" @click.prevent="addProject">
                                <div class="content py-5 text-center">
                                    <h3 class="ui icon header">
                                        <i class="plus icon"></i>
                                        <div class="content">
                                            Add more projects
                                            <div class="sub header">Click here to add</div>
                                        </div>
                                    </h3>
                                </div>
                            </a>
                        </div>
                    </template>
                    <template v-else>
                        <div class="ui cards p-5">
                            <a class="card" @click.prevent="addProject">
                                <div class="content py-5 text-center">
                                    <h3 class="ui icon header">
                                        <i class="plus icon"></i>
                                        <div class="content">
                                            No projects yet
                                            <div class="sub header">Add a project now</div>
                                        </div>
                                    </h3>
                                </div>
                            </a>
                        </div>
                    </template>
                </template>
                <div class="ui hidden divider"></div>
                <div class="text-center">
                    <router-link to="/dashboard" class="ui tiny teal button text-uppercase mx-auto">Back to Dashboard</router-link>
                </div>
            </div>
        </div>

        <div id="add-project-modal" class="ui modal">
            <div class="header">Add Project</div>
            <div class="content">
                <form class="ui form">
                    <h4 class="ui dividing header">Project Information</h4>
                    <div class="field">
                        <label>Name</label>
                        <div class="field">
                            <input type="text" name="name" v-model="project.name">
                        </div>
                    </div>
                    <div class="field">
                        <label>URL</label>
                        <div class="two fields">
                            <div class="field">
                                <input type="text" name="url-local" placeholder="Local Server" v-model="project.url.local">
                            </div>
                            <div class="field">
                                <input type="text" name="url-live" placeholder="Live Server" v-model="project.url.live">
                            </div>
                        </div>
                    </div>
                    <h4 class="ui dividing header">
                        Files
                    </h4>
                    <div class="ui list">
                        <template v-if="directories.length > 0" >
                            <div class="item" v-for="(directory,i) in directories">
                                <div class="right floated content">
                                    <div class="ui mini icon button negative text-uppercase" @click.prevent="removeNewDirectory(i)">
                                        <i class="trash icon"></i>
                                    </div>
                                </div>
                                <i class="folder icon"></i>
                                <div class="content">
                                    <div class="header">{{ directory.name }}</div>
                                    <div class="description">{{ directory.path }}</div>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="item"><i class="warning icon"></i> <div class="content"><div class="header">No Directories yet</div> <div class="description">Please specify directories you want to backup</div></div></div>
                        </template>
                    </div>
                    <div class="field" :class="{ 'error' : searchPathExist === false }">
                        <div class="ui left icon searchPathInput action input">
                            <i class="folder icon"></i>
                            <input type="text" :placeholder="path.root" v-model="searchPath" >
                            <button class="ui icon button" @click.prevent="addNewDirectory"><i class="plus icon"></i></button>
                        </div>
                        <div v-if="searchPathExist === false" class="ui pointing red basic label">
                            Folder does not exist
                        </div>
                        <div v-if="searchPathExist" class="ui pointing green basic label">
                            Ok! {{ searchPathDirectoryCount }} Directories, {{ searchPathFileCount }} Files
                        </div>
                        <div v-if="searchPathLoading" class="ui pointing basic label">
                            Checking Path
                        </div>
                    </div>
                    <h4 class="ui dividing header">
                        Database
                    </h4>
                    <div class="field">
                        <div class="two fields">
                            <div class="field">
                                <label>Host</label>
                                <input type="text" name="db.host" v-model="db.host" placeholder="127.0.0.1">
                            </div>
                            <div class="field">
                                <label>Name</label>
                                <input type="text" name="db.name" v-model="db.name" placeholder="database">
                            </div>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <label>Port</label>
                                <input type="text" name="db.port" v-model="db.port" placeholder="3306">
                            </div>
                            <div class="field">
                                <label>Socket</label>
                                <input type="text" name="db.socket" v-model="db.socket" placeholder="/Applications/AMPPS/var/mysql.sock">
                            </div>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <label>Username</label>
                                <input type="text" name="db.user" v-model="db.user" placeholder="root">
                                <div v-if="dbTest === false" class="ui pointing red basic label">
                                    Connection Failed
                                </div>
                                <div v-if="dbTest" class="ui pointing green basic label">
                                    Ok! DB Connection Success
                                </div>
                                <div v-if="dbTestChecking" class="ui pointing basic label">
                                    Testing Connection
                                </div>
                            </div>
                            <div class="field">
                                <label>Password</label>
                                <input type="password" name="db.pass" v-model="db.pass" placeholder="*****">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"> </div>
                    <div class="ui error message"></div>
                    <template v-if="projectSavingError">
                        <div class="ui error visible message">
                            <i class="close icon"></i>
                            <div class="header">
                                {{ projectSavingError.message }}
                            </div>
                            <ul class="list">
                                <li v-for="errors in projectSavingError.errors">
                                    <template v-for="error in errors">
                                        {{ error }}
                                    </template>
                                </li>
                            </ul>
                        </div>
                    </template>
                    <template v-if="!(directories.length == 0 && !dbTest)">
                        <button id="btn-save-project" class="ui positive mini button" @click.prevent="saveProject"><i class="plus icon"></i>Save Project</button>
                    </template>
                </form>
            </div>
        </div>
    </div>

</template>

<script>
window._ = require('lodash');
import LineChart from '../components/charts/BarChart'
export default {
    data(){
        return {
            projectsLoading : true,
            projectSavingError : false,
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
            }],
            searchPath : '',
            folderPath : '',
            folderName : 'Untitled Folder',
            searchPathLoading : false,
            searchPathExist : null,
            searchPathFileCount : 0,
            searchPathDirectoryCount : 0,
            directories: [],
            project : {
                name : '',
                url: {
                    live : '',
                    local : ''
                }
            },
            db: {
                host : 'localhost',
                name : '',
                user : 'root',
                pass : 'root',
                port : 3306,
                socket : ''
            },
            dbTest: null,
            dbTestChecking: false,
            path : {
                root : ''
            }
        }
    },
    components: {
        LineChart
    },
    watch: {
        // whenever question changes, this function will run
        searchPath: function (newPath, oldPath) {

            this.checkNewPath(newPath);

        },
        db: {
            handler(newDB, oldDB){
                if(
                    newDB.host != '' &&
                    newDB.name != '' &&
                    newDB.user != ''
                ){
                    this.checkDBConnection(newDB);
                }
            },
            deep: true
        }
    },
    mounted() {
        let _this = this;

        _this.getProjectList();


        // Get Path of this Apps Directory
        axios.get('/api/directory/path/root').then(res => {
            _this.path.root = res.data.path;
            _this.searchPath = res.data.path;
        });

        // Add Validation to Add Project Form
        $('#add-project-modal').modal();
        $('#add-project-modal .ui.form').form({
            fields: {
                name : {
                    rules:[
                        {
                            type: 'empty',
                            prompt: 'Please provide a project name'
                        }
                    ]
                }
            }
        });

    },
    methods: {
        getBasename(path) {
            return path.split('/').pop();
        },
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
        addProject(){
            $('#add-project-modal').modal('show');
        },
        deleteProject(id,event){

            let _this = this;

            $(event.target).addClass('loading');

            axios.delete('/projects/' + id)
            .then(res => {
                console.log(res);
            }).then(res => {
                $(event.target).removeClass('loading');
                _this.getProjectList();
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
                // _this.getProjectList();
            });
        },
        removeNewDirectory(i){

            this.directories.splice(i,1);

        },
        addNewDirectory(){
            let _this = this;
            let newDirectory = {
                'name' : _this.folderName,
                'path' : _this.folderPath
            };

            let exist  = _.find(_this.directories,newDirectory);

            if(_this.searchPathExist && !exist){
                _this.directories.push(newDirectory);
            }
        },
        checkNewPath : _.debounce(function (newPath) {

            let _this = this;

            _this.searchPathExist = null;


            if(newPath == '') return;

            _this.searchPathLoading = true;


            $('.searchPathInput').addClass('loading').removeClass('error');

            axios.get('/api/directory/check',{
                params: {
                    path : newPath
                }
            }).then(res => {
                if(res.status == 200){
                    _this.searchPathExist = res.data.status;
                    _this.searchPathFileCount = res.data.files;
                    _this.searchPathDirectoryCount = res.data.directories;
                    _this.folderPath = res.data.path;
                    _this.folderName = res.data.name;
                }else{
                    $('.searchPathInput').closest('field').addClass('error');
                }
            }).catch(err => {
                console.log(err);
                $('.searchPathInput').closest('field').addClass('error');
            }).then(res => {
                $('.searchPathInput').removeClass('loading');
                _this.searchPathLoading = false;
            });
        }, 500),
        checkDBConnection : _.debounce(function (db) {
            let _this = this;
            _this.dbTest = null;
            _this.dbTestChecking = true;
            axios.get('/api/database/check',{
                params: {
                    db : db
                }
            }).then(res => {
                if(res.status == 200 && res.data.status){
                    _this.dbTest = true;
                }
            }).catch(err => {
                _this.dbTest = false;
            }).then(res => {
                _this.dbTestChecking = false;
            });
        }, 500),
        saveProject(){
            let _this = this;
            $('#add-project-modal .ui.form').form('validate form');
            if( $('#add-project-modal .ui.form').form('is valid')) {
                // Save to Database

                $('#btn-save-project').addClass('loading');

                axios.post('/projects',{
                    project : _this.project,
                    directories : _this.directories,
                    database : _this.dbTest ? _this.db : {}
                })
                .then(function(response){
                    console.log(response);

                    $('#add-project-modal').modal('hide');
                })
                .catch(function(error){

                    let originalText = $('#btn-save-project').html();

                    _this.projectSavingError = error.response.data;

                    $('#btn-save-project')
                    .removeClass('loading')
                    .html('Saving Failed')
                    .addClass('negative');

                    setTimeout(function(){
                        $('#btn-save-project')
                        .removeClass('negative')
                        .html(originalText);

                        _this.projectSavingError = false;
                    },3000);

                })
                .then(function (response) {
                    $('#btn-save-project').removeClass('loading');
                    _this.getProjectList();
                });

            }
            return true;
        }
    }
}
</script>
