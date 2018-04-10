<template lang="pug">
    button(:disabled="loading", @click.prevent.stop="$emit('click', $event)")
        span(v-if="loading")
            i.fa.fa-spin.fa-spinner
            | &nbsp;
        slot
</template>

<script>
    export default {
        props: {
            loading: { default: false, type: Boolean },
        }
    }
</script>
