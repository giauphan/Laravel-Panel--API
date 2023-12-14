<script setup>
import { Modal } from 'flowbite-vue'

const props = defineProps(['isShowModal', 'onClose'])

const handleOpen = () => {
  if (props.onClose) {
    props.onClose()
  }
}
</script>

<template>
  <Modal
    v-if="$props.isShowModal"
    @close="handleOpen"
    size="5xl"
    allowfullscreen
  >
    <template #body>
      <slot></slot>
    </template>
  </Modal>
</template>
