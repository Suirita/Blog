<script setup lang="ts">
// Import necessary components for creating, editing, deleting, importing, and viewing articles.
import CreateUserDialog from '@/components/CreateUserDialog.vue';
import DeleteUserDialog from '@/components/DeleteUserDialog.vue';
import EditUserDialog from '@/components/EditUserDialog.vue';
import ImportUsersPopover from '@/components/ImportUsersPopover.vue';
import ViewUserDialog from '@/components/ViewUserDialog.vue';

// Import UI components for buttons, inputs, pagination, popovers, selects, tables, and tooltips.
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
  Pagination,
  PaginationEllipsis,
  PaginationFirst,
  PaginationLast,
  PaginationList,
  PaginationListItem,
  PaginationNext,
  PaginationPrev,
} from '@/components/ui/pagination';
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip';

// Import layout and type definitions.
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Role } from '@/types/Role';
import type { User } from '@/types/User';

// Import InertiaJS Head component for managing document head.
import { Head } from '@inertiajs/vue3';

// Import icons.
import { Ellipsis, FileUp } from 'lucide-vue-next';

// Import Vue composition API functions.
import { computed, ref, watch } from 'vue';

// Import localization/composition function.
import { useI18n } from 'vue-i18n';

// Setup for internationalization (i18n) using the useI18n hook.
const { t } = useI18n();

// Define component properties with TypeScript for users, roles and admin status.
const props = defineProps<{
  users: User[];
  roles: Role[];
  isAdmin: boolean;
}>();

// Define breadcrumb items for navigation.
// These breadcrumbs are displayed using the AppLayout component.
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: t('dashboardUsers.breadcrumbs.dashboard'),
    href: '/dashboard/',
  },
  {
    title: t('dashboardUsers.breadcrumbs.users'),
    href: '/users',
  },
];

// Create reactive variables for search query and category filter.
const searchQuery = ref('');
const selectedRole = ref('');

// Create reactive variable to manage the current page for pagination.
const currentPage = ref(1);
// Define the number of users displayed per page.
const pageSize = 5;

// Compute filtered users based on user input (search and category).
// The filtering checks both the user name and its category (unless 'all' is selected).
const filteredUsers = computed(() => {
  return props.users.filter((user) => {
    // Check if the users name includes the search query (case-insensitive).
    const matchesName = user.name
      .toLowerCase()
      .includes(searchQuery.value.toLowerCase());
    // Default to matching all roles if 'all' is selected.
    let matchesRoles = true;
    if (selectedRole.value !== 'all') {
      matchesRoles = selectedRole.value
        ? user.roles[0]?.name === selectedRole.value
        : true;
    }
    return matchesName && matchesRoles;
  });
});

// Compute articles for the current page based on pagination.
const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  return filteredUsers.value.slice(start, start + pageSize);
});

// Reset current page to 1 whenever the search query or selected category changes.
watch(searchQuery, () => {
  currentPage.value = 1;
});
watch(selectedRole, () => {
  currentPage.value = 1;
});

// Define reactive variable to store the selected user for view/edit/delete operations.
const selectedUser = ref<User | undefined>(undefined);

// Define reactive variables to control the visibility of different dialogs.
const isCreateUserDialogOpen = ref(false);
const isDeleteUserDialogOpen = ref(false);
const isEditUserDialogOpen = ref(false);
const isViewUserDialogOpen = ref(false);
</script>

<template>
  <!-- Set the page title dynamically using the Head component -->
  <Head :title="t('dashboardUsers.title')" />
  <!-- Use the AppLayout component to wrap the page content with navigation breadcrumbs -->
  <AppLayout :breadcrumbs="breadcrumbs" :isAdmin="isAdmin">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <!-- Page header -->
      <h2 class="text-2xl font-bold">
        {{ t('dashboardUsers.header') }}
      </h2>

      <!-- Search and filter section with Input and Select components -->
      <div class="mb-2 flex items-center justify-between">
        <div class="flex w-2/4 items-center gap-4">
          <!-- Search input bound to searchQuery -->
          <Input
            v-model="searchQuery"
            name="search"
            type="text"
            :placeholder="t('dashboardUsers.searchPlaceholder')"
          />

          <!-- Category filter dropdown using the Select components -->
          <Select v-model="selectedRole">
            <SelectTrigger>
              <SelectValue
                :placeholder="
                  t('dashboardUsers.RoleFilterPlaceholder')
                "
              />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectLabel>
                  {{ t('dashboardUsers.RoleSelectLabel') }}
                </SelectLabel>
                <!-- 'All' option to display all categories -->
                <SelectItem value="all">
                  {{ t('dashboardUsers.RoleAll') }}
                </SelectItem>

                <!-- Dynamically list all available roles -->

                <div v-for="role in props.roles" :key="role.id">
                  <SelectItem :value="role.name">
                    {{ role.name }}
                  </SelectItem>
                </div>
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
        <!-- Action buttons section -->
        <div class="flex items-center gap-4">
          <!-- Export and Import options (admin-only) -->
          <div v-if="isAdmin" class="flex items-center gap-2">
            <ImportUsersPopover />
            <TooltipProvider>
              <Tooltip>
                <TooltipTrigger>
                  <Button asChild>
                    <a href="/users/export">
                      <FileUp class="h-5 w-5" />
                    </a>
                  </Button>
                </TooltipTrigger>
                <TooltipContent>
                  <p>{{ t('dashboardUsers.export') }}</p>
                </TooltipContent>
              </Tooltip>
            </TooltipProvider>
          </div>
          <!-- Button to open the create user dialog -->
          <Button @click="isCreateUserDialogOpen = true">
            {{ t('dashboardUsers.create') }}
          </Button>
        </div>
      </div>

      <!-- Conditional rendering: only show user list if there are users -->
      <div v-if="props.users.length > 0">
        <!-- Show table only if there are matching users based on filters -->
        <div v-if="filteredUsers.length > 0">
          <Table>
            <!-- Table header defining columns -->
            <TableHeader>
              <TableRow>
                <TableHead>
                  {{ t('dashboardUsers.table.name') }}
                </TableHead>
                <TableHead>
                  {{ t('dashboardUsers.table.email') }}
                </TableHead>
                <TableHead>
                  {{ t('dashboardUsers.table.hashedPassword') }}
                </TableHead>
                <TableHead>
                  {{ t('dashboardUsers.table.role') }}
                </TableHead>
                <TableHead class="text-right">
                  {{ t('dashboardUsers.table.actions') }}
                </TableHead>
              </TableRow>
            </TableHeader>
            <!-- Table body listing users -->
            <TableBody>
              <TableRow v-for="user in paginatedUsers" :key="user.id">
                <TableCell class="font-medium">
                  {{ user.name }}
                </TableCell>
                <TableCell> {{ user.email }} </TableCell>
                <TableCell> {{ user.password }} </TableCell>
                <TableCell> {{ user.roles[0]?.name }} </TableCell>
                <TableCell class="text-right">
                  <!-- Popover for additional actions for each user -->
                  <Popover>
                    <PopoverTrigger>
                      <button
                        class="rounded-md p-2 hover:bg-background"
                      >
                        <Ellipsis class="h-5 w-5" />
                      </button>
                    </PopoverTrigger>
                    <PopoverContent
                      class="w-40 rounded-md border p-2 shadow-lg"
                    >
                      <div class="flex flex-col space-y-1">
                        <!-- Action to view the user -->
                        <button
                          class="w-full rounded-md px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-neutral-900"
                          @click="
                            selectedUser = user;
                            isViewUserDialogOpen = true;
                          "
                        >
                          {{
                            t(
                              'dashboardUsers.table.actionsPopover.view',
                            )
                          }}
                        </button>
                        <!-- Action to edit the user -->
                        <button
                          class="w-full rounded-md px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-neutral-900"
                          @click="
                            selectedUser = user;
                            isEditUserDialogOpen = true;
                          "
                        >
                          {{
                            t(
                              'dashboardUsers.table.actionsPopover.edit',
                            )
                          }}
                        </button>
                        <!-- Action to delete the user -->
                        <button
                          class="w-full rounded-md px-3 py-2 text-left text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-800"
                          @click="
                            selectedUser = user;
                            isDeleteUserDialogOpen = true;
                          "
                        >
                          {{
                            t(
                              'dashboardUsers.table.actionsPopover.delete',
                            )
                          }}
                        </button>
                      </div>
                    </PopoverContent>
                  </Popover>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>

          <!-- Pagination control: only shown if total filtered users exceed pageSize -->
          <div
            v-if="filteredUsers.length > pageSize"
            class="mt-4 flex items-center justify-center"
          >
            <Pagination
              v-model:page="currentPage"
              :items-per-page="pageSize"
              :total="filteredUsers.length"
              :sibling-count="1"
              show-edges
            >
              <!-- Render each pagination item -->
              <PaginationList
                v-slot="{ items }"
                class="flex items-center gap-1"
              >
                <PaginationFirst />
                <PaginationPrev />
                <template v-for="(item, index) in items" :key="index">
                  <!-- Display a page button if item is a page -->
                  <PaginationListItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    as-child
                  >
                    <Button
                      class="h-9 w-9 p-0"
                      :variant="
                        item.value === currentPage
                          ? 'default'
                          : 'outline'
                      "
                    >
                      {{ item.value }}
                    </Button>
                  </PaginationListItem>
                  <!-- Display ellipsis if item represents a gap in page numbers -->
                  <PaginationEllipsis
                    v-else
                    :key="item.type"
                    :index="index"
                  />
                </template>
                <PaginationNext />
                <PaginationLast />
              </PaginationList>
            </Pagination>
          </div>
        </div>
        <!-- If no users match the search/filter criteria, show a no matches message -->
        <div v-else class="py-6 text-center">
          <p class="text-lg font-medium text-gray-600">
            {{ t('dashboardUsers.noMatches') }}
          </p>
        </div>
      </div>
      <!-- If there are no users at all, show an informational message -->
      <div v-else>
        <div
          class="mt-6 flex h-full flex-1 flex-col items-center justify-center gap-4"
        >
          <div
            class="flex flex-col items-center justify-center gap-4"
          >
            <div
              class="flex h-full flex-1 flex-col items-center justify-center gap-4"
            >
              <div
                class="flex h-full flex-1 flex-col items-center justify-center gap-4"
              >
                <h2 class="text-2xl font-bold">
                  {{ t('dashboardUsers.noUsers') }}
                </h2>
                <p class="text-lg text-gray-500">
                  {{ t('dashboardUsers.noUsersDescription') }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

  <!-- Dialog components for creating, viewing, editing, and deleting users -->
  <CreateUserDialog
    v-model:open="isCreateUserDialogOpen"
    :isopen="isCreateUserDialogOpen"
    :roles="props.roles"
    @update:open="isCreateUserDialogOpen = $event"
  />
  <ViewUserDialog
    v-if="selectedUser"
    v-model:open="isViewUserDialogOpen"
    :user="selectedUser"
    :isopen="isViewUserDialogOpen"
  />
  <EditUserDialog
    v-if="selectedUser"
    :key="selectedUser.id"
    v-model:open="isEditUserDialogOpen"
    :user="selectedUser"
    :roles="props.roles"
    :isopen="isEditUserDialogOpen"
  />
  <DeleteUserDialog
    v-if="selectedUser"
    v-model:open="isDeleteUserDialogOpen"
    :user="selectedUser"
    :isopen="isDeleteUserDialogOpen"
  />
</template>
