<script setup>
import { Table, TableHead, TableCell, TableBody, TableRow } from 'flowbite-vue'
import { ref } from 'vue'
import route from 'ziggy-js'
import { FolderIcon } from '@heroicons/vue/24/solid'
import ModalComponent from './ModalComponent.vue'

const isShowModal = ref(false)

const handleOpen = () => {
  isShowModal.value = true
}

const onClose = () => {
  isShowModal.value = false
}
</script>

<template>
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
      <TableRow
        v-for="storage_data in storages.data ?? storages"
        class="w-full"
      >
        <modal-component :isShowModal="isShowModal" :onClose="onClose">
          <div class="flex justify-center items-center">
            <iframe
              :src="
                storage_data.url_preview +
                '&&DatabaseID=' +
                folder.id +
                '#toolbar=1'
              "
              width="100%"
              height="500px"
              allowfullscreen
            ></iframe>
          </div>
        </modal-component>

        <TableCell class="flex items-center gap-5 flex-wrap w-full">
          <template v-if="storage_data.has_database_name">
            <a
              :href="route('files.show', [storage_data.has_database_name])"
              class="flex gap-4"
            >
              <template v-if="storage_data.type_data == 'folder'">
                <FolderIcon class="h-5 w-5" />
              </template>
              <template v-else>
                <img
                  class=""
                  :src="
                    'https://drive-thirdparty.googleusercontent.com/16/type/' +
                    (storage_data.type_data === 'pdf'
                      ? 'application/pdf'
                      : storage_data.type_data)
                  "
                  :alt="storage_data.type_data"
                />
              </template>

              {{ storage_data.business_code }}
            </a>
          </template>
          <template v-else>
            <img
              class=""
              :src="
                'https://drive-thirdparty.googleusercontent.com/16/type/' +
                (storage_data.type_data === 'pdf'
                  ? 'application/pdf'
                  : storage_data.type_data)
              "
              :alt="storage_data.type_data"
            />
            <p @click="handleOpen" class="cursor-pointer">
              {{ storage_data.business_code }}
            </p>
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
      type: Object,
    },
  },
  methods: {
    goBack() {
      window.history.back()
    },
  },
}
</script>
