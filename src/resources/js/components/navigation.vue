<template>
    <div v-if="!loading">
        <a v-if="hasCurrent()" :href="parentUrl()" class="md:pl-5 md:pt-5 flex w-full items-center">
            <arrow>
                Back to {{ parentName() }}
            </arrow>
        </a>
        <div class="flex flex-wrap md:pl-2 pb-5">
            <div class="pt-5 pr-2 md:px-5 flex">
                <star active/>
                all
            </div>
            <a v-for="nav in navigation.children" :key="nav.name" class="pt-5 pr-2 md:px-5 flex"
               :href="childUrl(nav)">
                <star/>
                {{ nav.display_name || nav.name }}
            </a>
        </div>
    </div>
</template>

<script>
    import { isNotProduction } from '../config';
    import Star from '../components/star';
    import Arrow from '../components/arrow';

    export default {
        components: {Star, Arrow},
        props: {
            filters: {
                default: null,
                type: String,
            },
            routes: {
                default: {},
                type: Object,
            },
        },
        data() {
            return {
                loading: true,
                navigation: {
                    default: () => {
                    },
                    type: Object,
                },
            }
        },
        created() {
            this.getFilters();
        },
        methods: {
            getFilters() {
                let url = isNotProduction ? '/tags.json' : '/tags/';

                axios.get(url + (this.filters ?? ''), {
                    headers: {'Content-Type': 'application/json', 'X-Robots-Tag': 'noindex, nofollow'}
                }).then(response => {
                    this.navigation = response.data;
                    this.removeTheAllTag();
                    this.loading = false;
                });
            },
            removeTheAllTag() {
                if (this.navigation.children[0] && this.navigation.children[0].name === 'all') {
                    this.navigation.children.shift();
                }
            },
            hasCurrent() {
                return this.navigation.current;
            },
            parentName() {
                if (!this.navigation.current.parent) {
                    return 'portfolio';
                }
                return this.navigation.current.parent.name;
            },
            parentUrl() {
                if (!this.navigation.current.parent) {
                    return '/' + this.routes['portfolio'];
                }
                if (this.routes[this.navigation.current.parent.name]) {
                    return '/' + this.routes[this.navigation.current.parent.name];
                }
                return '/' + this.navigation.current.parent.name;
            },
            childUrl(nav) {
                if (this.routes[nav.name]) {
                    return '/' + this.routes[nav.name];
                }
                if (nav.parent?.name && ['events', 'unsorted', 'photos', 'digital'].includes(nav.parent.name)){
                    return '/' + nav.name;
                }
                if (nav.parent?.name) {
                    return '/' + nav.parent.name + '/' + nav.name;
                }
                return '/' + nav.name;
            }
        }
    };
</script>
