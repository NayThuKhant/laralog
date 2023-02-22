<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Table} from "@protonemedia/inertiajs-tables-laravel-query-builder";

defineProps({
    logs: {
        type: Array
    }
})

</script>

<template>
    <AppLayout title="Dashboard">
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <Table :resource="logs" preserve-scroll striped>
            </Table>
        </div>
    </AppLayout>
</template>
