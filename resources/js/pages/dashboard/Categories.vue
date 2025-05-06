<script setup lang="ts">
// Import category dialogs for create, delete, and edit functionalities.
import CreateCategoryDialog from '@/components/CreateCategoryDialog.vue';
import DeleteCategoryDialog from '@/components/DeleteCategoryDialog.vue';
import EditCategoryDialog from '@/components/EditCategoryDialog.vue';
// Import popover for importing categories.
import ImportCategoriesPopover from '@/components/ImportCategoriesPopover.vue';

// Import UI components for buttons and inputs.
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
// Import UI components for pagination controls.
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
// Import UI components for popovers.
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover';
// Import UI components for table rendering.
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
// Import UI components for tooltips.
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip';

// Import the main layout for the dashboard.
import AppLayout from '@/layouts/AppLayout.vue';
// Import type definition for breadcrumbs.
import { type BreadcrumbItem } from '@/types';
// Import type definition for Category.
import type { Category } from '@/types/Category';

// Import InertiaJS Head component to manage the document head (page title etc.).
import { Head } from '@inertiajs/vue3';
// Import icons.
import { Ellipsis, FileUp } from 'lucide-vue-next';

// Import Vue composition API functions.
import { computed, ref, watch } from 'vue';
// Import localization/composition function.
import { useI18n } from 'vue-i18n';

// Setup internationalization (i18n) using the useI18n hook.
const { t } = useI18n();

// Define component properties with TypeScript for categories and admin status.
const props = defineProps<{
  categories: Category[];
  isAdmin: boolean;
}>();

// Define breadcrumb items for navigation.
// These breadcrumbs will be displayed in the AppLayout.
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: t('dashboardCategories.breadcrumbs.dashboard'),
    href: '/dashboard/',
  },
  {
    title: t('dashboardCategories.breadcrumbs.categories'),
    href: '/categories',
  },
];

// Create reactive variable for storing the search query input.
const searchQuery = ref('');
// Create reactive variable for keeping track of the current page in the pagination.
const currentPage = ref(1);
// Define the number of categories displayed per page.
const pageSize = 5;

// Compute a list of categories filtered based on the search query.
// This filter checks if a category name includes the search term (case-insensitive).
const filteredCategories = computed(() => {
  return props.categories.filter((category) => {
    const matchesName = category.name
      .toLowerCase()
      .includes(searchQuery.value.toLowerCase());
    return matchesName;
  });
});

// Compute the list of categories that should be displayed on the current page.
const paginatedCategories = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  return filteredCategories.value.slice(start, start + pageSize);
});

// Reset the current page to 1 whenever the search query changes.
watch(searchQuery, () => {
  currentPage.value = 1;
});

// Reactive variable to hold the currently selected category for editing or deletion.
const selectedCategory = ref<Category | undefined>(undefined);
// Reactive variables to control the visibility of dialogs for creating, editing, and deleting categories.
const isCreateCategoryDialogOpen = ref(false);
const isDeleteCategoryDialogOpen = ref(false);
const isEditCategoryDialogOpen = ref(false);
</script>

<template>
  <!-- Set the page title dynamically using the Head component -->
  <Head :title="t('dashboardCategories.title')" />
  <!-- Wrap the page content with the AppLayout which accepts breadcrumbs and admin status -->
  <AppLayout :breadcrumbs="breadcrumbs" :isAdmin="isAdmin">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <!-- Page header displaying the main title -->
      <h2 class="text-2xl font-bold">
        {{ t('dashboardCategories.header') }}
      </h2>

      <!-- Section for search and action buttons -->
      <div class="mb-2 flex items-center justify-between">
        <!-- Left-side: Search input for filtering categories -->
        <div class="flex w-2/4 items-center gap-4">
          <Input
            v-model="searchQuery"
            name="search"
            type="text"
            :placeholder="t('dashboardCategories.searchPlaceholder')"
          />
        </div>
        <!-- Right-side: Import and export options and button to create new category -->
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-2">
            <!-- Popover component for importing categories -->
            <ImportCategoriesPopover />
            <TooltipProvider>
              <Tooltip>
                <!-- Tooltip trigger wrapping the export button -->
                <TooltipTrigger>
                  <Button asChild>
                    <a href="/categories/export">
                      <FileUp class="h-5 w-5" />
                    </a>
                  </Button>
                </TooltipTrigger>
                <TooltipContent>
                  <p>
                    {{ t('dashboardCategories.export') }}
                  </p>
                </TooltipContent>
              </Tooltip>
            </TooltipProvider>
          </div>
          <!-- Button to open the create category dialog -->
          <Button @click="isCreateCategoryDialogOpen = true">
            {{ t('dashboardCategories.create') }}
          </Button>
        </div>
      </div>

      <!-- Conditional rendering: Only show table if there are categories -->
      <div v-if="props.categories.length > 0">
        <!-- Only display the table if there are matching categories after filtering -->
        <div v-if="filteredCategories.length > 0">
          <!-- Table for listing categories -->
          <Table>
            <TableHeader>
              <TableRow>
                <!-- Table head for the category name -->
                <TableHead>
                  {{ t('dashboardCategories.table.name') }}
                </TableHead>
                <!-- Table head for the actions column (aligned to the right) -->
                <TableHead class="text-right">
                  {{ t('dashboardCategories.table.actions') }}
                </TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <!-- Loop through each paginated category and display in a table row -->
              <TableRow
                v-for="category in paginatedCategories"
                :key="category.id"
              >
                <TableCell class="font-medium">
                  {{ category.name }}
                </TableCell>
                <TableCell class="text-right">
                  <!-- Popover for action options (edit, delete) -->
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
                        <!-- Edit category action -->
                        <button
                          class="w-full rounded-md px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-neutral-900"
                          @click="
                            selectedCategory = category;
                            isEditCategoryDialogOpen = true;
                          "
                        >
                          {{
                            t(
                              'dashboardCategories.table.actionsPopover.edit',
                            )
                          }}
                        </button>
                        <!-- Delete category action with highlighted red text -->
                        <button
                          class="w-full rounded-md px-3 py-2 text-left text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-800"
                          @click="
                            selectedCategory = category;
                            isDeleteCategoryDialogOpen = true;
                          "
                        >
                          {{
                            t(
                              'dashboardCategories.table.actionsPopover.delete',
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

          <!-- Pagination controls: Only show if the number of filtered categories exceed the pageSize -->
          <div
            v-if="filteredCategories.length > pageSize"
            class="mt-4 flex items-center justify-center"
          >
            <Pagination
              v-model:page="currentPage"
              :items-per-page="pageSize"
              :total="filteredCategories.length"
              :sibling-count="1"
              show-edges
            >
              <PaginationList
                v-slot="{ items }"
                class="flex items-center gap-1"
              >
                <PaginationFirst />
                <PaginationPrev />

                <!-- Render individual page items and ellipsis for gaps -->
                <template v-for="(item, index) in items" :key="index">
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
        <!-- If no categories match the search criteria, display a message -->
        <div v-else class="py-6 text-center">
          <p class="text-lg font-medium text-gray-600">
            {{ t('dashboardCategories.noMatches') }}
          </p>
        </div>
      </div>
      <!-- If there are no categories at all, display an informational message -->
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
              <h2 class="text-2xl font-bold">
                {{ t('dashboardCategories.noArticles') }}
              </h2>
              <p class="text-lg text-gray-500">
                {{ t('dashboardCategories.noArticlesDescription') }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

  <!-- Dialog components for creating, editing, and deleting categories.
       They are conditionally rendered based on the selected category and open states. -->
  <CreateCategoryDialog
    v-model:open="isCreateCategoryDialogOpen"
    :categories="props.categories"
    :isopen="isCreateCategoryDialogOpen"
    @update:open="isCreateCategoryDialogOpen = $event"
  />
  <EditCategoryDialog
    v-if="selectedCategory"
    :key="selectedCategory.id"
    v-model:open="isEditCategoryDialogOpen"
    :category="selectedCategory"
    :isopen="isEditCategoryDialogOpen"
  />
  <DeleteCategoryDialog
    v-if="selectedCategory"
    v-model:open="isDeleteCategoryDialogOpen"
    :category="selectedCategory"
    :isopen="isDeleteCategoryDialogOpen"
  />
</template>
