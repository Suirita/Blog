<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import type { Article } from '@/types/Article';
import { Link } from '@inertiajs/vue3';
import { Eye } from 'lucide-vue-next';

interface Props {
  article: Article;
}

defineProps<Props>();
</script>

<template>
  <Card class="flex h-full flex-col shadow-md">
    <CardHeader>
      <CardTitle
        class="line-clamp-2 transition-colors hover:text-primary"
      >
        <Link :href="`/articles/${article.id}`">
          {{ article.title }}
        </Link>
      </CardTitle>
    </CardHeader>
    <CardContent class="flex-grow">
      <div
        class="mb-2 flex items-center gap-2 text-sm text-muted-foreground"
      >
        <Badge>{{ article.category.name }}</Badge>
      </div>
      <div
        class="flex items-center gap-2 text-sm text-muted-foreground"
      >
        <span>By {{ article.user.name }}</span>
      </div>
      <span class="flex items-center gap-1">
        {{ article.views }}
        <Eye class="mr-1 h-4 w-4" />
      </span>
    </CardContent>
    <CardFooter>
      <Button variant="outline" class="w-full" asChild>
        <Link :href="`/articles/${article.id}`">Read</Link>
      </Button>
    </CardFooter>
  </Card>
</template>
