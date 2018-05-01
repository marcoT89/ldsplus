<template lang="pug">
    li.list-group-item.cursor-drag(:class="{ 'list-group-item-danger': errors.has('user') }")
        .d-flex.flex-column(:class="{ 'text-danger': errors.has('user') }")
            div.d-flex
                .mr-auto(v-if="!user_form.editing") {{ user_form.name }}
                .w-100(v-if="user_form.editing")
                    input-text.mb-2(v-model="user_form.name", field="name", :form="user_form", :autofocus="true", placeholder="Nome da pessoa")
                    .custom-control.custom-radio.custom-control-inline
                        input.custom-control-input(type="radio", id="male", v-model="user_form.gender", value="male")
                        label.custom-control-label(for="male") Masculino
                    .custom-control.custom-radio.custom-control-inline
                        input.custom-control-input(type="radio", id="female", v-model="user_form.gender", value="female")
                        label.custom-control-label(for="female") Feminino
                    .w-100
                    button-loading.btn.btn-success.float-right.mt-2(@click="create", :loading="user_form.submitting") Salvar
                    button.btn.btn-default.float-right.mt-2.mr-2(@click="removeFirstUser") Cancelar

                .text-success(v-if="has_new_calling")
                    i.fa.fa-long-arrow-alt-up
                .text-primary(v-if="has_supported_calling")
                    i.fa.fa-hand-paper
                .text-danger(v-if="has_calling_to_release")
                    i.fa.fa-long-arrow-alt-down
            div(v-for="calling of user_form.callings", :key="calling.id")
                small(
                    :class="{'text-danger': isCallingStatus(calling, 'release'), 'text-success': isCallingStatus(calling, 'indicated'), 'text-primary': isCallingStatus(calling, 'supported')}")
                    | {{ calling.name }}
                    | {{ status(calling.pivot.status) }}
            small(v-if="errors.has('user')")
                b.text-danger {{ errors.get('user') }}
</template>

<script>
import { mapMutations } from 'vuex';

export default {
    props: {
        user: { required: true },
    },

    data() {
        return {
            user_form: new Form(this.user),
        }
    },

    watch: {
        user: {
            handler: function () {
                this.setupForm(this.user)
                if (this.user.errors) this.user_form.errors.record(this.user.errors.errors);
            },
            deep: true,
        },
    },

    computed: {
        has_new_calling() {
            return this.user
                && this.user_form.callings
                && this.user_form.callings.some(calling => calling.pivot.status === 'indicated');
        },
        has_calling_to_release() {
            return this.user
                && this.user_form.callings
                && this.user_form.callings.some(calling => calling.pivot.status === 'release');
        },
        has_supported_calling() {
            return this.user
                && this.user_form.callings
                && this.user_form.callings.some(calling => calling.pivot.status === 'supported');
        },
        errors() { return this.user_form.errors || new Errors() },
    },

    methods: {
        ...mapMutations('organizations', ['removeFirstUser', 'updateNewUser']),
        setupForm(data = {}) {
            return this.user_form = new Form(data);
        },
        async create() {
            try {
                const { data } = await this.user_form.post(route('api.users.store'));
                this.updateNewUser({ user: data.data });
            } catch ({ response }) {
                console.error('ERROR', response);
            }
        },
        isCallingStatus(calling, status) {
            return calling.pivot.status === status;
        },
        status(status) {
            switch (status) {
                case 'supported': return '(Apoiado)';
                case 'indicated': return '(Indicado)';
                case 'release': return '(Desobrigar)';
                default: return '';
            }
        },
    },
}
</script>
