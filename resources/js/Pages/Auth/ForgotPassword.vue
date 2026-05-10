<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />

    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-200">
                    <AuthenticationCardLogo class="w-12 h-12 fill-current text-white" />
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900 tracking-tight">
                Reset Password
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                We'll send a secure link to your email.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-10 px-6 shadow-xl shadow-slate-200/60 border border-slate-100 sm:rounded-3xl sm:px-12">
                
                <div class="mb-6 text-sm text-slate-600 leading-relaxed text-center">
                    Forgot your password? No problem. Just enter your email address and we will email you a password reset link to choose a new one.
                </div>

                <div v-if="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-sm font-medium text-emerald-600 text-center">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <InputLabel for="email" value="Email Address" class="text-slate-700 font-semibold mb-1" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl transition duration-200"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="name@company.com"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <PrimaryButton 
                            class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 transform active:scale-[0.98]" 
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing }" 
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Sending link...</span>
                            <span v-else>Email Password Reset Link</span>
                        </PrimaryButton>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <Link :href="route('login')" class="text-sm font-bold text-indigo-600 hover:text-indigo-500 transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Login
                    </Link>
                </div>
            </div>
            
            <p class="mt-8 text-center text-xs text-slate-400 uppercase tracking-widest font-medium">
                &copy; 2026 SupportHub Security
            </p>
        </div>
    </div>
</template>

<style scoped>
input {
    transition: all 0.25s ease-in-out;
}
</style>