<template lang="pug">
    li.list-group-item.cursor-drag(:class="{ 'list-group-item-danger': errors.has('user') }")
        .d-flex.flex-column(:class="{ 'text-danger': errors.has('user') }")
            div.d-flex
                .mr-auto {{ user.name }}
                .text-success(v-if="has_new_calling")
                    i.fa.fa-long-arrow-alt-up
                .text-danger(v-if="has_calling_to_release")
                    i.fa.fa-long-arrow-alt-down
            div(v-for="calling of user.callings", :key="calling.id")
                small(:class="{'text-danger': calling.pivot && calling.pivot.status === 'release', 'text-success': calling.pivot && calling.pivot.status === 'assign'}") {{ calling.name }}
            small(v-if="errors.has('user')")
                b.text-danger {{ errors.get('user') }}
</template>

<script>
export default {
    props: {
        user: { required: true },
    },

    computed: {
        has_new_calling() {
            return this.user
                && this.user.callings
                && this.user.callings.some(calling => calling.pivot.status === 'assign');
        },
        has_calling_to_release() {
            return this.user
                && this.user.callings
                && this.user.callings.some(calling => calling.pivot.status === 'release');
        },
        errors() { return this.user.errors || new Errors() },
    }
}
</script>
