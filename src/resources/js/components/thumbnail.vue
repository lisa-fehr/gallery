<template>
    <img v-if="image" class="w-full" :src="image" :alt="alt"/>
</template>

<script>
    export default {
        props: {
            image: {
                required: true,
                type: String,
            },
            alt: {
                type: String,
                default: '',
            }
        }
    };
</script>
