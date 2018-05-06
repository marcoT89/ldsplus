<template lang="pug">
    modal(ref="modal", style="min-height: 200px", @hidden="close")
        span(slot="title") Formulário de Usuário

        icon.fa.fa-2x.d-flex.h-100.items-align-center.justify-content-center(v-if="loading", :loading="loading")
        div(v-if="user && !loading")
            input-text.mb-3(v-model="user.name", label="Nome", field="name", :form="user")

            .form-group
                label Aniversário

                v-date-picker.w-100(
                    v-model="user.birthday",
                    :input-props="{class: 'form-control'}")

            .form-group.mb-0
                .custom-control.custom-radio.custom-control-inline
                    input.custom-control-input(type="radio", id="male", v-model="user.gender", value="male")
                    label.custom-control-label(for="male") Masculino
                .custom-control.custom-radio.custom-control-inline
                    input.custom-control-input(type="radio", id="female", v-model="user.gender", value="female")
                    label.custom-control-label(for="female") Feminino

        .w-100(slot="footer")
            .float-left(v-if="user.id")
                button-loading.btn.btn-outline-danger(:loading="user.deleting", @click="remove")
                    icon.fa.fa-trash.mr-2(v-if="!user.deleting")
                    | Excluir
            .float-right
                button.btn.btn-light(type="button", @click="close") Cancelar
                button-loading.btn.btn-primary.ml-2(:loading="user.submitting", @click="onSubmit") Salvar
</template>

<script>
export default {
    data() {
        return {
            id: null,
            user: this.setupForm(),
            loading: false,
            formats: {
                title: 'MMMM YYYY',
                weekdays: 'W',
                navMonths: 'MMM',
                input: ['YYYY-MM-DD', 'YYYY/MM/DD'], // Only for `v-date-picker`
                dayPopover: 'L', // Only for `v-date-picker`
                data: ['YYYY-MM-DD', 'YYYY/MM/DD'] // For attribute dates
            }
        };
    },

    watch: {
        id() {
            if (!this.id) return;
            this.loading = true;
            this.fetchUser(this.id)
                .then(user => this.user = this.setupForm(user))
                .then(() => this.loading = false);
        },
    },

    methods: {
        open(id) {
            this.$refs.modal.open();
            this.loading = true;
            this.id = id;
        },

        close() {
            this.$refs.modal.close();
            this.setupForm();
            this.id = null;
        },

        onSubmit() {
            return this.save()
                .then(({data}) => data.data)
                .then(user => {
                    this.$emit('updated', user);
                    this.close();
                });
        },

        save() {
            return this.user.id ?
                this.user.put(route('api.users.update', { user: this.user.id })) :
                this.user.post(route('api.users.store'));
        },

        remove() {
            confirm({ dangerMode: true })
                .then(() => this.user.delete(this.$laroute('api.users.destroy', { user: this.user.id })))
                .then(res => {
                    this.$emit('removed', this.user);
                    swal.stopLoading();
                    swal.close();
                    this.close();
                })
                .catch(cancel => console.log('error', cancel));
        },

        fetchUser(id) {
            return this.$http.get(route('api.users.show', { user: id })).then(({ data }) => data.data)
        },

        setupForm(data = {}) {
            return new Form({
                id: data.id,
                name: data.name,
                gender: data.gender || 'male',
                birthday: data.birthday ? moment(data.birthday).toDate() : null,
            });
        }
    }
}
</script>
