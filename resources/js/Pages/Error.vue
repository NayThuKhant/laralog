<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    code: Number | String,
    message: String
});
</script>

<template>
    <AppLayout title="Error">
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="mt-16">
                <div class="flex flex-col justify-center items-center">
                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                         width="100pt" height="100pt" viewBox="0 0 200 200"
                         preserveAspectRatio="xMidYMid meet">

                        <g transform="translate(0.000000,200.000000) scale(0.100000,-0.100000)"
                           fill="#4a5568" stroke="none" class="text-black-50">
                            <path d="M935 1731 c-45 -27 -52 -38 -180 -261 -227 -395 -309 -537 -453 -785
-124 -213 -146 -257 -149 -300 -5 -59 18 -104 69 -135 32 -19 52 -20 784 -20
l751 0 34 23 c41 27 69 79 69 128 0 36 -36 101 -425 774 -70 121 -166 288
-213 370 -47 83 -95 161 -106 173 -47 53 -126 68 -181 33z m122 -73 c12 -13
78 -122 148 -243 70 -121 150 -260 179 -310 276 -473 406 -706 406 -731 0 -15
-10 -38 -22 -51 l-21 -23 -743 0 -743 0 -21 22 c-40 43 -31 64 191 448 116
201 279 482 361 625 82 143 156 266 165 273 27 20 77 15 100 -10z"/>
                            <path d="M912 1358 c-7 -7 -12 -30 -12 -52 0 -75 61 -670 71 -688 14 -26 57
-23 69 5 9 22 70 620 70 688 0 52 -13 59 -105 59 -50 0 -86 -5 -93 -12z"/>
                            <path d="M946 544 c-8 -20 -8 -68 0 -88 8 -20 96 -22 112 -3 6 6 8 33 5 60
l-6 47 -52 0 c-39 0 -54 -4 -59 -16z"/>
                        </g>
                    </svg>
                    <div class="text-gray-600 dark:text-gray-400">
                        <p class="text-2xl font-bold text-center">Beep Beep!</p>
                        <p class="text-center mt-10">{{ message }}</p>
                        <p class="text-center text-xs mt-3">ERROR CODE : {{ code }}</p>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
