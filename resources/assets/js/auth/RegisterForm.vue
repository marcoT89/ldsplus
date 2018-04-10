<template lang="pug">
    div(@change="form.errors.clear()")
        .form-group.row
            label.col-md-4.col-form-label.text-md-right Name
            .col-md-6
                input-text(v-model="form.name", :form="form", field="name")

        .form-group.row
            label.col-md-4.col-form-label.text-md-right E-mail Address
            .col-md-6
                input-text(v-model="form.email", :form="form", field="email")

        .form-group.row
            label.col-md-4.col-form-label.text-md-right Ward
            .col-md-6
                select.form-control(v-model="form.ward_id", :class="{'is-invalid': form.errors.has('ward_id')}")
                    option(v-for="ward of wards", :key="ward.id", :value="ward.id") {{ ward.name }}
                p.invalid-feedback.mb-0(v-if="form.errors.has('ward_id')") {{ form.errors.get('ward_id') }}

        .form-group.row
            label.col-md-4.col-form-label.text-md-right Birthday
            .col-md-6
                v-date-picker.w-100(mode="single", v-model="form.birthday", :max-date="new Date()", popover-visibility='focus')
                    input-text(:value="props.inputValue", @input="props.updateValue($event.target.value)", :form="form", field="birthday", slot-scope="props")

        .form-group.row
            label.col-md-4.col-form-label.text-md-right Gender
            .col-md-6(:class="{'is-invalid': form.errors.has('ward_id')}")
                .p-1
                    .custom-control.custom-radio.custom-control-inline
                        input.custom-control-input(type="radio", value="male", id="male", name="male", v-model="form.gender")
                        label.custom-control-label.cursor-pointer(for="male") Male
                    .custom-control.custom-radio.custom-control-inline
                        input.custom-control-input(type="radio", value="female", id="female", name="female", v-model="form.gender")
                        label.custom-control-label.cursor-pointer(for="female") Female
                    p.mt-0.invalid-feedback.mb-0.d-block(v-if="form.errors.has('gender')") {{ form.errors.get('gender') }}

        .form-group.row
            label.col-md-4.col-form-label.text-md-right Password
            .col-md-6
                input-text(type="password", v-model="form.password", :form="form", field="password")

        .form-group.row
            label.col-md-4.col-form-label.text-md-right Confirm Password
            .col-md-6
                input-text(type="password", v-model="form.password_confirmation", :form="form", field="password_confirmation")

        .form-group.row
            .col.md-6.offset-md-4
                button-loading.btn.btn-primary(:loading="form.submitting", @click="register") Register
</template>

<script>
export default {
    data() {
        return {
            wards: [],
            form: new Form({
                name: null,
                email: null,
                birthday: null,
                gender: null,
                ward_id: null,
                password: null,
                password_confirmation: null,
            })
        }
    },

    created() {
        this.fetchWards();
    },

    methods: {
        register() {
            this.form.post(route('ajax.register'))
                .then(() => {
                    const url = route('dashboard');
                    window.location.replace(url);
                });
        },

        fetchWards() {
            return this.$http.get(route('api.wards.index'))
                .then(({data}) => this.wards = data.data);
        },
    }
}
</script>
