<script setup lang="ts">
// Import necessary components for creating, editing, deleting, importing, and viewing comments.
import CreateCommentDialog from '@/components/CreateCommentDialog.vue';
import DeleteCommentDialog from '@/components/DeleteCommentDialog.vue';
import EditCommentDialog from '@/components/EditCommentDialog.vue';
import ImportCommentsPopover from '@/components/ImportCommentsPopover.vue';

// Import UI components for buttons, inputs, pagination, popovers, selects, tables, and tooltips.
import { Button } from '@/components/ui/button';
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
import type { Article } from '@/types/Article';
import type { Comment } from '@/types/Comment';
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

// Define component properties with TypeScript for comments, articles, users, and admin status.
const props = defineProps<{
  comments: Comment[];
  articles: Article[];
  users: User[];
  isAdmin: boolean;
}>();

// Define breadcrumb items for navigation.
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: t('dashboardComments.breadcrumbs.dashboard'),
    href: '/dashboard/',
  },
  {
    title: t('dashboardComments.breadcrumbs.comments'),
    href: '/comments',
  },
];

// Reactive variables for filtering and pagination.
const selectedArticle = ref<string | 'all'>('all');
const selectedUser = ref<string | 'all'>('all');
const currentPage = ref(1);
const pageSize = 5;

// Computed: filter comments by article and user.
const filteredComments = computed(() => {
  return props.comments.filter((comment) => {
    let matchesArticle = true;
    if (selectedArticle.value !== 'all') {
      matchesArticle =
        comment.article.title === selectedArticle.value;
    }

    let matchesUser = true;
    if (selectedUser.value !== 'all') {
      matchesUser = comment.user.name === selectedUser.value;
    }

    return matchesArticle && matchesUser;
  });
});

// Computed: paginate filtered comments.
const paginatedComments = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  return filteredComments.value.slice(start, start + pageSize);
});

// Reset page when any filter changes.
watch([selectedArticle, selectedUser], () => {
  currentPage.value = 1;
});

// Selected comment for operations.
const selectedComment = ref<Comment | undefined>(undefined);

// Dialog visibility states.
const isCreateCommentDialogOpen = ref(false);
const isDeleteCommentDialogOpen = ref(false);
const isEditCommentDialogOpen = ref(false);
</script>

<template>
  <!-- Set the page title dynamically using the Head component -->
  <Head :title="t('dashboardComments.title')" />
  <!-- Use the AppLayout component to wrap the page content with navigation breadcrumbs -->
  <AppLayout :breadcrumbs="breadcrumbs" :isAdmin="isAdmin">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <!-- Page header -->
      <h2 class="text-2xl font-bold">
        {{ t('dashboardComments.header') }}
      </h2>

      <!-- Search and filter section with Input and Select components -->
      <div class="mb-2 flex items-center justify-between">
        <div class="flex w-2/4 items-center gap-4">
          <!-- User filter -->
          <Select v-model="selectedUser">
            <SelectTrigger>
              <SelectValue
                :placeholder="
                  t('dashboardComments.userFilterPlaceholder')
                "
              />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectLabel>{{
                  t('dashboardComments.userSelectLabel')
                }}</SelectLabel>
                <SelectItem value="all">{{
                  t('dashboardComments.userAll')
                }}</SelectItem>
                <SelectItem
                  v-for="user in props.users"
                  :key="user.id"
                  :value="user.name"
                >
                  {{ user.name }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>

          <!-- Article filter -->
          <Select v-model="selectedArticle">
            <SelectTrigger>
              <SelectValue
                :placeholder="
                  t('dashboardComments.articleFilterPlaceholder')
                "
              />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectLabel>
                  {{ t('dashboardComments.articleSelectLabel') }}
                </SelectLabel>
                <!-- 'All' option to display all articles -->
                <SelectItem value="all">
                  {{ t('dashboardComments.articleAll') }}
                </SelectItem>
                <!-- Dynamically list all available articles -->
                <div
                  v-for="article in props.articles"
                  :key="article.id"
                >
                  <SelectItem :value="article.title">
                    {{ article.title }}
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
            <ImportCommentsPopover />
            <TooltipProvider>
              <Tooltip>
                <TooltipTrigger>
                  <Button asChild>
                    <a href="/comments/export">
                      <FileUp class="h-5 w-5" />
                    </a>
                  </Button>
                </TooltipTrigger>
                <TooltipContent>
                  <p>{{ t('dashboardComments.export') }}</p>
                </TooltipContent>
              </Tooltip>
            </TooltipProvider>
          </div>
          <!-- Button to open the create comment dialog -->
          <Button @click="isCreateCommentDialogOpen = true">
            {{ t('dashboardComments.create') }}
          </Button>
        </div>
      </div>

      <!-- Conditional rendering: only show comment list if there are comments -->
      <div v-if="props.comments.length > 0">
        <!-- Show table only if there are matching comments based on filters -->
        <div v-if="filteredComments.length > 0">
          <Table>
            <!-- Table header defining columns -->
            <TableHeader>
              <TableRow>
                <TableHead>
                  {{ t('dashboardComments.table.user') }}
                </TableHead>
                <TableHead>
                  {{ t('dashboardComments.table.comments') }}
                </TableHead>
                <TableHead>
                  {{ t('dashboardComments.table.article') }}
                </TableHead>
                <TableHead class="text-right">
                  {{ t('dashboardComments.table.actions') }}
                </TableHead>
              </TableRow>
            </TableHeader>
            <!-- Table body listing comments -->
            <TableBody>
              <TableRow
                v-for="comment in paginatedComments"
                :key="comment.id"
              >
                <TableCell class="font-medium">
                  {{ comment.user.name }}
                </TableCell>
                <TableCell class="max-w-xs truncate">
                  {{ comment.content }}
                </TableCell>
                <TableCell> {{ comment.article.title }} </TableCell>
                <TableCell class="text-right">
                  <!-- Popover for additional actions for each comment -->
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
                        <!-- Action to edit the comment -->
                        <button
                          class="w-full rounded-md px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-neutral-900"
                          @click="
                            selectedComment = comment;
                            isEditCommentDialogOpen = true;
                          "
                        >
                          {{
                            t(
                              'dashboardComments.table.actionsPopover.edit',
                            )
                          }}
                        </button>
                        <!-- Action to delete the comment -->
                        <button
                          class="w-full rounded-md px-3 py-2 text-left text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-800"
                          @click="
                            selectedComment = comment;
                            isDeleteCommentDialogOpen = true;
                          "
                        >
                          {{
                            t(
                              'dashboardComments.table.actionsPopover.delete',
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

          <!-- Pagination control: only shown if total filtered articles exceed pageSize -->
          <div
            v-if="filteredComments.length > pageSize"
            class="mt-4 flex items-center justify-center"
          >
            <Pagination
              v-model:page="currentPage"
              :items-per-page="pageSize"
              :total="filteredComments.length"
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
        <!-- If no articles match the search/filter criteria, show a no matches message -->
        <div v-else class="py-6 text-center">
          <p class="text-lg font-medium text-gray-600">
            {{ t('dashboardComments.noMatches') }}
          </p>
        </div>
      </div>
      <!-- If there are no articles at all, show an informational message -->
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
                  {{ t('dashboardComments.noComments') }}
                </h2>
                <p class="text-lg text-gray-500">
                  {{ t('dashboardComments.noCommentsDescription') }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

  <!-- Dialog components for creating, viewing, editing, and deleting comments -->
  <CreateCommentDialog
    v-model:open="isCreateCommentDialogOpen"
    :articles="props.articles"
    :users="props.users"
    :isopen="isCreateCommentDialogOpen"
    @update:open="isCreateCommentDialogOpen = $event"
  />
  <EditCommentDialog
    v-if="selectedComment"
    :key="selectedComment.id"
    v-model:open="isEditCommentDialogOpen"
    :comment="selectedComment"
    :articles="props.articles"
    :users="props.users"
    :isopen="isEditCommentDialogOpen"
  />
  <DeleteCommentDialog
    v-if="selectedComment"
    v-model:open="isDeleteCommentDialogOpen"
    :comment="selectedComment"
    :isopen="isDeleteCommentDialogOpen"
  />
</template>
