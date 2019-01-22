/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually
 */

export default {
    data: function () {
        return {
            token: false
        }
    },
    mounted: function () {
        this.token = document.head.querySelector('meta[name="csrf-token"]');

        if (this.token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = this.token.content;
        } else {
            console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
        }
        return true;
    },
    computed: {
        csrfTokenField: function () {
            return '<input type="hidden" name="_token" value"' + this.token.content + '">';
        }
    }
};
