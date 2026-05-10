<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Welcome - Support System" />
    
    <div class="bg-slate-50 min-h-screen font-sans text-slate-900">
        <nav class="bg-white shadow-sm border-b border-slate-200 p-4 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="text-2xl font-bold tracking-tight text-indigo-600">
                    Support<span class="text-slate-800">Hub</span>
                </div>
                
                <div v-if="canLogin" class="flex items-center space-x-6">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition"
                    >
                        Go to Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="bg-indigo-600 text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-indigo-700 shadow-md shadow-indigo-100 transition"
                        >
                            Get Started
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-6 py-24 text-center lg:py-32">
            <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 mb-6 tracking-tight">
                Swift Solutions for <br/>
                <span class="text-indigo-600">Every Technical Issue.</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                Connect with our expert team through our streamlined ticketing system. 
                Whether it's a technical bug or a service inquiry, we're here to help you move forward.
            </p>

            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('tickets.create')"
                    class="w-full sm:w-auto bg-indigo-600 text-white px-10 py-4 rounded-xl text-lg font-bold shadow-xl hover:bg-indigo-700 transition transform hover:-translate-y-1"
                >
                    Submit a Ticket
                </Link>
                <Link
                    v-else
                    :href="route('register')"
                    class="w-full sm:w-auto bg-white text-indigo-600 border-2 border-indigo-600 px-10 py-4 rounded-xl text-lg font-bold hover:bg-indigo-50 transition transform hover:-translate-y-1"
                >
                    Create Account
                </Link>
            </div>

            <div class="grid md:grid-cols-3 gap-10 mt-32">
                <div class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:border-indigo-100 transition duration-300">
                    <div class="bg-indigo-50 w-14 h-14 rounded-xl flex items-center justify-center mb-6 mx-auto group-hover:bg-indigo-600 transition-colors">
                        <svg class="w-7 h-7 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">24/7 Accessibility</h3>
                    <p class="text-slate-500 leading-relaxed">Log your issues anytime, anywhere. Our system is always open to receive your inquiries.</p>
                </div>

                <div class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:border-indigo-100 transition duration-300">
                    <div class="bg-emerald-50 w-14 h-14 rounded-xl flex items-center justify-center mb-6 mx-auto group-hover:bg-emerald-600 transition-colors">
                        <svg class="w-7 h-7 text-emerald-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Priority Support</h3>
                    <p class="text-slate-500 leading-relaxed">Tickets are categorized by urgency to ensure critical issues get handled by our experts immediately.</p>
                </div>

                <div class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:border-indigo-100 transition duration-300">
                    <div class="bg-violet-50 w-14 h-14 rounded-xl flex items-center justify-center mb-6 mx-auto group-hover:bg-violet-600 transition-colors">
                        <svg class="w-7 h-7 text-violet-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Real-time Tracking</h3>
                    <p class="text-slate-500 leading-relaxed">Stay updated with status alerts. Track every step from 'Open' to 'Resolved' in real-time.</p>
                </div>
            </div>
        </main>

        <footer class="mt-20 py-12 border-t border-slate-200 bg-white text-center">
            <p class="text-slate-400 text-sm font-medium uppercase tracking-widest">
                &copy; 2026 SupportHub Operations. Powered by Laravel & Vue.
            </p>
        </footer>
    </div>
</template>