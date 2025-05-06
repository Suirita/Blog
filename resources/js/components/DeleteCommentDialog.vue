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
import type { Comment } from '@/types/Comment';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { toast } = useToast();

const props = defineProps<{ comment: Comment; isopen: boolean }>();

const onSubmit = async () => {
  router.delete(`/comments/${props.comment.id}`, {
    onSuccess: () => {
      toast({
        title: t('deleteCommentDialog.toast.success.title'),
        description: t(
          'deleteCommentDialog.toast.success.description',
        ),
      });
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('deleteCommentDialog.toast.error.title'),
        description: t('deleteCommentDialog.toast.error.description'),
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
          {{ t('deleteCommentDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('deleteCommentDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <DialogFooter>
        <DialogClose as-child>
          <Button type="button" variant="secondary">
            {{ t('deleteCommentDialog.cancelButton') }}
          </Button>
        </DialogClose>
        <DialogClose as-child>
          <Button
            type="submit"
            variant="destructive"
            class="ml-auto"
            @click="onSubmit"
          >
            {{ t('deleteCommentDialog.submitButton') }}
          </Button>
        </DialogClose>
      </DialogFooter>
    </DialogContent>

    <Toaster />
  </Dialog>
</template>
