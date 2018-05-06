<template lang="pug">
    .row
        .col-md-3.mb-5
            .form-group.d-flex
                input-text.w-100(
                    placeholder="Filtrar pessoas...",
                    v-model="search_users",
                    :loading="input_searching",
                    @input="userFilterChange")
                button.btn.btn-primary.ml-2(@click="newUser", title="Adicionar Pessoa")
                    i.fa.fa-plus
            ul.list-group
                draggable.list-container(v-model="users", :options="options", @change="releaseCalling")
                    user-card(v-for="user in users", :key="user.id", :user="user", @create="createUser", @clicked="openUserModal")

        .col-md-9.mb-5
            .form-group
                input.form-control(placeholder="Filtrar organizações...")

            .form-row
                .col-md-6.mb-3(v-for="organization of organizations", :key="organization.id")
                    organization-list-item(:organization="organization", @clicked="openUserModal")

        user-modal(ref="userModal", @updated="load", @removed="removeUser")
</template>

<style lang="sass" scoped>
.list-container
    min-height: 72px
    background: #f1f1f1
</style>


<script>
import Draggable from 'vuedraggable';
import { mapActions, mapState, mapMutations } from 'vuex';
import _ from 'lodash';

export default {
    components: { Draggable },

    data() {
        return {
            input_searching: false,
            search_users: null,
            search_organizations: null,
            options: {
                animation: 150,
                group: 'users',
                item: '.list-group-item',
            },
        }
    },

    created() {
        this.load();
    },

    mounted() {
        $('[title]').tooltip();
    },

    computed: {
        users: {
            get() { return this.$store.state.organizations.users },
            set(users) { this.setUsers({users}) },
        },
        ...mapState('organizations', ['organizations']),
    },

    methods: {
        ...mapActions('organizations', [
            'fetchUsers',
            'fetchUsersWithouCalling',
            'fetchOrganizations',
            'updateCalling',
            'fetchCallingChanges',
            'createUser',
        ]),
        ...mapMutations('organizations', [
            'setUsers',
            'distributeUsersToCallings',
            'newUser',
            'updateUsersFilters',
            'removeUser'
        ]),

        load() {
            this.fetchOrganizations().then(() => this.fetchUsers().then(() => {
                this.distributeUsersToCallings();
            }));
        },

        releaseCalling(event) {
            if (event.added) {
                const user = event.added.element;
                this.updateCalling({ user, calling: null })
                    .then(res => {
                        this.fetchCallingChanges()
                    });
            }
        },

        userFilterChange: _.debounce(function (byName) {
            this.input_searching = true;
            this.updateUsersFilters({ byName });
            this.fetchUsersWithouCalling().then(() => this.input_searching = false);
        }, 500),
        openUserModal(user) {
            this.$refs.userModal.open(user.id);
        }
    },
}
</script>
