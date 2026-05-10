<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];
    if (! photo) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation" class="modern-form-section">
        <template #title>
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                <span class="text-slate-900 font-bold text-xl tracking-tight">Personal Information</span>
            </div>
        </template>

        <template #description>
            <span class="text-slate-500 font-medium">Update your account's public identity and contact email address.</span>
        </template>

        <template #form>
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6">
                <input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">
                <InputLabel for="photo" value="Profile Picture" class="text-slate-700 font-bold mb-3 uppercase text-xs tracking-widest" />

                <div class="flex items-center gap-8 p-4 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                    <div class="relative group">
                        <div v-show="! photoPreview" class="photo-container">
                            <img :src="user.profile_photo_url" :alt="user.name" class="profile-img">
                        </div>

                        <div v-show="photoPreview" class="photo-container">
                            <span class="profile-img-preview" :style="'background-image: url(\'' + photoPreview + '\');'" />
                        </div>
                        
                        <div @click="selectNewPhoto" class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 flex items-center justify-center cursor-pointer transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <SecondaryButton type="button" class="action-btn select-btn" @click.prevent="selectNewPhoto">
                            Upload New Photo
                        </SecondaryButton>

                        <SecondaryButton v-if="user.profile_photo_path" type="button" class="action-btn remove-btn" @click.prevent="deletePhoto">
                            Remove Photo
                        </SecondaryButton>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-tight">JPG, PNG or GIF. Max 2MB.</p>
                    </div>
                </div>
                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4 mt-2">
                <InputLabel for="name" value="Display Name" class="text-slate-700 font-bold mb-1.5 ml-1" />
                <TextInput 
                    id="name" 
                    v-model="form.name" 
                    type="text" 
                    class="modern-input" 
                    required 
                    autocomplete="name" 
                    placeholder="Enter your full name"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4 mt-2">
                <InputLabel for="email" value="Email Address" class="text-slate-700 font-bold mb-1.5 ml-1" />
                <TextInput 
                    id="email" 
                    v-model="form.email" 
                    type="email" 
                    class="modern-input" 
                    required 
                    autocomplete="username" 
                    placeholder="email@example.com"
                />
                <InputError :message="form.errors.email" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-4 text-emerald-600 font-bold flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3z" clip-rule="evenodd" />
                </svg>
                Success
            </ActionMessage>

            <PrimaryButton class="save-button" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save Changes
            </PrimaryButton>
        </template>
    </FormSection>
</template>

<style scoped>
.modern-form-section {
    @apply bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-md;
}

.photo-container {
    @apply relative h-28 w-28 p-1 bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 overflow-hidden;
}

.profile-img {
    @apply rounded-[14px] h-full w-full object-cover transition duration-500;
}

.profile-img-preview {
    @apply block rounded-[14px] h-full w-full bg-cover bg-no-repeat bg-center;
}

.action-btn {
    @apply border-none px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 active:scale-95;
}

.select-btn {
    @apply bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-100;
}

.remove-btn {
    @apply bg-white text-red-500 ring-1 ring-red-100 hover:bg-red-50;
}

.modern-input {
    @apply block w-full px-5 py-3.5 bg-slate-50 border-transparent focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-2xl transition-all duration-200 text-slate-800 font-medium;
}

.save-button {
    @apply bg-slate-900 text-white px-10 py-4 rounded-2xl shadow-xl shadow-slate-200 hover:bg-black hover:-translate-y-0.5 transition-all duration-200 font-bold uppercase tracking-widest text-xs;
}

/* Jetstream default spacing reset */
:deep(.grid) {
    @apply gap-y-6;
}
</style>