export default [
    {
        path: '/',
        redirect: '/dashboard'
    },
    {
        path: '/dashboard',
        name: 'dashboard.index',
        component: require('./pages/dashboard.vue')
    },
    {
        path: '/projects',
        name: 'projects.index',
        component: require('./pages/projects.vue')
    },
    {
        path: '/project/:id',
        name: 'project.show',
        component: require('./pages/project.vue')
    },
    {
        path: '*',
        component: require('./pages/404.vue')
    }
];
