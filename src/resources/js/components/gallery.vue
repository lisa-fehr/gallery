<template>
    <div class="flex flex-col">
        <navigation :filters="filters" :routes="routes"></navigation>
        <pagination @next="next" @previous="previous" @goTo="goToPage" :data="pagination" />
        <div class="grid grid-cols-2 md:grid-cols-6 gap-2 py-5 md:p-5">
            <thumbnail v-for="(image, index) in images" :alt="image.alt" :image="image.thumbnail" :key="`image-${index}`" @contextmenu.prevent @click.native="(currentImage = image)"/>
        </div>
        <pagination @next="next" @previous="previous" @goTo="goToPage" :data="pagination" />
    </div>
    <teleport to="body">
        <modal v-if="currentImage" :image="currentImage" @close="currentImage=null" />
    </teleport>
</template>

<script>
    import { isNotProduction } from '../config';
    import Navigation from '../components/navigation';

    import Thumbnail from './thumbnail';
    import Modal from './modal';

    import Pagination from './pagination';

    export default {
        components: {Pagination, Navigation, Modal, Thumbnail},
        props: {
            filters: {
                default: null,
                type: String,
            },
            routes: {
                type: Object,
            }
        },
        data() {
            return {
                currentImage: null,
                images: [],
                pagination: null,
            }
        },
        created() {
            const currentPageFromUrl = this.currentPageFromUrl();
            this.getImages(isNaN(currentPageFromUrl) ? 1 : currentPageFromUrl);

            window.onpopstate = () => {
                window.location.reload();
            };
        },
        watch: {
            currentPageNumber(page) {
                this.getImages(page);
            },
        },
        computed: {
            currentPageNumber() {
                const currentPageFromUrl = this.currentPageFromUrl();
                if (this.pagination && !isNaN(currentPageFromUrl) && currentPageFromUrl !== this.pagination.current_page) {
                    return currentPageFromUrl;
                }
                return this.pagination ? this.pagination.current_page : 1;
            },
        },
        methods: {
            currentPageFromUrl() {
                return parseInt(window.location.search.replace(/[^0-9]/g, ''));
            },
            goToPage({pageNumber, url}) {
                this.pagination.current_page = pageNumber;
                window.history.pushState({}, '', url);
            },
            next(url) {
                this.pagination.current_page = this.currentPageNumber + 1;
                window.history.pushState({}, '', url);
            },
            previous(url) {
                this.pagination.current_page = this.currentPageNumber - 1;
                window.history.pushState({}, '', url);
            },
            galleryUrl(page) {
                let url = isNotProduction ? '/gallery.json' : `/gallery?timestamp=${new Date().getTime()}`;

                url += "&page=" + page;
                if (this.filters) {
                    url += '&filter[tags]=' + this.filters;
                }

                return url;
            },
            getImages(page) {
                axios.get(this.galleryUrl(page), {
                    headers: {'Content-Type': 'application/json', 'X-Robots-Tag': 'noindex, nofollow'}
                }).then(response => {
                    const { data, current_page, from, last_page, per_page, to, total } = response.data;
                    this.images = data;
                    this.pagination = { current_page, from, last_page, per_page,to, total };
                });
            },
        },
    };
</script>
