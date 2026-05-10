<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    sessions: Array,
});

const confirmingLogout = ref(false);
const passwordInput = ref(null);
const form = useForm({ password: '' });

const confirmLogout = () => {
    confirmingLogout.value = true;
    setTimeout(() => passwordInput.value.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route('other-browser-sessions.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingLogout.value = false;
    form.reset();
};
</script>

<template>
    <ActionSection class="mt-10">
        <template #title>
            <span class="text-slate-900 font-bold text-xl">Browser Sessions</span>
        </template>

        <template #description>
            <span class="text-slate-500">Manage and log out your active sessions on other browsers and devices.</span>
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-slate-600">
                If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
            </div>

            <div v-if="sessions.length > 0" class="mt-8 space-y-6">
                <div v-for="(session, i) in sessions" :key="i" class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div>
                        <svg v-if="session.agent.is_desktop" class="w-8 h-8 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.878 2.122L7.5 21h9l-.622-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                        </svg>

                        <svg v-else class="w-8 h-8 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                    </div>

                    <div class="ms-4">
                        <div class="text-sm font-semibold text-slate-900">
                            {{ session.agent.platform ? session.agent.platform : 'Unknown' }} - {{ session.agent.browser ? session.agent.browser : 'Unknown' }}
                        </div>

                        <div>
                            <div class="text-xs text-slate-500">
                                {{ session.ip_address }},
                                <span v-if="session.is_current_device" class="text-emerald-500 font-bold">This device</span>
                                <span v-else>Last active {{ session.last_active }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center mt-8">
                <PrimaryButton class="bg-slate-800 hover:bg-slate-900 px-6 py-3 rounded-xl shadow-lg transition transform active:scale-95" @click="confirmLogout">
                    Log Out Other Browser Sessions
                </PrimaryButton>
            </div>

            <DialogModal :show="confirmingLogout" @close="closeModal">
                <template #title>
                    Log Out Other Browser Sessions
                </template>

                <template #content>
                    Please enter your password to confirm.
                    <div class="mt-4">
                        <TextInput ref="passwordInput" v-model="form.password" type="password" class="mt-1 block w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl" placeholder="Password" @keyup.enter="logoutOtherBrowserSessions" />
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal" class="rounded-xl">Cancel</SecondaryButton>
                    <PrimaryButton class="ms-3 bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="logoutOtherBrowserSessions">
                        Confirm Logout
                    </PrimaryButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>