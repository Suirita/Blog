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
import type { Category } from '@/types/Category';
import { router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { watch } from 'vue';
import { useI18n } from 'vue-i18n';
import * as z from 'zod';

const { t } = useI18n();
const { toast } = useToast();

const props = defineProps<{
  category: Category;
  isopen: boolean;
}>();

watch(
  () => props.isopen,
  (isOpen) => {
    if (isOpen) {
      form.resetForm({
        values: {
          name: props.category.name,
        },
      });
    }
  },
);

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({
        message: t('editCategoryDialog.formSchema.name.string'),
      })
      .min(3, {
        message: t('editCategoryDialog.formSchema.name.min'),
      })
      .max(25, {
        message: t('editCategoryDialog.formSchema.name.max'),
      })
      .regex(/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?']+$/, {
        message: t('editCategoryDialog.formSchema.name.regex'),
      }),
  }),
);

const form = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.category.name,
  },
});

const onSubmit = form.handleSubmit(async (values) => {
  router.put(`/categories/${props.category.id}`, values, {
    onSuccess: () => {
      toast({
        title: t('editCategoryDialog.toast.success.title'),
        description: t(
          'editCategoryDialog.toast.success.description',
        ),
      });
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('editCategoryDialog.toast.error.title'),
        description: t('editCategoryDialog.toast.error.description'),
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
          {{ t('editCategoryDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('editCategoryDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <form @submit="onSubmit" class="mt-2 flex flex-col gap-4">
        <FormField v-slot="{ componentField }" name="name">
          <FormItem>
            <FormLabel>
              {{ t('editCategoryDialog.nameInputLabel') }}
            </FormLabel>
            <FormControl>
              <Input
                type="text"
                v-bind="componentField"
                :placeholder="
                  t('editCategoryDialog.nameInputPlaceholder')
                "
              />
            </FormControl>
            <FormDescription>
              {{ t('editCategoryDialog.nameInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <DialogFooter>
          <DialogClose as-child>
            <Button type="button" variant="secondary">
              {{ t('editCategoryDialog.cancelButton') }}
            </Button>
          </DialogClose>
          <Button type="submit" class="ml-auto">
            {{ t('editCategoryDialog.submitButton') }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>

    <Toaster />
  </Dialog>
</template>
