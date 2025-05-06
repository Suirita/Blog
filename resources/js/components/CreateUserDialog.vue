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
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import type { Role } from '@/types/Role';
import { router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { useI18n } from 'vue-i18n';
import * as z from 'zod';

const { t } = useI18n();
const { toast } = useToast();

defineProps<{
  roles: Role[];
  isopen: boolean;
}>();

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({
        message: t('createUserDialog.formSchema.name.string'),
      })
      .min(3, {
        message: t('createUserDialog.formSchema.name.min'),
      })
      .max(100, {
        message: t('createUserDialog.formSchema.name.max'),
      })
      .regex(/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?']+$/, {
        message: t('createUserDialog.formSchema.name.regex'),
      }),
    email: z
      .string({
        message: t('createUserDialog.formSchema.email.string'),
      })
      .email({
        message: t('createUserDialog.formSchema.email.email'),
      }),
    password: z
      .string({
        message: t('createUserDialog.formSchema.password.string'),
      })
      .min(8, {
        message: t('createUserDialog.formSchema.password.min'),
      })
      .max(15, {
        message: t('createUserDialog.formSchema.password.max'),
      }),
    role: z.string({
      message: t('createUserDialog.formSchema.role'),
    }),
  }),
);

const form = useForm({
  validationSchema: formSchema,
});

const onSubmit = form.handleSubmit((values) => {
  router.post('/users', values, {
    onSuccess: () => {
      toast({
        title: t('createUserDialog.toast.success.title'),
        description: t('createUserDialog.toast.success.description'),
      });
      form.resetForm();
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('createUserDialog.toast.error.title'),
        description: t('createUserDialog.toast.error.description'),
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
          {{ t('createUserDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('createUserDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <form
        @submit.prevent="onSubmit"
        class="mt-2 flex flex-col gap-4"
      >
        <!-- Name Field -->
        <FormField v-slot="{ componentField }" name="name">
          <FormItem>
            <FormLabel>
              {{ t('createUserDialog.nameInputLabel') }}
            </FormLabel>
            <FormControl>
              <Input
                type="text"
                :placeholder="
                  t('createUserDialog.nameInputPlaceholder')
                "
                v-bind="componentField"
              />
            </FormControl>
            <FormDescription>
              {{ t('createUserDialog.nameInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Email Field -->
        <FormField v-slot="{ componentField }" name="email">
          <FormItem>
            <FormLabel>
              {{ t('createUserDialog.emailInputLabel') }}
            </FormLabel>
            <FormControl>
              <Input
                type="email"
                :placeholder="
                  t('createUserDialog.emailInputPlaceholder')
                "
                v-bind="componentField"
              />
            </FormControl>
            <FormDescription>
              {{ t('createUserDialog.emailInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Password Field -->
        <FormField v-slot="{ componentField }" name="password">
          <FormItem>
            <FormLabel>
              {{ t('createUserDialog.passwordInputLabel') }}
            </FormLabel>
            <FormControl>
              <Input type="password" v-bind="componentField" />
            </FormControl>
            <FormDescription>
              {{ t('createUserDialog.passwordInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Role Field -->
        <FormField v-slot="{ componentField }" name="role">
          <FormItem>
            <FormLabel>
              {{ t('createUserDialog.roleInputLabel') }}
            </FormLabel>
            <FormControl>
              <Select v-bind="componentField">
                <SelectTrigger>
                  <SelectValue
                    :placeholder="
                      t('createUserDialog.roleInputPlaceholder')
                    "
                  />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>
                      {{ t('createUserDialog.roleSelectLabel') }}
                    </SelectLabel>
                    <div v-for="role in roles" :key="role.id">
                      <SelectItem :value="role.name">
                        {{ role.name }}
                      </SelectItem>
                    </div>
                  </SelectGroup>
                </SelectContent>
              </Select>
            </FormControl>
            <FormDescription>
              {{ t('createUserDialog.roleInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Form Actions -->
        <DialogFooter>
          <DialogClose as-child>
            <Button type="button" variant="secondary">
              {{ t('createUserDialog.cancelButton') }}
            </Button>
          </DialogClose>
          <Button type="submit" class="ml-auto">
            {{ t('createUserDialog.submitButton') }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
    <Toaster />
  </Dialog>
</template>
