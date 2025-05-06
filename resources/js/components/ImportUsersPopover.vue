<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
  FormControl,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip';
import { router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { FileDown } from 'lucide-vue-next';
import { useForm } from 'vee-validate';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import * as z from 'zod';
import { FormField } from './ui/form';

const { t } = useI18n();
const { toast } = useToast();

const formSchema = toTypedSchema(
  z.object({
    file: z.instanceof(File, {
      message: t('importUsersPopover.fileError'),
    }),
  }),
);

const form = useForm({
  validationSchema: formSchema,
});

const selectedFile = ref<File | null>(null);

function handleFileChange(event: Event) {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    selectedFile.value = target.files[0];
    form.setFieldValue('file', target.files[0]);
  } else {
    selectedFile.value = null;
    form.setFieldValue('file', undefined);
  }
}

const onSubmit = form.handleSubmit((values) => {
  router.post('/users/import', values, {
    onSuccess: () => {
      toast({
        title: t('importUsersPopover.toast.success.title'),
        description: t(
          'importUsersPopover.toast.success.description',
        ),
      });
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('importUsersPopover.toast.error.title'),
        description: t('importUsersPopover.toast.error.description'),
      });
      console.error(errors);
    },
  });
});
</script>

<template>
  <Popover>
    <PopoverTrigger>
      <TooltipProvider>
        <Tooltip>
          <TooltipTrigger>
            <Button variant="outline">
              <FileDown class="h-5 w-5" />
            </Button>
          </TooltipTrigger>
          <TooltipContent>
            <p>{{ t('dashboardArticles.import') }}</p>
          </TooltipContent>
        </Tooltip>
      </TooltipProvider>
    </PopoverTrigger>
    <PopoverContent class="w-96 rounded-md border p-4 shadow-lg">
      <header class="mb-4">
        <h1 class="text-lg font-bold">
          {{ t('importUsersPopover.title') }}
        </h1>
        <p class="text-sm text-gray-600">
          {{ t('importUsersPopover.description') }}
        </p>
      </header>
      <form @submit.prevent="onSubmit">
        <FormField name="file" v-slot="{ errors }">
          <FormItem>
            <FormLabel>
              {{ t('importUsersPopover.inputLabel') }}
            </FormLabel>
            <FormControl>
              <Input
                type="file"
                @change="handleFileChange"
                :placeholder="
                  t('importUsersPopover.inputPlaceholder')
                "
              />
            </FormControl>
            <FormMessage v-if="errors.length">
              {{ errors[0] }}
            </FormMessage>
          </FormItem>
        </FormField>
        <div class="mt-3 flex justify-end">
          <Button>
            {{ t('importUsersPopover.importButton') }}
          </Button>
        </div>
      </form>
    </PopoverContent>
  </Popover>
  <Toaster />
</template>
