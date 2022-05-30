<template>
    <div>
        <gallery :filters="filters" :routes="JSON.parse(routes)"/>
    </div>
</template>
<script>
    import Gallery from "../components/gallery";

    export default {
        components: {Gallery},
        props: {
            filters: {
                type: String,
            },
            routes: {
                type: String,
                default: '{"portfolio":"portfolio"}',
            }
        }
    };
</script>
