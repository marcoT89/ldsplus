<template>
    <div>
        <div class="organization mb-5" v-for="organization of organizations" :key="organization.id">
            <h2 class="text-center">{{ organization.name }}</h2>

            <div class="form-row justify-content-center">
                <div class="col calling-card" v-for="leader of sortByOrder(organization.leaders)" :key="leader.id">
                    <div class="card">
                        <img class="card-img-top" src="/img/male.png">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ leader.name }}</h5>
                            <h6 class="card-subtitle text-muted">{{ leader.callings[0].name }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="sass" scoped>
.calling-card
    max-width: 180px
.organization
    padding: 10px
</style>


<script>
export default {
    data() {
        return {
            organizations: [
                {
                    id: 1,
                    name: 'Bispado',
                    leaders: [
                        { id: 1, order: 2, name: 'Raimundo Vasconselos', gender: 'male', callings: [{id: 1, key: 'bishop', name: 'Bispo'}] },
                        { id: 2, order: 1, name: 'Wellington Rocha', gender: 'male', callings: [{id: 2, key: 'first_counselor_bishopric', name: '1o Conselheiro no Bispado'}] },
                        { id: 3, order: 3, name: 'Marco Túlio Santos', gender: 'male', callings: [{id: 3, key: 'second_counselor_bishopric', name: '2o Conselheiro no Bispado'}] },
                    ]
                }
            ]
        }
    },

    methods: {
        sortByOrder(array) {
            console.log('sortByOrder', array)
            return array.sort((a, b) => a.order - b.order);
        }
    }
}
</script>
