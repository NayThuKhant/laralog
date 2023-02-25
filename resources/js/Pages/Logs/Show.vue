<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {JsonTreeView} from "json-tree-view-vue3";
import {computed, reactive} from "vue";

const state = reactive({
    openJsonViewer: true
})


const props = defineProps({
    log: {
        type: Object
    },
    context: {
        type: String
    },
    reports: {
        total_incidents: Number,
        one_day_incidents: Number,
        one_week_incidents: Number,
        one_month_incidents: Number,
        timestamps: Array
    },
})

const isValidJsonMessage = computed(() => {
    try {
        JSON.parse(props.log.message)
    } catch (e) {
        return false;
    }
    return true;
})

</script>

<template>
    <AppLayout title="Log">
        <div class="flex mb-4  cursor-pointer">
            <div class="flex justify-between items-center"
                 @click="state.openJsonViewer = !state.openJsonViewer">
                <div class="w-12 h-6 flex items-center bg-gray-300 rounded-full p-1 duration-300 ease-in-out"
                     :class="{ 'bg-green-400': state.openJsonViewer}">
                    <div class="bg-white w-5 h-5 rounded-full shadow-md transform duration-300 ease-in-out"
                         :class="{ 'translate-x-5': state.openJsonViewer}"></div>
                </div>
            </div>

            <span class="ml-3 text-gray-600 dark:text-gray-400">Json Viewer</span>
        </div>

        <div class="report-card">
            <p class="font-bold mb-3">LEVEL</p>
            <p>{{ log.log_level.level }}</p>
        </div>

        <div class="report-card">
            <p class="font-bold mb-3">Message</p>
            <p v-if="!isValidJsonMessage">{{ log.message }}</p>
            <div v-else>
                <JsonTreeView v-if="state.openJsonViewer" :data="log.message" :maxDepth="3" color-scheme="dark"/>
                <p v-if="!state.openJsonViewer">{{ log.message }}</p>
            </div>
        </div>
        <div class="report-card">
            <div class="flex justify-between">
                <p class="font-bold mb-3">Context</p>
            </div>

            <div v-if="log.context">
                <JsonTreeView v-if="state.openJsonViewer" :data="context" :maxDepth="3" color-scheme="dark"/>
                <p v-else>
                    {{ context }}
                </p>
            </div>
            <div v-else>No context found for this log.</div>
        </div>

        <div class="report-card flex flex-col">
            <h2 class="text-xl mb-4">Incidents Report</h2>
            <div class="flex-1 flex justify-around">
                <span class="text-sm"><span class="text-4xl">{{ reports.total_incidents }}</span> / total </span>
                <span class="text-sm"><span class="text-4xl">{{ reports.one_day_incidents }}</span> / 24 hours </span>
                <span class="text-sm"><span class="text-4xl">{{ reports.one_week_incidents }}</span> / 7 days </span>
                <span class="text-sm"><span class="text-4xl">{{ reports.total_incidents }}</span> / 1 month </span>
            </div>
        </div>


    </AppLayout>
</template>

<style scoped>
.report-card {
    @apply text-sm w-full p-4 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-md sm:rounded-md my-6 text-gray-600 dark:text-gray-400;
}


</style>
