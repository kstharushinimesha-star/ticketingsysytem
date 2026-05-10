<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <AppLayout title="Profile Settings">
        <div class="relative min-h-screen bg-[#f8fafc] overflow-hidden py-12">
            
            <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                
                <div class="mb-10 text-center">
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">
                        Account <span class="text-indigo-600">Profile</span>
                    </h2>
                    <p class="text-slate-500 mt-2 font-medium">Manage your public information and digital identity</p>
                </div>

                <div v-if="$page.props.jetstream.canUpdateProfileInformation" class="profile-card-wrapper">
                    <div class="glass-card">
                        <UpdateProfileInformationForm :user="$page.props.auth.user" />
                    </div>
                </div>

                <div class="mt-16 flex flex-col items-center">
                    <div class="h-px w-20 bg-gradient-to-r from-transparent via-slate-200 to-transparent mb-6"></div>
                    <div class="flex items-center gap-4 text-slate-400 text-[11px] font-bold uppercase tracking-[0.3em]">
                        <span>Verified</span>
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                        <span>SupportHub 2026</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Glassmorphism Card Style */
.glass-card {
    @apply bg-white/70 backdrop-blur-xl border border-white rounded-[3rem] p-4 shadow-[0_20px_50px_rgba(0,0,0,0.05)];
}

/* Form එක ඇතුළත ඇති Jetstream කොටස් වල ඉඩ ප්‍රමාණයන් නිවැරදි කිරීම */
:deep(.md\:grid) {
    @apply flex flex-col gap-0 !important;
}

:deep(.md\:col-span-1) {
    @apply px-6 pt-8 pb-2 !important;
}

:deep(.mt-5.md\:mt-0.md\:col-span-2) {
    @apply mt-0 !important;
}

:deep(.bg-white) {
    @apply bg-transparent shadow-none border-none !important;
}

/* පවතින HR රේඛා ඉවත් කිරීම */
:deep(hr) {
    display: none;
}

/* Background Blobs Animation */
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

/* Form එක ඇතුළත ඇති Action Button එකට සෙවනැල්ලක් ලබා දීම */
:deep(button[type="submit"]) {
    @apply shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transition-all !important;
}
</style>