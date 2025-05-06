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
import {
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import { router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { useI18n } from 'vue-i18n';
import * as z from 'zod';

const { t } = useI18n();
const { toast } = useToast();

defineProps<{
  categories: Array<{ id: number; name: string }>;
  isopen: boolean;
}>();

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({
        message: t('createCategoryDialog.formSchema.name.string'),
      })
      .min(3, {
        message: t('createCategoryDialog.formSchema.name.min'),
      })
      .max(25, {
        message: t('createCategoryDialog.formSchema.name.max'),
      })
      .regex(/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?']+$/, {
        message: t('createCategoryDialog.formSchema.name.regex'),
      }),
  }),
);

const form = useForm({
  validationSchema: formSchema,
});

const onSubmit = form.handleSubmit((values) => {
  router.post('/categories', values, {
    onSuccess: () => {
      toast({
        title: t('createCategoryDialog.toast.success.title'),
        description: t(
          'createCategoryDialog.toast.success.description',
        ),
      });
      form.resetForm();
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('createCategoryDialog.toast.error.title'),
        description: t(
          'createCategoryDialog.toast.error.description',
        ),
      });
      console.error(errors);
    },
  });
});
</script>

<template>
  <Dialog>
    <DialogContent class="max-h-[80vh] overflow-y-auto">
      <DialogHeader>
        <DialogTitle>
          {{ t('createCategoryDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('createCategoryDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <form
        @submit.prevent="onSubmit"
        class="mt-2 flex flex-col gap-4"
      >
        <!-- name Field -->
        <FormField v-slot="{ componentField }" name="name">
          <FormItem>
            <FormLabel>
              {{ t('createCategoryDialog.nameInputLabel') }}
            </FormLabel>
            <FormControl>
              <Input
                type="text"
                :placeholder="
                  t('createCategoryDialog.nameInputPlaceholder')
                "
                v-bind="componentField"
              />
            </FormControl>
            <FormDescription>
              {{ t('createCategoryDialog.nameInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Form Actions -->
        <DialogFooter>
          <DialogClose as-child>
            <Button type="button" variant="secondary">
              {{ t('createCategoryDialog.cancelButton') }}
            </Button>
          </DialogClose>
          <Button type="submit" class="ml-auto">
            {{ t('createCategoryDialog.submitButton') }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
    <Toaster />
  </Dialog>
</template>
