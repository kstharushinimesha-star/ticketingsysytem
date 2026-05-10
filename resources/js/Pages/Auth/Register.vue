<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user', // Default role set to user
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Create Account" />

    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-200">
                    <AuthenticationCardLogo class="w-12 h-12 fill-current text-white" />
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900 tracking-tight">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                Join our support community and get started today.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-10 px-6 shadow-xl shadow-slate-200/60 border border-slate-100 sm:rounded-3xl sm:px-12">
                
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <InputLabel for="name" value="Full Name" class="text-slate-700 font-semibold mb-1" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl transition duration-200"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="John Doe"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Email Address" class="text-slate-700 font-semibold mb-1" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl transition duration-200"
                            required
                            autocomplete="username"
                            placeholder="name@company.com"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- <div>
                        <InputLabel for="role" value="Register As" class="text-slate-700 font-semibold mb-1" />
                        <select 
                            id="role"
                            v-model="form.role"
                            class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl transition duration-200 text-slate-600"
                            required
                        >
                            <option value="user">Standard User</option>
                            <option value="admin">System Administrator</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div> -->

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <InputLabel for="password" value="Password" class="text-slate-700 font-semibold mb-1" />
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl transition duration-200"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                            />
                        </div>
                        <div>
                            <InputLabel for="password_confirmation" value="Confirm" class="text-slate-700 font-semibold mb-1" />
                            <TextInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl transition duration-200"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                            />
                        </div>
                    </div>
                    <InputError class="mt-1" :message="form.errors.password" />

                    <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                        <label class="flex items-start cursor-pointer">
                            <Checkbox id="terms" v-model:checked="form.terms" name="terms" required class="mt-1 w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                            <div class="ms-3 text-sm text-slate-600 leading-tight">
                                I agree to the 
                                <a target="_blank" :href="route('terms.show')" class="font-bold text-indigo-600 hover:underline underline-offset-2">Terms</a> 
                                and 
                                <a target="_blank" :href="route('policy.show')" class="font-bold text-indigo-600 hover:underline underline-offset-2">Privacy</a>
                            </div>
                        </label>
                        <InputError class="mt-2" :message="form.errors.terms" />
                    </div>

                    <div class="pt-2">
                        <PrimaryButton 
                            class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 transform active:scale-[0.98]" 
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing }" 
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Creating account...</span>
                            <span v-else>Register Now</span>
                        </PrimaryButton>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500">
                        Already have an account? 
                        <Link :href="route('login')" class="font-bold text-indigo-600 hover:text-indigo-500 underline-offset-4 hover:underline transition">
                            Sign in here
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
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
</style>