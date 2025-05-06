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
import type { Category } from '@/types/Category';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { toast } = useToast();

const props = defineProps<{ category: Category; isopen: boolean }>();

const onSubmit = async () => {
  router.delete(`/categories/${props.category.id}`, {
    onSuccess: () => {
      toast({
        title: t('deleteCategoryDialog.toast.success.title'),
        description: t(
          'deleteCategoryDialog.toast.success.description',
        ),
      });
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('deleteCategoryDialog.toast.error.title'),
        description: t(
          'deleteCategoryDialog.toast.error.description',
        ),
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
          {{ t('deleteCategoryDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('deleteCategoryDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <DialogFooter>
        <DialogClose as-child>
          <Button type="button" variant="secondary">
            {{ t('deleteCategoryDialog.cancelButton') }}
          </Button>
        </DialogClose>
        <DialogClose as-child>
          <Button
            type="submit"
            variant="destructive"
            class="ml-auto"
            @click="onSubmit"
          >
            {{ t('deleteCategoryDialog.submitButton') }}
          </Button>
        </DialogClose>
      </DialogFooter>
    </DialogContent>

    <Toaster />
  </Dialog>
</template>
