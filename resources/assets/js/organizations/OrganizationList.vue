<template lang="pug">
    .row
        .col-md-3
            .form-group.d-flex
                input.form-control(placeholder="Filtrar pessoas...")
                button.btn.btn-primary.ml-2(@click="newUser", title="Adicionar Pessoa")
                    i.fa.fa-plus
            ul.list-group
                draggable.list-container(v-model="users", :options="options", @change="releaseCalling")
                    user-card(v-for="user in users", :key="user.id", :user="user", @create="createUser")

        .col-md-9
            .form-group
                input.form-control(placeholder="Filtrar organizações...")

            .form-row
                .col-md-6.mb-3(v-for="organization of organizations", :key="organization.id")
                    organization-list-item(:organization="organization")

</template>

<style lang="sass" scoped>
.list-container
    min-height: 72px
    background: #f1f1f1
</style>


<script>
import Draggable from 'vuedraggable';
import { mapActions, mapState, mapMutations } from 'vuex';

export default {
    components: { Draggable },

    data() {
        return {
            options: {
                animation: 150,
                group: 'users',
                item: '.list-group-item',
            },
        }
    },

    created() {
        this.fetchOrganizations().then(() => this.fetchUsers().then(() => {
            this.distributeUsersToCallings();
        }));
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
        ...mapActions('organizations', ['fetchUsers', 'fetchOrganizations', 'updateCalling', 'fetchCallingChanges', 'createUser']),
        ...mapMutations('organizations', ['setUsers', 'distributeUsersToCallings', 'newUser']),

        releaseCalling(event) {
            if (event.added) {
                const user = event.added.element;
                this.updateCalling({ user, calling: null })
                    .then(res => {
                        this.fetchCallingChanges()
                    });
            }
        },
    },
}
</script>
