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
        <div class="max-w-2xl mx-auto py-12">
            <form @submit.prevent="submit" class="bg-white p-6 shadow rounded-lg">
                <div class="mb-4">
                    <label class="block text-gray-700">Subject</label>
                    <input v-model="form.subject" type="text" class="w-full border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Description</label>
                    <textarea v-model="form.description" class="w-full border-gray-300 rounded mt-1" rows="4" required></textarea>
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
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold" :disabled="form.processing">Submit Ticket</button>
            </form>
        </div>
    </AppLayout>
</template>