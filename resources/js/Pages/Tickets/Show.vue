<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({ ticket: Object });

const commentForm = useForm({
    body: '',
});

const postComment = () => {
    commentForm.post(route('comments.store', props.ticket.id), {
        onSuccess: () => commentForm.reset(),
    });
};
</script>

<template>
    <AppLayout title="Ticket Details">
        <div class="max-w-4xl mx-auto py-12 space-y-6">
            <div class="bg-white p-6 shadow rounded-lg">
                <h1 class="text-2xl font-bold">{{ ticket.subject }}</h1>
                <p class="mt-4 text-gray-600">{{ ticket.description }}</p>
            </div>

            <div class="bg-gray-50 p-6 shadow rounded-lg">
                <h3 class="font-semibold mb-4">Responses</h3>
                <div v-for="comment in ticket.comments" :key="comment.id" class="mb-4 p-4 bg-white rounded border">
                    <p class="text-sm font-bold text-blue-600">{{ comment.user.name }} <span class="text-xs text-gray-400 italic">{{ comment.created_at }}</span></p>
                    <p class="mt-1 text-gray-700">{{ comment.body }}</p>
                </div>

                <form @submit.prevent="postComment" class="mt-6">
                    <textarea v-model="commentForm.body" class="w-full border-gray-300 rounded" placeholder="Write a reply..." required></textarea>
                    <button class="mt-2 bg-green-600 text-white px-4 py-2 rounded">Post Reply</button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>