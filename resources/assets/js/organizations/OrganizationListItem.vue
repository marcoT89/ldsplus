<template lang="pug">
    .card
        .card-header {{ organization.name }}
        ul.list-group.list-group-flush(v-for="calling of organization.callings", :key="calling.id")
            .empty-slot.d-flex.align-items-center.justify-content-center(slot="footer")
                .placeholder {{ calling.name }}
            draggable.list-container(
                v-model="calling.users",
                :options="options",
                @change="onCallingChange($event, calling)"
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
import { mapActions } from 'vuex';

export default {
    components: { draggable },

    props: {
        organization: { required: true },
    },

    data() {
        return {
            errors: new Errors(),
            options: {
                animation: 150,
                group: 'users',
                item: '.list-group-item',
            },
        }
    },

    methods: {
        async onCallingChange(event, calling) {
            if (event.added) {
                try {
                    const user = event.added.element;
                    await this.updateCalling({ user, calling });
                    this.fetchCallingChanges();
                } catch (e) {
                    console.log('error', e.response);
                }
            }
        },

        ...mapActions('organizations', ['updateCalling', 'fetchCallingChanges']),
    }
}
</script>
