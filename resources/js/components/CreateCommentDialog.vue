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
import type { User } from '@/types/User';
import { router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { useI18n } from 'vue-i18n';
import * as z from 'zod';

const { t } = useI18n();
const { toast } = useToast();

defineProps<{
  articles: Article[];
  users: User[];
  isopen: boolean;
}>();

const formSchema = toTypedSchema(
  z.object({
    article: z.number({
      message: t('createCommentDialog.formSchema.article'),
    }),
    user: z.number({
      message: t('createCommentDialog.formSchema.user'),
    }),
    content: z
      .string({
        message: t('createCommentDialog.formSchema.content.string'),
      })
      .min(5, {
        message: t('createCommentDialog.formSchema.content.min'),
      })
      .max(100, {
        message: t('createCommentDialog.formSchema.content.max'),
      })
      .regex(/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?']+$/, {
        message: t('createCommentDialog.formSchema.content.regex'),
      }),
  }),
);

const form = useForm({
  validationSchema: formSchema,
});

const onSubmit = form.handleSubmit((values) => {
  router.post('/comments', values, {
    onSuccess: () => {
      toast({
        title: t('createCommentDialog.toast.success.title'),
        description: t(
          'createCommentDialog.toast.success.description',
        ),
      });
      form.resetForm();
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('createCommentDialog.toast.error.title'),
        description: t('createCommentDialog.toast.error.description'),
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
          {{ t('createCommentDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('createCommentDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <form
        @submit.prevent="onSubmit"
        class="mt-2 flex flex-col gap-4"
      >
        <!-- Artilce Field -->
        <FormField v-slot="{ componentField }" name="article">
          <FormItem>
            <FormLabel>
              {{ t('createCommentDialog.articleInputLabel') }}
            </FormLabel>
            <FormControl>
              <Select v-bind="componentField">
                <SelectTrigger>
                  <SelectValue
                    :placeholder="
                      t('createCommentDialog.articleInputPlaceholder')
                    "
                  />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>
                      {{
                        t('createCommentDialog.articleSelectLabel')
                      }}
                    </SelectLabel>
                    <div
                      v-for="article in articles"
                      :key="article.id"
                    >
                      <SelectItem :value="article.id">
                        {{ article.title }}
                      </SelectItem>
                    </div>
                  </SelectGroup>
                </SelectContent>
              </Select>
            </FormControl>
            <FormDescription>
              {{ t('createCommentDialog.articleInputDescription') }}
            </FormDescription>
          </FormItem>
        </FormField>

        <!-- User Field -->
        <FormField v-slot="{ componentField }" name="user">
          <FormItem>
            <FormLabel>
              {{ t('createCommentDialog.userInputLabel') }}
            </FormLabel>
            <FormControl>
              <Select v-bind="componentField">
                <SelectTrigger>
                  <SelectValue
                    :placeholder="
                      t('createCommentDialog.userInputPlaceholder')
                    "
                  />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>
                      {{ t('createCommentDialog.userSelectLabel') }}
                    </SelectLabel>
                    <div v-for="user in users" :key="user.id">
                      <SelectItem :value="user.id">
                        {{ user.name }}
                      </SelectItem>
                    </div>
                  </SelectGroup>
                </SelectContent>
              </Select>
            </FormControl>
            <FormDescription>
              {{ t('createCommentDialog.userInputDescription') }}
            </FormDescription>
          </FormItem>
        </FormField>

        <!-- Content Field -->
        <FormField v-slot="{ componentField }" name="content">
          <FormItem>
            <FormLabel>
              {{ t('createCommentDialog.contentInputLabel') }}
            </FormLabel>
            <FormControl>
              <Textarea
                :placeholder="
                  t('createCommentDialog.contentInputPlaceholder')
                "
                v-bind="componentField"
              />
            </FormControl>
            <FormDescription>
              {{ t('createCommentDialog.contentInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Form Actions -->
        <DialogFooter>
          <DialogClose as-child>
            <Button type="button" variant="secondary">
              {{ t('createCommentDialog.cancelButton') }}
            </Button>
          </DialogClose>
          <Button type="submit" class="ml-auto">
            {{ t('createCommentDialog.submitButton') }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
    <Toaster />
  </Dialog>
</template>
