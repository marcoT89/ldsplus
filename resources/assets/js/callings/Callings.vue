<template>
    <div>
        <!-- <div class="organization mb-5">
            <h2 class="text-center">Bispado 2</h2>
            <draggable :options="{animation: 150}">
                <li class="list-group-item">Foo</li>
                <li class="list-group-item">Bar</li>
                <li class="list-group-item">Baz</li>
            </draggable>

            <div class="form-row justify-content-center">
                <draggable element="div.test" :options="{animation: 200}">
                    <div class="col calling-card" v-for="leader of leaders" :key="leader.id">
                        <div class="card">
                            <img class="card-img-top" src="/img/male.png">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ leader.name }}</h5>
                                <h6 class="card-subtitle text-muted">{{ leader.callings[0].name }}</h6>
                            </div>
                        </div>
                    </div>
                </draggable>
            </div>
        </div> -->
    </div>
</template>

<style lang="sass" scoped>
.calling-card
    max-width: 180px
.organization
    padding: 10px
</style>


<script>
import Draggable from 'vuedraggable';

export default {
    components: { Draggable },

    created() {
        // this.fetchOrganizations();
    },

    methods: {
    }
}
</script>
