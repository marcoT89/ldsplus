import Vue from 'vue';

export default {
    namespaced: true,
    state: {
        organizations: [],
        users: [],
        changes: [],
        filters: {
            users: {
                byName: null,
            },
            organizations: {
                byName: null,
            },
        }
    },

    mutations: {
        setUsers(state, { users }) {
            state.users = users;
        },
        newUser(state) {
            if (state.users.find(u => !u.id)) return;

            state.users.unshift({
                name: null,
                editing: true,
                gender: 'male',
            })
        },
        removeFirstUser(state) {
            return state.users.shift();
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
        updateUser(state, { user, errors }) {
            Vue.set(user, 'errors', errors);
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
        updateNewUser(state, { user }) {
            let oldUser = state.users[0];
            oldUser = Object.assign(oldUser, user);
            Vue.set(oldUser, 'editing', false);
            console.log('updating new user', oldUser);
        },
        setChanges(state, { changes }) {
            state.changes = changes
        },
        updateUsersFilters(state, { byName }) {
            state.filters.users.byName = byName;
        },
    },

    actions: {
        createUser({ commit }, { user }) {
            return axios.post(route('api.users.store'), user);
        },

        fetchUsers({ commit }) {
            return axios.get(route('api.users.index'))
                .then(({ data }) => data.data)
                .then(users => {
                    commit('setUsers', { users });
                    return users
                });
        },

        fetchUsersWithouCalling({ state, commit }) {
            return axios.get(route('api.users.without-calling'), { params: state.filters.users })
                .then(({ data }) => data.data)
                .then(users => {
                    commit('setUsers', { users });
                    return users;
                });
        },

        fetchOrganizations({ commit, state }) {
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
                }))
                .then(({ data }) => {
                    commit('updateUser', { user: data.data });
                    return data.data;
                })
                .catch(e => {
                    if (e.response && e.response.status === 422) {
                        const errors = new Errors();
                        errors.record(e.response.data.errors);
                        commit('updateUser', { user, errors })
                    }
                    return Promise.reject(e);
                });
        },
    }
}
