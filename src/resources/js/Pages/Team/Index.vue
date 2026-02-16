<script setup>
import {ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    users: Array,
});

const showingInviteModal = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: 'tempPassword', //TODO create an invite link
});

const inviteMember = () => {
    form.post(route('team.store'), {
        onSuccess: () => {
            showingInviteModal.value = false;
            form.reset();
        },
    });
};

</script>

<template>
    <Head :title="__('Team Members')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Team Members') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div class="mb-6 flex justify-end">
                            <PrimaryButton @click="showingInviteModal = true">
                                {{ __('Invite Member') }}
                            </PrimaryButton>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Name') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Email') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Joined') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in users" :key="user.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ user.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ user.email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ new Date(user.created_at).toLocaleDateString() }}
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showingInviteModal" @close="showingInviteModal = false">
            <form @submit.prevent="inviteMember" class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Invite New Team Member') }}
                </h2>

                <div class="mt-6">
                    <InputLabel for="name" value="Name" class="sr-only" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="Name"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div class="mt-6">
                    <InputLabel for="email" value="Email" class="sr-only" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="Email"
                    />
                    <InputError :message="form.errors.email" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="showingInviteModal = false">
                        {{ __('Cancel') }}
                    </SecondaryButton>
                    <PrimaryButton class="ms-3" :class="{ 'opacity-25' : form.processing }" :disabled="form.processing">
                        {{ __('Send Invitation') }}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>

    </AuthenticatedLayout>
</template>
