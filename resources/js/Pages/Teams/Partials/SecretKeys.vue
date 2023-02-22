<script setup>
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {useForm} from "@inertiajs/vue3";
import {copyToClipboard} from "../../../Utils/useClipboard";
import {ref} from "vue";

const props = defineProps({
    team: {
        type: Object
    }
});



const confirmingSecretKeyGeneration = ref(false);
const form = useForm({});

const confirmSecretKeyGeneration = () => {
    confirmingSecretKeyGeneration.value = true;
};

const generateSecretKey = () => {
    form.post(route('teams.generate-secret-token', props.team), {
        errorBag: 'generateSecretKey',
        onSuccess: confirmingSecretKeyGeneration.value = false,
        preserveScroll: true,
    });
};

</script>

<template>
    <ActionSection>
        <template #title>
            Secret Keys
        </template>

        <template #description>
            The secret keys to interact with this system.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                Keep your team's secret keys secured and let nobody knows unless you don't want to mess up your
                recording app exceptions.
            </div>

            <div class="mt-5 flex cursor-pointer" v-for="secretKey in team.secret_keys" @click="copyToClipboard(secretKey.encrypted_secret_key)">
                <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                    {{ secretKey.encrypted_secret_key }}
                </div>
                <div>
                    <svg  class="ml-4" style="color: white" width="20" height="20" viewBox="0 0 20 20" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8 2C7.44772 2 7 2.44772 7 3C7 3.55228 7.44772 4 8 4H10C10.5523 4 11 3.55228 11 3C11 2.44772 10.5523 2 10 2H8Z"
                            fill="white"></path>
                        <path
                            d="M3 5C3 3.89543 3.89543 3 5 3C5 4.65685 6.34315 6 8 6H10C11.6569 6 13 4.65685 13 3C14.1046 3 15 3.89543 15 5V11H10.4142L11.7071 9.70711C12.0976 9.31658 12.0976 8.68342 11.7071 8.29289C11.3166 7.90237 10.6834 7.90237 10.2929 8.29289L7.29289 11.2929C6.90237 11.6834 6.90237 12.3166 7.29289 12.7071L10.2929 15.7071C10.6834 16.0976 11.3166 16.0976 11.7071 15.7071C12.0976 15.3166 12.0976 14.6834 11.7071 14.2929L10.4142 13H15V16C15 17.1046 14.1046 18 13 18H5C3.89543 18 3 17.1046 3 16V5Z"
                            fill="white"></path>
                        <path d="M15 11H17C17.5523 11 18 11.4477 18 12C18 12.5523 17.5523 13 17 13H15V11Z"
                              fill="white"></path>
                    </svg>
                </div>
            </div>
            <div v-if="team.secret_keys.length === 0" class="mt-6 max-w-xl text-sm text-yellow-600">
                There is no secret keys yet, generate one for your team.
            </div>

            <div class="mt-5">
                <PrimaryButton @click="confirmSecretKeyGeneration">
                    Generate A New Secret Key
                </PrimaryButton>
            </div>

            <!-- Generate New Secret Key -->
            <ConfirmationModal :show="confirmingSecretKeyGeneration" @close="confirmingSecretKeyGeneration == false">
                <template #title>
                    Generate A New Secret Key
                </template>

                <template #content>
                    Are you sure to generate a new secret key for the team <b>{{ team.name }} ?</b>
                </template>

                <template #footer>
                    <SecondaryButton @click="confirmingSecretKeyGeneration= false">
                        Cancel
                    </SecondaryButton>

                    <PrimaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="generateSecretKey"
                    >
                        Generate
                    </PrimaryButton>
                </template>
            </ConfirmationModal>
        </template>
    </ActionSection>
</template>
