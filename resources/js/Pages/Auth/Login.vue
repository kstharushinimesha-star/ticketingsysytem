<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-200">
                    <AuthenticationCardLogo class="w-12 h-12 fill-current text-white" />
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900 tracking-tight">
                Welcome Back
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                Please enter your details to access your account.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-10 px-6 shadow-xl shadow-slate-200/60 border border-slate-100 sm:rounded-3xl sm:px-12">
                
                <div v-if="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-sm font-medium text-emerald-600">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <InputLabel for="email" value="Email Address" class="text-slate-700 font-semibold mb-1" />
                        <div class="relative group">
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
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <InputLabel for="password" value="Password" class="text-slate-700 font-semibold" />
                            <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs font-bold text-indigo-600 hover:text-indigo-500 transition">
                                Forgot password?
                            </Link>
                        </div>
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl transition duration-200"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center">
                        <Checkbox v-model:checked="form.remember" name="remember" class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                        <span class="ms-3 text-sm text-slate-600 font-medium">Keep me signed in</span>
                    </div>

                    <div>
                        <PrimaryButton 
                            class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 transform active:scale-[0.98]" 
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing }" 
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Signing in...</span>
                            <span v-else>Sign In</span>
                        </PrimaryButton>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500">
                        Don't have an account? 
                        <Link :href="route('register')" class="font-bold text-indigo-600 hover:text-indigo-500 underline-offset-4 hover:underline">
                            Create one now
                        </Link>
                    </p>
                </div>
            </div>
            
            <p class="mt-8 text-center text-xs text-slate-400 uppercase tracking-widest font-medium">
                &copy; 2026 SupportHub Security
            </p>
        </div>
    </div>
</template>

<style scoped>
/* Smooth transitions for inputs */
input {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>