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
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import type { Article } from '@/types/Article';
import { router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { watch } from 'vue';
import { useI18n } from 'vue-i18n';
import * as z from 'zod';

const { t } = useI18n();
const { toast } = useToast();

const props = defineProps<{
  article: Article;
  categories: Array<{ id: number; name: string }>;
  isopen: boolean;
}>();

watch(
  () => props.isopen,
  (isOpen) => {
    if (isOpen) {
      form.resetForm({
        values: {
          title: props.article.title,
          category: props.article.category.id,
          content: props.article.content,
        },
      });
    }
  },
);

const formSchema = toTypedSchema(
  z.object({
    title: z
      .string({
        message: t('editArticleDialog.formSchema.title.string'),
      })
      .min(5, {
        message: t('editArticleDialog.formSchema.title.min'),
      })
      .max(100, {
        message: t('editArticleDialog.formSchema.title.max'),
      })
      .regex(/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?']+$/, {
        message: t('editArticleDialog.formSchema.title.regex'),
      }),
    category: z.number({
      message: t('editArticleDialog.formSchema.category'),
    }),
    content: z
      .string({
        message: t('editArticleDialog.formSchema.content.string'),
      })
      .min(5, {
        message: t('editArticleDialog.formSchema.content.min'),
      })
      .regex(/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?']+$/, {
        message: t('editArticleDialog.formSchema.content.regex'),
      }),
  }),
);

const form = useForm({
  validationSchema: formSchema,
  initialValues: {
    title: props.article.title,
    category: props.article.category.id,
    content: props.article.content,
  },
});

const onSubmit = form.handleSubmit(async (values) => {
  router.put(`/articles/${props.article.id}`, values, {
    onSuccess: () => {
      toast({
        title: t('editArticleDialog.toast.success.title'),
        description: t('editArticleDialog.toast.success.description'),
      });
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('editArticleDialog.toast.error.title'),
        description: t('editArticleDialog.toast.error.description'),
      });
      console.error(errors);
    },
  });
});
</script>

<template>
  <Dialog>
    <DialogContent class="max-h-[80vh] min-w-[80vw] overflow-y-auto">
      <DialogHeader>
        <DialogTitle>
          {{ t('editArticleDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('editArticleDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <form @submit="onSubmit" class="mt-2 flex flex-col gap-4">
        <FormField v-slot="{ componentField }" name="title">
          <FormItem>
            <FormLabel>
              {{ t('editArticleDialog.titleInputLabel') }}
            </FormLabel>
            <FormControl>
              <Input
                type="text"
                v-bind="componentField"
                :placeholder="
                  t('editArticleDialog.titleInputPlaceholder')
                "
              />
            </FormControl>
            <FormDescription>
              {{ t('editArticleDialog.titleInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="category">
          <FormItem>
            <FormLabel>
              {{ t('editArticleDialog.categoryInputLabel') }}
            </FormLabel>
            <FormControl>
              <Select v-bind="componentField">
                <SelectTrigger>
                  <SelectValue
                    :placeholder="
                      t('editArticleDialog.categoryInputPlaceholder')
                    "
                  />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>
                      {{ t('editArticleDialog.categorySelectLabel') }}
                    </SelectLabel>
                    <div
                      v-for="category in props.categories"
                      :key="category.id"
                    >
                      <SelectItem :value="category.id">
                        {{ category.name }}
                      </SelectItem>
                    </div>
                  </SelectGroup>
                </SelectContent>
              </Select>
            </FormControl>
            <FormDescription>
              {{ t('editArticleDialog.categoryInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="content">
          <FormItem>
            <FormLabel>
              {{ t('editArticleDialog.contentInputLabel') }}
            </FormLabel>
            <FormControl>
              <Textarea
                v-bind="componentField"
                :placeholder="
                  t('editArticleDialog.contentInputPlaceholder')
                "
              />
            </FormControl>
            <FormDescription>
              {{ t('editArticleDialog.contentInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <DialogFooter>
          <DialogClose as-child>
            <Button type="button" variant="secondary">
              {{ t('editArticleDialog.cancelButton') }}
            </Button>
          </DialogClose>
          <Button type="submit" class="ml-auto">
            {{ t('editArticleDialog.submitButton') }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>

    <Toaster />
  </Dialog>
</template>
