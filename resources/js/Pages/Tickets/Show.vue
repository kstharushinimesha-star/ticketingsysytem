<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ 
    ticket: Object 
});

const commentForm = useForm({
    body: '',
    attachment: null,
});

const editingCommentId = ref(null);


const editForm = useForm({
    body: '',
});

const postComment = () => {
    commentForm.post(route('comments.store', props.ticket.id), {
        onSuccess: () => {
            commentForm.reset();
            if (document.getElementById('file-upload')) {
                document.getElementById('file-upload').value = null;
            }
        },
        forceFormData: true,
        preserveScroll: true, 
    });
};

const startEdit = (comment) => {
    editingCommentId.value = comment.id;
    editForm.body = comment.body;
};

const updateComment = (id) => {
    
    editForm.patch(route('comments.update', id), {
        onSuccess: () => {
            editingCommentId.value = null;
            editForm.reset();
        },
        preserveScroll: true,
    });
};

const deleteComment = (id) => {
    if (confirm('Are you sure you want to delete this comment?')) {
        
        router.delete(route('comments.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                
            }
        });
    }
};

const isImage = (path) => {
    return /\.(jpg|jpeg|png|webp|gif)$/i.test(path);
};
</script>

<template>
    <AppLayout title="Ticket Details">
        <div class="max-w-4xl mx-auto py-12 px-4 space-y-6">
            
            <div class="bg-white p-6 shadow-sm rounded-xl border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider">
                        {{ ticket.category }}
                    </span>
                    <span class="text-xs text-gray-400 italic">Opened on {{ ticket.created_at }}</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">{{ ticket.subject }}</h1>
                <p class="mt-4 text-gray-600 leading-relaxed">{{ ticket.description }}</p>
            </div>

            <div class="space-y-4">
                <h3 class="font-bold text-gray-700 flex items-center px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Responses ({{ ticket.comments ? ticket.comments.length : 0 }})
                </h3>

                <div v-for="comment in ticket.comments" :key="comment.id" class="p-5 bg-white rounded-xl border border-gray-200 shadow-sm transition hover:border-indigo-200">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-xs uppercase">
                                {{ comment.user.name.charAt(0) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ comment.user.name }}</p>
                                <p class="text-[10px] text-gray-400 italic">{{ comment.created_at }}</p>
                            </div>
                        </div>
                        
                       <div v-if="comment.user_id === $page.props.auth.user.id" class="flex space-x-3">
                                    <button @click="startEdit(comment)" >Edit</button>
                                    <button @click="deleteComment(comment.id)" >Delete</button>
                                </div>
                    </div>

                    <div v-if="editingCommentId === comment.id" class="mt-4">
                        <textarea v-model="editForm.body" class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500" rows="3"></textarea>
                        <div class="mt-2 flex space-x-2">
                            <button @click="updateComment(comment.id)" :disabled="editForm.processing" class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm hover:bg-indigo-700 disabled:opacity-50">
                                {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                            </button>
                            <button @click="editingCommentId = null" class="text-gray-500 text-xs font-bold px-4 py-1.5 hover:text-gray-700">Cancel</button>
                        </div>
                    </div>

                    <div v-else>
                        <p class="mt-3 text-gray-700 text-sm whitespace-pre-wrap leading-relaxed">{{ comment.body }}</p>
                        
                        <div v-if="comment.file_path" class="mt-4 pt-4 border-t border-gray-50">
                            <div v-if="isImage(comment.file_path)">
                                <a :href="'/storage/' + comment.file_path" target="_blank" class="inline-block">
                                    <img :src="'/storage/' + comment.file_path" class="max-w-xs rounded-lg border border-gray-200 shadow-sm hover:opacity-90 transition cursor-zoom-in" alt="Attachment" />
                                </a>
                            </div>
                            <div v-else>
                                <a :href="'/storage/' + comment.file_path" target="_blank" class="inline-flex items-center px-3 py-2 bg-gray-50 text-indigo-600 rounded-lg border border-gray-100 hover:bg-indigo-50 transition text-xs font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download Attachment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-indigo-100 shadow-sm">
                <form @submit.prevent="postComment">
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Write your response</label>
                        <textarea v-model="commentForm.body" class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm" rows="4" placeholder="Type your answer here..." required></textarea>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center">
                            <input type="file" @input="commentForm.attachment = $event.target.files[0]" id="file-upload" class="hidden" accept="image/*,.pdf,.doc,.docx,.zip" />
                            <label for="file-upload" class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl cursor-pointer transition text-xs font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                {{ commentForm.attachment ? commentForm.attachment.name : 'Add Attachment (Image/PDF)' }}
                            </label>
                        </div>

                        <button type="submit" :disabled="commentForm.processing" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-md transition-all active:scale-95 disabled:opacity-50">
                            {{ commentForm.processing ? 'Sending...' : 'Post Reply' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>