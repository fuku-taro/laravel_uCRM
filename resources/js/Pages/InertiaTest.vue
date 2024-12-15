<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
<h1>Inertiaテストです。</h1>
<a href="/">aタグ経由です</a><br>
<Link href="/">Link経由です</Link><br>
<Link :href="route('inertia.index')">名前付きLink経由です</Link><br>
</template>