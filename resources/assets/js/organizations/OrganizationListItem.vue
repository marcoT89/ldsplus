<template lang="pug">
    .card
        .card-header {{ organization.name }}
        ul.list-group.list-group-flush(v-for="calling of organization.callings", :key="calling.id")
            .empty-slot.d-flex.align-items-center.justify-content-center(slot="footer")
                .placeholder {{ calling.name }}
            draggable.list-container(
                v-model="calling.users",
                :options="options",
                @change="updateCalling($event, calling)"
                :class="{ 'invalid': calling.status === 'invalid' }"
            )
                user-card(v-for="user of calling.users", :key="user.id", :user="user")
</template>


<style lang="sass" scoped>
.small
    font-size: 12px

.list-group
    position: relative
.empty-slot
    opacity: 0.5
    color: #777
    padding: 5px 10px
    position: absolute
    background-color: #eee
    top: 0
    right: 0
    bottom: 0
    left: 0
    z-index: 0
    .placeholder
        border: 3px dashed #bbb
        padding: 5px 10px
        width: 100%
        text-align: center

.list-container
    min-height: 45px
    position: relative

    &.invalid
        border: 1px solid #dc3545
</style>

<script>
import draggable from 'vuedraggable';

export default {
    components: { draggable },

    props: {
        organization: { required: true },
    },

    data() {
        return {
            options: {
                animation: 150,
                group: 'users',
                item: '.list-group-item',
            },
        }
    },

    methods: {
        updateCalling(event, calling) {
            if (event.added) {
                const user = event.added.element;
                this.$store.dispatch('organizations/updateCalling', { user, calling });
            }
        }
    }
}
</script>
