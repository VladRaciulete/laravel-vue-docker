<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const posts = ref([]);
const newPost = ref({ title: '', content: '' });

const fetchPosts = async () => {
    const response = await axios.get('/api/posts');
    posts.value = response.data;
};

const submitPost = async () => {
    try {
        await axios.post('/api/posts', newPost.value);
        newPost.value = { title: '', content: '' }; // Clear form
        await fetchPosts(); // Refresh list
    } catch (error) {
        alert("Validation failed: " + JSON.stringify(error.response.data.errors));
    }
};

onMounted(fetchPosts);
</script>

<template>
    <div class="p-10 bg-gray-50 min-h-screen">
        <div class="max-w-md mx-auto">
            <form @submit.prevent="submitPost" class="bg-white p-6 rounded shadow mb-8">
                <h2 class="font-bold mb-4">Create New Post</h2>
                <input v-model="newPost.title" placeholder="Title" class="w-full border p-2 mb-2 rounded" />
                <textarea v-model="newPost.content" placeholder="Content" class="w-full border p-2 mb-4 rounded"></textarea>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Save Post</button>
            </form>

            <div v-for="post in posts" :key="post.id" class="bg-white p-4 rounded shadow mb-2 border-l-4 border-blue-400">
                <h3 class="font-bold">{{ post.title }}</h3>
                <p class="text-gray-600">{{ post.content }}</p>
            </div>
        </div>
    </div>
</template>
