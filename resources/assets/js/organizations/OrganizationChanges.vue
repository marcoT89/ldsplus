<template lang="pug">
    div
        .mb-4(v-if="releases.length")
            h4.text-danger Desobrigações
            ul.list-group
                li.list-group-item(v-for="user of releases", :key="user.id")
                    | Irmão(ã) {{ user.name }} está sendo desobrigado(a) do chamado de {{ callingToRelease(user).name }}
        .mb-4(v-if="callings.length")
            h4.text-success Chamados
            ul.list-group
                li.list-group-item(v-for="user of callings", :key="user.id")
                    | Irmão(ã) {{ user.name }} está sendo chamado(a) a servir como {{ callingToAssign(user).name }}
</template>

<script>
import { mapActions, mapState, mapMutations } from 'vuex';

export default {
    data() {
        return {
            users: {
                data: [],
                links: null,
                meta: null,
            }
        }
    },

    created() {
        this.fetchCallingChanges().then(changes => {
            console.log('fetchCallingChanges', changes);
            this.users = changes;
        });
    },

    computed: {
        releases() {
            return this.users.data.filter(u => {
                return u.callings.some(c => c.pivot.status === 'release')
            });
        },
        callings() { return this.users.data.filter(u => u.callings.some(c => c.pivot.status === 'assign')); },
    },

    methods: {
        ...mapActions('organizations', ['fetchCallingChanges']),
        callingToRelease(user) {
            return user.callings.filter(c => c.pivot.status === 'release')[0];
        },
        callingToAssign(user) {
            return user.callings.filter(c => c.pivot.status === 'assign')[0];
        },
    }
}
</script>
