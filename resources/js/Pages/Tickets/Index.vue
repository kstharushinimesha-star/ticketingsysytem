<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm, usePage, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps({ 
    tickets: Array,
    filters: Object 
});

const page = usePage();
const authUser = computed(() => page.props.auth?.user);


const category = ref(props.filters?.category || '');
const priority = ref(props.filters?.priority || '');

const form = useForm({});


watch([category, priority], ([newCategory, newPriority]) => {
    router.get(route('tickets.index'), { 
        category: newCategory, 
        priority: newPriority 
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
});

const resetFilters = () => {
    category.value = '';
    priority.value = '';
};

const deleteTicket = (id) => {
   if (confirm('Do you want to permanently delete this ticket?')) {
        form.delete(route('tickets.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <AppLayout title="My Tickets">
        <div class="py-12 bg-[#f8fafc] min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="flex items-center justify-between mb-8 px-2">
                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Support Tickets</h2>
                    <Link :href="route('tickets.create')" class="create-ticket-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Ticket
                    </Link>
                </div>

                <div class="mb-6 flex flex-wrap items-center gap-4 px-4 py-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <label class="text-sm font-bold text-slate-600">Category:</label>
                        <select v-model="category" class="rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 min-w-[160px] cursor-pointer">
                            <option value="">All Categories</option>
                            <option value="Technical">Technical</option>
                            <option value="Billing">Billing</option>
                            <option value="General">General</option>
                        </select>
                    </div>

                    <div class="flex items-center space-x-3">
                        <label class="text-sm font-bold text-slate-600">Priority:</label>
                        <select v-model="priority" class="rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 min-w-[160px] cursor-pointer">
                            <option value="">All Priorities</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>

                    <button v-if="category || priority" @click="resetFilters" class="ml-auto text-sm text-indigo-600 font-bold hover:text-indigo-800 underline transition px-2">
                        Reset All Filters
                    </button>
                </div>

                <div class="bg-white shadow-2xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-100">
                                    <th class="p-5 text-sm font-bold text-slate-600 uppercase tracking-wide">Subject</th>
                                    <th class="p-5 text-sm font-bold text-slate-600 uppercase tracking-wide">Submitted By</th>
                                    <th class="p-5 text-sm font-bold text-slate-600 uppercase tracking-wide">Category</th>
                                    <th class="p-5 text-sm font-bold text-slate-600 uppercase tracking-wide text-center">Priority</th>
                                    <th class="p-5 text-sm font-bold text-slate-600 uppercase tracking-wide text-center">Status</th>
                                    <th class="p-5 text-sm font-bold text-slate-600 uppercase tracking-wide text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-if="tickets.length === 0">
                                    <td colspan="6" class="p-10 text-center text-slate-400 font-medium">
                                        No tickets found matching your filters.
                                    </td>
                                </tr>
                                <tr v-for="ticket in tickets" :key="ticket.id" class="hover:bg-indigo-50/30 transition-colors duration-200">
                                    <td class="p-5 font-semibold text-slate-900">{{ ticket.subject }}</td>
                                    <td class="p-5">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 rounded-full bg-indigo-400 mr-2"></div>
                                            <span class="text-base font-medium text-indigo-700">{{ ticket.user?.name || 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-sm font-medium">
                                            {{ ticket.category }}
                                        </span>
                                    </td>
                                    <td class="p-5 text-center">
                                        <span :class="{
                                            'bg-rose-100 text-rose-700 border-rose-200': ticket.priority === 'High', 
                                            'bg-amber-100 text-amber-700 border-amber-200': ticket.priority === 'Medium', 
                                            'bg-sky-100 text-sky-700 border-sky-200': ticket.priority === 'Low'
                                        }" class="px-3 py-1 rounded-lg text-sm font-bold border uppercase tracking-wider">
                                            {{ ticket.priority }}
                                        </span>
                                    </td>
                                    <td class="p-5 text-center">
                                        <span class="px-4 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-sm font-bold inline-flex items-center">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></span>
                                            {{ ticket.status }}
                                        </span>
                                    </td>
                                    <td class="p-5 text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <Link :href="route('tickets.show', ticket.id)" class="action-btn text-blue-600 hover:bg-blue-50" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </Link>

                                            <template v-if="authUser">
                                                <Link v-if="authUser.role === 'admin' || authUser.id === ticket.user_id" 
                                                      :href="route('tickets.edit', ticket.id)" 
                                                      class="action-btn text-green-600 hover:bg-green-50" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </Link>

                                                <button v-if="authUser.role === 'admin' || authUser.id === ticket.user_id"
                                                        @click="deleteTicket(ticket.id)" 
                                                        class="action-btn text-red-600 hover:bg-red-50" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.create-ticket-btn {
    @apply bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-2xl shadow-lg shadow-indigo-200 flex items-center transition-all active:scale-95 font-bold;
}

.action-btn {
    @apply p-2 rounded-xl transition-all duration-300 flex items-center justify-center border border-transparent;
}

.action-btn:hover {
    @apply border-current shadow-sm;
}

.action-btn:hover svg {
    transform: scale(1.1);
}

select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
</style>