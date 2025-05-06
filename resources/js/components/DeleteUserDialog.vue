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
import type { User } from '@/types/User';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { toast } = useToast();

const props = defineProps<{ user: User; isopen: boolean }>();

const onSubmit = async () => {
  router.delete(`/users/${props.user.id}`, {
    onSuccess: () => {
      toast({
        title: t('deleteUserDialog.toast.success.title'),
        description: t(
          'deleteUserDialog.toast.success.description',
        ),
      });
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('deleteUserDialog.toast.error.title'),
        description: t('deleteUserDialog.toast.error.description'),
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
          {{ t('deleteUserDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('deleteUserDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <DialogFooter>
        <DialogClose as-child>
          <Button type="button" variant="secondary">
            {{ t('deleteUserDialog.cancelButton') }}
          </Button>
        </DialogClose>
        <DialogClose as-child>
          <Button
            type="submit"
            variant="destructive"
            class="ml-auto"
            @click="onSubmit"
          >
            {{ t('deleteUserDialog.submitButton') }}
          </Button>
        </DialogClose>
      </DialogFooter>
    </DialogContent>

    <Toaster />
  </Dialog>
</template>
