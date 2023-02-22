<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import LogLevelCountCard from "@/Components/LogLevelCountCard.vue";

defineProps({
    logLevelCounts : {
        type: Object
    }
})


</script>

<template>
    <AppLayout title="Dashboard">
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="mt-16">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8">
                    <LogLevelCountCard v-for="(count, level) in logLevelCounts" :key="level" :level="level" :count="count"/>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
