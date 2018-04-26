import Vue from 'vue';

Vue.directive('tooltip', {
    inserted: function (el, binding) {
        $(el).tooltip(binding.value);
    },
});
