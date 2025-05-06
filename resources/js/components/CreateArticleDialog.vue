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
    title: z
      .string({
        message: t('createArticleDialog.formSchema.title.string'),
      })
      .min(5, {
        message: t('createArticleDialog.formSchema.title.min'),
      })
      .max(100, {
        message: t('createArticleDialog.formSchema.title.max'),
      })
      .regex(/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?']+$/, {
        message: t('createArticleDialog.formSchema.title.regex'),
      }),
    category: z.number({
      message: t('createArticleDialog.formSchema.category'),
    }),
    content: z
      .string({
        message: t('createArticleDialog.formSchema.content.string'),
      })
      .min(5, {
        message: t('createArticleDialog.formSchema.content.min'),
      })
      .regex(/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?']+$/, {
        message: t('createArticleDialog.formSchema.content.regex'),
      }),
  }),
);

const form = useForm({
  validationSchema: formSchema,
});

const onSubmit = form.handleSubmit((values) => {
  router.post('/articles', values, {
    onSuccess: () => {
      toast({
        title: t('createArticleDialog.toast.success.title'),
        description: t(
          'createArticleDialog.toast.success.description',
        ),
      });
      form.resetForm();
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('createArticleDialog.toast.error.title'),
        description: t('createArticleDialog.toast.error.description'),
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
          {{ t('createArticleDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('createArticleDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <form
        @submit.prevent="onSubmit"
        class="mt-2 flex flex-col gap-4"
      >
        <!-- Title Field -->
        <FormField v-slot="{ componentField }" name="title">
          <FormItem>
            <FormLabel>
              {{ t('createArticleDialog.titleInputLabel') }}
            </FormLabel>
            <FormControl>
              <Input
                type="text"
                :placeholder="
                  t('createArticleDialog.titleInputPlaceholder')
                "
                v-bind="componentField"
              />
            </FormControl>
            <FormDescription>
              {{ t('createArticleDialog.titleInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Category Field -->
        <FormField v-slot="{ componentField }" name="category">
          <FormItem>
            <FormLabel>
              {{ t('createArticleDialog.categoryInputLabel') }}
            </FormLabel>
            <FormControl>
              <Select v-bind="componentField">
                <SelectTrigger>
                  <SelectValue
                    :placeholder="
                      t(
                        'createArticleDialog.categoryInputPlaceholder',
                      )
                    "
                  />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>
                      {{
                        t('createArticleDialog.categorySelectLabel')
                      }}
                    </SelectLabel>
                    <div
                      v-for="category in categories"
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
              {{ t('createArticleDialog.categoryInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Content Field using TipTap -->
        <FormField v-slot="{ componentField }" name="content">
          <FormItem>
            <FormLabel>
              {{ t('createArticleDialog.contentInputLabel') }}
            </FormLabel>
            <FormControl>
              <Textarea
                :placeholder="
                  t('createArticleDialog.contentInputPlaceholder')
                "
                v-bind="componentField"
              />
            </FormControl>
            <FormDescription>
              {{ t('createArticleDialog.contentInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Form Actions -->
        <DialogFooter>
          <DialogClose as-child>
            <Button type="button" variant="secondary">
              {{ t('createArticleDialog.cancelButton') }}
            </Button>
          </DialogClose>
          <Button type="submit" class="ml-auto">
            {{ t('createArticleDialog.submitButton') }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
    <Toaster />
  </Dialog>
</template>
