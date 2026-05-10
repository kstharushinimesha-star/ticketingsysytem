<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    subject: '',
    description: '',
    category: 'Technical',
    priority: 'Low',
});

const submit = () => {
    form.post(route('tickets.store'));
};
</script>

<template>
    <AppLayout title="Create Ticket">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Support Ticket
            </h2>
        </template>

        <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 border border-gray-100">
                
                <div class="mb-8 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Ticket Information</h3>
                    <p class="mt-1 text-sm text-gray-600">Please provide detailed information about the issue you are facing.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Subject</label>
                        <input 
                            v-model="form.subject" 
                            type="text" 
                            placeholder="Briefly describe your issue"
                            class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition duration-150" 
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Description</label>
                        <textarea 
                            v-model="form.description" 
                            class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition duration-150" 
                            rows="5" 
                            placeholder="Explain your problem in detail..."
                            required
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Issue Category</label>
                            <select 
                                v-model="form.category" 
                                class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition duration-150"
                            >
                                <option>Technical</option>
                                <option>Billing</option>
                                <option>General</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Priority Level</label>
                            <select 
                                v-model="form.priority" 
                                class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition duration-150"
                                :class="{'text-red-600 font-bold': form.priority === 'High', 'text-orange-500': form.priority === 'Medium'}"
                            >
                                <option class="text-gray-700">Low</option>
                                <option class="text-gray-700">Medium</option>
                                <option class="text-gray-700">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button 
                            type="submit" 
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 border border-transparent rounded-md font-bold text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50" 
                            :disabled="form.processing"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Submitting...' : 'Submit Support Ticket' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

input::placeholder, textarea::placeholder {
    color: #9ca3af;
    font-size: 0.9rem;
}

select {
    cursor: pointer;
}
</style>