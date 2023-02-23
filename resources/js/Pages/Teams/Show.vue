<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteTeamForm from '@/Pages/Teams/Partials/DeleteTeamForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TeamMemberManager from '@/Pages/Teams/Partials/TeamMemberManager.vue';
import UpdateTeamNameForm from '@/Pages/Teams/Partials/UpdateTeamNameForm.vue';
import SecretKeys from "@/Pages/Teams/Partials/SecretKeys.vue";

defineProps({
    team: Object,
    availableRoles: Array,
    permissions: Object,
});
</script>

<template>
    <AppLayout title="Team Settings">
        <UpdateTeamNameForm :team="team" :permissions="permissions"/>

        <TeamMemberManager
            class="mt-10 sm:mt-0"
            :team="team"
            :available-roles="availableRoles"
            :user-permissions="permissions"
        />

        <SectionBorder/>
        <SecretKeys :team="team"/>

        <template v-if="permissions.canDeleteTeam && ! team.personal_team">
            <SectionBorder/>

            <DeleteTeamForm class="mt-10 sm:mt-0" :team="team"/>
        </template>
    </AppLayout>
</template>
