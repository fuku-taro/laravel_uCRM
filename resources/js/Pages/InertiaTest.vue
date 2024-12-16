<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const newTitle = ref('')
const newContent = ref('')
</script>

<template>
<h1>Inertiaテストです。</h1>
<a href="/">aタグ経由です</a><br>
<Link href="/">Link経由です</Link><br>
<Link :href="route('inertia.index')">名前付きLink経由です</Link><br>
<Link :href="route('inertia.show', { id: 1 })">ルートパラメータのテストです</Link><br>

<div class="mb-8"></div>

<input type="text" name="newTitle" v-model="newTitle">{{ newTitle }}<br>
<input type="text" name="newContent" v-model="newContent">{{ newContent }}<br>
<Link  as="button"  method="POST" :href="route('inertia.store')"
:data="{
  title: newTitle,
  content: newContent
}">DBテスト</Link><br>
</template>