<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import type { Article } from '@/types/Article';
import { useI18n } from 'vue-i18n';

const props = defineProps<{
  article: Article;
  isopen: boolean;
}>();

const { t } = useI18n();
</script>

<template>
  <Dialog>
    <DialogContent class="max-h-[80vh] min-w-[80vw] overflow-y-auto">
      <DialogHeader>
        <!-- Article title as dialog title -->
        <DialogTitle>{{ props.article.title }}</DialogTitle>
        <DialogDescription>
          <div class="space-y-1">
            <!-- Key article information -->
            <p>
              <strong>{{ t('viewArticleDialog.author') }}:</strong>
              {{ props.article.user.name }}
            </p>
            <p>
              <strong>{{ t('viewArticleDialog.category') }}:</strong>
              {{ props.article.category.name }}
            </p>
            <p>
                <strong>{{ t('viewArticleDialog.views') }}:</strong>
                {{ props.article.views }}
            </p>
          </div>
        </DialogDescription>
      </DialogHeader>

      <!-- Article content -->
      <div class="mt-4">
        <p class="whitespace-pre-wrap">
          {{ props.article.content }}
        </p>
      </div>

      <!-- Comments Section -->
      <div v-if="props.article.comments.length > 0" class="mt-4">
        <p>{{ t('viewArticleDialog.comments') }}:</p>
        <div
          v-for="comment in props.article.comments"
          :key="comment.id"
          class="mb-4 border-b pb-2"
        >
          <p class="font-semibold">{{ comment.user.name }}:</p>
          <p class="whitespace-pre-wrap">
            {{ comment.content }}
          </p>
        </div>
      </div>
      <div v-else class="mt-4 text-center">
        <p class="text-lg font-medium text-gray-600">
          {{ t('viewArticleDialog.noComments') }}
        </p>
      </div>

      <!-- Dialog footer with cancel button -->
      <DialogFooter>
        <DialogClose as-child>
          <Button variant="secondary">
            {{ t('viewArticleDialog.cancelButton') }}
          </Button>
        </DialogClose>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
