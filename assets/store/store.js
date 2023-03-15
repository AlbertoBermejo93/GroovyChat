import Vue from 'vue';
import Vuex from 'vuex';

import conversation from './modules/conversation';
import user from './modules/user';

export default new Vuex.Store({
    modules: {
        conversation,
        user
    }
})
