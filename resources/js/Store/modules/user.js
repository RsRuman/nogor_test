const state = {
    users: [],
    user_info: {},
    validation_errors: []
};

const getters = {
    users: state => state.users,
    userInfo: state => state.user_info,
    validationErrors: state => state.validation_errors
};

const actions = {
    async fetchUsers({commit}){
        return await axios.get(route('ajax.users.data'))
            .then((response) => {
                if (response.data.status === 200){
                    commit('setUsersData', response.data.data);
                }
                return response.data;
            })
    },

    async storeUser({commit}, formData){
        return await axios.post(route('ajax.user.store'), formData)
            .then((response) => {
                if (response.data.status === 200){
                    commit('addUsersData', response.data.data);
                }
                return response.data;
            })
    },

    async updateUser({commit}, formData){
        return await axios.post(route('ajax.user.update', formData.id), formData.fData)
            .then((response) => {
                if (response.data.status === 200){
                    commit('updateUsersData', response.data.data);
                }
                return response.data;
            })
    },

    async deleteUser({commit}, id){
        return await axios.delete(route('ajax.user.delete', id))
            .then((response) => {
                if (response.data.status === 200){
                    commit('deleteUserData', id);
                }
                return response.data;
            })
    }
};

const mutations = {
    setUsersData: (state, data) => {
        state.users = data;
    },

    addUsersData: (state, data) => {
        state.users.unshift(data);
    },

    updateUsersData: (state, data) => {
        const idx = state.users.map((item) => item.id).indexOf(data.id);
        if (idx > -1){
            state.users.splice(idx, 1, data);
        }
    },

    deleteUserData: (state, id) => {
        const idx = state.users.map((item) => item.id).indexOf(id);
        if (idx > -1){
            state.users.splice(idx, 1);
        }
    },

    setUserInfo: (state, data) => {
        state.user_info = data;
    },

    setValidationErrors: (state, data) => {
        state.validation_errors = data;
    }
};


export default {
    state,
    getters,
    actions,
    mutations,
};
