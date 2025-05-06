<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Article } from '@/types/Article';
import { Category } from '@/types/Category';
import { Comment } from '@/types/Comment';
import { User } from '@/types/User';
import { Head } from '@inertiajs/vue3';
import {
  Eye,
  MessageCircle,
  Newspaper,
  Users,
} from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
  articles: Article[];
  categories: Category[];
  users: User[];
  comments: Comment[];
  isAdmin: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  articles: () => [],
  categories: () => [],
  users: () => [],
  comments: () => [],
  isAdmin: () => false,
});

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
];

// Metrics
const totalPosts = computed(() => props.articles.length);
const totalViews = computed(() =>
  props.articles.reduce((sum, article) => sum + article.views, 0),
);
const totalComments = computed(() => props.comments.length);
const totalUsers = computed(() => props.users.length);

// Recent items
const recentArticles = computed(() =>
  props.articles.slice(-5).reverse(),
);
</script>

<template>
  <Head title="Dashboard" />
  <AppLayout :breadcrumbs="breadcrumbs" :isAdmin="props.isAdmin">
    <div
      class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 xl:grid-cols-4"
    >
      <!-- Total Users -->
      <Card>
        <CardHeader
          class="flex flex-row items-center justify-between pb-2"
        >
          <CardTitle class="text-xl font-medium">
            Total Users
          </CardTitle>
          <Users class="h-5 w-5 text-muted-foreground" />
        </CardHeader>
        <CardContent>
          <div class="text-5xl font-bold">{{ totalUsers }}</div>
        </CardContent>
      </Card>

      <!-- Total Posts -->
      <Card>
        <CardHeader
          class="flex flex-row items-center justify-between pb-2"
        >
          <CardTitle class="text-xl font-medium">
            Total Posts
          </CardTitle>
          <Newspaper class="h-5 w-5 text-muted-foreground" />
        </CardHeader>
        <CardContent>
          <div class="text-5xl font-bold">{{ totalPosts }}</div>
        </CardContent>
      </Card>

      <!-- Total Views -->
      <Card>
        <CardHeader
          class="flex flex-row items-center justify-between pb-2"
        >
          <CardTitle class="text-xl font-medium">
            Total Views
          </CardTitle>
          <Eye class="h-5 w-5 text-muted-foreground" />
        </CardHeader>
        <CardContent>
          <div class="text-5xl font-bold">{{ totalViews }}</div>
        </CardContent>
      </Card>

      <!-- Total Comments -->
      <Card>
        <CardHeader
          class="flex flex-row items-center justify-between pb-2"
        >
          <CardTitle class="text-xl font-medium">
            Total Comments
          </CardTitle>
          <MessageCircle class="h-5 w-5 text-muted-foreground" />
        </CardHeader>
        <CardContent>
          <div class="text-5xl font-bold">{{ totalComments }}</div>
        </CardContent>
      </Card>
    </div>

    <!-- Latest Articles -->
    <Card class="m-4">
      <CardHeader class="flex flex-row justify-between pb-3">
        <CardTitle class="text-xl font-medium">
          Latest Articles
        </CardTitle>
        <Button>
          <a href="/articles"> Manage Articles </a>
        </Button>
      </CardHeader>
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Title</TableHead>
            <TableHead>Author</TableHead>
            <TableHead>Category</TableHead>
            <TableHead>Views</TableHead>
            <TableHead>Comments</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow
            v-for="article in recentArticles"
            :key="article.id"
          >
            <TableCell class="font-medium">
              {{ article.title }}
            </TableCell>
            <TableCell> {{ article.user.name }} </TableCell>
            <TableCell> {{ article.category.name }} </TableCell>
            <TableCell> {{ article.views }} </TableCell>
            <TableCell> {{ article.comments.length }} </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </Card>
  </AppLayout>
</template>
