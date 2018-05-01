<template lang="pug">
    i(:class="{'fa-spin': loading, [loaderIcon]: loading}")
</template>

<script>
export default {
    props: {
        loading: { default: false },
        loaderIcon: { default: 'fa-spinner' },
    }
}
</script>
