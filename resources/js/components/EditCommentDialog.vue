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
import type { Comment } from '@/types/Comment';
import type { User } from '@/types/User';
import { router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { watch } from 'vue';
import { useI18n } from 'vue-i18n';
import * as z from 'zod';

const { t } = useI18n();
const { toast } = useToast();

const props = defineProps<{
  comment: Comment;
  articles: Article[];
  users: User[];
  isopen: boolean;
}>();

watch(
  () => props.isopen,
  (isOpen) => {
    if (isOpen) {
      form.resetForm({
        values: {
          article: props.comment.article.id,
          user: props.comment.user.id,
          content: props.comment.content,
        },
      });
    }
  },
);

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
  initialValues: {
    article: props.comment.article.id,
    user: props.comment.user.id,
    content: props.comment.content,
  },
});

const onSubmit = form.handleSubmit(async (values) => {
  router.put(`/comments/${props.comment.id}`, values, {
    onSuccess: () => {
      toast({
        title: t('editCommentDialog.toast.success.title'),
        description: t('editCommentDialog.toast.success.description'),
      });
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('editCommentDialog.toast.error.title'),
        description: t('editCommentDialog.toast.error.description'),
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
          {{ t('editCommentDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('editCommentDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <form @submit="onSubmit" class="mt-2 flex flex-col gap-4">
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

        <DialogFooter>
          <DialogClose as-child>
            <Button type="button" variant="secondary">
              {{ t('editCommentDialog.cancelButton') }}
            </Button>
          </DialogClose>
          <Button type="submit" class="ml-auto">
            {{ t('editCommentDialog.submitButton') }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>

    <Toaster />
  </Dialog>
</template>
