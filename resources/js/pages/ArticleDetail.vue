<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/components/ui/toast';
import type { Article } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Eye } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

interface Props {
  article: Article;
}

const props = defineProps<Props>();
const article = props.article;
const { toast } = useToast();

const isAuthenticated = ref(false);
const commentForm = ref({ content: '' });
const isSubmitting = ref(false);

// fallback if no hero image on article
const placeholderImage = '/images/placeholder-16-9.png';

const getInitials = (name: string) =>
  name
    .split(' ')
    .map((p) => p[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);

const userInitials = computed(() => {
  // you can pull user info from your auth store
  const name = localStorage.getItem('userName') || '??';
  return getInitials(name);
});

// format functions for dates
const formattedDate = computed(() => {
  const d = new Date(
    article.updated_at || article.created_at || Date.now(),
  );
  return d.toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
});
const formatCommentDate = (comment: any) => {
  const d = new Date(comment.created_at || Date.now());
  return d.toLocaleDateString(undefined, {
    month: 'short',
    day: 'numeric',
  });
};

const submitComment = async () => {
  if (!commentForm.value.content.trim()) {
    return toast({
      title: 'Error',
      description: 'Please enter a comment',
      variant: 'destructive',
    });
  }
  isSubmitting.value = true;

  try {
    // replace with your actual API call:
    // await axios.post(`/api/articles/${article.id}/comments`, { content: commentForm.value.content });
    await new Promise((r) => setTimeout(r, 1000));

    toast({
      title: 'Success',
      description: 'Your comment has been posted',
      variant: 'default',
    });
    commentForm.value.content = '';
    // optionally: refresh via Inertia.reload() or push new comment into article.comments
  } catch {
    toast({
      title: 'Error',
      description: 'There was a problem posting your comment',
      variant: 'destructive',
    });
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => {
  isAuthenticated.value = localStorage.getItem('loggedIn') === 'true';
});
</script>

<template>
  <div class="flex min-h-screen flex-col">
    <Navbar />

    <main class="flex-grow py-8">
      <div class="container mx-auto px-4">
        <div class="mx-auto max-w-3xl">
          <!-- Article Header -->
          <div class="mb-6">
            <Badge class="mb-4">{{ article.category.name }}</Badge>
            <h1 class="mb-4 text-4xl font-bold">
              {{ article.title }}
            </h1>

            <div class="mb-6 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <Avatar>
                  <AvatarFallback>{{
                    getInitials(article.user.name)
                  }}</AvatarFallback>
                </Avatar>
                <div>
                  <div class="font-medium">
                    {{ article.user.name }}
                  </div>
                  <div class="text-sm text-muted-foreground">
                    {{ formattedDate }}
                  </div>
                </div>
              </div>
              <div
                class="flex items-center gap-2 text-muted-foreground"
              >
                <Eye class="h-5 w-5" />
                <span>{{ article.views }} views</span>
              </div>
            </div>
          </div>

          <!-- Article Content -->
          <div
            class="prose prose-lg mb-12 max-w-none"
            v-html="article.content"
          />

          <!-- Comments Section -->
          <div class="mt-8 border-t pt-8">
            <h3 class="mb-6 text-2xl font-bold">
              Comments ({{ article.comments.length }})
            </h3>

            <!-- Comment Form -->
            <div v-if="isAuthenticated" class="mb-8">
              <div class="mb-4 flex gap-4">
                <Avatar>
                  <AvatarFallback>{{ userInitials }}</AvatarFallback>
                </Avatar>
                <Textarea
                  v-model="commentForm.content"
                  class="flex-1 rounded-md border p-3 focus:outline-none focus:ring-2 focus:ring-primary"
                  placeholder="Add a comment..."
                  rows="3"
                />
              </div>
              <div class="flex justify-end">
                <Button
                  :disabled="isSubmitting"
                  @click="submitComment"
                >
                  {{
                    isSubmitting ? 'Submitting...' : 'Post Comment'
                  }}
                </Button>
              </div>
            </div>
            <div
              v-else
              class="mb-8 rounded-lg bg-muted p-4 text-center"
            >
              <p class="mb-2">Please log in to leave a comment.</p>
              <Button asChild>
                <Link href="/login">Login</Link>
              </Button>
            </div>

            <!-- Comments List -->
            <div class="space-y-6">
              <div
                v-for="comment in article.comments"
                :key="comment.id"
                class="flex gap-4"
              >
                <Avatar>
                  <AvatarFallback>{{
                    getInitials(comment.user.name)
                  }}</AvatarFallback>
                </Avatar>
                <div class="flex-1">
                  <div class="rounded-lg bg-muted p-4">
                    <div
                      class="mb-2 flex items-center justify-between"
                    >
                      <span class="font-medium">{{
                        comment.user.name
                      }}</span>
                      <span class="text-sm text-muted-foreground">
                        {{ formatCommentDate(comment) }}
                      </span>
                    </div>
                    <p>{{ comment.content }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <Footer />
  </div>
</template>
