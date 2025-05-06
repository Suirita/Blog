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
  user: User;
  roles: Role[];
  isopen: boolean;
}>();

watch(
  () => props.isopen,
  (isOpen) => {
    if (isOpen) {
      form.resetForm({
        values: {
          name: props.user.name,
          email: props.user.email,
          role: props.user.roles[0].name,
        },
      });
    }
  },
);

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({
        message: t('editUserDialog.formSchema.name.string'),
      })
      .min(3, {
        message: t('editUserDialog.formSchema.name.min'),
      })
      .max(100, {
        message: t('editUserDialog.formSchema.name.max'),
      })
      .regex(/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?']+$/, {
        message: t('editUserDialog.formSchema.name.regex'),
      }),
    email: z
      .string({
        message: t('editUserDialog.formSchema.email.string'),
      })
      .email({
        message: t('editUserDialog.formSchema.email.email'),
      }),
    oldPassword: z
      .string({
        message: t('editUserDialog.formSchema.oldPassword.string'),
      })
      .min(8, {
        message: t('editUserDialog.formSchema.oldPassword.min'),
      })
      .max(15, {
        message: t('editUserDialog.formSchema.oldPassword.max'),
      }),
    newPassword: z
      .string({
        message: t('editUserDialog.formSchema.newPassword.string'),
      })
      .min(8, {
        message: t('editUserDialog.formSchema.newPassword.min'),
      })
      .max(15, {
        message: t('editUserDialog.formSchema.newPassword.max'),
      }),
    role: z.string({
      message: t('editUserDialog.formSchema.role'),
    }),
  }),
);

const form = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.user.name,
    email: props.user.email,
    role: props.user.roles[0].name,
  },
});

const onSubmit = form.handleSubmit(async (values) => {
  router.put(`/users/${props.user.id}`, values, {
    onSuccess: () => {
      toast({
        title: t('editUserDialog.toast.success.title'),
        description: t('editUserDialog.toast.success.description'),
      });
    },
    onError: (errors) => {
      toast({
        variant: 'destructive',
        title: t('editUserDialog.toast.error.title'),
        description: t('editUserDialog.toast.error.description'),
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
          {{ t('editUserDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('editUserDialog.description') }}
        </DialogDescription>
      </DialogHeader>

      <form @submit="onSubmit" class="mt-2 flex flex-col gap-4">
        <!-- Name Field -->
        <FormField v-slot="{ componentField }" name="name">
          <FormItem>
            <FormLabel>
              {{ t('editUserDialog.nameInputLabel') }}
            </FormLabel>
            <FormControl>
              <Input
                type="text"
                :placeholder="
                  t('editUserDialog.nameInputPlaceholder')
                "
                v-bind="componentField"
              />
            </FormControl>
            <FormDescription>
              {{ t('editUserDialog.nameInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Email Field -->
        <FormField v-slot="{ componentField }" name="email">
          <FormItem>
            <FormLabel>
              {{ t('editUserDialog.emailInputLabel') }}
            </FormLabel>
            <FormControl>
              <Input
                type="email"
                :placeholder="
                  t('editUserDialog.emailInputPlaceholder')
                "
                v-bind="componentField"
              />
            </FormControl>
            <FormDescription>
              {{ t('editUserDialog.emailInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Old Password Field -->
        <FormField v-slot="{ componentField }" name="oldPassword">
          <FormItem>
            <FormLabel>
              {{ t('editUserDialog.oldPasswordInputLabel') }}
            </FormLabel>
            <FormControl>
              <Input type="password" v-bind="componentField" />
            </FormControl>
            <FormDescription>
              {{ t('editUserDialog.oldPasswordInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- New Password Field -->
        <FormField v-slot="{ componentField }" name="newPassword">
          <FormItem>
            <FormLabel>
              {{ t('editUserDialog.newPasswordInputLabel') }}
            </FormLabel>
            <FormControl>
              <Input type="password" v-bind="componentField" />
            </FormControl>
            <FormDescription>
              {{ t('editUserDialog.newPasswordInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <!-- Role Field -->
        <FormField v-slot="{ componentField }" name="role">
          <FormItem>
            <FormLabel>
              {{ t('editUserDialog.roleInputLabel') }}
            </FormLabel>
            <FormControl>
              <Select v-bind="componentField">
                <SelectTrigger>
                  <SelectValue
                    :placeholder="
                      t('editUserDialog.roleInputPlaceholder')
                    "
                  />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>
                      {{ t('editUserDialog.roleSelectLabel') }}
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
              {{ t('editUserDialog.roleInputDescription') }}
            </FormDescription>
            <FormMessage />
          </FormItem>
        </FormField>

        <DialogFooter>
          <DialogClose as-child>
            <Button type="button" variant="secondary">
              {{ t('editUserDialog.cancelButton') }}
            </Button>
          </DialogClose>
          <Button type="submit" class="ml-auto">
            {{ t('editUserDialog.submitButton') }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>

    <Toaster />
  </Dialog>
</template>
