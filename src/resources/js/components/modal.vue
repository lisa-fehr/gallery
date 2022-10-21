<template>
    <div class="shadow" @contextmenu.prevent='$emit("close")' @click='$emit("close")'>
        <div class="modal" v-if="image">
            <div class="rounded-t-md flex justify-between bg-black opacity-75 text-base text-gray-500 p-2">
                <div>{{ image.alt }}</div>
                <div>Click to Close</div>
            </div>
            <img :src="image.image" class="rounded-b-md cursor-pointer object-scale-down max-w-screen max-h-screen"/>
        </div>
    </div>
</template>

<script>
    export default {
        emits: ['close'],
        props: {
            image: {
                required: false,
                type: Object,
                default: null
            },
        }
    };
</script>

<style scoped lang="css">
    div.shadow {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        height: 100vh;
        width: 100vw;
        background-color: rgba(0, 0, 0, 0.53);
        display: flex;
        transition: opacity 0.3s ease;
        align-items: center;
        justify-content: center;
    }
</style>
