<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({ tickets: Array });
</script>

<template>
    <AppLayout title="My Tickets">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-between mb-6">
                        <h2 class="text-xl font-semibold">Support Tickets</h2>
                        <Link :href="route('tickets.create')" class="bg-blue-600 text-white px-4 py-2 rounded">Create Ticket</Link>
                    </div>
                    
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3">Subject</th>
                                <th class="p-3">Category</th>
                                <th class="p-3">Priority</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ticket in tickets" :key="ticket.id" class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ ticket.subject }}</td>
                                <td class="p-3">{{ ticket.category }}</td>
                                <td class="p-3">
                                    <span :class="{'text-red-600': ticket.priority === 'High', 'text-yellow-600': ticket.priority === 'Medium'}">{{ ticket.priority }}</span>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">{{ ticket.status }}</span>
                                </td>
                                <td class="p-3 space-x-2">
                                <Link :href="route('tickets.show', ticket.id)" class="text-blue-500 underline">View</Link>
                                <Link :href="route('tickets.edit', ticket.id)" class="text-green-600 underline">Edit</Link>
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>