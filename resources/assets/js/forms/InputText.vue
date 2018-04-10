<template lang="pug">
    .form-group.mb-0
        label(v-if="label") {{ label }}

        .input-group(v-if="group")
            .input-group-prepend(v-if="groupPrepend")
                span.input-group-text {{ groupPrepend }}

            input.form-control(:type="type",
                   :value="value",
                   @input="$emit('input', $event.target.value)",
                   :placeholder="placeholder",
                   :class="{'is-invalid': form && form.errors.has(field)}",
                   :disabled="disabled",
                   :readonly="readonly")

            .input-group-append(v-if="groupAppend")
                span.input-group-text {{ groupAppend }}


        input.form-control(v-else,
               :type="type",
               :value="value",
               @input="$emit('input', $event.target.value)",
               :placeholder="placeholder",
               :class="{'is-invalid': form && form.errors.has(field)}",
               :disabled="disabled",
               :readonly="readonly")


        p.invalid-feedback.mb-0(v-if="form && form.errors.has(field)") {{ form.errors.get(field) }}
</template>

<script>
    export default {
        props: {
            label       : {default: null},
            type        : {default: 'text'},
            placeholder : {default: null},
            form        : {default: null},
            field       : {default: null},
            value       : {default: null},
            disabled    : {default: false},
            readonly    : {default: false},
            group       : {default: false},
            groupPrepend: {default: null},
            groupAppend : {default: null},
        },
    }
</script>
