import Vue from 'vue';

export default {
    namespaced: true,
    state: {
        organizations: [],
        users: [],
        changes: [],
    },

    mutations: {
        setUsers(state, { users }) {
            state.users = users;
        },
        setOrganizations(state, { organizations }) {
            state.organizations = organizations;
        },
        distributeUsersToCallings(state) {
            const users = state.users;
            state.organizations.forEach(organization => {
                organization.callings.forEach(calling => {
                    users.forEach((user, index) => {
                        if (user.callings.filter(c => ['assign', 'assigned'].includes(c.pivot.status)).some(c => c.id === calling.id)) {
                            calling.users.push(user);
                            users.splice(index, 1);
                        }
                    })
                })
            })
        },
        updateUser(state, { user }) {
            state.organizations.forEach(org => {
                org.callings.forEach(calling => {
                    let index = calling.users.findIndex(p => p.id === user.id);
                    if (index >= 0) {
                        Vue.set(calling.users, index, user);
                    }
                });
            });
            const index = state.users.findIndex(p => p.id === user.id);

            if (index >= 0) {
                Vue.set(state.users, index, user);
            }
        },

        setChanges(state, { changes }) {
            state.changes = changes
        },
    },

    actions: {
        fetchUsers({ commit }) {
            return axios.get(route('api.users.index'))
                .then(({ data }) => data.data)
                .then(users => {
                    commit('setUsers', { users });
                    return users
                });
        },

        fetchOrganizations({ commit }) {
            return axios.get(route('api.organizations.index'))
                .then(({ data }) => data.data)
                .then(organizations => {
                    commit('setOrganizations', { organizations });
                    return organizations
                });
        },

        fetchCallingChanges({ commit }) {
            return axios.get(route('api.callings.changes'))
                .then(({ data }) => {
                    commit('setChanges', { changes: data.data })
                    return data
                })
        },

        updateCalling({ commit }, { user, calling }) {
            return axios.get(route('api.users.check-status', {
                user_id: user.id,
                calling_id: calling ? calling.id : '',
            })).then(({ data }) => {
                commit('updateUser', { user: data.data });
                return data.data;
            });
        },
    }
}
