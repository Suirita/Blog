<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogFooter,
  DialogHeader,
} from '@/components/ui/dialog';
import type { User } from '@/types/User';
import { useI18n } from 'vue-i18n';

const props = defineProps<{
  user: User;
  isopen: boolean;
}>();

const { t } = useI18n();
</script>

<template>
  <Dialog>
    <DialogContent class="max-h-[80vh] min-w-[80vw] overflow-y-auto">
      <DialogHeader>
        <!-- User basic information -->
        <h2 class="text-2xl font-bold">{{ props.user.name }}</h2>
        <p>
          <span class="font-semibold">
            {{ t('viewUserDialog.email') }}:
          </span>
          <span class="text-sm text-gray-600">
            {{ props.user.email }}
          </span>
        </p>
        <p>
          <span class="font-semibold">
            {{ t('viewUserDialog.role') }}:
          </span>
          <span class="text-sm text-gray-600">
            {{ props.user.roles[0]?.name }}
          </span>
        </p>
      </DialogHeader>

      <!-- User Articles Section -->
      <section
        v-if="props.user.articles && props.user.articles.length"
        class="mt-6"
      >
        <h3 class="mb-2 text-lg font-semibold">
          {{ t('viewUserDialog.articles') }}
        </h3>
        <ul class="space-y-2">
          <li
            v-for="article in props.user.articles"
            :key="article.id"
            class="rounded border-l-4 border-primary p-3 shadow"
          >
            <h4 class="text-lg font-medium">{{ article.title }}</h4>
          </li>
        </ul>
      </section>

      <!-- User Comments Section -->
      <section
        v-if="props.user.comments && props.user.comments.length"
        class="mt-6"
      >
        <h3 class="mb-2 text-lg font-semibold">
          {{ t('viewUserDialog.comments') }}
        </h3>
        <ul class="space-y-3">
          <li
            v-for="comment in props.user.comments"
            :key="comment.id"
            class="rounded border p-3 shadow"
          >
            <p class="text-sm text-gray-700">{{ comment.content }}</p>
          </li>
        </ul>
      </section>

      <!-- Dialog Footer with Cancel Button -->
      <DialogFooter class="mt-6">
        <DialogClose as-child>
          <Button variant="secondary">
            {{ t('viewUserDialog.cancelButton') }}
          </Button>
        </DialogClose>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
