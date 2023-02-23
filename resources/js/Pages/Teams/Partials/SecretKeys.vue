<script setup>
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Bin from '@/Icons/Bin.vue';
import {useForm} from "@inertiajs/vue3";
import {copyToClipboard} from "../../../Utils/useClipboard";
import {ref} from "vue";

const props = defineProps({
    team: {
        type: Object
    }
});

const form = useForm({});

let confirmingSecretKeyGeneration = ref(false);

const confirmSecretKeyGeneration = () => confirmingSecretKeyGeneration.value = true;

const generateSecretKey = () =>
    form.post(route('teams.generate-secret-key', props.team), {
        errorBag: 'generateSecretKey',
        onSuccess: confirmingSecretKeyGeneration.value = false,
        preserveScroll: true,
    });


let deletingTeamSecretKey = ref(false);

const confirmSecretKeyDeletion = (key) => deletingTeamSecretKey.value = key;


let deleteSecretKey = () => {
    console.log(props.team)
    form.delete(route("teams.destroy-secret-key", {team: props.team.id, key: deletingTeamSecretKey.value.id}), {
        errorBag: "destroySecretKey",
        onSuccess: deletingTeamSecretKey.value = null,
        preserveScroll: true
    })
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
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400 mb-10">
                Keep your team's secret keys secured and let nobody knows unless you don't want to mess up your
                recording app exceptions. The following keys can be copied by clicking onto them.
            </div>

            <div class="mt-6 items-center flex cursor-pointer" v-for="secretKey in team.secret_keys">
                <Bin @click="confirmSecretKeyDeletion(secretKey)"/>
                <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400 ml-6"
                     @click="copyToClipboard(secretKey.encrypted_secret_key)">
                    {{ secretKey.encrypted_secret_key }}
                </div>

            </div>
            <div v-if="team.secret_keys.length === 0" class="mt-6 max-w-xl text-sm text-yellow-600">
                There is no secret keys yet, generate one for your team.
            </div>

            <div class="mt-10">
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

            <!-- Delete Secret Key -->
            <ConfirmationModal :show="deletingTeamSecretKey" @close="deletingTeamSecretKey == null">
                <template #title>
                    Delete Secret Key
                </template>

                <template #content>
                    Are you sure to delete secret key {{ deletingTeamSecretKey.encrypted_secret_key }} ?
                </template>

                <template #footer>
                    <SecondaryButton @click="deletingTeamSecretKey= null">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteSecretKey"
                    >
                        Delete
                    </DangerButton>
                </template>
            </ConfirmationModal>
        </template>
    </ActionSection>
</template>
