<script setup>
import ModalComponent from '@/components/ModalComponent.vue'
import { ref } from 'vue'

const isShowModal = ref(false)

const handleOpen = () => {
  isShowModal.value = true
}

const onClose = () => {
  isShowModal.value = false
}
</script>
<template>
  <button @click="handleOpen">Upload</button>

  <modal-component :isShowModal="isShowModal" :onClose="onClose">
    <slot></slot>
  </modal-component>
</template>
