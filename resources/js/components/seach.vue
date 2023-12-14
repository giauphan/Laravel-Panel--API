<script setup>
import { Button, Input } from 'flowbite-vue'
</script>

<template>
  <Input name="business_code" label="" placeholder="" size="sm">
    <template #prefix>
      <svg
        aria-hidden="true"
        class="w-5 h-5 text-gray-500 dark:text-gray-400"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
        />
      </svg>
    </template>
    <template #suffix>
      <button class="text-white">Search</button>
    </template>
  </Input>
</template>

<script>
export default {
  props: ['folders'],
}
</script>
