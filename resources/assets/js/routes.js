import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

var router = new VueRouter();
router.map({
        '/404': {
                component: function (resolve) {
                        resolve(require('../vue/404.vue'));
                }
        },
        '/': {
                component: function (resolve) {
                        resolve(require('../vue/Videos.vue'));
                }
        }
});

router.redirect({
	'*': '/404'
});

export default router;
