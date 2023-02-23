<script setup>
import {Link} from '@inertiajs/vue3';
import {computed} from "vue";

const props = defineProps({
    level: {
        type: String,
        default: "LOG LEVEL"
    },
    count: {
        type: Object,
        default: 0
    }
})

const linkClasses = computed(() => {
    let linkClasses = "scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500";
    return props.count.unresolved > 0 ? linkClasses : linkClasses + " opacity-30";
})

</script>

<template>
    <Link :href="route('logs.index')" :class="linkClasses">
        <div class="w-full">
            <p class="text-xl text-gray-600 dark:text-gray-400">{{ level }}</p>

            <p class="mt-8 text-sm text-gray-600 dark:text-gray-400">
                TOTAL : {{ count.total }}
            </p>
            <p class="mt-1 text-sm text-red-500 dark:text-red-400">
                UNRESOLVED : {{ count.unresolved }}
            </p>
            <p class="mt-1 text-sm text-green-500 dark:text-green-400">
                RESOLVED : {{ count.resolved }}
            </p>
        </div>
    </Link>
</template>
