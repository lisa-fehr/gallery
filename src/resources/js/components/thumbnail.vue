<template>
    <img v-if="image" class="transition delay-150 duration-300 ease-in-out hover:scale-90 cursor-pointer w-full" :src="image" :alt="alt"/>
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
