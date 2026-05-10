<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmsPassword from '@/Components/ConfirmsPassword.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    requiresConfirmation: Boolean,
});

const page = usePage();
const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: '',
});

const twoFactorEnabled = computed(
    () => ! enabling.value && page.props.auth.user?.two_factor_enabled,
);

watch(twoFactorEnabled, () => {
    if (! twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(route('two-factor.enable'), {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
            showQrCode(),
            showSetupKey(),
            showRecoveryCodes(),
        ]),
        onFinish: () => {
            enabling.value = false;
            confirming.value = props.requiresConfirmation;
        },
    });
};

const showQrCode = () => {
    return axios.get(route('two-factor.qr-code')).then(response => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route('two-factor.secret-key')).then(response => {
        setupKey.value = response.data.secretKey;
    });
}

const showRecoveryCodes = () => {
    return axios.get(route('two-factor.recovery-codes')).then(response => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route('two-factor.confirm'), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios
        .post(route('two-factor.recovery-codes'))
        .then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    router.delete(route('two-factor.disable'), {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};
</script>

<template>
    <ActionSection class="overflow-hidden">
        <template #title>
            <span class="text-slate-900 font-bold text-xl">Two Factor Authentication</span>
        </template>

        <template #description>
            <span class="text-slate-500 font-medium">Add an extra layer of security using two-factor authentication.</span>
        </template>

        <template #content>
            <div class="flex items-center gap-3 mb-6">
                <div :class="twoFactorEnabled && !confirming ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600'" class="p-2 rounded-full">
                    <svg v-if="twoFactorEnabled && !confirming" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 4.925-3.467 9.431-9.25 11.823a1 1 0 01-.75 0c-5.783-2.392-9.25-6.898-9.25-11.823 0-.68.056-1.35.166-2.001zm10.707 3.708a1 1 0 00-1.414-1.414L9 9.586 7.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3z" clip-rule="evenodd" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">
                    <span v-if="twoFactorEnabled && !confirming">Active Security</span>
                    <span v-else-if="twoFactorEnabled && confirming">Pending Confirmation</span>
                    <span v-else>Security Recommendation</span>
                </h3>
            </div>

            <div class="text-sm text-slate-600 leading-relaxed mb-6">
                <p>
                    When two-factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator or Authy application.
                </p>
            </div>

            <div v-if="twoFactorEnabled" class="space-y-6">
                <div v-if="qrCode" class="bg-slate-50 border border-slate-100 p-6 rounded-3xl inline-flex flex-col items-center gap-4">
                    <div class="text-center mb-2">
                        <p v-if="confirming" class="text-xs font-bold uppercase tracking-wider text-indigo-600">Step 1: Scan QR Code</p>
                        <p v-else class="text-xs font-bold uppercase tracking-wider text-slate-500">Your Secure QR Code</p>
                    </div>
                    
                    <div class="p-4 bg-white rounded-2xl shadow-sm ring-1 ring-slate-200" v-html="qrCode" />

                    <div v-if="setupKey" class="mt-2 text-center">
                        <p class="text-xs text-slate-500 font-medium">Unable to scan? Use setup key:</p>
                        <p class="font-mono text-sm font-bold text-slate-800 bg-white px-3 py-1 rounded-lg border border-slate-200 mt-1 inline-block" v-html="setupKey"></p>
                    </div>
                </div>

                <div v-if="confirming" class="max-w-xs">
                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-2">Step 2: Enter OTP Code</p>
                    <InputLabel for="code" value="Verification Code" class="sr-only" />
                    <TextInput
                        id="code"
                        v-model="confirmationForm.code"
                        type="text"
                        class="block w-full px-4 py-3 bg-white border-slate-200 focus:ring-2 focus:ring-indigo-500 rounded-xl font-mono text-center text-xl tracking-[0.5em]"
                        placeholder="000000"
                        inputmode="numeric"
                        autofocus
                        @keyup.enter="confirmTwoFactorAuthentication"
                    />
                    <InputError :message="confirmationForm.errors.code" class="mt-2" />
                </div>

                <div v-if="recoveryCodes.length > 0 && !confirming" class="bg-slate-900 rounded-3xl p-6 text-white overflow-hidden relative">
                    <div class="relative z-10">
                        <h4 class="text-sm font-bold text-indigo-300 uppercase tracking-widest mb-3">Backup Recovery Codes</h4>
                        <p class="text-xs text-slate-400 mb-4 leading-relaxed">Store these securely. They allow access if you lose your authentication device.</p>
                        
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 font-mono text-sm">
                            <div v-for="code in recoveryCodes" :key="code" class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500/50"></span>
                                {{ code }}
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-10">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <div v-if="!twoFactorEnabled">
                    <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
                        <PrimaryButton type="button" class="bg-indigo-600 hover:bg-indigo-700 px-8 py-3 rounded-xl shadow-lg shadow-indigo-100 transition transform active:scale-95 font-bold" :class="{ 'opacity-25': enabling }" :disabled="enabling">
                            Enable 2FA Protection
                        </PrimaryButton>
                    </ConfirmsPassword>
                </div>

                <div v-else class="flex flex-wrap gap-3">
                    <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                        <PrimaryButton
                            v-if="confirming"
                            type="button"
                            class="bg-indigo-600 hover:bg-indigo-700 px-6 py-3 rounded-xl shadow-lg transition transform active:scale-95 font-bold"
                            :class="{ 'opacity-25': enabling || confirmationForm.processing }"
                            :disabled="enabling || confirmationForm.processing"
                        >
                            Complete Setup
                        </PrimaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length > 0 && !confirming"
                            class="bg-slate-100 text-slate-700 border-none hover:bg-slate-200 px-4 py-3 rounded-xl font-bold transition"
                        >
                            Refresh Codes
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="showRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length === 0 && !confirming"
                            class="bg-slate-100 text-slate-700 border-none hover:bg-slate-200 px-4 py-3 rounded-xl font-bold transition"
                        >
                            Show Recovery Codes
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <SecondaryButton
                            v-if="confirming"
                            class="px-4 py-3 border-slate-200 rounded-xl font-bold transition"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                        >
                            Cancel Setup
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <DangerButton
                            v-if="!confirming"
                            class="bg-red-50 text-red-600 border-none hover:bg-red-100 px-4 py-3 rounded-xl font-bold transition"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                        >
                            Disable 2FA
                        </DangerButton>
                    </ConfirmsPassword>
                </div>
            </div>
        </template>
    </ActionSection>
</template>

<style scoped>
/* SVG QR code styling */
:deep(svg) {
    max-width: 100%;
    height: auto;
}
</style>