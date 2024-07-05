<script setup>
import Header from "@/Components/Frontend/Header.vue";
import Footer from "@/Components/Frontend/Footer.vue";
</script>
<template>
    <div class="bg-slate-200 dark:bg-slate-900">
        <!-- Head -->
        <Header />
        <main class="min-h-screen">
            <slot />
        </main>
        <!-- Footer -->
        <Footer></Footer>
    </div>
</template>
