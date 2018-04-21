<template lang="pug">
    .form-group.mb-0(@keyup="clearErrors")
        label(v-if="label") {{ label }}

        .input-group(v-if="group")
            .input-group-prepend(v-if="groupPrepend")
                span.input-group-text {{ groupPrepend }}

            input.form-control(:type="type",
                :autofocus="autofocus",
                :value="value",
                @input="$emit('input', $event.target.value)",
                :placeholder="placeholder",
                :class="{'is-invalid': form_errors && form_errors.has(field)}",
                :disabled="disabled",
                :readonly="readonly")

            .input-group-append(v-if="groupAppend")
                span.input-group-text {{ groupAppend }}


        input.form-control(v-if="!group",
            :autofocus="autofocus",
            :type="type",
            :value="value",
            @input="$emit('input', $event.target.value)",
            :placeholder="placeholder",
            :class="{'is-invalid': form_errors && form_errors.has(field)}",
            :disabled="disabled",
            :readonly="readonly")


        p.invalid-feedback.mb-0(v-if="form_errors && form_errors.has(field)") {{ form_errors.get(field) }}
</template>

<script>
    export default {
        props: {
            label       : { default: null },
            type        : { default: 'text' },
            placeholder : { default: null },
            form        : { default: null },
            errors      : { default: null },
            field       : { default: null },
            value       : { default: null },
            disabled    : { default: false },
            readonly    : { default: false },
            group       : { default: false },
            groupPrepend: { default: null },
            groupAppend : { default: null },
            autofocus   : { default: false },
        },

        computed: {
            form_errors() {
                if (this.form && this.form.errors) return this.form.errors;
                return this.errors;
            },
        },

        methods: {
            clearErrors() {
                if (this.errors) this.errors.clear();
            }
        }
    }
</script>
