<script setup lang="ts">
import ArticleCard from '@/components/ArticleCard.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card'
import { Input } from '@/components/ui/input';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import type { Article } from '@/types/Article';
import type { Category } from '@/types/Category';
import type { User } from '@/types/User';
import { computed, ref } from 'vue';

interface Props {
  articles: Article[];
  categories: Category[];
  authors: User[];
}

const props = defineProps<Props>();

const searchQuery = ref('');
const selectedCategory = ref('');
const selectedAuthor = ref('');

const filteredArticles = computed(() => {
  return props.articles.filter((article) => {
    // Filter by search query
    const matchesSearch =
      searchQuery.value === '' ||
      article.title
        .toLowerCase()
        .includes(searchQuery.value.toLowerCase());

    // Filter by category
    const matchesCategory =
      selectedCategory.value === '' ||
      article.category.id.toString() === selectedCategory.value;

    // Filter by author
    const matchesAuthor =
      selectedAuthor.value === '' ||
      article.user.id.toString() === selectedAuthor.value;

    return matchesSearch && matchesCategory && matchesAuthor;
  });
});

const clearFilters = () => {
  searchQuery.value = '';
  selectedCategory.value = '';
  selectedAuthor.value = '';
};
</script>

<template>
  <section class="bg-background py-16">
    <Card class="p-4 m-8 shadow-sm">
      <div class="container mx-auto px-4">
        <h2 class="mb-8 text-center text-3xl font-bold">
          Latest Articles
        </h2>

        <!-- Filters -->
        <div
          class="mb-8 rounded-lg border border-border bg-card p-6 shadow-sm"
        >
          <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
              <Input
                v-model="searchQuery"
                placeholder="Search by title or keyword"
                class="w-full"
              />
            </div>
            <div>
              <Select v-model="selectedCategory">
                <SelectTrigger>
                  <SelectValue placeholder="Select category" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id.toString()"
                  >
                    {{ category.name }}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div>
              <Select v-model="selectedAuthor">
                <SelectTrigger>
                  <SelectValue placeholder="Select author" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem
                    v-for="author in authors"
                    :key="author.id"
                    :value="author.id.toString()"
                  >
                    {{ author.name }}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
          <div class="mt-4 flex justify-end">
            <Button variant="outline" @click="clearFilters">
              Clear Filters
            </Button>
          </div>
        </div>

        <!-- Articles List -->
        <div
          v-if="filteredArticles.length > 0"
          class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
        >
          <ArticleCard
            v-for="article in filteredArticles"
            :key="article.id"
            :article="article"
          />
        </div>
        <div v-else class="py-12 text-center">
          <p class="text-muted-foreground">
            No articles found matching your criteria.
          </p>
        </div>
      </div>
    </Card>
  </section>
</template>
