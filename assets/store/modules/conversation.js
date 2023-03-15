export default {
    state: {
        conversations: []
    },
    getters: {
        CONVERSATIONS: state => state.conversations
    },
    mutations:{
        SET_CONVERSATIONS: (state, payload) => {
            state.conversations = payload
        }
    },
    actions:{
        GET_CONVERSATIONS: ({commit})=> {
            return fetch(`.conversations`)
                .then(result => result.json())
                .then((result)=> {
                    commit("SET_CONVERSATIONS", result)
                })
        }
    }
}