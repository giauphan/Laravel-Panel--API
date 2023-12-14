<script setup>
import {
    Modal,
} from 'flowbite-vue'

const handleOpen = () => {
    if (onClose) {
        onClose()
    }
}
</script>
<template>
    <Modal v-if="isShowModal" @close="handleOpen" size="5xl" allowfullscreen>
        <template #body>
            <slot></slot>
        </template>
    </modal>
</template>

<script>
export default {
    props: ['isShowModal','onClose'],
}
</script>
