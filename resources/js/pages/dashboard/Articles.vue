<script setup lang="ts">
// Import necessary components for creating, editing, deleting, importing, and viewing articles.
import CreateArticleDialog from '@/components/CreateArticleDialog.vue';
import DeleteArticleDialog from '@/components/DeleteArticleDialog.vue';
import EditArticleDialog from '@/components/EditArticleDialog.vue';
import ImportArticlesPopover from '@/components/ImportArticlesPopover.vue';

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
import ViewArticleDialog from '@/components/ViewArticleDialog.vue';

// Import layout and type definitions.
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Article } from '@/types/Article';
import type { Category } from '@/types/Category';

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

// Define component properties with TypeScript for articles, categories, and admin status.
const props = defineProps<{
  articles: Article[];
  categories: Category[];
  isAdmin: boolean;
}>();

// Define breadcrumb items for navigation.
// These breadcrumbs are displayed using the AppLayout component.
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: t('dashboardArticles.breadcrumbs.dashboard'),
    href: '/dashboard/',
  },
  {
    title: t('dashboardArticles.breadcrumbs.articles'),
    href: '/articles',
  },
];

// Create reactive variables for search query and category filter.
const searchQuery = ref('');
const selectedCategory = ref('');

// Create reactive variable to manage the current page for pagination.
const currentPage = ref(1);
// Define the number of articles displayed per page.
const pageSize = 5;

// Compute filtered articles based on user input (search and category).
// The filtering checks both the article title and its category (unless 'all' is selected).
const filteredArticles = computed(() => {
  return props.articles.filter((article) => {
    // Check if the article title includes the search query (case-insensitive).
    const matchesTitle = article.title
      .toLowerCase()
      .includes(searchQuery.value.toLowerCase());
    // Default to matching all categories if 'all' is selected.
    let matchesCategory = true;
    if (selectedCategory.value !== 'all') {
      matchesCategory = selectedCategory.value
        ? article.category.name === selectedCategory.value
        : true;
    }
    return matchesTitle && matchesCategory;
  });
});

// Compute articles for the current page based on pagination.
const paginatedArticles = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  return filteredArticles.value.slice(start, start + pageSize);
});

// Reset current page to 1 whenever the search query or selected category changes.
watch(searchQuery, () => {
  currentPage.value = 1;
});
watch(selectedCategory, () => {
  currentPage.value = 1;
});

// Define reactive variable to store the selected article for view/edit/delete operations.
const selectedArticle = ref<Article | undefined>(undefined);

// Define reactive variables to control the visibility of different dialogs.
const isCreateArticleDialogOpen = ref(false);
const isDeleteArticleDialogOpen = ref(false);
const isEditArticleDialogOpen = ref(false);
const isViewArticleDialogOpen = ref(false);
</script>

<template>
  <!-- Set the page title dynamically using the Head component -->
  <Head :title="t('dashboardArticles.title')" />
  <!-- Use the AppLayout component to wrap the page content with navigation breadcrumbs -->
  <AppLayout :breadcrumbs="breadcrumbs" :isAdmin="isAdmin">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <!-- Page header -->
      <h2 class="text-2xl font-bold">
        {{ t('dashboardArticles.header') }}
      </h2>

      <!-- Search and filter section with Input and Select components -->
      <div class="mb-2 flex items-center justify-between">
        <div class="flex w-2/4 items-center gap-4">
          <!-- Search input bound to searchQuery -->
          <Input
            v-model="searchQuery"
            name="search"
            type="text"
            :placeholder="t('dashboardArticles.searchPlaceholder')"
          />

          <!-- Category filter dropdown using the Select components -->
          <Select v-model="selectedCategory">
            <SelectTrigger>
              <SelectValue
                :placeholder="
                  t('dashboardArticles.categoryFilterPlaceholder')
                "
              />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectLabel>
                  {{ t('dashboardArticles.categorySelectLabel') }}
                </SelectLabel>
                <!-- 'All' option to display all categories -->
                <SelectItem value="all">
                  {{ t('dashboardArticles.categoryAll') }}
                </SelectItem>
                <!-- Dynamically list all available categories -->
                <div
                  v-for="category in props.categories"
                  :key="category.id"
                >
                  <SelectItem :value="category.name">
                    {{ category.name }}
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
            <ImportArticlesPopover />
            <TooltipProvider>
              <Tooltip>
                <TooltipTrigger>
                  <Button asChild>
                    <a href="/articles/export">
                      <FileUp class="h-5 w-5" />
                    </a>
                  </Button>
                </TooltipTrigger>
                <TooltipContent>
                  <p>{{ t('dashboardArticles.export') }}</p>
                </TooltipContent>
              </Tooltip>
            </TooltipProvider>
          </div>
          <!-- Button to open the create article dialog -->
          <Button @click="isCreateArticleDialogOpen = true">
            {{ t('dashboardArticles.create') }}
          </Button>
        </div>
      </div>

      <!-- Conditional rendering: only show article list if there are articles -->
      <div v-if="props.articles.length > 0">
        <!-- Show table only if there are matching articles based on filters -->
        <div v-if="filteredArticles.length > 0">
          <Table>
            <!-- Table header defining columns -->
            <TableHeader>
              <TableRow>
                <TableHead>
                  {{ t('dashboardArticles.table.title') }}
                </TableHead>
                <TableHead>
                  {{ t('dashboardArticles.table.author') }}
                </TableHead>
                <TableHead>
                  {{ t('dashboardArticles.table.category') }}
                </TableHead>
                <TableHead>
                  {{ t('dashboardArticles.table.views') }}
                </TableHead>
                <TableHead>
                  {{ t('dashboardArticles.table.comments') }}
                </TableHead>
                <TableHead class="text-right">
                  {{ t('dashboardArticles.table.actions') }}
                </TableHead>
              </TableRow>
            </TableHeader>
            <!-- Table body listing articles -->
            <TableBody>
              <TableRow
                v-for="article in paginatedArticles"
                :key="article.id"
              >
                <TableCell class="font-medium">
                  {{ article.title }}
                </TableCell>
                <TableCell> {{ article.user.name }} </TableCell>
                <TableCell> {{ article.category.name }} </TableCell>
                <TableCell> {{ article.views }} </TableCell>
                <TableCell> {{ article.comments.length }} </TableCell>
                <TableCell class="text-right">
                  <!-- Popover for additional actions for each article -->
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
                        <!-- Action to view the article -->
                        <button
                          class="w-full rounded-md px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-neutral-900"
                          @click="
                            selectedArticle = article;
                            isViewArticleDialogOpen = true;
                          "
                        >
                          {{
                            t(
                              'dashboardArticles.table.actionsPopover.view',
                            )
                          }}
                        </button>
                        <!-- Action to edit the article -->
                        <button
                          class="w-full rounded-md px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-neutral-900"
                          @click="
                            selectedArticle = article;
                            isEditArticleDialogOpen = true;
                          "
                        >
                          {{
                            t(
                              'dashboardArticles.table.actionsPopover.edit',
                            )
                          }}
                        </button>
                        <!-- Action to delete the article -->
                        <button
                          class="w-full rounded-md px-3 py-2 text-left text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-800"
                          @click="
                            selectedArticle = article;
                            isDeleteArticleDialogOpen = true;
                          "
                        >
                          {{
                            t(
                              'dashboardArticles.table.actionsPopover.delete',
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
            v-if="filteredArticles.length > pageSize"
            class="mt-4 flex items-center justify-center"
          >
            <Pagination
              v-model:page="currentPage"
              :items-per-page="pageSize"
              :total="filteredArticles.length"
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
            {{ t('dashboardArticles.noMatches') }}
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
                  {{ t('dashboardArticles.noArticles') }}
                </h2>
                <p class="text-lg text-gray-500">
                  {{ t('dashboardArticles.noArticlesDescription') }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

  <!-- Dialog components for creating, viewing, editing, and deleting articles -->
  <CreateArticleDialog
    v-model:open="isCreateArticleDialogOpen"
    :categories="props.categories"
    :isopen="isCreateArticleDialogOpen"
    @update:open="isCreateArticleDialogOpen = $event"
  />
  <ViewArticleDialog
    v-if="selectedArticle"
    v-model:open="isViewArticleDialogOpen"
    :article="selectedArticle"
    :isopen="isViewArticleDialogOpen"
  />
  <EditArticleDialog
    v-if="selectedArticle"
    :key="selectedArticle.id"
    v-model:open="isEditArticleDialogOpen"
    :article="selectedArticle"
    :categories="props.categories"
    :isopen="isEditArticleDialogOpen"
  />
  <DeleteArticleDialog
    v-if="selectedArticle"
    v-model:open="isDeleteArticleDialogOpen"
    :article="selectedArticle"
    :isopen="isDeleteArticleDialogOpen"
  />
</template>
