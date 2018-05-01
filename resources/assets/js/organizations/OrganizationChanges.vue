<template lang="pug">
    div
        .mb-4(v-if="releases.length")
            h4.text-danger Desobrigações
            ul.list-group
                li.list-group-item(v-for="calling of releases")
                    .row
                        .col-md-10 {{ userOf(calling).name }} está sendo desobrigado(a) do chamado de {{ calling.name }}
                        .col-md-2.d-flex.align-items-center.justify-content-end
                            button-loading.btn.btn-sm.btn-light(v-tooltip="{title: 'Confirmar Desobrigação'}", @click="release(userOf(calling))")
                                icon.fa.fa-check(:loading="callingIsLoading(calling)")
        .mb-4(v-if="indicated_callings.length")
            h4.text-success Chamados
            ul.list-group
                li.list-group-item(v-for="calling of indicated_callings")
                    .row
                        .col-md-10 {{ userOf(calling).name }} está sendo chamado(a) a servir como {{ calling.name }}
                        .col-md-2.d-flex.align-items-center.justify-content-end
                            button-loading.btn.btn-sm.btn-light(v-tooltip="{title: 'Apoiar'}", @click="support(userOf(calling))")
                                icon.fa.fa-hand-paper(:loading="callingIsLoading(calling)")

        .mb-4(v-if="supported_callings.length")
            h4.text-primary Designações
            ul.list-group
                li.list-group-item(v-for="calling of supported_callings")
                    .row
                        .col-md-10 {{ userOf(calling).name }} foi apoiado(a) e precisa ser designado(a) como {{ calling.name }}
                        .col-md-2.d-flex.align-items-center.justify-content-end
                            button-loading.btn.btn-sm.btn-light(v-tooltip="{title: 'Confirmar Designação'}", @click="designate(userOf(calling))")
                                icon.fa.fa-check(:loading="callingIsLoading(calling)")

</template>

<script>
import { mapActions } from 'vuex';

export default {
    data() {
        return {
            tooltip: {
                title: 'Confirmar',
                delay: 500
            },
            users: [],
            calling_loadings: [],
        }
    },

    created() {
        this.fetchCallingChanges().then(changes => {
            this.users = changes.data;
        });
    },

    computed: {
        releases() { return this.callingsByStatus('release'); },
        indicated_callings() { return this.callingsByStatus('indicated'); },
        supported_callings() { return this.callingsByStatus('supported'); },
    },

    methods: {
        ...mapActions('organizations', ['fetchCallingChanges']),
        callingToRelease(user) {
            return user.changes.filter(c => c.pivot.status === 'release')[0];
        },
        indicatedCalling(user) {
            return user.changes.filter(c => c.pivot.status === 'indicated')[0];
        },
        supportedCalling(user) {
            return user.changes.filter(c => c.pivot.status === 'supported')[0];
        },
        async designate(user) {
            const calling = this.supportedCalling(user);
            try {
                this.startLoading(calling);
                await this.$http.put(route('api.users.designate-calling', { user: user.id, calling: calling.id }));
            } catch (error) {
                console.error('ERROR', error);
            }
            this.stopLoading(calling);
            this.refresh();
        },
        async support(user) {
            const calling = this.indicatedCalling(user);
            try {
                this.startLoading(calling);
                await this.$http.put(route('api.users.support-calling', { user: user.id, calling: calling.id }));
            } catch (error) {
                console.error('ERROR', error);
            }
            this.stopLoading(calling);
            this.refresh();
        },
        async release(user) {
            const calling = this.callingToRelease(user);
            try {
                this.startLoading(calling);
                await this.$http.put(route('api.users.release-calling', { user: user.id, calling: calling.id }));
            } catch (error) {
                console.error('ERROR', error);
            }
            this.stopLoading(calling);
            this.refresh();
        },
        userOf(calling) {
            return this.users.find(u => u.id === calling.pivot.user_id);
        },
        callingsByStatus(status) {
            return _.flatten(this.users.filter(u => {
                return u.changes.some(c => c.pivot.status === status)
            }).map(u => {
                return u.changes.filter(c => c.pivot.status === status);
            }))
        },
        callingIsLoading(calling) { return this.calling_loadings.includes(calling.id); },
        stopLoading(calling) { this.calling_loadings.splice(this.calling_loadings.indexOf(calling.id), 1); },
        startLoading(calling) { this.calling_loadings.includes(calling.id) ? null : this.calling_loadings.push(calling.id); },
        refresh() {
            this.fetchCallingChanges().then(changes => this.users = changes.data);
        },
    }
}
</script>
