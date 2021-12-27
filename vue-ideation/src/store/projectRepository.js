import api from '@/api/projectApi'

const state = {
    projects: {},
    project: null,
    collaborator: [],
}

const getters = {
    projects: state => state.projects,
    project: state => state.project,
    collaborator: state => state.collaborator
}

const mutations = {
    SET_PROJECTS(state, payload) {
        state.projects = payload
    },

    SET_COLLABORATOR(state, payload) {
        let tempArray = []
        payload.forEach(user => {
            let obj = {name: user.name, id: user.id, username: user.username}
            tempArray.push(obj)
        });

        state.collaborator = tempArray
    },

    SET_PROJECT_LOCALLY(state, payload) {
        state.project = payload
    },


    DESTROY_PROJECT_LOCALLY(state) {
        state.project = {}
    },
    
}

const actions = {
    async getProjects({ commit }) {
        let res = await api.getProjects()
        // console.log(res.data.projects + 'from getProject api')
        commit('SET_PROJECTS', res.data.projects)
        return res
    },

    async setProjects({ commit }, payload) {
        let res = await api.setProjects(payload)
        // console.log(res)
        if(res.data.success) {
            let { data } = await api.getProjects()
            commit('SET_PROJECTS', data.project)
        }

        return res
    },

    async getProject({ commit }, payload) {
        let res = await api.getProject(payload)
        // console.log(res)
        if(res.data.success === false) return res
        commit('SET_PROJECT_LOCALLY', res.data.project)
        commit('SET_COLLABORATOR', res.data.project.collaborator)
        sessionStorage.project = JSON.stringify(res.data.project)
    },


}


export default {
    state,
    getters,
    mutations,
    actions
}