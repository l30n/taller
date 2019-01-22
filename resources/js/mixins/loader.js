import {
    mapActions,
    mapGetters
} from 'vuex';

export default {
    computed: mapGetters([
        'loaderStatus'
    ]),
    methods: mapActions([
        'setLoaderState'
    ])
};
