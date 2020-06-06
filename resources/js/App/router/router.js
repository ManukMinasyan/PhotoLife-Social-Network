import Vue from 'vue';
import VueRouter from 'vue-router';
import {store} from '@/store';
import NProgress from 'nprogress'

Vue.use(VueRouter);

// Local Storage Service
import LocalStorageService from '@/services/localstorageservice'
const localStorageService = LocalStorageService.getService()

/**
 * Lazy Loading Component Import
 * @param view
 * @returns {function(): (Promise<*>|*)}
 */
function loadView(view) {
    return () => import(`../components/${view}.vue`)
}


let routes = [
    {
        path: '/',
        name: 'home',
        component: loadView('pages/Home/HomeComponent'),
        meta: {requiresAuth: true}
    },
    {
        path: '/login',
        name: 'login',
        component: loadView('pages/Auth/LoginComponent')
    },
    {
        path: '/register',
        name: 'register',
        component: loadView('pages/Auth/RegisterComponent')
    },
    {
        path: '/explore',
        name: 'explore',
        component: loadView('pages/Explore/ExploreComponent'),
        meta: {requiresAuth: true}
    },
    {
        path: '/explore/tag/:tag',
        name: 'hashtag',
        component: loadView('pages/Explore/ExploreComponent'),
        meta: {requiresAuth: true}
    },
    {
        path: '/post/:id',
        name: 'post',
        component: loadView('pages/Post/PostComponent'),
        meta: {requiresAuth: true},
        beforeEnter(routeTo, routeFrom, next) {
            store
                .dispatch('post/FIND_POST_BY_ID', routeTo.params.id)
                .then(post => {
                    next()
                })
                .catch(error => {
                    if (error.response && error.response.status === 404) {
                        next({name: '404', params: {resource: 'user'}})
                    } else {
                        next({name: 'network-issue'})
                    }
                })
        }
    },
    {
        path: '/messages',
        component: loadView('pages/Messenger/MessengerComponent'),
        meta: {requiresAuth: true},
        children: [
            {
                path: '',
                name: 'messengerHome',
                component: loadView('pages/Messenger/MessengerHomeComponent'),
            },
            {
                path: ':conversationId',
                name: 'conversation',
                component: loadView('pages/Messenger/ChatRoomComponent')
            }
        ]
    },
    {
        path: '/404',
        name: 'error-404',
        component: loadView('pages/Errors/404')
    },
    {
        path: '/settings',
        name: 'settings',
        component: loadView('pages/User/SettingsComponent'),
        meta: {requiresAuth: true}
    },
    {
        path: '/:username',
        name: 'user',
        component: loadView('pages/User/UserComponent'),
        meta: {requiresAuth: true}
    },
    {
        path: '/pages/:alias',
        name: 'page',
        component: loadView('pages/Page/PageComponent'),
        beforeEnter(routeTo, routeFrom, next) {
            store
                .dispatch('page/GET_PAGE_BY_ALIAS', routeTo.params.alias)
                .then(page => {
                    next()
                })
                .catch(error => {
                    if (error.response && error.response.status === 404) {
                        next({name: '404', params: {resource: 'page'}})
                    } else {
                        next({name: 'network-issue'})
                    }
                })
        }
    }
];

let router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {
    NProgress.start();

    const publicPages = ['/login', '/register'];
    const authRequired = !publicPages.includes(to.path);
    const authNotRequired = publicPages.includes(to.path);
    const loggedIn = localStorageService.getAccessToken();

    if (authRequired && !loggedIn) {
        return next('/login');
    } else if (authNotRequired && loggedIn) {
        return next('/');
    }

    next();
});

router.afterEach(() => {
    NProgress.done();
});

export default router;
