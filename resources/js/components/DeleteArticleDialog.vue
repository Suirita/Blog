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
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import type { Article } from '@/types/Article';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { toast } = useToast();

const props = defineProps<{ article: Article; isopen: boolean }>();

const onSubmit = async () => {
  router.delete(`/articles/${props.article.id}`, {
    onSuccess: () => {
      toast({
        title: t('deleteArticleDialog.toast.success.title'),
        description: t(
          'deleteArticleDialog.toast.success.description',
        ),
      });
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('deleteArticleDialog.toast.error.title'),
        description: t('deleteArticleDialog.toast.error.description'),
      });
      console.error(errors);
    },
  });
};
</script>

<template>
  <Dialog>
    <DialogContent>
      <DialogHeader>
        <DialogTitle>
          {{ t('deleteArticleDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('deleteArticleDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <DialogFooter>
        <DialogClose as-child>
          <Button type="button" variant="secondary">
            {{ t('deleteArticleDialog.cancelButton') }}
          </Button>
        </DialogClose>
        <DialogClose as-child>
          <Button
            type="submit"
            variant="destructive"
            class="ml-auto"
            @click="onSubmit"
          >
            {{ t('deleteArticleDialog.submitButton') }}
          </Button>
        </DialogClose>
      </DialogFooter>
    </DialogContent>

    <Toaster />
  </Dialog>
</template>
