<script setup lang="ts">
import AboutSection from '@/components/AboutSection.vue';
import ArticlesSection from '@/components/ArticlesSection.vue';
import ContactSection from '@/components/ContactSection.vue';
import Footer from '@/components/Footer.vue';
import HeroSection from '@/components/HeroSection.vue';
import Navbar from '@/components/Navbar.vue';
import type { Article } from '@/types/Article';
import type { Category } from '@/types/Category';
import type { User } from '@/types/User';

interface Props {
  articles: Article[];
  categories: Category[];
  users: User[];
  isAuthenticated: boolean;
}

const props = defineProps<Props>();

console.log(props.isAuthenticated);
</script>

<template>
  <div class="flex min-h-screen flex-col">
    <Navbar :isAuthenticated="isAuthenticated" />
    <main class="flex-grow">
      <HeroSection />
      <ArticlesSection
        :articles="articles"
        :categories="categories"
        :authors="users"
        :isAuthenticated="isAuthenticated"
      />
      <AboutSection />
      <ContactSection />
    </main>
    <Footer />
  </div>
</template>
