<script setup>
import {
  Table,
  TableHead,
  TableCell,
  TableBody,
  TableRow,
  Modal,
} from 'flowbite-vue'
import { ref } from 'vue'
import route from 'ziggy-js'
import { usePage } from '@inertiajs/inertia-vue3'

const isShowModal = ref(false)

function closeModal() {
  isShowModal.value = false
}
function showModal() {
  isShowModal.value = true
}
const page = usePage()

</script>

<template>
  <div class="mb-5">
    <a :href="route('files.index')">Back</a>
  </div>


  <Table hoverable>
    <TableHead>
      <TableCell>Name</TableCell>
      <TableCell>Type</TableCell>
      <TableCell>Time</TableCell>
      <TableCell>
        <span class="sr-only">Edit</span>
      </TableCell>
    </TableHead>
    <TableBody class="overflow-y-auto w-full">
      <TableRow v-for="storage_data in storages.data ?? storages" class="w-full">
        <modal v-if="isShowModal" @close="closeModal" size="5xl" allowfullscreen>
          <template #body>
            <div class="flex justify-center items-center">
              <iframe :src="storage_data.url_preview+'&&DatabaseID='+folder + '#toolbar=1'" width="100%" height="500px"
                allowfullscreen></iframe>
            </div>
          </template>
        </modal>

        <TableCell class="flex items-center gap-5 flex-wrap w-full">
          <template v-if="storage_data.has_database_name">
            <a :href="route('files.show', [storage_data.has_database_name])" class="flex gap-4">
              <img class="" :src="'https://drive-thirdparty.googleusercontent.com/16/type/' +
                (storage_data.type_data === 'pdf'
                  ? 'application/pdf'
                  : storage_data.type_data)
                " :alt="storage_data.type_data" />
              {{ storage_data.business_code }}
            </a>
          </template>
          <template v-else >
            <img class="" :src="'https://drive-thirdparty.googleusercontent.com/16/type/' +
              (storage_data.type_data === 'pdf'
                ? 'application/pdf'
                : storage_data.type_data)
              " :alt="storage_data.type_data" />
          <p @click="showModal" class="cursor-pointer">
              {{ storage_data.business_code }}   </p>
          </template>
        </TableCell>
        <TableCell>{{ storage_data.type_data }}</TableCell>
        <TableCell>{{ storage_data.created_at }}</TableCell>
        <TableCell></TableCell>
      </TableRow>
    </TableBody>
  </Table>

  <paginate-page :pagination="pagination" v-if="pagination" />
</template>

<script>
export default {
  props: {
    storages: {
      type: Array,
      required: true,
    },
    pagination: {
      type: Object,
      required: true,
    },
    folder: {
      type: Number
    },
  },
  methods: {
    goBack() {
      window.history.back()
    },
  },
}
</script>
