<template lang="pug">
    div
        .mb-4
            router-link.ml-4.mt-2.float-right.cursor-pointer.text-primary(:to="{ name: 'organization-list' }", exact) Organizações
            router-link.ml-4.mt-2.float-right.cursor-pointer.text-primary(v-if="changes.length", :to="{ name: 'organization-changes' }")
                | Mudanças ({{ changes.length }})
            h1
                | Organizações

        transition(name="fade")
            router-view

</template>

<style lang="sass" scoped>
.active
    display: none

.fade-enter-active
  transition: opacity .3s

.fade-enter
  opacity: 0

</style>


<script>
import OrganizationList from './OrganizationList';
import OrganizationChanges from './OrganizationChanges';
import { mapState, mapActions } from 'vuex';

import VueRouter from 'vue-router';

const routes = [
    { path: '/', name: 'organization-list', component: OrganizationList },
    { path: '/mudancas', name: 'organization-changes', component: OrganizationChanges },
];

export default {
    router: new VueRouter({ routes, linkActiveClass: 'active', linkExactActiveClass: 'exact-active'}),

    created() {
        this.fetchCallingChanges();
    },

    computed: {
        ...mapState('organizations', ['changes']),
    },

    methods: {
        ...mapActions('organizations', ['fetchCallingChanges']),
    }
}
</script>
