<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const form = useForm({ password: '' });

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route('current-user.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <ActionSection class="mt-10">
        <template #title>
            <span class="text-red-600 font-bold text-xl">Delete Account</span>
        </template>

        <template #description>
            <span class="text-slate-500">Permanently delete your account and all associated data.</span>
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-slate-600 bg-red-50 p-4 rounded-2xl border border-red-100">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
            </div>

            <div class="mt-8">
                <DangerButton class="px-6 py-3 bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-100 transition transform active:scale-95 font-bold" @click="confirmUserDeletion">
                    Delete My Account
                </DangerButton>
            </div>

            <DialogModal :show="confirmingUserDeletion" @close="closeModal">
                <template #title>
                    Delete Account
                </template>

                <template #content>
                    Are you sure? This action cannot be undone. Enter your password to confirm.
                    <div class="mt-4">
                        <TextInput ref="passwordInput" v-model="form.password" type="password" class="mt-1 block w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl" placeholder="Password" @keyup.enter="deleteUser" />
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal" class="rounded-xl">Cancel</SecondaryButton>
                    <DangerButton class="ms-3 bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-100" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="deleteUser">
                        Delete Account Permanently
                    </DangerButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>