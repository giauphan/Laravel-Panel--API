<script setup>
import {
    Table,
    TableHead,
    TableCell,
    TableBody,
    TableRow,
} from 'flowbite-vue'
</script>

<template>
    <Table hoverable>
        <TableHead>
            <TableCell>Name</TableCell>
            <TableCell>Type </TableCell>
            <TableCell>Size</TableCell>
            <TableCell>Time</TableCell>

            <TableCell>
                <span class="sr-only">Edit</span>
            </TableCell>
        </TableHead>
        <TableBody class="overflow-y-auto w-full" v-for="storage in storages">
            <TableRow v-for="storage_data in storage" class="w-full">
                <TableCell class="flex items-center gap-5 flex-wrap w-full">
                    <img class="a-Ua-c"
                        :src="'https://drive-thirdparty.googleusercontent.com/16/type/' + (storage_data.type_data === 'pdf' ? 'application/pdf' : storage_data.type_data)"
                        :alt="storage_data.type_data">
{{ storage_data.type_data }}
                    {{ storage_data.business_code }}
                </TableCell>
                <TableCell> {{ storage_data.type_data }}</TableCell>
                <TableCell>{{ calculateFileSize(storage_data.file_data) }}</TableCell>
                <TableCell> {{ storage_data.created_at }}</TableCell>
                <TableCell>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>

<script>
export default {
    props: {
        storages: {
            type: Array,
            required: true,
        },
    },
    methods: {
        calculateFileSize(fileData) {

            // Decode the Base64 string
            const binaryData = btoa(fileData);

            // Calculate the size in bytes
            const fileSizeInBytes = binaryData.length;

            // Format the size as needed (e.g., in kilobytes or megabytes)
            const fileSizeFormatted = `${fileSizeInBytes} bytes`;

            return fileSizeFormatted;

        },
    },
    // mounted() {
    //     console.log("storages prop:", this.storages);
    // },
};
</script>

