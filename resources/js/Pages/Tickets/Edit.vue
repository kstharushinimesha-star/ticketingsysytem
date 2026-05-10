<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({ ticket: Object });
const user = usePage().props.auth.user;

const form = useForm({
    subject: props.ticket.subject,
    description: props.ticket.description,
    category: props.ticket.category,
    priority: props.ticket.priority,
    status: props.ticket.status,
});

const submit = () => {
    form.put(route('tickets.update', props.ticket.id));
};
</script>

<template>
    <AppLayout title="Edit Ticket">
        <div class="max-w-2xl mx-auto py-12 px-4">
            <div class="bg-white p-6 shadow rounded-lg">
                <h2 class="text-xl font-bold mb-4">Edit Ticket #{{ ticket.id }}</h2>
                
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block text-gray-700">Subject</label>
                        <input v-model="form.subject" type="text" class="w-full border-gray-300 rounded mt-1">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Description</label>
                        <textarea v-model="form.description" class="w-full border-gray-300 rounded mt-1" rows="4"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700">Category</label>
                            <select v-model="form.category" class="w-full border-gray-300 rounded">
                                <option>Technical</option>
                                <option>Billing</option>
                                <option>General</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700">Priority</label>
                            <select v-model="form.priority" class="w-full border-gray-300 rounded">
                                <option>Low</option>
                                <option>Medium</option>
                                <option>High</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="user.role === 'admin'" class="mb-6">
                        <label class="block text-gray-700 font-bold text-red-600">Ticket Status (Admin Only)</label>
                        <select v-model="form.status" class="w-full border-red-300 bg-red-50 rounded mt-1">
                            <option>Open</option>
                            <option>In Progress</option>
                            <option>Resolved</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold" :disabled="form.processing">
                            Update Ticket
                        </button>
                        <button type="button" @click="window.history.back()" class="text-gray-600 underline">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>