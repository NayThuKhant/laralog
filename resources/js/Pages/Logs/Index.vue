<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Table} from "@protonemedia/inertiajs-tables-laravel-query-builder";
import {Link} from '@inertiajs/vue3';
import Eye from '@/Icons/Eye.vue';

defineProps({
    logs: {
        type: Array
    }
})

</script>

<template>
    <AppLayout :title="$page.props.auth.user.current_team.name">
            <Table :resource="logs" preserve-scroll striped>
                <template #cell(actions)="{ item: log }">
                    <Link :href="route('logs.show', log.id)">
                        <Eye/>
                    </Link>
                </template>
            </Table>
    </AppLayout>
</template>
